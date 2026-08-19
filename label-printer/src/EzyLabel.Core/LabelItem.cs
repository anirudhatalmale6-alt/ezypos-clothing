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
