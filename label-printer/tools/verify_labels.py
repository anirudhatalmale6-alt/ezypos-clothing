#!/usr/bin/env python3
"""
Check a TSPL job by reading it back the way a scanner would.

It renders the job at the printer's own resolution, then runs a barcode
decoder over each sticker. That answers the questions that actually matter
before a run of a few hundred labels:

  - does every barcode decode?
  - does it decode to the right item code?
  - is every barcode fully inside its own sticker, not touching the next one?
  - is the count right, and in the right order?

    python3 verify_labels.py job.tspl --expect placement.json
    python3 verify_labels.py job.tspl --label 38 --gap 2 --columns 2

Needs Pillow and pyzbar.
"""

import argparse
import json
import sys

from PIL import Image
from pyzbar import pyzbar

import tspl_render

MM_PER_INCH = 25.4


def sticker_boxes(job, label_w_dots, gap_dots, columns, left_dots, rows):
    """Pixel rectangle of every sticker, row by row, left to right."""
    pitch = job.height + job.gap
    for r in range(rows):
        top = r * pitch
        for c in range(columns):
            x0 = left_dots + c * (label_w_dots + gap_dots)
            yield r, c, (x0, top, x0 + label_w_dots, top + job.height)


def main():
    ap = argparse.ArgumentParser(description="Render a TSPL job and read the barcodes back.")
    ap.add_argument("tspl")
    ap.add_argument("--dpi", type=int, default=203)
    ap.add_argument("--label", type=float, default=38.0, help="sticker width in mm")
    ap.add_argument("--gap", type=float, default=2.0, help="gap between the two stickers, mm")
    ap.add_argument("--columns", type=int, default=2)
    ap.add_argument("--left", type=float, default=0.0, help="left margin, mm")
    ap.add_argument("--expect", default=None, help="placement.json from 'ezylabel emit --placement'")
    ap.add_argument("--save", default=None, help="write the rendered roll here as well")
    args = ap.parse_args()

    with open(args.tspl, "r", encoding="utf-8", errors="replace") as fh:
        job = tspl_render.parse(fh.read(), args.dpi)

    rows = len(job.rows)
    if rows == 0:
        print("FAIL: no PRINT commands in that file.")
        return 1

    img = tspl_render.render(job, rows=rows)
    if args.save:
        img.save(args.save)

    def mm(v):
        return int(round(v * args.dpi / MM_PER_INCH))

    lw, gp, lm = mm(args.label), mm(args.gap), mm(args.left)

    expected = None
    if args.expect:
        with open(args.expect) as fh:
            expected = json.load(fh)["placement"]

    problems = []
    decoded = []
    idx = 0

    for r, c, (x0, y0, x1, y1) in sticker_boxes(job, lw, gp, args.columns, lm, rows):
        crop = img.crop((x0, y0, x1, y1))
        # Scale up: pyzbar wants a few pixels per module to be reliable, and
        # a 203 dpi bitmap is right at its limit.
        crop = crop.resize((crop.width * 3, crop.height * 3), Image.NEAREST)
        found = pyzbar.decode(crop)

        if expected is not None and idx >= len(expected):
            # Past the end of the job: this cell should be blank.
            if found:
                problems.append("row %d col %d should be blank but has a barcode (%s)"
                                % (r + 1, c + 1, found[0].data.decode()))
            idx += 1
            continue

        if not found:
            problems.append("row %d col %d: no barcode could be read" % (r + 1, c + 1))
            decoded.append(None)
            idx += 1
            continue

        if len(found) > 1:
            problems.append("row %d col %d: %d barcodes read inside one sticker - "
                            "the neighbouring label is bleeding in"
                            % (r + 1, c + 1, len(found)))

        sym = found[0]
        value = sym.data.decode("utf-8", "replace")
        decoded.append(value)

        # Fully inside its own sticker, with the quiet zone intact.
        left = sym.rect.left / 3.0
        right = (sym.rect.left + sym.rect.width) / 3.0
        if left < 1 or right > (x1 - x0) - 1:
            problems.append("row %d col %d: the barcode touches the sticker edge (%.0f..%.0f of %d dots)"
                            % (r + 1, c + 1, left, right, x1 - x0))

        if expected is not None:
            want = expected[idx]["barcode"]
            if value != want:
                problems.append("row %d col %d: reads \"%s\" but should be \"%s\""
                                % (r + 1, c + 1, value, want))
        idx += 1

    total_expected = len(expected) if expected is not None else None
    read = sum(1 for d in decoded if d)

    print("rows            : %d" % rows)
    print("stickers checked: %d" % idx)
    print("barcodes read   : %d" % read)
    if total_expected is not None:
        print("expected labels : %d" % total_expected)
        counts = {}
        for d in decoded:
            if d:
                counts[d] = counts.get(d, 0) + 1
        for k in sorted(counts):
            print("   %-24s %d" % (k, counts[k]))

    if problems:
        print("\nPROBLEMS (%d):" % len(problems))
        for p in problems[:40]:
            print("  - " + p)
        return 1

    print("\nOK - every barcode read back correctly, in order, inside its own sticker.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
