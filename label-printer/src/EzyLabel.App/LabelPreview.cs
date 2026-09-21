using System;
using System.Collections.Generic;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Globalization;
using EzyLabel.Core;

namespace EzyLabel.App
{
    /// <summary>
    /// Draws the queue on screen exactly as it will land on the roll: one pixel
    /// per printer dot, both stickers of each row, the die-cut edges in grey and
    /// the backing gap between rows.
    ///
    /// It works from the same BuildResult the printer gets, so what is on screen
    /// and what comes out of the printer cannot drift apart.
    /// </summary>
    public static class LabelPreview
    {
        private static readonly Color DieCut  = Color.FromArgb(255, 180, 180, 180);
        private static readonly Color Backing = Color.FromArgb(255, 228, 228, 228);
        private static readonly Color Gutter  = Color.FromArgb(255, 236, 236, 236);

        public static Bitmap Render(BuildResult built, LabelSpec spec, int maxRows = 6)
        {
            int rows = Math.Min(maxRows, Math.Max(1, built.RowCount));
            int rowPitch = spec.LabelHeightDots + spec.Mm(spec.RowGapMm);
            int width = Math.Max(1, spec.Mm(spec.WebWidthMm));
            int height = Math.Max(1, rowPitch * rows);

            var bmp = new Bitmap(width, height);
            using (var g = Graphics.FromImage(bmp))
            {
                g.SmoothingMode = SmoothingMode.None;
                g.InterpolationMode = InterpolationMode.NearestNeighbor;
                g.Clear(Color.White);

                using (var backing = new SolidBrush(Backing))
                using (var gutter = new SolidBrush(Gutter))
                using (var edge = new Pen(DieCut, 1))
                {
                    for (int r = 0; r < rows; r++)
                    {
                        int top = r * rowPitch;
                        g.FillRectangle(backing, 0, top + spec.LabelHeightDots, width, spec.Mm(spec.RowGapMm));
                        for (int c = 0; c < spec.Columns; c++)
                        {
                            int x = spec.ColumnOriginDots(c);
                            g.DrawRectangle(edge, x, top, spec.LabelWidthDots - 1, spec.LabelHeightDots - 1);
                            if (c < spec.Columns - 1)
                                g.FillRectangle(gutter, x + spec.LabelWidthDots, top,
                                                spec.ColumnGapDots, spec.LabelHeightDots);
                        }
                    }
                }

                int topOffset = spec.Mm(spec.TopOffsetMm);
                foreach (var p in built.Placed)
                {
                    if (p.Row >= rows) continue;
                    // Same arithmetic the printer is given - column nudge, row
                    // nudge and the item's own offset. If this drifted from
                    // TsplBuilder the preview would be showing a label the TSC
                    // never prints, which is worse than no preview at all.
                    int ox = spec.ColumnOriginDots(p.Column) + spec.RowOffsetXDots(p.Row)
                           + spec.Mm(p.Item.OffsetXMm);
                    int oy = p.Row * rowPitch + topOffset
                           + spec.CellOffsetYDots(p.Row, p.Column) + spec.Mm(p.Item.OffsetYMm);
                    DrawLabel(g, spec, p.Item, ox, oy, p.NarrowDots);
                }
            }
            return bmp;
        }

