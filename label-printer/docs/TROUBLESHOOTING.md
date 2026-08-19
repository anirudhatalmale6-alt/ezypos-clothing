# When something is wrong

Grouped by what you actually see.

---

## Nothing comes out at all

**The program says "Printer Not Connected".**
Windows cannot see a printer by that name. Check the USB lead and that the
printer is switched on, then press **Refresh** on the Settings tab. If the list
is still empty, the TSC driver is not installed.

**The program says it printed, but nothing came out.**
Windows took the job and queued it. Open Windows > Settings > Printers > the TSC
> Open queue. A job sitting there means the printer is offline, paused, or out of
paper. A queue that is empty and a printer that did nothing usually means the
printer is in an error state — switch it off and on.

**The light on the printer is flashing red.**
Out of labels, out of ribbon, or the head is not latched down. Fix that, then
press FEED once.

---

## The stickers come out blank

Almost always the wrong media setting on the printer itself, or the wrong
direct-thermal / thermal-transfer mode.

- These rolls are usually **direct thermal** — no ribbon. If the printer is set
  to thermal transfer and there is no ribbon fitted, nothing prints.
- Raise **Density** on the Settings tab. 8 is the starting point; some rolls
  want 10 to 12.
- Print the calibration sheet. If that is blank too, it is the printer or the
  media, not the label layout.

---

## The printer feeds label after label and never prints

The gap sensor has not been calibrated to this roll. Switch the printer off, hold
**FEED**, switch it on, let go when the light flashes. It will feed a few labels,
measure the gap, and stop.

If it still does it, **Gap between rows** in the program does not match the roll.
Measure the die-cut gap between one row and the next and put the real number in.

---

## The words "SIZE 78 mm, 25 mm" are printed on the stickers

The job went through a driver that rendered it as text instead of passing it
through raw. The program does not do this — it opens the printer as RAW on
purpose. If you see it, something else sent the file: dragging a `.tspl` onto the
printer, or a "print to file" that got picked up. Send it from the program.

---

## Everything prints one sticker to the left, or one to the right

The whole job is offset. Print the calibration sheet, measure how far off the
rectangle is, and put that into **Left margin**. Positive moves it right.

If the LEFT sticker is right but the RIGHT one is not, that is not the margin —
it is **Gap between them**. Measure the backing between the two stickers.

---

## It starts right, then drifts further off with every label

This is the one to take seriously, and it has three possible causes.

1. **The printer's own gap calibration was never done.** Off, hold FEED, on.
   Do this first.
2. **Gap between rows is wrong.** Every row is out by the error, so it
   accumulates. Measure the real die-cut gap.
3. **Sticker height is wrong.** Same effect. Measure a sticker top to bottom,
   not including the gap.

The program itself cannot drift: every row is a fresh `CLS ... PRINT 1,1` with
its own absolute coordinates, so nothing carries over from the row before. If the
drift is there, it is in the media settings or the printer's calibration.

---

## The barcode runs off the sticker

It should not be possible — the width is checked before anything is sent — but
if it happens:

- **Sticker width** is set wider than the sticker really is. Measure it.
- **Margin inside sticker** is too small. Raise it to 2 mm.
- **Narrow bar (dots)** is set to 3 or more. Put it back to 2.

If the program **refused** the job and named some item codes, those codes are
genuinely too long for a 38 mm sticker. Shorten the code, or use a wider label.
A 38 mm sticker fits about 11 characters comfortably and 14 at a squeeze.

---

## The barcode will not scan

- Try **Density** at 10 or 12. Bars that are too light are the usual cause.
- If the program warned that it used thinner bars, that code is near the limit
  for the sticker. It scans in testing, but a worn scanner may struggle.
  Shortening the item code is the real fix.
- Check the scanner is set to read Code 128. Most are, by default.
- Make sure nothing is printed hard against the bars. Raise **Margin inside
  sticker**.

---

## The last sticker of the job is blank

That is expected when the total is an odd number. Two stickers to a row: 7
labels is 4 rows and the eighth cell has nothing to put in it. The program warns
before printing. Add one more label if you would rather not waste it.

---

## Items are missing from the search

- The search matches item code, name and brand.
- Only **active** items are returned. An item made inactive in EzyPOS will not
  appear.
- If nothing at all comes back, press **Test connection** on the Settings tab.

---

## "EzyPOS refused the API key"

The key on the Settings tab does not match the one in EzyPOS. Open **Masters >
Barcode Settings** and copy it again. Copy and paste it — do not retype it.
`l` and `1`, and `O` and `0`, look identical in most fonts.

---

## "That address is not there" when testing the connection

The EzyPOS address is wrong. It should be just the site address:

```
https://sub.asrenish.com
```

Not `https://sub.asrenish.com/index.php`, and no trailing slash needed.

---

## The online test page says "agent not running"

- The Windows program is not open. Open it.
- Or the tick box **"Let a browser page print through this program"** on its
  Settings tab is off.
- Or something else on the PC is already using port 9110. Change the port in the
  program, save, and change it in the box at the top of the test page to match.
- The page has to be open **on the same PC as the printer**. Opening it on a
  phone will not work — there is no printer on the phone for it to reach.

---

## Still stuck

Turn on the online test page and press **Show the TSPL**. That is the exact
command stream. Send me that, a photograph of the stickers as they came out, and
the numbers on the Settings tab, and I can tell you which of them is wrong.
