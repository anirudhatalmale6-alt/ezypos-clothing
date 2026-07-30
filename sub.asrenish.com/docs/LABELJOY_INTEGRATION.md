# LabelJoy Integration Guide — EzyPOS Clothing

This guide explains how to connect **LabelJoy** (label design & printing software) to
the EzyPOS system so you can print barcode labels for your items directly from the
data held in the POS. It is written so the integration can be re-configured in the
future without a developer.

The system exposes a small, secure **Barcode API**. LabelJoy connects to that API as a
"data source", pulls the item list (code, name, price, brand, category, stock), and
prints one label per item/quantity using a template you design once.

---

## 1. Overview — how the pieces fit together

```
   EzyPOS  ──(Barcode API, secured by an API key)──►  LabelJoy  ──►  Label Printer
   (item data)                                        (template)      (physical labels)
```

- **EzyPOS** holds your items and generates the barcode value (the item Code).
- **The Barcode API** serves that data as JSON over HTTPS, protected by an API key.
- **LabelJoy** reads the JSON, maps fields to a label template, and prints.

You configure three things, once:
1. The **API key** (in EzyPOS).
2. The **data source** (in LabelJoy, pointing at the API URL + key).
3. The **label template + barcode field** (in LabelJoy).

---

## 2. EzyPOS configuration (where the settings live)

### 2.1 Open the LabelJoy settings page
Log in to EzyPOS as an **Admin** (or a user with the **LabelJoy** permission), then:

**Menu → Settings → LabelJoy API**

Direct URL:
```
https://sub.asrenish.com/barcode-api/settings
```

This page shows your **API Key**, your **API Base URL**, the list of endpoints, and a
built-in **Test API Connection** button.

### 2.2 Generate the API key
- Click **Generate New Key**. A long random key is created and stored.
- Use the **copy** button to copy it exactly (never re-type it — the characters are
  case-sensitive and easy to mistype).
- Regenerating a key **immediately invalidates the previous key**. Any LabelJoy data
  source still using the old key will stop working until you update it.

The key is stored in the EzyPOS database table `ezy_pos_config2` under the config key
`labeljoy_api_key`. There is only ever one active key.

### 2.3 Note your API Base URL
On the same page, copy the **API Base URL**:
```
https://sub.asrenish.com/barcode-api/
```
Every endpoint below is relative to this base.

### 2.4 Permissions
- **Admins** always have access.
- A non-admin user needs the **LabelJoy** module permission (`privLabeljoy`) enabled
  under **Users → permissions** to view the settings page or generate/read the key.

---

## 3. Authentication

Every API call (except `/info`) must include the API key. You can pass it in **any one**
of these three ways — use whichever LabelJoy makes easiest:

| Method | How |
|--------|-----|
| HTTP header (recommended) | `X-API-Key: YOUR_API_KEY` |
| Bearer token | `Authorization: Bearer YOUR_API_KEY` |
| Query parameter | `?api_key=YOUR_API_KEY` |

Responses:
- `401` — no key supplied.
- `403` — key supplied but wrong/invalid.
- `200` — success (JSON body, see below).

---

## 4. API endpoints

Base URL: `https://sub.asrenish.com/barcode-api/`

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `items` | List all active items (optional filters). |
| GET | `item/{id}` | One item by its EzyPOS item id. |
| POST | `batch` | Labels for a set of items, with a `label_count` per item. |
| GET/POST | `batch-flat` | Flat list — **one JSON row per printed label** (best for LabelJoy). |
| GET | `categories` | List of categories (for filtering). |
| GET | `stores` | List of stores. |
| GET | `info` | Self-describing endpoint list (no key needed). |

### 4.1 GET `items`
Optional query params: `search` (matches code/name/brand), `category` (category id),
`store_id` (returns stock for that store; omit for total stock).