        private static void DrawLabel(Graphics g, LabelSpec spec, LabelItem item, int originX, int originY, int narrowDots)
        {
            var inv = CultureInfo.InvariantCulture;
            int x0 = originX + spec.InnerMarginDots;
            int usable = spec.LabelWidthDots - 2 * spec.InnerMarginDots;

            // Same block-centred layout the printer is given. It has to be, or
            // the preview would be showing something the TSC never prints -
            // TsplBuilder.AppendOneLabel is the one to change if this needs to
            // move, and this follows it.
            int gap = spec.Mm(spec.LineGapMm);
            if (gap < 1) gap = 1;

            var tShop = spec.ShopLineTweak ?? new ElementTweak();
            var tName = spec.NameTweak     ?? new ElementTweak();
            var tCode = spec.CodeTweak     ?? new ElementTweak();
            var tBar  = spec.BarcodeTweak  ?? new ElementTweak();
            var tPrice= spec.PriceTweak    ?? new ElementTweak();

            bool wantShop  = spec.ShopLine.Length > 0;
            bool wantName  = spec.ShowItemName && !string.IsNullOrWhiteSpace(item.ItemName);
            bool wantCode  = TsplBuilder.WantsCodeLine(spec, item);
            string code    = item.EffectiveBarcode;
            bool wantBars  = !string.IsNullOrEmpty(code) && narrowDots > 0;
            bool wantPrice = spec.ShowPrice;

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

            double barMm = item.BarcodeHeightMm > 0.001 ? item.BarcodeHeightMm : spec.BarcodeHeightMm;

            int shopH  = wantShop ? TsplBuilder.FontHeight(shopFont) * shopSY : 0;
            int nameH  = wantName ? TsplBuilder.FontHeight(nameFont) * nameSY : 0;
            int codeH  = wantCode ? TsplBuilder.FontHeight(codeFont) * codeSY : 0;
            int barH   = spec.Mm(barMm);
            int readableH = spec.ShowBarcodeText ? 20 : 0;
            int barsH  = wantBars ? barH + readableH : 0;
            int priceH = wantPrice ? TsplBuilder.FontHeight(priceFont) * priceSY : 0;

            int blocks = (wantShop ? 1 : 0) + (wantName ? 1 : 0) + (wantCode ? 1 : 0)
                       + (wantBars ? 1 : 0) + (wantPrice ? 1 : 0);
            int total = shopH + nameH + codeH + barsH + priceH + (blocks > 1 ? (blocks - 1) * gap : 0);

            int inner = spec.LabelHeightDots - 2 * spec.InnerMarginDots;
            int y = originY + spec.InnerMarginDots + Math.Max(0, (inner - total) / 2);

            if (wantShop)
            {
                int cw = TsplBuilder.FontWidth(shopFont) * shopSX;
                string t = TsplBuilder.Fit(spec.ShopLine, usable, cw);
                FixedPitch(g, TsplBuilder.AlignedX(x0, usable, t.Length * cw, tShop.Align) + spec.Mm(tShop.OffsetXMm),
                           y + spec.Mm(tShop.OffsetYMm), t, cw, shopH);
                y += shopH + gap;
            }
            if (wantName)
            {
                int cw = TsplBuilder.FontWidth(nameFont) * nameSX;
                string t = TsplBuilder.Fit(item.ItemName, usable, cw);
                FixedPitch(g, TsplBuilder.AlignedX(x0, usable, t.Length * cw, tName.Align) + spec.Mm(tName.OffsetXMm),
                           y + spec.Mm(tName.OffsetYMm), t, cw, nameH);
                y += nameH + gap;
            }
            if (wantCode)
            {
                int cw = TsplBuilder.FontWidth(codeFont) * codeSX;
                string t = TsplBuilder.Fit(item.ItemCode, usable, cw);
                FixedPitch(g, TsplBuilder.AlignedX(x0, usable, t.Length * cw, tCode.Align) + spec.Mm(tCode.OffsetXMm),
                           y + spec.Mm(tCode.OffsetYMm), t, cw, codeH);
                y += codeH + gap;
            }

            int barY = y + spec.Mm(tBar.OffsetYMm);
            int priceY = y + barsH + (wantBars ? gap : 0);

            if (wantBars)
            {
                int barW = Code128.WidthDots(code, narrowDots);
                int barX = TsplBuilder.AlignedX(x0, usable, barW, tBar.Align) + spec.Mm(tBar.OffsetXMm);
                int cx = barX;
                using (var black = new SolidBrush(Color.Black))
                {
                    foreach (var (isBar, modules) in Code128.Bars(code))
                    {
                        int w = modules * narrowDots;
                        if (isBar) g.FillRectangle(black, cx, barY, w, barH);
                        cx += w;
                    }
                }
                if (spec.ShowBarcodeText)
                {
                    int tw = code.Length * 8;
                    FixedPitch(g, barX + Math.Max(0, (barW - tw) / 2), barY + barH + 3, code, 8, 12);
                }
            }

            if (spec.ShowPrice)
            {
                string price = spec.CurrencyPrefix + " " + item.SellingPrice.ToString("N2", inv);
                int cw = TsplBuilder.FontWidth(priceFont) * priceSX;
                int ch = TsplBuilder.FontHeight(priceFont) * priceSY;
                string txt = TsplBuilder.Fit(price, usable, cw);
                int px = TsplBuilder.AlignedX(x0, usable, txt.Length * cw, tPrice.Align) + spec.Mm(tPrice.OffsetXMm);
                FixedPitch(g, px, priceY + spec.Mm(tPrice.OffsetYMm), txt, cw, ch);
            }
        }

        /// <summary>
        /// One character per cell, advancing by exactly the printer's cell
        /// width. The TSC built-ins are fixed-pitch bitmap fonts, so a
        /// proportional font on screen would show a long item name fitting when
        /// on paper it does not.
        /// </summary>
        private static void FixedPitch(Graphics g, int x, int y, string text, int cellW, int cellH)
        {
            if (string.IsNullOrEmpty(text)) return;
            using var font = new Font("Consolas", cellH * 0.72f, GraphicsUnit.Pixel);
            using var brush = new SolidBrush(Color.Black);
            var fmt = StringFormat.GenericTypographic;
            for (int i = 0; i < text.Length; i++)
                g.DrawString(text[i].ToString(), font, brush, x + i * cellW, y, fmt);
        }
    }
}
