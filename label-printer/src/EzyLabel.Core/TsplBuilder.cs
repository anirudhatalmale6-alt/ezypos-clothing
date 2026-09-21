using System;
using System.Collections.Generic;
using System.Globalization;
using System.Text;

namespace EzyLabel.Core
{
    public class BuildResult
    {
        /// <summary>The TSPL to send to the printer, exactly as bytes.</summary>
        public string Tspl { get; set; } = "";
        /// <summary>Stickers this job will produce.</summary>
        public int LabelCount { get; set; }
        /// <summary>Rows of the web that will be fed. On a 2-up roll this is half the stickers, rounded up.</summary>
        public int RowCount { get; set; }
        /// <summary>Anything the operator should know but which does not stop the job.</summary>
        public List<string> Warnings { get; } = new List<string>();
        /// <summary>Set when the job must not be printed. Null when it is fine.</summary>
        public string Error { get; set; }
        /// <summary>One entry per sticker, in print order. Used by the preview and the proof.</summary>
        public List<PlacedLabel> Placed { get; } = new List<PlacedLabel>();
    }

    /// <summary>Where one sticker landed: which row, which column across.</summary>
    public class PlacedLabel
    {
        public int Row;
        public int Column;
        public LabelItem Item;
        public int NarrowDots;
    }

    /// <summary>
    /// Turns a print queue into TSPL for a TSC TTP-244 Pro.
    ///
    /// THE ONE THING THAT MATTERS ON A 2-UP ROLL
    /// -----------------------------------------
    /// The printer does not know there are two stickers side by side. It feeds
    /// the paper one ROW at a time and looks for the die-cut gap between rows
    /// with its sensor. So:
    ///
    ///   SIZE  = the width of the whole row (both stickers plus the gap between
    ///           them), NOT the width of one sticker
    ///   GAP   = the vertical gap between one row and the next, which is the
    ///           only gap the sensor can see
    ///
    /// Both stickers of a row are then drawn into that one image, the right-hand
    /// one shifted across by (label width + column gap). Every row is a fresh
    /// CLS ... PRINT 1,1, so nothing carries over and the alignment cannot
    /// creep as the job goes on.
    ///
    /// Set SIZE to one sticker instead, and the printer treats the roll as one
    /// wide label, feeds half a row at a time and the whole job walks off the
    /// paper. That is the failure the brief is describing.
    /// </summary>
    public static class TsplBuilder
    {
        private const string CRLF = "\r\n";

        /// <summary>
        /// Expand a queue into one entry per sticker, in the order they will be
        /// printed. Item A x10 then Item B x20 gives ten A's followed by twenty
        /// B's, with no break between them - so the stickers come off the roll
        /// continuously and in order, and no sticker is left blank in the middle
        /// of the job.
        /// </summary>
        public static List<LabelItem> Flatten(IEnumerable<LabelItem> queue)
        {
            var flat = new List<LabelItem>();
            foreach (var it in queue)
            {
                int n = Math.Max(0, it.Quantity);
                for (int i = 0; i < n; i++) flat.Add(it);
            }
            return flat;
        }

