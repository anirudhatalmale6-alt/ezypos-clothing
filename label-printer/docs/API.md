# API reference

Two APIs are involved. The **EzyPOS barcode API**, which the label program reads
from, and the **print agent**, which is the label program's own small API on the
PC with the printer.

---

## 1. The EzyPOS barcode API

Already part of EzyPOS. Base address is the site address, e.g.
`https://sub.asrenish.com`.

### Authentication

The API key from **Masters > Barcode Settings**, sent any of three ways:

```
X-API-Key: <key>
Authorization: Bearer <key>
?api_key=<key>
```

A missing key is `401`, a wrong key `403`.

### `GET /barcode-api/items`

| Parameter | Meaning |
|---|---|
| `search` | matches item code, name or brand |
| `category` | category id |
| `store_id` | stock figure for that branch instead of the total |

```json
{
  "success": true,
  "count": 2066,
  "items": [
    {
      "item_id": 412,
      "item_code": "RUBKBG2C1",
      "barcode_value": "RUBKBG2C1",
      "item_name": "2 COLOUR BATIK BAG",
      "selling_price": 3250,
      "brand": "",
      "category": "BAGS",
      "uom": "PCS",
      "stock_qty": 14
    }
  ]
}
```

### `GET /barcode-api/item/{id}`

One item, same fields, wrapped in `"item"`.

### `POST /barcode-api/batch`

The one the label program uses just before printing, to pick up any price
changes.

```json
{ "items": [ { "item_id": 412, "quantity": 10 },
             { "item_code": "TR-2004", "quantity": 20 } ] }
```

```json
{
  "success": true,
  "total_items": 2,
  "total_labels": 30,
  "labels": [
    { "item_id": 412, "item_code": "RUBKBG2C1", "barcode_value": "RUBKBG2C1",
      "item_name": "2 COLOUR BATIK BAG", "selling_price": 3250,
      "brand": "", "category": "BAGS", "label_count": 10 }
  ]
}
```

An item that no longer exists, or has been made inactive, is simply left out of
`labels`. The program notices and reports it as skipped rather than printing a
sticker for something that is gone.

### `GET /barcode-api/info`

Used by **Test connection**. Any successful reply counts as connected.

---

## 2. The print agent

Runs inside the Windows program when the tick box on the Settings tab is on.
Listens on `http://127.0.0.1:9110` — **this PC only**. Nothing on the network can
reach it. No authentication, because nothing off the machine can get to it in the
first place.

Cross-origin requests are allowed, because the test page is served from the
EzyPOS site while the agent is local.

### `GET /status`

```json
{
  "ok": true,
  "agent": "EzyPOS Label Printer",
  "version": "1.0",
  "printer": "TSC TTP-244 Pro",
  "printer_ready": true,
  "printer_message": "Ready",
  "label": { "width_mm": 38, "height_mm": 25, "columns": 2,
             "column_gap_mm": 2, "row_gap_mm": 2 }
}
```

`printer_ready: false` comes with a `printer_message` that says why in plain
words, e.g. *"Printer Not Connected - Windows has no printer called ..."*.

### `POST /preview`

Same body as `/print`. Builds the job and hands the TSPL back **without
printing**.

```json
{ "ok": true, "labels": 30, "rows": 15, "warnings": [], "tspl": "SIZE 78 mm..." }
```

### `POST /print`

```json
{ "labels": [
    { "item_code": "SH-1001", "barcode_value": "SH-1001",
      "item_name": "Cotton Casual Shirt", "selling_price": 2450,
      "label_count": 10 }
] }
```

| Field | Needed | Notes |
|---|---|---|
| `item_code` | yes | printed on the label |
| `barcode_value` | no | what goes in the bars; falls back to `item_code` |
| `item_name` | no | cut to fit rather than wrapped |
| `selling_price` | no | shown with the currency prefix from Settings |
| `label_count` | yes | how many stickers |

Success:

```json
{ "ok": true, "labels": 30, "rows": 15, "warnings": [],
  "message": "Sent 30 labels to TSC TTP-244 Pro." }
```

Failure — nothing is sent to the printer:

| Status | When |
|---|---|
| `400` | the queue is empty, or a barcode will not fit the sticker |
| `409` | the printer is not connected |
| `500` | something unexpected; the message says what |

```json
{ "ok": false, "error": "These item codes are too long to fit a barcode inside a
 38 mm sticker, even at the thinnest bar width: LONG-CODE-123456. Shorten the
 item code, or use a wider label. Nothing has been printed." }
```

`warnings` is advice, not failure. Two can appear: the barcode had to use thinner
bars to fit, and the total was odd so the last sticker is blank.

---

## 3. The command line

The same engine with no window. Useful for scripting, and for producing proofs.

```
ezylabel emit      --queue queue.json [--out job.tspl] [--placement placement.json]
ezylabel calibrate [--out cal.tspl]
ezylabel fetch     --items 1:10,2:20 [--out queue.json]
ezylabel serve     [--port 9110] [--spool <folder>]
ezylabel spec
```

Common options: `--settings <path>`, `--url <address>`, `--key <api key>`.

Label overrides, all in millimetres except where noted:

```
--label-width --label-height --columns --column-gap --row-gap
--left-margin --top-offset --inner-margin
--barcode-height --narrow-dots (DOTS) --dpi --speed --density
--no-price --no-name --no-code --no-barcode-text --shop-line <text>
```

Exit codes: `0` fine, `1` bad arguments or an unexpected error, `2` EzyPOS could
not be reached, `3` the queue cannot be printed as it stands.

`--spool <folder>` on `serve` writes each job to a file instead of a printer,
which is how the whole chain can be exercised with no hardware attached.
