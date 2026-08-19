# Setting up the label printer program

About twenty minutes, once. After that anyone can print labels.

---

## 1. The printer driver

1. Plug the TSC TTP-244 Pro into the PC with the USB lead and switch it on.
2. Install the TSC Windows driver (the "Seagull" driver TSC supply, or the one
   on the disc that came with the printer).
3. Windows > Settings > Printers. The printer should be listed, usually as
   **TSC TTP-244 Pro**.

You do **not** have to set the paper size in the driver. The program sends the
label size with every job. The driver is only there so Windows has a printer to
hand the bytes to.

> **Why a driver at all, if the program sends its own commands?**
> Windows will not let a program talk to a printer that is not installed. The
> program opens the printer as **RAW**, which means Windows passes the bytes
> through untouched instead of rendering them. If it did not do that, the TSC
> would print the words `SIZE 78 mm, 25 mm` across your stickers.

---

## 2. Calibrate the printer to the roll

Do this once with the actual roll loaded, before anything else:

1. Switch the printer off.
2. Hold the **FEED** button down.
3. Switch it on, still holding FEED.
4. Let go when the light flashes.

The printer feeds a few labels, measures the die-cut gap for itself and
remembers it. This is the printer's own gap-sensor calibration and it is
separate from the settings in the program. Do it again whenever you change to a
different roll.

---

## 3. The program

1. Copy `EzyPOS Label Printer.exe` anywhere on the PC - the Desktop is fine.
   There is nothing to install and no .NET runtime to add; it is one file.
2. Run it. Windows SmartScreen may warn that the publisher is unknown, because
   the file is not code-signed. Click **More info** then **Run anyway**.
3. Go to the **4. Settings** tab.

### EzyPOS connection

| Field | What to put |
|---|---|
| EzyPOS address | `https://sub.asrenish.com` — the site address, **no** `/index.php` on the end |
| API key | from **Masters > Barcode Settings** in EzyPOS |

Press **Test connection**. It should say it connected.

### Printer

Pick the TSC in the dropdown. If it is the only TSC installed the program
usually finds it by itself. If nothing is listed, press **Refresh**; if it is
still empty, the driver is not installed - go back to step 1.

### The roll

Defaults are for a 38 x 25 mm 2-up roll:

| Setting | Default | What it is |
|---|---|---|
| Sticker width | 38 | one sticker, not the paper |
| Sticker height | 25 | one sticker |
| Stickers across | 2 | **the setting that matters most** |
| Gap between them | 2 | the bit of backing between the two stickers |
| Gap between rows | 2 | the die-cut gap the printer's sensor sees |
| Left margin | 0 | paper edge to the first sticker |
| Vertical trim | 0 | positive moves the print down |
| Margin inside sticker | 1.5 | white space kept clear at the edges |
| Barcode height | 8 | the bars, not counting the number underneath |

**Measure your roll rather than trusting these.** With a ruler, take: one
sticker's width and height, the gap between the two stickers, the gap between
one row and the next, and the distance from the paper edge to the first sticker.

Press **Save settings**.

---

## 4. Line it up

Press **Print calibration sheet**. One row comes out with a rectangle drawn
round where the program thinks each sticker is, and a ruler along the edges.

Look at where the rectangle sits against the real sticker:

| What you see | What to change |
|---|---|
| Rectangle sits too far left or right | **Left margin** |
| Rectangle sits too high or too low | **Vertical trim** (positive moves it down) |
| The ruler's 38 mm mark is not at the sticker edge | **Sticker width** is wrong - measure again |
| The second column lands on the backing, not the sticker | **Gap between them** is wrong |
| The next row creeps further off every time | **Gap between rows** is wrong, or the printer's own FEED calibration was never done - go back to step 2 |

Change one number, save, print it again. Two or three goes is normal. When the
rectangle sits neatly on the sticker you are finished, and you never have to do
it again unless the roll changes.

---

## 5. Printing

**1. Items** — type part of an item code, name or brand and press
**Search EzyPOS**. Set **Labels** to how many stickers you want of each, pick
one or more rows, press **Add to queue**. (Double-clicking a row adds it too.)

**2. Print queue** — every item with its quantity and a status. Quantities can
be typed over. Rows can be removed. The line at the bottom says how many
stickers that is and how many rows of the roll it will use.

**3. Preview** — the roll as it will come out, at actual size, with the sticker
edges drawn in. Worth a look before a big run.

Then press **PRINT**.

Just before printing, the program re-reads the prices from EzyPOS, so a queue
built an hour ago cannot put a stale price on a hundred stickers. If EzyPOS
cannot be reached it asks whether to go ahead with the prices on screen.

---

## 6. If you want to print from the browser too

On the Settings tab, tick **Let a browser page print through this program** and
leave the port at 9110. Then put `label-test.php` next to `index.php` on the
EzyPOS site and open `https://your-site/label-test.php` **on this PC**.

See [ONLINE_TEST_PAGE.md](ONLINE_TEST_PAGE.md).

---

## Copying the setup to another till

The settings live in one plain file:

```
%APPDATA%\EzyPOS Label Printer\settings.json
```

Once one PC is calibrated, copy that file to the same place on the next one. Only
the printer name may need changing.