        public static BuildResult Build(IEnumerable<LabelItem> queue, LabelSpec spec)
        {
            var result = new BuildResult();

            string bad = spec.Validate();
            if (bad != null) { result.Error = bad; return result; }

            var flat = Flatten(queue);
            if (flat.Count == 0) { result.Error = "There is nothing in the print queue."; return result; }

            int usable = spec.LabelWidthDots - 2 * spec.InnerMarginDots;

            // Work out the barcode width for every distinct code BEFORE printing
            // anything. A code that cannot fit is named, and the whole job is
            // refused rather than printing a barcode that runs over the edge.
            var narrowFor = new Dictionary<string, int>(StringComparer.Ordinal);
            var tooLong = new List<string>();
            var shrunk = new List<string>();
            foreach (var it in flat)
            {
                // An item can ask for its own bar width, so the answer depends
                // on the code AND on what was asked for - not the code alone.
                int want = it.NarrowDots > 0 ? it.NarrowDots : spec.BarcodeNarrowDots;
                string code = it.EffectiveBarcode;
                string key = code + "\u0001" + want;
                if (narrowFor.ContainsKey(key)) continue;
                if (string.IsNullOrEmpty(code))
                {
                    narrowFor[key] = 0;
                    continue;
                }
                int n = Code128.FitNarrowDots(code, usable, want);
                narrowFor[key] = n;
                if (n == 0) tooLong.Add(code);
                else if (n < want) shrunk.Add(code);
            }

            if (tooLong.Count > 0)
            {
                result.Error =
                    "These item codes are too long to fit a barcode inside a "
                    + spec.LabelWidthMm.ToString("0.#", CultureInfo.InvariantCulture)
                    + " mm sticker, even at the thinnest bar width: "
                    + string.Join(", ", tooLong)
                    + ". Shorten the item code, or use a wider label. Nothing has been printed.";
                return result;
            }
            if (shrunk.Count > 0)
            {
                result.Warnings.Add(
                    "Thinner bars used so the barcode fits the sticker for: " + string.Join(", ", shrunk)
                    + ". These still scan, but check one on your scanner before printing a large run.");
            }

            int cols = spec.Columns;
            int rows = (flat.Count + cols - 1) / cols;
            result.LabelCount = flat.Count;
            result.RowCount = rows;
            if (flat.Count % cols != 0)
            {
                result.Warnings.Add(
                    "The total is an odd number, so the very last sticker on the roll is left blank. "
                    + "Add one more label to fill it if you would rather not waste it.");
            }

            // A nudge is free to move a column or a row - that is the point of
            // it - but it can also push the printing off the edge of the paper,
            // where it simply will not appear. Say so before the run starts.
            // Measured in dots the same way the columns are, so rounding the
            // millimetres does not make a column look one dot too wide.
            int webDots = spec.LeftMarginDots + cols * spec.LabelWidthDots + (cols - 1) * spec.ColumnGapDots;
            var offPaper = new List<string>();
            for (int c = 0; c < cols; c++)
            {
                int x0 = spec.ColumnOriginDots(c);
                if (x0 < 0) offPaper.Add("column " + (c + 1) + " goes off the left edge");
                else if (x0 + spec.LabelWidthDots > webDots) offPaper.Add("column " + (c + 1) + " goes off the right edge");
            }
            for (int r = 0; r < Math.Min(rows, 2); r++)
            {
                if (spec.CellOffsetYDots(r, 0) < 0) offPaper.Add("row " + (r + 1) + " starts above the sticker");
            }
            if (offPaper.Count > 0)
            {
                result.Warnings.Add(
                    "Some of the printing has been moved off the paper - " + string.Join(", ", offPaper)
                    + ". Reduce the move on the Layout tab, or nothing will appear there.");
            }

            var sb = new StringBuilder();
            AppendJobHeader(sb, spec, flat.Count, rows);

            for (int r = 0; r < rows; r++)
            {
                sb.Append("CLS").Append(CRLF);
                for (int c = 0; c < cols; c++)
                {
                    int idx = r * cols + c;
                    if (idx >= flat.Count) break;         // odd total: last cell stays empty
                    var item = flat[idx];
                    int narrow = narrowFor[item.EffectiveBarcode + "\u0001"
                                           + (item.NarrowDots > 0 ? item.NarrowDots : spec.BarcodeNarrowDots)];
                    // Where this one sticker lands: the column, plus that
                    // column's nudge, plus this row's nudge, plus anything set
                    // on the item itself.
                    int ox = spec.ColumnOriginDots(c) + spec.RowOffsetXDots(r) + spec.Mm(item.OffsetXMm);
                    int oy = spec.CellOffsetYDots(r, c) + spec.Mm(item.OffsetYMm);
                    AppendOneLabel(sb, spec, item, ox, oy, narrow);
                    result.Placed.Add(new PlacedLabel { Row = r, Column = c, Item = item, NarrowDots = narrow });
                }
                // One row at a time. Letting the printer repeat with PRINT n,1
                // would be fewer bytes, but then every row of the job would be
                // identical - which is only true when every sticker is the same
                // item.
                sb.Append("PRINT 1,1").Append(CRLF);
            }

            result.Tspl = sb.ToString();
            return result;
        }

