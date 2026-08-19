#!/usr/bin/env python3
"""
Draw a TSPL job exactly as the printer would put it on the paper.

This is not a mock-up. It reads the same .tspl file that gets sent to the
TSC TTP-244 Pro and plots every command at 203 dots per inch, one pixel per
printer dot. If a barcode would run off a sticker, it runs off it here too.

    python3 tspl_render.py job.tspl --out proof.png
    python3 tspl_render.py job.tspl --out proof.pdf --rows 4

What it understands is the subset this program emits: SIZE, GAP, DIRECTION,
REFERENCE, OFFSET, CLS, PRINT, TEXT, BARCODE, BAR, BOX. Anything else is
listed at the end so nothing is silently ignored.

Needs Pillow.
"""

import argparse
import math
import re
import sys

from PIL import Image, ImageDraw, ImageFont

MM_PER_INCH = 25.4

# TSC built-in bitmap fonts: (cell width, cell height) in dots at 203 dpi.
FONTS = {
    "1": (8, 12),
    "2": (12, 20),
    "3": (16, 24),
    "4": (24, 32),
    "5": (32, 48),
}

# ----------------------------------------------------------------- Code 128
# Bar/space module widths for every Code 128 symbol, value 0..106.
CODE128_PATTERNS = [
    "212222", "222122", "222221", "121223", "121322", "131222", "122213", "122312",
    "132212", "221213", "221312", "231212", "112232", "122132", "122231", "113222",
    "123122", "123221", "223211", "221132", "221231", "213212", "223112", "312131",
    "311222", "321122", "321221", "312212", "322112", "322211", "212123", "212321",
    "232121", "111323", "131123", "131321", "112313", "132113", "132311", "211313",
    "231113", "231311", "112133", "112331", "132131", "113123", "113321", "133121",
    "313121", "211331", "231131", "213113", "213311", "213131", "311123", "311321",
    "331121", "312113", "312311", "332111", "314111", "221411", "431111", "111224",
    "111422", "121124", "121421", "141122", "141221", "112214", "112412", "122114",
    "122411", "142112", "142211", "241211", "221114", "413111", "241112", "134111",
    "111242", "121142", "121241", "114212", "124112", "124211", "411212", "421112",
    "421211", "212141", "214121", "412121", "111143", "111341", "131141", "114113",
    "114311", "411113", "411311", "113141", "114131", "311141", "411131", "211412",
    "211214", "211232", "2331112",
]

START_B = 104
START_C = 105
STOP = 106


def code128_symbols(data):
    """
    Encode the way a TSC printer does: subset C for an even run of digits,
    subset B otherwise. Returns the list of symbol values, checksum included.
    """
    all_digits = data.isdigit()
    if all_digits and len(data) % 2 == 0 and len(data) > 0:
        values = [START_C]
        for i in range(0, len(data), 2):
            values.append(int(data[i:i + 2]))
    else:
        values = [START_B]
        for ch in data:
            values.append(ord(ch) - 32)

    checksum = values[0]
    for i, v in enumerate(values[1:], start=1):
        checksum += v * i
    values.append(checksum % 103)
    values.append(STOP)
    return values


def code128_modules(data):
    """A list of (is_bar, module_count) for the whole symbol."""
    out = []
    for v in code128_symbols(data):
        pattern = CODE128_PATTERNS[v]
        is_bar = True
        for c in pattern:
            out.append((is_bar, int(c)))
            is_bar = not is_bar
    return out


# ------------------------------------------------------------------ parsing
TOKEN = re.compile(r'"((?:[^"\\]|\\.)*)"|([^,\s]+)')


def split_args(rest):
    """Split a TSPL argument list on commas, respecting quoted strings."""
    args, buf, in_str, esc = [], "", False, False
    for ch in rest:
        if esc:
            buf += ch
            esc = False
            continue
        if ch == "\\" and in_str:
            esc = True
            continue
        if ch == '"':
            in_str = not in_str
            continue
        if ch == "," and not in_str:
            args.append(buf.strip())
            buf = ""
            continue
        buf += ch
    args.append(buf.strip())
    return args


def to_dots(value, dpi):
    """TSPL takes either raw dots or a number followed by mm/inch."""
    v = value.strip().lower()
    m = re.match(r"^(-?[\d.]+)\s*(mm|inch|in)?$", v)
    if not m:
        return 0
    n = float(m.group(1))
    unit = m.group(2)
    if unit == "mm":
        return int(round(n * dpi / MM_PER_INCH))
    if unit in ("inch", "in"):
        return int(round(n * dpi))
    return int(round(n))


