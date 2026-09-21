using System.Collections.Generic;
using System.Text.Json.Serialization;

namespace EzyLabel.Core
{
    /// <summary>One item from the POS, and how many stickers are wanted for it.</summary>
    public class LabelItem
    {
        [JsonPropertyName("item_id")]       public int ItemId { get; set; }
        [JsonPropertyName("item_code")]     public string ItemCode { get; set; } = "";
        [JsonPropertyName("barcode_value")] public string BarcodeValue { get; set; } = "";
        [JsonPropertyName("item_name")]     public string ItemName { get; set; } = "";
        [JsonPropertyName("selling_price")] public double SellingPrice { get; set; }
        [JsonPropertyName("brand")]         public string Brand { get; set; } = "";
        [JsonPropertyName("category")]      public string Category { get; set; } = "";
        [JsonPropertyName("uom")]           public string Uom { get; set; } = "";
        [JsonPropertyName("stock_qty")]     public double StockQty { get; set; }

        /// <summary>How many stickers to print. Not sent by the POS - set here.</summary>
        [JsonPropertyName("label_count")]   public int Quantity { get; set; } = 1;

        /// <summary>Shown in the queue: Ready, Printed, or the reason it cannot print.</summary>
        [JsonIgnore] public string Status { get; set; } = "Ready";

        // ---- this item's own sticker ---------------------------------------
        // One item can need treating differently - a name too long for the
        // usual font, or a code that wants thinner bars. Everything here is
        // "leave it alone" by default: 0 means use the roll's setting.

        /// <summary>Shift this item's whole sticker, in millimetres.</summary>
        [JsonPropertyName("offset_x_mm")] public double OffsetXMm { get; set; } = 0;
        [JsonPropertyName("offset_y_mm")] public double OffsetYMm { get; set; } = 0;

        /// <summary>Override the item-name font for this item. Empty = use the roll's.</summary>
        [JsonPropertyName("name_font")] public string NameFont { get; set; } = "";

        /// <summary>Override the bar height for this item, in mm. 0 = use the roll's.</summary>
        [JsonPropertyName("barcode_height_mm")] public double BarcodeHeightMm { get; set; } = 0;

        /// <summary>Override the narrow bar width for this item, in dots. 0 = use the roll's.</summary>
        [JsonPropertyName("narrow_dots")] public int NarrowDots { get; set; } = 0;

        /// <summary>True when anything about this item's sticker has been changed by hand.</summary>
        [JsonIgnore]
        public bool HasOwnLayout =>
            System.Math.Abs(OffsetXMm) > 0.001 || System.Math.Abs(OffsetYMm) > 0.001
            || !string.IsNullOrWhiteSpace(NameFont)
            || BarcodeHeightMm > 0.001 || NarrowDots > 0;

        /// <summary>What actually goes under the bars. Falls back to the item code.</summary>
        [JsonIgnore]
        public string EffectiveBarcode =>
            !string.IsNullOrWhiteSpace(BarcodeValue) ? BarcodeValue.Trim() : (ItemCode ?? "").Trim();

        public LabelItem Clone() => (LabelItem)MemberwiseClone();
    }

    public class ItemsResponse
    {
        [JsonPropertyName("success")] public bool Success { get; set; }
        [JsonPropertyName("count")]   public int Count { get; set; }
        [JsonPropertyName("items")]   public List<LabelItem> Items { get; set; } = new List<LabelItem>();
        [JsonPropertyName("error")]   public string Error { get; set; }
    }

    public class BatchResponse
    {
        [JsonPropertyName("success")]      public bool Success { get; set; }
        [JsonPropertyName("total_items")]  public int TotalItems { get; set; }
        [JsonPropertyName("total_labels")] public int TotalLabels { get; set; }
        [JsonPropertyName("labels")]       public List<LabelItem> Labels { get; set; } = new List<LabelItem>();
        [JsonPropertyName("error")]        public string Error { get; set; }
    }
}