        private static void AppendJobHeader(StringBuilder sb, LabelSpec spec, int labels, int rows)
        {
            var inv = CultureInfo.InvariantCulture;
            sb.Append("; EzyPOS label job - ").Append(labels).Append(" labels in ")
              .Append(rows).Append(" rows of ").Append(spec.Columns).Append(CRLF);
            sb.Append("; media ").Append(spec.LabelWidthMm.ToString("0.##", inv)).Append(" x ")
              .Append(spec.LabelHeightMm.ToString("0.##", inv)).Append(" mm, ")
              .Append(spec.Columns).Append("-up, column gap ")
              .Append(spec.ColumnGapMm.ToString("0.##", inv)).Append(" mm").Append(CRLF);

            // SIZE is the WHOLE ROW. See the note at the top of this file.
            sb.Append("SIZE ").Append(spec.WebWidthMm.ToString("0.##", inv)).Append(" mm, ")
              .Append(spec.LabelHeightMm.ToString("0.##", inv)).Append(" mm").Append(CRLF);
            sb.Append("GAP ").Append(spec.RowGapMm.ToString("0.##", inv)).Append(" mm, 0 mm").Append(CRLF);
            sb.Append("DIRECTION ").Append(spec.Direction).Append(",").Append(spec.Mirror).Append(CRLF);
            // The reference point is the vertical trim from the calibration
            // sheet. X stays 0 - the left margin is applied per column instead,
            // so the two stickers stay the correct distance apart whatever the
            // margin is set to.
            sb.Append("REFERENCE 0,").Append(spec.Mm(spec.TopOffsetMm)).Append(CRLF);
            sb.Append("OFFSET 0 mm").Append(CRLF);
            sb.Append("SPEED ").Append(spec.Speed).Append(CRLF);
            sb.Append("DENSITY ").Append(spec.Density).Append(CRLF);
            sb.Append("SET COUNTER @0 1").Append(CRLF);
            sb.Append("SET TEAR ON").Append(CRLF);
            sb.Append("CODEPAGE UTF-8").Append(CRLF);
            // No CLS here on purpose - every row opens with its own.
        }

        /// <summary>
        /// Should the separate item-code line be printed at all?
        ///
        /// In this shop the item code and the barcode number are the same
        /// string. Printing both put it on the sticker twice and pushed the
        /// name away from the bars, so when they match the readable number
        /// under the barcode is the one that is kept.
        /// </summary>
        public static bool WantsCodeLine(LabelSpec spec, LabelItem item)
        {
            if (!spec.ShowItemCode || string.IsNullOrWhiteSpace(item.ItemCode)) return false;
            if (!spec.HideCodeWhenSameAsBarcode) return true;
            // Only the readable number counts as "already shown".
            if (!spec.ShowBarcodeText) return true;
            string bar = item.EffectiveBarcode;
            if (string.IsNullOrWhiteSpace(bar)) return true;
            return !string.Equals(bar.Trim(), item.ItemCode.Trim(), StringComparison.OrdinalIgnoreCase);
        }

