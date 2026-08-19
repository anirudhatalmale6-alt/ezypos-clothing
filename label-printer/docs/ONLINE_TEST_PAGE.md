# The online test page

Queue up labels in a browser and have them come out of the TSC on your desk.

---

## Why it is built this way

A browser cannot talk to a USB thermal printer. Not Chrome, not Edge, not any
browser on any operating system — a web page has no way to reach a USB device
like that. Anyone who tells you otherwise is describing the ordinary Windows
print dialog, which renders a picture of a page and is no use for TSPL.

So the page does not try. It does this instead:

```
  browser  ---(1)-->  EzyPOS          item list, prices  (the API key stays on the server)
  browser  ---(2)-->  127.0.0.1:9110  the finished queue
                          |
                          '--(3)-->   TSC TTP-244 Pro    raw TSPL over USB
```

Step 2 goes to **this PC only**. `127.0.0.1` is the machine you are sitting at;
nothing on the internet can reach it. That address is served by the EzyPOS Label
Printer program, which is what does the printing.

---

## Putting it up

1. In the Windows program, **Settings** tab, tick
   **"Let a browser page print through this program"**. Leave the port at 9110.
   Save.
2. Upload `web/label-test.php` next to `index.php` on the EzyPOS site.
3. On the PC with the printer, open `https://your-site/label-test.php`.

The page reads the barcode API key out of the EzyPOS config table by itself. If
you would rather it did not touch the database, paste the key into the line at
the top of the file instead:

```php
define('LABEL_TEST_API_KEY', 'your-key-here');
```

---

## Using it

The **Print agent** box at the top says what it found:

| It says | Meaning |
|---|---|
| `ready` | the program is running and the printer is connected |
| `printer not connected` | the program is running, the printer is not — check the USB lead and the power |
| `agent not running` | the program is not open, or the tick box on its Settings tab is off |

Then: search for items, set how many labels each, press **Add**. The queue shows
underneath with the totals. **Show the TSPL** displays the exact commands that
would be sent, which is useful when something looks wrong. **Print** sends them.

The reply says how many labels and how many rows of the roll, or exactly why the
job was refused.

---

## Test 1, the one from the brief

1. Open the page.
2. Search, add **Item A** with 10 labels.
3. Search, add **Item B** with 20 labels.
4. The totals should read **30 labels**.
5. Press Print.

You should get **15 rows** off the roll: 5 rows of A, then 10 rows of B, 30
stickers, none blank, none skipped, in that order.

---

## Is it safe to leave running?

Yes. The listener is bound to `127.0.0.1`, so it accepts nothing from the
network — only from a browser on the same PC. It answers exactly three requests:

| Address | Does |
|---|---|
| `GET /status` | says whether the printer is ready and what the label size is |
| `POST /preview` | builds the TSPL and hands it back without printing |
| `POST /print` | builds it and prints it |

There is no way to make it read a file, run a program, or print anything except
a label queue.

---

## Driving it from somewhere else

Any program on the same PC can post a queue to it. curl, for example:

```bash
curl -X POST http://127.0.0.1:9110/print \
  -H 'Content-Type: application/json' \
  -d '{"labels":[
        {"item_code":"SH-1001","barcode_value":"SH-1001",
         "item_name":"Cotton Casual Shirt","selling_price":2450,"label_count":10},
        {"item_code":"TR-2004","barcode_value":"TR-2004",
         "item_name":"Linen Trouser Beige","selling_price":3950,"label_count":20}
      ]}'
```

```json
{"ok":true,"labels":30,"rows":15,"warnings":[],
 "message":"Sent 30 labels to TSC TTP-244 Pro."}
```

A job that cannot be printed comes back with `ok: false` and a plain-English
reason, and nothing is sent to the printer:

```json
{"ok":false,"error":"These item codes are too long to fit a barcode inside a 38 mm
 sticker, even at the thinnest bar width: THIS-IS-A-VERY-LONG-CODE. Shorten the
 item code, or use a wider label. Nothing has been printed."}
```

---

## Trying the whole chain with no printer attached

The command line build can run the same agent and write each job to a folder
instead of to a printer:

```bash
ezylabel serve --port 9110 --spool ./spool
```

The test page then behaves exactly as it will on the day, and every job appears
in `./spool` as a `.tspl` file you can render and check. This is how the chain
was tested before any hardware was involved.