Example:
```
GET https://sub.asrenish.com/barcode-api/items?search=BATIK&api_key=YOUR_API_KEY
```
Response:
```json
{
  "success": true,
  "count": 2,
  "items": [
    {
      "item_id": 1,
      "item_code": "KBKCOFB03",
      "barcode_value": "KBKCOFB03",
      "item_name": "BATIK COTTON 3 YARD FABRIC",
      "selling_price": 3250.00,
      "brand": "",
      "category": "MALE",
      "uom": "pcs",
      "stock_qty": 12
    }
  ]
}
```

### 4.2 GET `item/{id}`
```
GET https://sub.asrenish.com/barcode-api/item/1?api_key=YOUR_API_KEY
```
Returns a single `item` object with the same fields as above.

### 4.3 POST `batch` — labels with counts
Send the items and how many labels you want for each. Accepts `item_id` or `item_code`.
```
POST https://sub.asrenish.com/barcode-api/batch
Content-Type: application/json
X-API-Key: YOUR_API_KEY

{ "items": [ { "item_id": 1, "quantity": 10 },
             { "item_code": "RTBKSI002", "quantity": 20 } ] }
```
Response includes `total_labels` and a `labels` array, each with a `label_count`.

### 4.4 GET/POST `batch-flat` — one row per label (recommended for LabelJoy)
This is usually the easiest for LabelJoy because it returns the list already
"exploded" — if you asked for quantity 10 of an item, you get 10 identical rows, so
LabelJoy prints exactly one label per row with no counting logic needed.

GET form (compact `id:qty` pairs):
```
GET https://sub.asrenish.com/barcode-api/batch-flat?items=1:10,2:20&api_key=YOUR_API_KEY
```
POST form (same body shape as `/batch`):
```
POST https://sub.asrenish.com/barcode-api/batch-flat
X-API-Key: YOUR_API_KEY
{ "items": [ { "item_id": 1, "quantity": 10 } ] }
```
Response:
```json
{
  "success": true,
  "total_labels": 30,
  "labels": [
    { "item_code": "KBKCOFB03", "barcode_value": "KBKCOFB03",
      "item_name": "BATIK COTTON 3 YARD FABRIC", "selling_price": 3250.00,
      "brand": "", "category": "MALE" }
  ]
}
```

### 4.5 Field reference (what you can put on a label)
| JSON field | Meaning |
|------------|---------|
| `barcode_value` | The value to encode as the barcode (the item Code). |
| `item_code` | Same as barcode_value, for printing as text. |
| `item_name` | Product name. |
| `selling_price` | Selling price (number). |
| `brand` | Brand (may be blank). |
| `category` | Category name. |
| `uom` | Unit of measure (items endpoint only). |
| `stock_qty` | Stock on hand (items endpoint only). |

---

## 5. LabelJoy configuration

### 5.1 Connect LabelJoy to the API (data source)
LabelJoy can use an external data source. Configure it to read the JSON from the API:

1. In LabelJoy open **Data → Connect to a database / external data** (the "Merge / Data
   sources" area).
2. Choose a **Web / URL / REST (JSON)** data source (LabelJoy reads a URL that returns
   records).
3. Enter the URL, including the key as a query parameter (simplest for a URL-only
   connector):
   ```
   https://sub.asrenish.com/barcode-api/batch-flat?items=1:10,2:20&api_key=YOUR_API_KEY
   ```
   or, to pull the whole catalogue:
   ```
   https://sub.asrenish.com/barcode-api/items?api_key=YOUR_API_KEY
   ```
4. Point the connector at the **`labels`** array (for `batch-flat`/`batch`) or the
   **`items`** array (for `items`) as the list of records/rows.
5. LabelJoy will detect the fields (`barcode_value`, `item_name`, `selling_price`, …).

> Tip: If your LabelJoy connector cannot send an `X-API-Key` header, always use the
> `?api_key=YOUR_API_KEY` query-parameter form shown above — it works everywhere.

### 5.2 Design the label template
1. Create a new label matching your **physical label size** (e.g. 40mm × 25mm) and your
   printer's media.
2. Add a **Barcode** object and bind its value to the **`barcode_value`** field.
3. Add **Text** objects and bind them to `item_name`, `selling_price`, `item_code`, etc.
4. Arrange/scale so the barcode has enough quiet space (blank margin) on both sides.

### 5.3 Barcode format / symbology
- The `barcode_value` is the item **Code** exactly as entered in EzyPOS Item Master.
- Choose the LabelJoy symbology that matches how your scanner reads and how your codes
  look:
  - **Code 128** — recommended default. Encodes letters + digits (most EzyPOS codes,
    e.g. `KBKCOFB03`, are alphanumeric, so Code 128 is the safe choice).
  - **Code 39** — alphanumeric, older scanners.
  - **EAN-13 / UPC-A** — only if your codes are pure numeric barcodes of the exact
    required length. Do **not** use these for alphanumeric codes.
- Whatever you choose in LabelJoy, make sure your **handheld scanner** is enabled for the
  same symbology (most scanners read Code 128 out of the box).
- Keep the **Item Code** in EzyPOS consistent (no spaces / special characters) so the
  barcode scans cleanly back into the Sales screen.

### 5.4 Printer configuration
1. Install your label printer's driver in Windows (e.g. TSC, Zebra, Godex, or an office
   laser/inkjet with label sheets).