class Job:
    def __init__(self, dpi=203):
        self.dpi = dpi
        self.width = 0
        self.height = 0
        self.gap = 0
        self.ref_x = 0
        self.ref_y = 0
        self.rows = []          # each row is a list of draw commands
        self.unknown = []


def parse(text, dpi=203):
    job = Job(dpi)
    current = []
    for raw in text.splitlines():
        line = raw.strip()
        if not line or line.startswith(";"):
            continue
        parts = line.split(None, 1)
        cmd = parts[0].upper()
        rest = parts[1] if len(parts) > 1 else ""
        args = split_args(rest) if rest else []

        if cmd == "SIZE":
            job.width = to_dots(args[0], dpi)
            job.height = to_dots(args[1], dpi) if len(args) > 1 else 0
        elif cmd == "GAP":
            job.gap = to_dots(args[0], dpi)
        elif cmd == "REFERENCE":
            job.ref_x = to_dots(args[0], dpi)
            job.ref_y = to_dots(args[1], dpi) if len(args) > 1 else 0
        elif cmd == "CLS":
            current = []
        elif cmd == "PRINT":
            n = 1
            try:
                n = int(float(args[0]))
            except (ValueError, IndexError):
                pass
            copies = 1
            if len(args) > 1:
                try:
                    copies = int(float(args[1]))
                except ValueError:
                    copies = 1
            for _ in range(max(1, n) * max(1, copies)):
                job.rows.append(list(current))
        elif cmd in ("TEXT", "BARCODE", "BAR", "BOX"):
            current.append((cmd, args))
        elif cmd in ("DIRECTION", "OFFSET", "SPEED", "DENSITY", "SET",
                     "CODEPAGE", "CLEAR", "HOME", "SHIFT", "COUNTER"):
            pass
        else:
            job.unknown.append(line)
    return job


# ----------------------------------------------------------------- drawing
_FONT_CACHE = {}


def pil_font(cell_w, cell_h):
    """
    A stand-in for the printer's built-in bitmap font. Exact glyph shapes vary
    between firmware versions and do not matter here; what matters is that a
    character occupies the same BOX it will on the printer, so a name that
    overflows a sticker overflows it in the proof too. That is handled by
    draw_fixed_pitch below, which advances by the TSC cell width regardless of
    what this font would naturally advance.
    """
    key = (cell_w, cell_h)
    if key in _FONT_CACHE:
        return _FONT_CACHE[key]
    font = None
    for path in (
        "/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf",
        "/usr/share/fonts/truetype/liberation/LiberationMono-Regular.ttf",
        "/usr/share/fonts/TTF/DejaVuSansMono.ttf",
    ):
        try:
            # Size to the cell HEIGHT; the pitch is forced separately.
            font = ImageFont.truetype(path, max(6, int(round(cell_h * 0.86))))
            break
        except OSError:
            continue
    if font is None:
        font = ImageFont.load_default()
    _FONT_CACHE[key] = font
    return font


def draw_fixed_pitch(draw, x, y, text, cell_w, cell_h):
    """
    Draw one character per cell, advancing by exactly the printer's cell width.
    A TSC built-in font is a fixed-pitch bitmap font, so this is how the text
    really lands: 19 characters of font "2" occupy 19 x 12 = 228 dots, no more
    and no less. Approximating with a proportional font would make the proof
    lie about whether a long item name fits.
    """
    font = pil_font(cell_w, cell_h)
    for i, ch in enumerate(text):
        draw.text((x + i * cell_w, y), ch, fill=0, font=font)


