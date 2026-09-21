using System;
using System.Drawing;
using System.Windows.Forms;
using EzyLabel.Core;

namespace EzyLabel.App
{
    /// <summary>
    /// Change one item's sticker without touching the rest of the roll.
    ///
    /// There is always one line that will not behave - a name three words too
    /// long for the usual font, or a code whose bars need to be a shade thinner
    /// to fit. Rather than shrink every label on the roll to suit it, that one
    /// item carries its own settings.
    ///
    /// Everything here is "leave it alone" by default: an empty font and a zero
    /// mean the roll's own setting is used.
    /// </summary>
    public class ItemLayoutDialog : Form
    {
        private readonly LabelItem _item;
        private readonly LabelSpec _spec;

        private ComboBox _font;
        private NumericUpDown _offX, _offY, _barH, _narrow;

        public ItemLayoutDialog(LabelItem item, LabelSpec spec)
        {
            _item = item;
            _spec = spec;

            Text = "Adjust this label";
            FormBorderStyle = FormBorderStyle.FixedDialog;
            StartPosition = FormStartPosition.CenterParent;
            MaximizeBox = MinimizeBox = false;
            ClientSize = new Size(470, 320);

            int y = 12;
            Controls.Add(new Label
            {
                Left = 12, Top = y, Width = 440, Height = 34,
                Font = new Font("Segoe UI", 9.5F, FontStyle.Bold),
                Text = item.ItemCode + "  -  " + item.ItemName
            });
            y += 38;
            Controls.Add(new Label
            {
                Left = 12, Top = y, Width = 440, Height = 32, ForeColor = Color.Gray,
                Text = "Only this item's stickers change. Leave a box at 0, or the font on \"auto\", to use the roll's setting."
            });
            y += 40;

            NumericUpDown Spin(string caption, decimal min, decimal max, int dec, string hint)
            {
                Controls.Add(new Label { Left = 12, Top = y + 4, Width = 170, Text = caption });
                var n = new NumericUpDown
                {
                    Left = 186, Top = y, Width = 80, Minimum = min, Maximum = max,
                    DecimalPlaces = dec, Increment = dec > 0 ? 0.1M : 1M
                };
                Controls.Add(n);
                Controls.Add(new Label { Left = 276, Top = y + 4, Width = 180, ForeColor = Color.Gray, Text = hint });
                y += 30;
                return n;
            }

            Controls.Add(new Label { Left = 12, Top = y + 4, Width = 170, Text = "Item name font" });
            _font = new ComboBox { Left = 186, Top = y, Width = 80, DropDownStyle = ComboBoxStyle.DropDownList };
            _font.Items.AddRange(new object[] { "auto", "1", "2", "3", "4", "5" });
            Controls.Add(_font);
            Controls.Add(new Label { Left = 276, Top = y + 4, Width = 180, ForeColor = Color.Gray, Text = "smaller number = smaller text" });
            y += 30;

            _offX   = Spin("Move right (mm)", -60, 60, 1, "negative moves left");
            _offY   = Spin("Move down (mm)",  -60, 60, 1, "negative moves up");
            _barH   = Spin("Barcode height (mm)", 0, 100, 1, "0 = use the roll's");
            _narrow = Spin("Narrow bar (dots)", 0, 6, 0, "0 = use the roll's");

            _font.Text  = string.IsNullOrWhiteSpace(item.NameFont) ? "auto" : item.NameFont;
            _offX.Value = (decimal)item.OffsetXMm;
            _offY.Value = (decimal)item.OffsetYMm;
            _barH.Value = (decimal)item.BarcodeHeightMm;
            _narrow.Value = item.NarrowDots;

            y += 10;
            var clear = new Button { Text = "Use the roll's settings", Left = 12, Top = y, Width = 170, Height = 30 };
            clear.Click += (s, e) =>
            {
                _font.Text = "auto";
                _offX.Value = _offY.Value = _barH.Value = _narrow.Value = 0;
            };
            Controls.Add(clear);

            var ok = new Button { Text = "Apply", Left = 260, Top = y, Width = 90, Height = 30, DialogResult = DialogResult.OK };
            ok.Click += (s, e) => Apply();
            Controls.Add(ok);
            var cancel = new Button { Text = "Cancel", Left = 360, Top = y, Width = 90, Height = 30, DialogResult = DialogResult.Cancel };
            Controls.Add(cancel);

            AcceptButton = ok;
            CancelButton = cancel;
        }

        private void Apply()
        {
            _item.NameFont = (_font.SelectedIndex > 0) ? _font.Text : "";
            _item.OffsetXMm = (double)_offX.Value;
            _item.OffsetYMm = (double)_offY.Value;
            _item.BarcodeHeightMm = (double)_barH.Value;
            _item.NarrowDots = (int)_narrow.Value;
        }
    }
}
