# EzyPOS Label Printer

Barcode label printing for the **TSC TTP-244 Pro**, driven straight from EzyPOS.

Built for a **38 mm x 25 mm, 2-up** die-cut roll — two stickers side by side —
but every measurement is a setting, so a different roll is a change of numbers,
not a change of program.

---

## What is in here

| Folder | What it is |
|---|---|
| `src/EzyLabel.Core` | The label engine: TSPL generation, Code 128 sizing, the EzyPOS API client, the print agent. No Windows in it. |
| `src/EzyLabel.App` | The Windows program. The window, the printer, the on-screen preview. |
| `src/EzyLabel.Cli` | The same engine with no window. Produces the print proofs and can run the print agent headless. |
| `web/label-test.php` | The online test page. Goes next to `index.php` on the EzyPOS site. |
| `tools/tspl_render.py` | Draws a TSPL job exactly as the printer would put it on paper. |
| `tools/verify_labels.py` | Renders a job and reads the barcodes back with a decoder. |
| `docs/` | Setup, printer configuration, troubleshooting, API. |
| `dist/` | The built Windows program. |

---

## The short version

1. Install the TSC driver, plug the printer in.
2. Run `EzyPOS Label Printer.exe`.
3. Settings tab: EzyPOS address, API key, pick the printer. Save.
4. Print the calibration sheet, measure, adjust the two margin numbers.
5. Items tab: search, set a quantity, add to queue.
6. Queue tab: check it, press Preview, press PRINT.

Full instructions: [docs/WINDOWS_SETUP.md](docs/WINDOWS_SETUP.md).

---

## The part that actually matters: 2-up

A 2-up roll is not two labels the printer knows about. The printer sees **one
row at a time** and finds the die-cut gap between rows with its sensor. So the
job is built like this:

```
SIZE 78 mm, 25 mm     <- the WHOLE row: 38 + 2 + 38
GAP  2 mm, 0 mm       <- the vertical gap between rows, the only one the sensor sees
CLS
TEXT/BARCODE ...      <- left sticker,  x from 0
TEXT/BARCODE ...      <- right sticker, x from (38 + 2) mm = 320 dots
PRINT 1,1             <- feeds ONE row, both stickers
```

Set `SIZE` to one sticker instead and the printer treats the roll as a single
wide label, feeds half a row at a time, and the whole job walks down the paper
until nothing lands on a sticker at all. That is the failure this is written to
avoid.

**Item A x 10 then Item B x 20** becomes 5 rows of A, then 10 rows of B: 15 rows,
30 stickers, no blanks in the middle, in order. Every row is its own
`CLS ... PRINT 1,1`, so nothing carries over from the row before and the
alignment cannot creep as the job goes on.

---

## Before a barcode is ever printed

A Code 128 barcode's width is arithmetic, not a guess:

```
modules = 11 x dataSymbols + 35        (subset C packs two digits per symbol)
width   = modules x narrow-bar-width
```

So the width of every code in the queue is worked out **before anything is
sent**. Then:

- fits at the chosen bar width → print it;
- only fits at a thinner bar → use the thinner bar, and say so;
- will not fit even at one dot per module → **refuse the whole job** and name
  the item codes, rather than print a barcode that runs onto the next sticker.

That is what stops the failure the brief describes: no overflow, no touching the
neighbouring label, no barcode too small to scan.

---

## Checking without a printer

```bash
# build the exact TSPL for a queue
dotnet run --project src/EzyLabel.Cli -- emit --queue queue.json --out job.tspl --placement placement.json

# draw it at 203 dpi, one pixel per printer dot, with the sticker edges marked
python3 tools/tspl_render.py job.tspl --out proof.png --diecut 38,2,2,0

# render it and read every barcode back with a decoder
python3 tools/verify_labels.py job.tspl --expect placement.json
```

`verify_labels.py` is the real test. It reports:

```
rows            : 15
stickers checked: 30
barcodes read   : 30
expected labels : 30
   SH-1001                  10
   TR-2004                  20

OK - every barcode read back correctly, in order, inside its own sticker.
```

It fails the job if a barcode cannot be read, reads back the wrong code, is out
of order, touches a sticker edge, or if two barcodes appear inside one sticker
(which is what bleeding into the neighbour looks like to a scanner).

---

## Building it

Needs the .NET 8 SDK.

```bash
# the Windows program, one self-contained .exe, no runtime to install
dotnet publish src/EzyLabel.App/EzyLabel.App.csproj -c Release -r win-x64 \
  --self-contained true -p:PublishSingleFile=true -o dist/app

# the command line tool
dotnet publish src/EzyLabel.Cli/EzyLabel.Cli.csproj -c Release -r win-x64 \
  --self-contained true -p:PublishSingleFile=true -o dist/cli
```

On Linux or macOS add `-p:EnableWindowsTargeting=true` (it is already in the
project file).
