using System;
using System.Collections.Generic;
using System.Text.Json.Serialization;

namespace EzyLabel.Core
{
    /// <summary>
    /// A nudge in millimetres. Used to shift one column of the roll, one row,
    /// or one item's sticker, without disturbing anything else.
    ///
    /// Die-cut rolls are not perfect. The right-hand sticker is often a
    /// fraction out from the left one, and that fraction is constant for the
    /// whole roll - so it belongs here and not in the layout.
    /// </summary>
    public class Nudge
    {
        public double XMm { get; set; } = 0;
        public double YMm { get; set; } = 0;

        [JsonIgnore] public bool IsZero => Math.Abs(XMm) < 0.001 && Math.Abs(YMm) < 0.001;
        public Nudge Clone() => new Nudge { XMm = XMm, YMm = YMm };
    }

    /// <summary>
    /// Per-line control: where one line sits, how big it is, how it lines up.
    ///
    /// Every value here is "leave it alone" by default - an empty font and a
    /// scale of 0 mean "use the setting this element already had", so an older
    /// settings.json keeps printing exactly as it did.
    /// </summary>
    public class ElementTweak
    {
        /// <summary>left | center | right. Centre is what the label has always done.</summary>
        public string Align { get; set; } = "center";
        public double OffsetXMm { get; set; } = 0;
        public double OffsetYMm { get; set; } = 0;
        /// <summary>TSPL character multiplier. 0 means use this element's normal size.</summary>
        public int ScaleX { get; set; } = 0;
        public int ScaleY { get; set; } = 0;
        /// <summary>TSPL built-in font number. Empty means use this element's normal font.</summary>
        public string Font { get; set; } = "";

        public string FontOr(string fallback) => string.IsNullOrWhiteSpace(Font) ? fallback : Font.Trim();
        public int ScaleXOr(int fallback) => ScaleX > 0 ? ScaleX : fallback;
        public int ScaleYOr(int fallback) => ScaleY > 0 ? ScaleY : fallback;

        public ElementTweak Clone() => (ElementTweak)MemberwiseClone();
    }

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

        /// <summary>
        /// In this shop the item code and the barcode number are the same
        /// string, so printing both put the same text on the sticker twice and
        /// pushed everything else apart. When they match, the readable number
        /// under the bars is the one that stays.
        ///
        /// Turn this off if the two are ever different and both are wanted.
        /// </summary>
        public bool HideCodeWhenSameAsBarcode { get; set; } = true;

        /// <summary>
        /// Space between one line of the label and the next. Small on purpose:
        /// a 25 mm sticker has no room to waste, and the item name sitting
        /// right above the bars is what makes the label readable at arm's
        /// length. Raise it if the print looks cramped on your roll.
        /// </summary>
        public double LineGapMm { get; set; } = 0.4;
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

        // ---- per line: where it sits, how big, how it lines up --------------
        // These default to "leave it alone", so a settings.json written before
        // they existed prints exactly as it did.
        public ElementTweak ShopLineTweak { get; set; } = new ElementTweak();
        public ElementTweak NameTweak     { get; set; } = new ElementTweak();
        public ElementTweak CodeTweak     { get; set; } = new ElementTweak();
        public ElementTweak BarcodeTweak  { get; set; } = new ElementTweak();
        public ElementTweak PriceTweak    { get; set; } = new ElementTweak();

        // ---- per column and per row ----------------------------------------
        /// <summary>
        /// One nudge per sticker across the web. Index 0 is the left column.
        /// A die-cut roll whose right-hand sticker sits 0.3 mm low is fixed
        /// here, once, instead of by moving the whole layout.
        /// </summary>
        public List<Nudge> ColumnNudges { get; set; } = new List<Nudge>();

        /// <summary>
        /// Nudges applied to rows as they print, repeating. One entry shifts
        /// every row; two entries alternate, which is what a roll that wanders
        /// by a fixed amount every other row needs.
        /// </summary>
        public List<Nudge> RowNudges { get; set; } = new List<Nudge>();

        /// <summary>The nudge for a column, or nothing if none is set.</summary>
        public Nudge ColumnNudge(int col)
        {
            if (ColumnNudges == null || col < 0 || col >= ColumnNudges.Count) return null;
            return ColumnNudges[col];
        }

        /// <summary>The nudge for a row. The list repeats, so two entries alternate.</summary>
        public Nudge RowNudge(int row)
        {
            if (RowNudges == null || RowNudges.Count == 0 || row < 0) return null;
            return RowNudges[row % RowNudges.Count];
        }

        /// <summary>Make sure there is one nudge per column, so the editor has something to bind to.</summary>
        public void EnsureNudgeSlots()
        {
            if (ColumnNudges == null) ColumnNudges = new List<Nudge>();
            while (ColumnNudges.Count < Columns) ColumnNudges.Add(new Nudge());
            while (ColumnNudges.Count > Columns && ColumnNudges.Count > 0) ColumnNudges.RemoveAt(ColumnNudges.Count - 1);
            if (RowNudges == null) RowNudges = new List<Nudge>();
            while (RowNudges.Count < 2) RowNudges.Add(new Nudge());
        }

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

        /// <summary>
        /// Left edge of sticker <paramref name="col"/> (0-based), in dots,
        /// including that column's own nudge.
        /// </summary>
        public int ColumnOriginDots(int col)
        {
            int x = LeftMarginDots + col * (LabelWidthDots + ColumnGapDots);
            var n = ColumnNudge(col);
            if (n != null) { x += Mm(n.XMm); }
            return x;
        }

        /// <summary>The extra vertical shift for a sticker, from its column and its row.</summary>
        public int CellOffsetYDots(int row, int col)
        {
            int y = 0;
            var c = ColumnNudge(col); if (c != null) { y += Mm(c.YMm); }
            var r = RowNudge(row);    if (r != null) { y += Mm(r.YMm); }
            return y;
        }

        /// <summary>The extra horizontal shift a row asks for, on top of the column.</summary>
        public int RowOffsetXDots(int row)
        {
            var r = RowNudge(row);
            return r != null ? Mm(r.XMm) : 0;
        }

        /// <summary>
        /// A deep copy. MemberwiseClone alone would hand the copy the SAME
        /// tweak and nudge objects, so editing the copy would silently edit the
        /// original - which is exactly what a preview must not do.
        /// </summary>
        public LabelSpec Clone()
        {
            var c = (LabelSpec)MemberwiseClone();
            c.ShopLineTweak = ShopLineTweak != null ? ShopLineTweak.Clone() : new ElementTweak();
            c.NameTweak     = NameTweak     != null ? NameTweak.Clone()     : new ElementTweak();
            c.CodeTweak     = CodeTweak     != null ? CodeTweak.Clone()     : new ElementTweak();
            c.BarcodeTweak  = BarcodeTweak  != null ? BarcodeTweak.Clone()  : new ElementTweak();
            c.PriceTweak    = PriceTweak    != null ? PriceTweak.Clone()    : new ElementTweak();
            c.ColumnNudges  = new List<Nudge>();
            if (ColumnNudges != null) { foreach (var n in ColumnNudges) c.ColumnNudges.Add(n.Clone()); }
            c.RowNudges = new List<Nudge>();
            if (RowNudges != null) { foreach (var n in RowNudges) c.RowNudges.Add(n.Clone()); }
            return c;
        }

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
            if (LineGapMm < 0 || LineGapMm > 5) return "The gap between lines should be between 0 and 5 mm.";
            return null;
        }
    }
}