def draw_commands(draw, cmds, ox, oy, dpi):
    for cmd, args in cmds:
        try:
            if cmd == "TEXT":
                x = int(float(args[0])) + ox
                y = int(float(args[1])) + oy
                font_id = args[2]
                xm = int(float(args[4])) if len(args) > 4 else 1
                ym = int(float(args[5])) if len(args) > 5 else 1
                content = args[6] if len(args) > 6 else ""
                cw, ch = FONTS.get(font_id, (12, 20))
                cw, ch = cw * xm, ch * ym
                # TSPL anchors text at its top-left corner.
                draw_fixed_pitch(draw, x, y, content, cw, ch)

            elif cmd == "BARCODE":
                x = int(float(args[0])) + ox
                y = int(float(args[1])) + oy
                height = int(float(args[3]))
                readable = int(float(args[4])) if len(args) > 4 else 1
                narrow = int(float(args[6])) if len(args) > 6 else 2
                content = args[8] if len(args) > 8 else ""
                cx = x
                for is_bar, mods in code128_modules(content):
                    w = mods * narrow
                    if is_bar:
                        draw.rectangle([cx, y, cx + w - 1, y + height - 1], fill=0)
                    cx += w
                if readable:
                    total = cx - x
                    tw = len(content) * 8
                    draw_fixed_pitch(draw, x + max(0, (total - tw) // 2),
                                     y + height + 3, content, 8, 12)

            elif cmd == "BAR":
                x = int(float(args[0])) + ox
                y = int(float(args[1])) + oy
                w = int(float(args[2]))
                h = int(float(args[3]))
                draw.rectangle([x, y, x + w - 1, y + h - 1], fill=0)

            elif cmd == "BOX":
                x0 = int(float(args[0])) + ox
                y0 = int(float(args[1])) + oy
                x1 = int(float(args[2])) + ox
                y1 = int(float(args[3])) + oy
                t = int(float(args[4])) if len(args) > 4 else 1
                draw.rectangle([x0, y0, x1, y1], outline=0, width=t)
        except (ValueError, IndexError):
            continue


def render(job, rows=None, show_diecut=None, scale=1.0):
    """
    Draw the roll: every row of the job, one under the other, separated by the
    real vertical gap. show_diecut, if given as (label_w_mm, gap_mm, count,
    left_margin_mm), draws the die-cut sticker edges in grey so you can see at
    a glance whether the print lands inside them.
    """
    dpi = job.dpi
    total_rows = len(job.rows) if rows is None else min(rows, len(job.rows))
    if total_rows == 0:
        raise SystemExit("That file contains no PRINT commands, so there is nothing to draw.")

    row_pitch = job.height + job.gap
    width = job.width
    height = row_pitch * total_rows

    img = Image.new("L", (width, height), 255)
    draw = ImageDraw.Draw(img)

    # The die-cut gap between rows is backing paper, not sticker.
    for r in range(total_rows):
        gy = r * row_pitch + job.height
        draw.rectangle([0, gy, width - 1, gy + job.gap - 1], fill=225)

    if show_diecut:
        lw_mm, gap_mm, count, left_mm = show_diecut
        lw = int(round(lw_mm * dpi / MM_PER_INCH))
        gp = int(round(gap_mm * dpi / MM_PER_INCH))
        lm = int(round(left_mm * dpi / MM_PER_INCH))
        for r in range(total_rows):
            top = r * row_pitch
            for c in range(count):
                x0 = lm + c * (lw + gp)
                draw.rectangle([x0, top, x0 + lw - 1, top + job.height - 1],
                               outline=170, width=1)
                if c < count - 1:
                    draw.rectangle([x0 + lw, top, x0 + lw + gp - 1, top + job.height - 1],
                                   fill=232)

    for r in range(total_rows):
        draw_commands(draw, job.rows[r], job.ref_x, r * row_pitch + job.ref_y, dpi)

    if scale != 1.0:
        img = img.resize((max(1, int(img.width * scale)), max(1, int(img.height * scale))),
                         Image.LANCZOS)
    return img


def main():
    ap = argparse.ArgumentParser(description="Render a TSPL job exactly as the printer would put it on paper.")
    ap.add_argument("tspl")
    ap.add_argument("--out", required=True, help="output .png or .pdf")
    ap.add_argument("--dpi", type=int, default=203)
    ap.add_argument("--rows", type=int, default=None, help="only draw the first N rows")
    ap.add_argument("--scale", type=float, default=1.0)
    ap.add_argument("--diecut", default=None,
                    help="draw the sticker edges: LABELW,GAP,COUNT,LEFTMARGIN in mm, e.g. 38,2,2,0")
    args = ap.parse_args()

    with open(args.tspl, "r", encoding="utf-8", errors="replace") as fh:
        job = parse(fh.read(), args.dpi)

    diecut = None
    if args.diecut:
        bits = [float(x) for x in args.diecut.split(",")]
        diecut = (bits[0], bits[1], int(bits[2]), bits[3] if len(bits) > 3 else 0.0)

    img = render(job, rows=args.rows, show_diecut=diecut, scale=args.scale)

    if args.out.lower().endswith(".pdf"):
        img.convert("RGB").save(args.out, "PDF", resolution=args.dpi)
    else:
        img.save(args.out)

    print("web %d dots (%.1f mm) wide, %d rows, %d dots per row + %d gap"
          % (job.width, job.width * MM_PER_INCH / args.dpi,
             len(job.rows), job.height, job.gap))
    print("written to %s (%dx%d px)" % (args.out, img.width, img.height))
    if job.unknown:
        print("commands not drawn: %s" % ", ".join(sorted(set(job.unknown))[:8]), file=sys.stderr)


if __name__ == "__main__":
    main()