        private static void AppendOneLabel(StringBuilder sb, LabelSpec spec, LabelItem item,
                                           int originX, int originY, int narrowDots)
        {
            var inv = CultureInfo.InvariantCulture;
            int x0 = originX + spec.InnerMarginDots;
            int usable = spec.LabelWidthDots - 2 * spec.InnerMarginDots;

            // Everything on the sticker is one line high, so the block is the
            // same height for every item on the roll. That is measured first,
            // then centred - which is what keeps the bars in the same place on
            // every sticker whether the item name is long or short. It also
            // means the only space between the name and the barcode is the one
            // line gap below, instead of the name being pinned to the top of
            // the label and the barcode to the bottom of it.
            int gap = spec.Mm(spec.LineGapMm);
            if (gap < 1) gap = 1;

            bool wantShop  = spec.ShopLine.Length > 0;
            bool wantName  = spec.ShowItemName && !string.IsNullOrWhiteSpace(item.ItemName);
            bool wantCode  = WantsCodeLine(spec, item);
            string code    = item.EffectiveBarcode;
            bool wantBars  = !string.IsNullOrEmpty(code) && narrowDots > 0;
            bool wantPrice = spec.ShowPrice;

            // Each line's own font and size. An item can override the name
            // font, and every line can be re-sized from the Layout tab; 0 or an
            // empty string anywhere means "as it was".
            var tShop = spec.ShopLineTweak ?? new ElementTweak();
            var tName = spec.NameTweak     ?? new ElementTweak();
            var tCode = spec.CodeTweak     ?? new ElementTweak();
            var tBar  = spec.BarcodeTweak  ?? new ElementTweak();
            var tPrice= spec.PriceTweak    ?? new ElementTweak();

            string shopFont  = tShop.FontOr("1");
            // The item's own font wins: it is the most specific setting there is,
            // and it was set precisely because this one name would not fit.
            string nameFont  = !string.IsNullOrWhiteSpace(item.NameFont)
                             ? item.NameFont.Trim()
                             : tName.FontOr(spec.NameFont);
            string codeFont  = tCode.FontOr(spec.CodeFont);
            string priceFont = tPrice.FontOr(spec.PriceFont);

            int shopSX = tShop.ScaleXOr(1),  shopSY = tShop.ScaleYOr(1);
            int nameSX = tName.ScaleXOr(1),  nameSY = tName.ScaleYOr(1);
            int codeSX = tCode.ScaleXOr(1),  codeSY = tCode.ScaleYOr(1);
            int priceSX = tPrice.ScaleXOr(spec.PriceMultiplier);
            int priceSY = tPrice.ScaleYOr(spec.PriceMultiplier);

            // Bar height and bar width can be set for this item alone.
            double barMm = item.BarcodeHeightMm > 0.001 ? item.BarcodeHeightMm : spec.BarcodeHeightMm;

            int shopH  = wantShop  ? FontHeight(shopFont) * shopSY : 0;
            int nameH  = wantName  ? FontHeight(nameFont) * nameSY : 0;
            int codeH  = wantCode  ? FontHeight(codeFont) * codeSY : 0;
            int barH   = spec.Mm(barMm);
            // The human-readable number the printer draws under the bars.
            int readH  = spec.ShowBarcodeText ? 20 : 0;
            int barsH  = wantBars ? barH + readH : 0;
            int priceH = wantPrice ? FontHeight(priceFont) * priceSY : 0;

            int blocks = (wantShop ? 1 : 0) + (wantName ? 1 : 0) + (wantCode ? 1 : 0)
                       + (wantBars ? 1 : 0) + (wantPrice ? 1 : 0);
            int total = shopH + nameH + codeH + barsH + priceH + (blocks > 1 ? (blocks - 1) * gap : 0);

            int inner = spec.LabelHeightDots - 2 * spec.InnerMarginDots;
            int y = originY + spec.InnerMarginDots + Math.Max(0, (inner - total) / 2);

            if (wantShop)
            {
                int cw = FontWidth(shopFont) * shopSX;
                sb.Append(Placed(spec, tShop, x0, y, usable, shopFont, shopSX, shopSY,
                                 Fit(spec.ShopLine, usable, cw)));
                y += shopH + gap;
            }

            if (wantName)
            {
                int cw = FontWidth(nameFont) * nameSX;
                sb.Append(Placed(spec, tName, x0, y, usable, nameFont, nameSX, nameSY,
                                 Fit(item.ItemName, usable, cw)));
                y += nameH + gap;
            }

            if (wantCode)
            {
                int cw = FontWidth(codeFont) * codeSX;
                sb.Append(Placed(spec, tCode, x0, y, usable, codeFont, codeSX, codeSY,
                                 Fit(item.ItemCode, usable, cw)));
                y += codeH + gap;
            }

            if (wantBars)
            {
                // Lined up inside the sticker the way the Layout tab asks -
                // centred unless told otherwise, so a short code does not sit
                // hard against the left edge while a long one fills the width.
                int barW = Code128.WidthDots(code, narrowDots);
                int barX = AlignedX(x0, usable, barW, tBar.Align) + spec.Mm(tBar.OffsetXMm);
                int barY = y + spec.Mm(tBar.OffsetYMm);
                sb.Append("BARCODE ").Append(barX).Append(",").Append(barY)
                  .Append(",\"128\",").Append(barH).Append(",")
                  .Append(spec.ShowBarcodeText ? 1 : 0).Append(",0,")
                  .Append(narrowDots).Append(",").Append(narrowDots * 2)
                  .Append(",\"").Append(Escape(code)).Append("\"").Append(CRLF);
                y += barsH + gap;
            }

            if (wantPrice)
            {
                string price = spec.CurrencyPrefix + " " + item.SellingPrice.ToString("N2", inv);
                int cw = FontWidth(priceFont) * priceSX;
                sb.Append(Placed(spec, tPrice, x0, y, usable, priceFont, priceSX, priceSY,
                                 Fit(price, usable, cw)));
            }
        }

        /// <summary>Left edge of something <paramref name="w"/> dots wide, lined up as asked.</summary>
        public static int AlignedX(int x0, int usableDots, int w, string align)
        {
            if (string.Equals(align, "left", StringComparison.OrdinalIgnoreCase)) return x0;
            if (string.Equals(align, "right", StringComparison.OrdinalIgnoreCase))
                return x0 + Math.Max(0, usableDots - w);
            return x0 + Math.Max(0, (usableDots - w) / 2);
        }

