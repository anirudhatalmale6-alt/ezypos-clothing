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
                string code = it.EffectiveBarcode;
                if (narrowFor.ContainsKey(code)) continue;
                if (string.IsNullOrEmpty(code))
                {
                    narrowFor[code] = 0;
                    continue;
                }
                int n = Code128.FitNarrowDots(code, usable, spec.BarcodeNarrowDots);
                narrowFor[code] = n;
                if (n == 0) tooLong.Add(code);
                else if (n < spec.BarcodeNarrowDots) shrunk.Add(code);
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
                    int narrow = narrowFor[item.EffectiveBarcode];
                    AppendOneLabel(sb, spec, item, spec.ColumnOriginDots(c), narrow);
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

        private static void AppendOneLabel(StringBuilder sb, LabelSpec spec, LabelItem item, int originX, int narrowDots)
        {
            var inv = CultureInfo.InvariantCulture;
            int x0 = originX + spec.InnerMarginDots;
            int usable = spec.LabelWidthDots - 2 * spec.InnerMarginDots;
            int y = spec.InnerMarginDots;

            if (spec.ShopLine.Length > 0)
            {
                sb.Append(Text(x0, y, "1", 1, 1, Fit(spec.ShopLine, usable, 8)));
                y += 14;
            }

            if (spec.ShowItemName && !string.IsNullOrWhiteSpace(item.ItemName))
            {
                int cw = FontWidth(spec.NameFont);
                sb.Append(Text(x0, y, spec.NameFont, 1, 1, Fit(item.ItemName, usable, cw)));
                y += FontHeight(spec.NameFont) + 3;
            }

            if (spec.ShowItemCode && !string.IsNullOrWhiteSpace(item.ItemCode))
            {
                int cw = FontWidth(spec.CodeFont);
                sb.Append(Text(x0, y, spec.CodeFont, 1, 1, Fit(item.ItemCode, usable, cw)));
                y += FontHeight(spec.CodeFont) + 3;
            }

            // The barcode is the point of the label, so it is placed from the
            // BOTTOM upwards. Whatever text is above it, the bars and the price
            // stay put - which is what stops the layout drifting between one
            // item with a long name and the next with a short one.
            int barH = spec.Mm(spec.BarcodeHeightMm);
            int readableH = spec.ShowBarcodeText ? 20 : 0;
            int priceH = spec.ShowPrice ? FontHeight(spec.PriceFont) * spec.PriceMultiplier : 0;

            int bottom = spec.LabelHeightDots - spec.InnerMarginDots;
            int priceY = bottom - priceH;
            int barY = priceY - (spec.ShowPrice ? 4 : 0) - readableH - barH;
            if (barY < y) barY = y;                       // never overlap the text above

            string code = item.EffectiveBarcode;
            if (!string.IsNullOrEmpty(code) && narrowDots > 0)
            {
                // Centre the bars inside the sticker so a short code does not sit
                // hard against the left edge while a long one fills the width.
                int barW = Code128.WidthDots(code, narrowDots);
                int barX = x0 + Math.Max(0, (usable - barW) / 2);
                sb.Append("BARCODE ").Append(barX).Append(",").Append(barY)
                  .Append(",\"128\",").Append(barH).Append(",")
                  .Append(spec.ShowBarcodeText ? 1 : 0).Append(",0,")
                  .Append(narrowDots).Append(",").Append(narrowDots * 2)
                  .Append(",\"").Append(Escape(code)).Append("\"").Append(CRLF);
            }

            if (spec.ShowPrice)
            {
                string price = spec.CurrencyPrefix + " " + item.SellingPrice.ToString("N2", inv);
                int cw = FontWidth(spec.PriceFont) * spec.PriceMultiplier;
                string txt = Fit(price, usable, cw);
                int w = txt.Length * cw;
                int px = x0 + Math.Max(0, (usable - w) / 2);
                sb.Append(Text(px, priceY, spec.PriceFont, spec.PriceMultiplier, spec.PriceMultiplier, txt));
            }
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
