# The label layout, and matching LabelJoy

## What goes on a 38 x 25 mm sticker

At 203 dpi that is **304 x 200 dots**, and every position below is in dots.

```
+--------------------------------------------------+  <- sticker edge
|  (1.5 mm clear all round)                        |
|            2 COLOUR BATIK BAG                    |  item name,  font 2 (12x20)
|        ||| || |||| | ||| || |||| ||              |  Code 128, 8 mm tall
|              RUBKBG2C1                           |  the number, font 1
|            Rs. 3,250.00                          |  price, font 3 (16x24)
+--------------------------------------------------+
```

**The item code is not printed on its own line.** In this shop the item code and
the barcode number are the same string, so printing both put it on the sticker
twice and pushed the name away from the bars. When the two match, the readable
number under the barcode is the one that is kept. If they are ever different,
both are printed — and the behaviour can be turned off entirely with **"Do not
repeat the item code when it is the same as the barcode number"** on the
Settings tab.

**The lines are one block, centred.** Every line on the sticker is one line
high, so the block is exactly the same height for every item on the roll. It is
measured, then centred in the sticker, with **Gap between lines** (0.4 mm by
default) between one line and the next. Because the height never changes, the
bars land in the same place on every sticker whether the item name is long or
short — no "drifting alignment". And because the name is separated from the
barcode by one line gap rather than being pinned to the top of the sticker while
the barcode was pinned to the bottom, there is no longer a band of white space
between them.

Everything is centred across the sticker, so a short item name does not sit hard
against the left edge while a long one fills the width.

Everything can be switched off individually on the Settings tab (item name,
item code, price, the number under the barcode), and there is an optional extra
line above the item name for a shop name.

---

## Fonts

The TSC's built-in bitmap fonts. Fixed pitch, so the width of a piece of text is
exactly `characters x cell width`:

| Font | Cell (dots) | Characters across a 38 mm sticker |
|---|---|---|
| 1 | 8 x 12 | 35 |
| 2 | 12 x 20 | 23 |
| 3 | 16 x 24 | 17 |
| 4 | 24 x 32 | 11 |
| 5 | 32 x 48 | 8 |

Text longer than will fit is cut, not wrapped and not shrunk, so it can never
run over the edge of the sticker.

---

## Barcode sizing

Code 128 width is arithmetic:

```
modules = 11 x dataSymbols + 35
```

`dataSymbols` is one per character in subset B, or one per **pair of digits** in
subset C — so an all-numeric code of even length is about half as wide. The
sizing assumes subset B unless the code is all digits and an even length; being
pessimistic means a barcode is occasionally narrower than it needed to be, never
wider than the sticker.

On a 38 mm sticker with 1.5 mm margins there are **280 dots** to play with:

| Code | Characters | Modules | At 2 dots | Fits? |
|---|---|---|---|---|
| `SH-1001` | 7 | 112 | 224 | yes |
| `RUBKBG2C1` | 9 | 134 | 268 | yes |
| `GARMENT-778899` | 14 | 189 | 378 | no — drops to 1 dot (189), fits, and is flagged |
| `THIS-IS-A-VERY-LONG-ITEM-CODE-123456` | 36 | 431 | 862 | no — **the job is refused** |

The last row is the important one. Rather than print a barcode that runs onto
the neighbouring sticker, the program stops, names the item codes, and prints
nothing.

---

## Matching the existing LabelJoy labels

The brief asks that nobody notice the difference between a LabelJoy label and
one of these. The layout above is the standard garment-tag arrangement and is a
close match for a typical LabelJoy 38 x 25 template, but **the existing template
has not been seen**, so this is a considered default rather than a copy.

To match it exactly, send either the LabelJoy layout file (`.lay`) or a clear
straight-on photograph of a label that is on a garment in the shop now. Then
these settings are adjusted to suit:

| What differs | What to change |
|---|---|
| Item name bigger or smaller | `NameFont` in `settings.json` — 1, 2, 3, 4 or 5 |
| Price bigger or smaller | `PriceFont`, and `PriceMultiplier` to double or treble it |
| Barcode taller or shorter | **Barcode height** on the Settings tab |
| Bars thicker or thinner | **Narrow bar (dots)** |
| Different order of the lines | a small change to `TsplBuilder.AppendOneLabel` |
| More or less white space round the edge | **Margin inside sticker** |
| More or less air between the lines | **Gap between lines** |
| Item code wanted as well as the number | untick **"Do not repeat the item code..."** |
| Currency shown differently | **Currency prefix** |
| Shop name on the label | **Extra top line** |

Everything except the order of the lines is a settings change, no rebuild.

---

## The proof, and what it does and does not prove

`tools/tspl_render.py` draws the job at 203 dpi, one pixel per printer dot, from
the same `.tspl` file the printer gets. The barcode is drawn from the real
Code 128 module pattern, so its width is exact. The text is drawn one character
per cell at the printer's own cell width, so a name that overflows a sticker
overflows it in the proof too.

`tools/verify_labels.py` then renders the job and runs a **barcode decoder** over
every sticker. That confirms each barcode decodes, decodes to the right item
code, appears in the right order, and sits inside its own sticker with the quiet
zone intact.

What that **does** settle: the commands, the geometry, the ordering, the counts,
the encoding, and whether anything overflows.

What it **cannot** settle: how the ink sits on your particular roll. Print
density, how well your labels take heat, and whether the paper is loaded a
millimetre off are physical, and no amount of software can measure them from
here. That is what the calibration sheet and the online test page are for — you
print one row on the real printer, measure it, and adjust two numbers.