        /// <summary>One line of text, lined up and nudged the way the Layout tab asks.</summary>
        private static string Placed(LabelSpec spec, ElementTweak t, int x0, int y, int usableDots,
                                     string font, int sx, int sy, string content)
        {
            int cw = FontWidth(font) * sx;
            int w = content.Length * cw;
            int x = AlignedX(x0, usableDots, w, t.Align) + spec.Mm(t.OffsetXMm);
            return Text(x, y + spec.Mm(t.OffsetYMm), font, sx, sy, content);
        }

        private static string Text(int x, int y, string font, int xm, int ym, string content)
        {
            return "TEXT " + x + "," + y + ",\"" + font + "\",0," + xm + "," + ym
                 + ",\"" + Escape(content) + "\"" + CRLF;
        }

        /// <summary>
        /// TSPL takes its arguments inside double quotes, so a quote or a
        /// backslash in an item name has to be escaped or the printer sees a
        /// broken command and silently drops the whole label.
        /// </summary>
        public static string Escape(string s)
        {
            if (string.IsNullOrEmpty(s)) return "";
            return s.Replace("\\", "\\\\").Replace("\"", "\\\"")
                    .Replace("\r", " ").Replace("\n", " ");
        }

        /// <summary>Cut a string to what will physically fit, rather than let it run off the sticker.</summary>
        public static string Fit(string s, int availableDots, int charWidthDots)
        {
            if (string.IsNullOrEmpty(s) || charWidthDots <= 0) return s ?? "";
            int max = availableDots / charWidthDots;
            if (max <= 0) return "";
            s = s.Trim();
            return s.Length <= max ? s : s.Substring(0, max);
        }

        // TSC built-in bitmap fonts, in dots. These are fixed by the firmware.
        public static int FontWidth(string font)
        {
            switch (font)
            {
                case "1": return 8;
                case "2": return 12;
                case "3": return 16;
                case "4": return 24;
                case "5": return 32;
                default:  return 12;
            }
        }
        public static int FontHeight(string font)
        {
            switch (font)
            {
                case "1": return 12;
                case "2": return 20;
                case "3": return 24;
                case "4": return 32;
                case "5": return 48;
                default:  return 20;
            }
        }

        /// <summary>
        /// A one-row sheet of rulers and reference marks. Print it once, measure
        /// where the marks actually land on your stickers, and put the two
        /// numbers into Settings. That is the whole calibration - no guessing at
        /// offsets in the dark.
        /// </summary>
        public static string BuildCalibrationSheet(LabelSpec spec)
        {
            var inv = CultureInfo.InvariantCulture;
            var sb = new StringBuilder();
            AppendJobHeader(sb, spec, 1, 1);
            sb.Append("CLS").Append(CRLF);

            for (int c = 0; c < spec.Columns; c++)
            {
                int ox = spec.ColumnOriginDots(c);
                int w = spec.LabelWidthDots;
                int h = spec.LabelHeightDots;

                // Outline of where the software thinks this sticker is. If the
                // printed rectangle does not sit on the die-cut edge, the
                // difference is exactly what goes into the margin settings.
                sb.Append("BOX ").Append(ox).Append(",0,").Append(ox + w - 1).Append(",").Append(h - 1).Append(",2").Append(CRLF);

                // A tick every 5 mm along the top edge, numbered.
                for (int mm = 0; mm <= (int)spec.LabelWidthMm; mm += 5)
                {
                    int x = ox + spec.Mm(mm);
                    sb.Append("BAR ").Append(x).Append(",0,2,").Append(mm % 10 == 0 ? 24 : 12).Append(CRLF);
                    if (mm % 10 == 0)
                        sb.Append(Text(x + 3, 26, "1", 1, 1, mm.ToString(inv)));
                }
                // A tick every 5 mm down the left edge.
                for (int mm = 0; mm <= (int)spec.LabelHeightMm; mm += 5)
                {
                    int yy = spec.Mm(mm);
                    sb.Append("BAR ").Append(ox).Append(",").Append(yy).Append(",")
                      .Append(mm % 10 == 0 ? 24 : 12).Append(",2").Append(CRLF);
                }
                sb.Append(Text(ox + 8, h / 2 - 10, "2", 1, 1, "COL " + (c + 1)));
                sb.Append(Text(ox + 8, h / 2 + 12, "1", 1, 1,
                          spec.LabelWidthMm.ToString("0.#", inv) + "x" + spec.LabelHeightMm.ToString("0.#", inv) + "mm"));
            }
            sb.Append("PRINT 1,1").Append(CRLF);
            return sb.ToString();
        }
    }
}