2. In LabelJoy choose that printer under **Print → Printer**.
3. Set the **label/media size** to match your stock and the template.
4. Print **one test label**, scan it with your POS scanner, and confirm the scanned
   value equals the Item Code.
5. For thermal printers, set the correct **speed/darkness** so the bars are crisp.

---

## 6. Testing procedure

1. **In EzyPOS**: open **Settings → LabelJoy API**, click **Test API Connection** — it
   should return item data. This proves the key + API work.
2. **From a browser** (quick check), open:
   ```
   https://sub.asrenish.com/barcode-api/items?api_key=YOUR_API_KEY
   ```
   You should see JSON with your items. (`401/403` means the key is missing/wrong.)
3. **In LabelJoy**: refresh the data source and confirm rows appear with the expected
   fields.
4. Print **one** label, then **scan it into the Sales screen** — the item must be found
   by its code.
5. Only then run a full batch.

---

## 7. Troubleshooting

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `{"error":"API key required."}` (401) | No key sent | Add `?api_key=…` or the `X-API-Key` header in the LabelJoy data source URL. |
| `{"error":"Invalid API key."}` (403) | Wrong/old key | Copy the current key from Settings → LabelJoy API; if you regenerated it, update LabelJoy. |
| LabelJoy shows no records | Wrong array selected | Point the connector at the `labels` array (batch/batch-flat) or `items` array (items). |
| Barcode won't scan | Symbology mismatch / code has spaces | Use **Code 128**; ensure the Item Code has no spaces/special characters; enlarge the barcode + keep side margins. |
| Only 1 label prints for a quantity | Using `/batch` (counts) instead of flat list | Use **`/batch-flat`** so there is one row per label. |
| Prices look wrong on the label | Bound to the wrong field | Bind price text to `selling_price`. |
| Works in browser, fails in LabelJoy | Connector can't send headers | Use the `?api_key=` query-parameter URL form. |
| Nothing loads at all | Site/URL wrong | Confirm the base URL `https://sub.asrenish.com/barcode-api/` and that you are logged-in-independent (the API uses the key, not a login session). |

---

## 8. Quick reference card

- **Settings page:** `https://sub.asrenish.com/barcode-api/settings`
- **API base URL:** `https://sub.asrenish.com/barcode-api/`
- **Auth:** `X-API-Key: KEY`  •  `Authorization: Bearer KEY`  •  `?api_key=KEY`
- **Best endpoint for LabelJoy:** `batch-flat?items=ID:QTY,ID:QTY&api_key=KEY`
- **Whole catalogue:** `items?api_key=KEY`
- **Barcode field:** `barcode_value` (= Item Code)  •  **Symbology:** Code 128
- **Key storage:** DB table `ezy_pos_config2`, key `labeljoy_api_key` (one active key)
- **Permission:** Admin or `privLabeljoy`
