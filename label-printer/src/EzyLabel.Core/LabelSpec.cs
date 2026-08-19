using System;
using System.Text.Json.Serialization;

namespace EzyLabel.Core
{
    /// <summary>
    /// The physical shape of the label roll, in millimetres, plus the printer
    /// settings that go with it.
    ///
    /// Every number here is adjustable from the Settings tab. Nothing about the
    /// layout is hard coded, because the only way to get a thermal printer to
    /// land exactly on a die-cut sticker is to measure the real roll and adjust.
    ///
    /// The roll this is written for is a 2-up (two stickers side by side)
    /// 38 mm x 25 mm roll on a TSC TTP-244 Pro, which is 203 dpi - exactly
    /// 8 dots per millimetre.
    /// </summary>
    public class LabelSpec
    {
        // ---- the sticker itself -------------------------------------------
        public double LabelWidthMm { get; set; } = 38.0;
        public double LabelHeightMm { get; set; } = 25.0;

        /// <summary>Stickers across the web. 2 for this roll.</summary>
        public int Columns { get; set; } = 2;

        /// <summary>Gap between the left sticker and the right one.</summary>
        public double ColumnGapMm { get; set; } = 2.0;

        /// <summary>
        /// Vertical gap between one row of stickers and the next. This is what
        /// the printer's gap sensor looks for, so it has to be the real
        /// measurement, not a guess.
        /// </summary>
        public double RowGapMm { get; set; } = 2.0;

        // ---- where the printing starts ------------------------------------
        /// <summary>
        /// Distance from the left edge of the paper to the left edge of the
        /// first sticker. Set this from the calibration sheet.
        /// </summary>
        public double LeftMarginMm { get; set; } = 0.0;

        /// <summary>
        /// Fine vertical trim, positive moves the print DOWN the label. Also
        /// from the calibration sheet. TSPL calls this the reference point.
        /// </summary>
        public double TopOffsetMm { get; set; } = 0.0;

        // ---- margins inside one sticker ------------------------------------
        public double InnerMarginMm { get; set; } = 1.5;

        // ---- printer ------------------------------------------------------
        public int Dpi { get; set; } = 203;
        /// <summary>TSPL SPEED, in inches per second. 2-4 suits small labels.</summary>
        public int Speed { get; set; } = 3;
        /// <summary>TSPL DENSITY, 0-15. 8 is the usual starting point.</summary>
        public int Density { get; set; } = 8;
        /// <summary>TSPL DIRECTION. 1 prints "the right way up" out of the front.</summary>
        public int Direction { get; set; } = 1;
        /// <summary>Mirror the image. Almost always 0.</summary>
        public int Mirror { get; set; } = 0;

        // ---- what goes on the label ---------------------------------------
        public bool ShowItemName { get; set; } = true;
        public bool ShowItemCode { get; set; } = true;
        public bool ShowPrice { get; set; } = true;
        public bool ShowBarcodeText { get; set; } = true;
        public string CurrencyPrefix { get; set; } = "Rs.";
        public string ShopLine { get; set; } = "";

        /// <summary>Height of the bars themselves, without the readable text.</summary>
        public double BarcodeHeightMm { get; set; } = 8.0;

        /// <summary>
        /// Width of the narrowest bar, in dots. 2 gives a comfortably scannable
        /// barcode; the builder drops to 1 by itself if the code is too long to
        /// fit at 2, and refuses the job if even 1 will not fit.
        /// </summary>
        public int BarcodeNarrowDots { get; set; } = 2;

        // ---- font sizes, as TSPL built-in font numbers ----------------------
        public string NameFont { get; set; } = "2";
        public string CodeFont { get; set; } = "1";
        public string PriceFont { get; set; } = "3";
        public int PriceMultiplier { get; set; } = 1;

        // ---- derived ------------------------------------------------------
        [JsonIgnore] public double DotsPerMm => Dpi / 25.4;
        [JsonIgnore] public int LabelWidthDots => Mm(LabelWidthMm);
        [JsonIgnore] public int LabelHeightDots => Mm(LabelHeightMm);
        [JsonIgnore] public int ColumnGapDots => Mm(ColumnGapMm);
        [JsonIgnore] public int LeftMarginDots => Mm(LeftMarginMm);
        [JsonIgnore] public int InnerMarginDots => Mm(InnerMarginMm);

        /// <summary>
        /// The width TSPL is told about. On a 2-up roll the printer feeds one
        /// ROW at a time, and that row is both stickers plus the gap between
        /// them, so that - not one sticker - is the SIZE. Getting this wrong is
        /// what makes a 2-up roll print as if it were one wide label.
        /// </summary>
        [JsonIgnore]
        public double WebWidthMm => LeftMarginMm + Columns * LabelWidthMm + (Columns - 1) * ColumnGapMm;

        public int Mm(double mm) => (int)Math.Round(mm * DotsPerMm);

        /// <summary>Left edge of sticker <paramref name="col"/> (0-based), in dots.</summary>
        public int ColumnOriginDots(int col) => LeftMarginDots + col * (LabelWidthDots + ColumnGapDots);

        public LabelSpec Clone() => (LabelSpec)MemberwiseClone();

        /// <summary>
        /// Sanity check before anything is sent to the printer. Returns null if
        /// the settings make physical sense, or a sentence explaining what does
        /// not.
        /// </summary>
        public string Validate()
        {
            if (LabelWidthMm <= 0 || LabelHeightMm <= 0) return "Label width and height must both be more than zero.";
            if (Columns < 1 || Columns > 4) return "Labels across must be between 1 and 4.";
            if (ColumnGapMm < 0 || RowGapMm < 0) return "The gaps cannot be negative.";
            if (Dpi <= 0) return "Printer resolution must be more than zero.";
            if (InnerMarginMm * 2 >= LabelWidthMm) return "The inner margin is wider than the label itself.";
            if (BarcodeHeightMm <= 0 || BarcodeHeightMm >= LabelHeightMm) return "The barcode height must fit inside the label.";
            if (Speed < 1 || Speed > 12) return "Speed should be between 1 and 12.";
            if (Density < 0 || Density > 15) return "Density should be between 0 and 15.";
            if (BarcodeNarrowDots < 1 || BarcodeNarrowDots > 6) return "The narrow bar width should be between 1 and 6 dots.";
            return null;
        }
    }
}
