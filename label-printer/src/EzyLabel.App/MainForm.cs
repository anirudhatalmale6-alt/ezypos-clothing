using System;
using System.Collections.Generic;
using System.Drawing;
using System.Globalization;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;
using EzyLabel.Core;

namespace EzyLabel.App
{
    public class MainForm : Form
    {
        private AppSettings _settings;
        private readonly BindingSource _found = new BindingSource();
        private readonly BindingSource _queueSource = new BindingSource();
        private readonly List<LabelItem> _foundItems = new List<LabelItem>();
        private readonly List<LabelItem> _queue = new List<LabelItem>();
        private LocalAgent _agent;
        private CancellationTokenSource _search;

        // ---- items tab
        private TextBox _txtSearch;
        private DataGridView _gridFound;
        private NumericUpDown _numQty;
        private Button _btnAdd, _btnSearch;

        // ---- queue tab
        private DataGridView _gridQueue;
        private Label _lblTotals;
        private Button _btnPrint, _btnPreview, _btnRemove, _btnClear;

        // ---- preview tab
        private PictureBox _pic;
        private Label _lblPreviewInfo;
        private TabPage _tabPreview;

        // ---- settings tab
        private TextBox _txtUrl, _txtKey, _txtShopLine, _txtCurrency;
        private ComboBox _cboPrinter;
        private NumericUpDown _numW, _numH, _numCols, _numColGap, _numRowGap,
                              _numLeft, _numTop, _numInner, _numBarH, _numNarrow,
                              _numSpeed, _numDensity, _numDpi, _numPort;
        private CheckBox _chkName, _chkCode, _chkPrice, _chkBarcodeText, _chkAgent;
        private Label _lblStatus;
        private TextBox _txtLog;

        private TabControl _tabs;

        public MainForm()
        {
            _settings = AppSettings.Load();
            BuildUi();
            LoadSettingsIntoUi();
            RefreshPrinters();
            StartAgentIfWanted();
            UpdateTotals();
        }

        // ================================================================ UI
        private void BuildUi()
        {
            Text = "EzyPOS Label Printer - TSC TTP-244 Pro";
            Width = 1120;
            Height = 780;
            StartPosition = FormStartPosition.CenterScreen;
            Font = new Font("Segoe UI", 9F);

            _tabs = new TabControl { Dock = DockStyle.Fill };
            _tabs.TabPages.Add(BuildItemsTab());
            _tabs.TabPages.Add(BuildQueueTab());
            _tabPreview = BuildPreviewTab();
            _tabs.TabPages.Add(_tabPreview);
            _tabs.TabPages.Add(BuildSettingsTab());

            _lblStatus = new Label
            {
                Dock = DockStyle.Bottom,
                Height = 26,
                TextAlign = ContentAlignment.MiddleLeft,
                Padding = new Padding(8, 0, 0, 0),
                BackColor = SystemColors.Control,
                Text = "Ready."
            };

            Controls.Add(_tabs);
            Controls.Add(_lblStatus);
        }

        private TabPage BuildItemsTab()
        {
            var page = new TabPage("1. Items") { Padding = new Padding(10) };

            var top = new Panel { Dock = DockStyle.Top, Height = 44 };
            top.Controls.Add(new Label { Text = "Search", Left = 0, Top = 12, Width = 50 });
            _txtSearch = new TextBox { Left = 52, Top = 8, Width = 320 };
            _txtSearch.KeyDown += (s, e) => { if (e.KeyCode == Keys.Enter) { e.SuppressKeyPress = true; _ = DoSearch(); } };
            _btnSearch = new Button { Text = "Search EzyPOS", Left = 380, Top = 7, Width = 130 };
            _btnSearch.Click += async (s, e) => await DoSearch();

            top.Controls.Add(new Label { Text = "Labels", Left = 600, Top = 12, Width = 48 });
            _numQty = new NumericUpDown { Left = 650, Top = 8, Width = 70, Minimum = 1, Maximum = 10000, Value = 10 };
            _btnAdd = new Button { Text = "Add to queue", Left = 730, Top = 7, Width = 130 };
            _btnAdd.Click += (s, e) => AddSelectedToQueue();

            top.Controls.AddRange(new Control[] { _txtSearch, _btnSearch, _numQty, _btnAdd });

            _gridFound = MakeGrid();
            _gridFound.DataSource = _found;
            _gridFound.CellDoubleClick += (s, e) => AddSelectedToQueue();
            _found.DataSource = _foundItems;

            page.Controls.Add(_gridFound);
            page.Controls.Add(top);
            return page;
        }

        private TabPage BuildQueueTab()
        {
            var page = new TabPage("2. Print queue") { Padding = new Padding(10) };

            var bar = new Panel { Dock = DockStyle.Bottom, Height = 48 };
            _btnPrint = new Button { Text = "PRINT", Left = 0, Top = 8, Width = 150, Height = 32 };
            _btnPrint.Font = new Font("Segoe UI", 10F, FontStyle.Bold);
            _btnPrint.Click += async (s, e) => await DoPrint();
            _btnPreview = new Button { Text = "Preview", Left = 160, Top = 8, Width = 110, Height = 32 };
            _btnPreview.Click += (s, e) => DoPreview();
            _btnRemove = new Button { Text = "Remove row", Left = 280, Top = 8, Width = 110, Height = 32 };
            _btnRemove.Click += (s, e) => RemoveSelected();
            _btnClear = new Button { Text = "Clear queue", Left = 400, Top = 8, Width = 110, Height = 32 };
            _btnClear.Click += (s, e) => { _queue.Clear(); _queueSource.ResetBindings(false); UpdateTotals(); };

            _lblTotals = new Label { Left = 540, Top = 16, Width = 460, Font = new Font("Segoe UI", 10F, FontStyle.Bold) };
            bar.Controls.AddRange(new Control[] { _btnPrint, _btnPreview, _btnRemove, _btnClear, _lblTotals });

            _gridQueue = MakeGrid();
            _gridQueue.ReadOnly = false;
            _gridQueue.DataSource = _queueSource;
            _queueSource.DataSource = _queue;
            _gridQueue.CellEndEdit += (s, e) => UpdateTotals();

            page.Controls.Add(_gridQueue);
            page.Controls.Add(bar);
            return page;
        }

        private TabPage BuildPreviewTab()
        {
            var page = new TabPage("3. Preview") { Padding = new Padding(10) };
            _lblPreviewInfo = new Label { Dock = DockStyle.Top, Height = 44, Text = "Press Preview on the queue tab." };
            var scroll = new Panel { Dock = DockStyle.Fill, AutoScroll = true, BackColor = Color.FromArgb(245, 245, 245) };
            _pic = new PictureBox { SizeMode = PictureBoxSizeMode.AutoSize, Location = new Point(10, 10) };
            scroll.Controls.Add(_pic);
            page.Controls.Add(scroll);
            page.Controls.Add(_lblPreviewInfo);
            return page;
        }

        private TabPage BuildSettingsTab()
        {
            var page = new TabPage("4. Settings") { Padding = new Padding(10), AutoScroll = true };
            int y = 10;

            Label Head(string t)
            {
                var l = new Label { Text = t, Left = 0, Top = y, Width = 500, Font = new Font("Segoe UI", 10F, FontStyle.Bold) };
                y += 26;
                page.Controls.Add(l);
                return l;
            }
            TextBox Row(string caption, int width = 380, string tip = null)
            {
                page.Controls.Add(new Label { Text = caption, Left = 0, Top = y + 3, Width = 200 });
                var tb = new TextBox { Left = 205, Top = y, Width = width };
                page.Controls.Add(tb);
                if (tip != null) page.Controls.Add(new Label { Text = tip, Left = 205 + width + 10, Top = y + 3, Width = 300, ForeColor = Color.Gray });
                y += 28;
                return tb;
            }
            NumericUpDown Num(string caption, decimal min, decimal max, int dec, string tip = null)
            {
                page.Controls.Add(new Label { Text = caption, Left = 0, Top = y + 3, Width = 200 });
                var n = new NumericUpDown
                {
                    Left = 205, Top = y, Width = 100, Minimum = min, Maximum = max,
                    DecimalPlaces = dec, Increment = dec > 0 ? 0.1M : 1M
                };
                page.Controls.Add(n);
                if (tip != null) page.Controls.Add(new Label { Text = tip, Left = 315, Top = y + 3, Width = 560, ForeColor = Color.Gray });
                y += 28;
                return n;
            }

            Head("EzyPOS connection");
            _txtUrl = Row("EzyPOS address", 380, "e.g. https://sub.asrenish.com  (no /index.php)");
            _txtKey = Row("API key", 380, "Masters > Barcode Settings in EzyPOS");
            var btnTest = new Button { Text = "Test connection", Left = 205, Top = y, Width = 140 };
            btnTest.Click += async (s, e) => await TestConnection();
            page.Controls.Add(btnTest);
            y += 40;

            Head("Printer");
            page.Controls.Add(new Label { Text = "Printer", Left = 0, Top = y + 3, Width = 200 });
            _cboPrinter = new ComboBox { Left = 205, Top = y, Width = 380, DropDownStyle = ComboBoxStyle.DropDownList };
            page.Controls.Add(_cboPrinter);
            var btnRefresh = new Button { Text = "Refresh", Left = 595, Top = y - 1, Width = 90 };
            btnRefresh.Click += (s, e) => RefreshPrinters();
            page.Controls.Add(btnRefresh);
            y += 34;
            var btnCal = new Button { Text = "Print calibration sheet", Left = 205, Top = y, Width = 180 };
            btnCal.Click += (s, e) => PrintCalibration();
            page.Controls.Add(btnCal);
            page.Controls.Add(new Label
            {
                Left = 395, Top = y + 4, Width = 620, ForeColor = Color.Gray,
                Text = "Prints rulers and a box round where each sticker is thought to be. Measure, then set the margins below."
            });
            y += 40;

            Head("Label roll (millimetres)");
            _numW      = Num("Sticker width",        1, 300, 1, "38 for this roll");
            _numH      = Num("Sticker height",       1, 300, 1, "25 for this roll");
            _numCols   = Num("Stickers across",      1, 4,   0, "2 for a 2-up roll. This is the setting that matters most.");
            _numColGap = Num("Gap between them",     0, 50,  1, "measure between the two stickers");
            _numRowGap = Num("Gap between rows",     0, 50,  1, "the die-cut gap the printer's sensor sees");
            _numLeft   = Num("Left margin",          0, 50,  1, "paper edge to the first sticker");
            _numTop    = Num("Vertical trim",        -20, 20, 1, "positive moves the print down");
            _numInner  = Num("Margin inside sticker", 0, 20, 1, "white space kept clear round the edge");
            _numBarH   = Num("Barcode height",       1, 100, 1);

            Head("Printer settings");
            _numDpi     = Num("Resolution (dpi)", 100, 600, 0, "203 for the TTP-244 Pro");
            _numNarrow  = Num("Narrow bar (dots)", 1, 6, 0, "2 is usual; drops to 1 by itself if a code will not fit");
            _numSpeed   = Num("Speed (ips)", 1, 12, 0, "3 to 4 suits small labels");
            _numDensity = Num("Density", 0, 15, 0, "8 to start; raise if the print is faint");

            Head("What goes on the label");
            _chkName = new CheckBox { Text = "Item name", Left = 205, Top = y, Width = 140 }; page.Controls.Add(_chkName);
            _chkCode = new CheckBox { Text = "Item code", Left = 350, Top = y, Width = 140 }; page.Controls.Add(_chkCode);
            _chkPrice = new CheckBox { Text = "Price", Left = 495, Top = y, Width = 100 }; page.Controls.Add(_chkPrice);
            _chkBarcodeText = new CheckBox { Text = "Number under the barcode", Left = 600, Top = y, Width = 220 }; page.Controls.Add(_chkBarcodeText);
            y += 32;
            _txtCurrency = Row("Currency prefix", 120, "e.g. Rs.");
            _txtShopLine = Row("Extra top line", 380, "optional - a shop name above the item name");

            Head("Online test page");
            _chkAgent = new CheckBox { Text = "Let a browser page print through this program", Left = 205, Top = y, Width = 420 };
            page.Controls.Add(_chkAgent); y += 30;
            _numPort = Num("Port", 1024, 65535, 0, "the page talks to http://127.0.0.1:<port> on this PC only");

            var btnSave = new Button { Text = "Save settings", Left = 205, Top = y + 10, Width = 140, Height = 32 };
            btnSave.Click += (s, e) => SaveSettingsFromUi(true);
            page.Controls.Add(btnSave);
            y += 56;

            page.Controls.Add(new Label { Text = "Activity", Left = 0, Top = y, Width = 200, Font = new Font("Segoe UI", 10F, FontStyle.Bold) });
            y += 24;
            _txtLog = new TextBox { Left = 0, Top = y, Width = 1020, Height = 120, Multiline = true, ReadOnly = true, ScrollBars = ScrollBars.Vertical };
            page.Controls.Add(_txtLog);
            return page;
        }

        private static DataGridView MakeGrid()
        {
            return new DataGridView
            {
                Dock = DockStyle.Fill,
                AutoGenerateColumns = false,
                AllowUserToAddRows = false,
                AllowUserToDeleteRows = false,
                ReadOnly = true,
                SelectionMode = DataGridViewSelectionMode.FullRowSelect,
                MultiSelect = true,
                RowHeadersVisible = false,
                AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
                Columns =
                {
                    new DataGridViewTextBoxColumn { HeaderText = "Code",  DataPropertyName = "ItemCode", FillWeight = 18, ReadOnly = true },
                    new DataGridViewTextBoxColumn { HeaderText = "Item",  DataPropertyName = "ItemName", FillWeight = 40, ReadOnly = true },
                    new DataGridViewTextBoxColumn { HeaderText = "Price", DataPropertyName = "SellingPrice", FillWeight = 14, ReadOnly = true,
                        DefaultCellStyle = new DataGridViewCellStyle { Format = "N2", Alignment = DataGridViewContentAlignment.MiddleRight } },
                    new DataGridViewTextBoxColumn { HeaderText = "Labels", DataPropertyName = "Quantity", FillWeight = 12,
                        DefaultCellStyle = new DataGridViewCellStyle { Alignment = DataGridViewContentAlignment.MiddleRight } },
                    new DataGridViewTextBoxColumn { HeaderText = "Status", DataPropertyName = "Status", FillWeight = 16, ReadOnly = true }
                }
            };
        }

        // ============================================================ actions
        private void Log(string msg)
        {
            if (InvokeRequired) { BeginInvoke(new Action<string>(Log), msg); return; }
            _txtLog.AppendText(DateTime.Now.ToString("HH:mm:ss") + "  " + msg + Environment.NewLine);
            _lblStatus.Text = msg;
        }

        private async Task DoSearch()
        {
            SaveSettingsFromUi(false);
            _search?.Cancel();
            _search = new CancellationTokenSource();
            _btnSearch.Enabled = false;
            try
            {
                using var api = new PosApi(_settings.PosUrl, _settings.ApiKey);
                var items = await api.SearchItemsAsync(_txtSearch.Text, _search.Token);
                _foundItems.Clear();
                foreach (var i in items) { i.Quantity = (int)_numQty.Value; _foundItems.Add(i); }
                _found.ResetBindings(false);
                Log(items.Count + " items found.");
                if (items.Count == 0)
                    MessageBox.Show(this, "No items matched that search.", "Nothing found",
                                    MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (OperationCanceledException) { }
            catch (Exception ex) { Fail("Could not fetch items", ex); }
            finally { _btnSearch.Enabled = true; }
        }

        private void AddSelectedToQueue()
        {
            int qty = (int)_numQty.Value;
            int added = 0;
            foreach (DataGridViewRow row in _gridFound.SelectedRows)
            {
                if (row.DataBoundItem is not LabelItem it) continue;
                // Same item twice just adds up, rather than making a second row
                // that is easy to miss when checking the queue.
                var existing = _queue.FirstOrDefault(q => q.ItemId == it.ItemId);
                if (existing != null) existing.Quantity += qty;
                else
                {
                    var copy = it.Clone();
                    copy.Quantity = qty;
                    copy.Status = "Ready";
                    _queue.Add(copy);
                }
                added++;
            }
            if (added == 0)
            {
                MessageBox.Show(this, "Pick one or more items in the list first.", "Nothing selected",
                                MessageBoxButtons.OK, MessageBoxIcon.Information);
                return;
            }
            _queueSource.ResetBindings(false);
            UpdateTotals();
            Log(added + " item(s) added to the queue.");
            _tabs.SelectedIndex = 1;
        }

        private void RemoveSelected()
        {
            var doomed = _gridQueue.SelectedRows.Cast<DataGridViewRow>()
                          .Select(r => r.DataBoundItem as LabelItem).Where(x => x != null).ToList();
            foreach (var d in doomed) _queue.Remove(d);
            _queueSource.ResetBindings(false);
            UpdateTotals();
        }

        private void UpdateTotals()
        {
            int labels = _queue.Sum(q => Math.Max(0, q.Quantity));
            int cols = Math.Max(1, _settings.Label.Columns);
            int rows = (labels + cols - 1) / cols;
            _lblTotals.Text = labels + " labels  =  " + rows + " rows of " + cols;
            _btnPrint.Enabled = labels > 0;
            _btnPreview.Enabled = labels > 0;
        }

        private BuildResult BuildCurrent()
        {
            SaveSettingsFromUi(false);
            return TsplBuilder.Build(_queue, _settings.Label);
        }

        private void DoPreview()
        {
            var built = BuildCurrent();
            if (built.Error != null)
            {
                MessageBox.Show(this, built.Error, "Cannot print this queue", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }
            using var old = _pic.Image;
            _pic.Image = LabelPreview.Render(built, _settings.Label, 6);
            _lblPreviewInfo.Text =
                built.LabelCount + " labels in " + built.RowCount + " rows of " + _settings.Label.Columns
                + ".  Showing the first " + Math.Min(6, built.RowCount) + " rows at actual printed size ("
                + _settings.Label.WebWidthMm.ToString("0.#", CultureInfo.InvariantCulture) + " mm across)."
                + (built.Warnings.Count > 0 ? "  " + string.Join("  ", built.Warnings) : "");
            _tabs.SelectedTab = _tabPreview;
        }

        private async Task DoPrint()
        {
            SaveSettingsFromUi(false);

            string problem = RawPrinter.CheckReady(_settings.PrinterName);
            if (problem != null)
            {
                MessageBox.Show(this, problem, "Printer Not Connected", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            _btnPrint.Enabled = false;
            try
            {
                // Take the prices straight from EzyPOS at the moment of
                // printing. A queue built half an hour ago could otherwise put
                // yesterday's price on a hundred stickers.
                try
                {
                    using var api = new PosApi(_settings.PosUrl, _settings.ApiKey);
                    var fresh = await api.RefreshQueueAsync(_queue);
                    _queue.Clear();
                    _queue.AddRange(fresh.Where(f => f.Status != "Not found in EzyPOS - skipped"));
                    foreach (var skipped in fresh.Where(f => f.Status == "Not found in EzyPOS - skipped"))
                        Log("Skipped " + skipped.ItemCode + " - EzyPOS no longer has it.");
                    _queueSource.ResetBindings(false);
                }
                catch (ApiException ex)
                {
                    var choice = MessageBox.Show(this,
                        "Could not refresh the prices from EzyPOS:" + Environment.NewLine + Environment.NewLine
                        + ex.Message + Environment.NewLine + Environment.NewLine
                        + "Print anyway, using the prices already on screen?",
                        "EzyPOS not reachable", MessageBoxButtons.YesNo, MessageBoxIcon.Warning);
                    if (choice != DialogResult.Yes) return;
                }

                var built = TsplBuilder.Build(_queue, _settings.Label);
                if (built.Error != null)
                {
                    MessageBox.Show(this, built.Error, "Cannot print this queue", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }
                if (built.Warnings.Count > 0)
                {
                    var choice = MessageBox.Show(this,
                        string.Join(Environment.NewLine + Environment.NewLine, built.Warnings)
                        + Environment.NewLine + Environment.NewLine + "Carry on and print?",
                        "Before printing", MessageBoxButtons.YesNo, MessageBoxIcon.Information);
                    if (choice != DialogResult.Yes) return;
                }

                RawPrinter.SendTspl(_settings.PrinterName, built.Tspl,
                                    "EzyPOS Labels (" + built.LabelCount + ")");
                foreach (var q in _queue) q.Status = "Printed";
                _queueSource.ResetBindings(false);
                Log("Printed " + built.LabelCount + " labels in " + built.RowCount + " rows on " + _settings.PrinterName + ".");
                MessageBox.Show(this,
                    built.LabelCount + " labels sent to " + _settings.PrinterName + "." + Environment.NewLine
                    + built.RowCount + " rows of " + _settings.Label.Columns + ".",
                    "Printing", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex) { Fail("Printing failed", ex); }
            finally { _btnPrint.Enabled = true; UpdateTotals(); }
        }

        private void PrintCalibration()
        {
            SaveSettingsFromUi(false);
            string problem = RawPrinter.CheckReady(_settings.PrinterName);
            if (problem != null)
            {
                MessageBox.Show(this, problem, "Printer Not Connected", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }
            try
            {
                RawPrinter.SendTspl(_settings.PrinterName,
                    TsplBuilder.BuildCalibrationSheet(_settings.Label), "EzyPOS calibration");
                Log("Calibration sheet sent.");
                MessageBox.Show(this,
                    "One row has been printed." + Environment.NewLine + Environment.NewLine
                    + "Look at where the printed rectangle sits against the real sticker edges:" + Environment.NewLine
                    + " - If it is too far left or right, adjust Left margin." + Environment.NewLine
                    + " - If it is too high or low, adjust Vertical trim." + Environment.NewLine
                    + " - If the ruler's 38 mm mark is not at the sticker edge, the Sticker width is wrong."
                    + Environment.NewLine + Environment.NewLine
                    + "Change the setting, save, and print it again until it lines up.",
                    "Calibration", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex) { Fail("Could not print the calibration sheet", ex); }
        }

        private async Task TestConnection()
        {
            SaveSettingsFromUi(false);
            try
            {
                using var api = new PosApi(_settings.PosUrl, _settings.ApiKey);
                string who = await api.TestConnectionAsync();
                Log("Connected to EzyPOS (" + who + ").");
                MessageBox.Show(this, "Connected to EzyPOS.  " + who, "Connection", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex) { Fail("Could not connect", ex); }
        }

        private void Fail(string what, Exception ex)
        {
            Log(what + ": " + ex.Message);
            MessageBox.Show(this, ex.Message, what, MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        // =========================================================== settings
        private void RefreshPrinters()
        {
            string keep = _cboPrinter.SelectedItem as string ?? _settings.PrinterName;
            _cboPrinter.Items.Clear();
            var printers = RawPrinter.InstalledPrinters();
            foreach (string p in printers) _cboPrinter.Items.Add(p);

            if (!string.IsNullOrEmpty(keep) && printers.Contains(keep)) _cboPrinter.SelectedItem = keep;
            else
            {
                string guess = RawPrinter.GuessTscPrinter();
                if (guess != null) { _cboPrinter.SelectedItem = guess; Log("Found the TSC printer: " + guess); }
                else if (printers.Count > 0) _cboPrinter.SelectedIndex = 0;
            }

            if (printers.Count == 0)
                Log("Windows reports no printers installed at all. Install the TSC driver first.");
            else if (RawPrinter.GuessTscPrinter() == null)
                Log("No TSC printer found among the installed printers. Choose it by hand if it has been renamed.");
        }

        private void LoadSettingsIntoUi()
        {
            var s = _settings; var L = s.Label;
            _txtUrl.Text = s.PosUrl;
            _txtKey.Text = s.ApiKey;
            _numW.Value = (decimal)L.LabelWidthMm;
            _numH.Value = (decimal)L.LabelHeightMm;
            _numCols.Value = L.Columns;
            _numColGap.Value = (decimal)L.ColumnGapMm;
            _numRowGap.Value = (decimal)L.RowGapMm;
            _numLeft.Value = (decimal)L.LeftMarginMm;
            _numTop.Value = (decimal)L.TopOffsetMm;
            _numInner.Value = (decimal)L.InnerMarginMm;
            _numBarH.Value = (decimal)L.BarcodeHeightMm;
            _numDpi.Value = L.Dpi;
            _numNarrow.Value = L.BarcodeNarrowDots;
            _numSpeed.Value = L.Speed;
            _numDensity.Value = L.Density;
            _chkName.Checked = L.ShowItemName;
            _chkCode.Checked = L.ShowItemCode;
            _chkPrice.Checked = L.ShowPrice;
            _chkBarcodeText.Checked = L.ShowBarcodeText;
            _txtCurrency.Text = L.CurrencyPrefix;
            _txtShopLine.Text = L.ShopLine;
            _chkAgent.Checked = s.AgentEnabled;
            _numPort.Value = s.AgentPort;
        }

        private void SaveSettingsFromUi(bool toDisk)
        {
            var s = _settings; var L = s.Label;
            s.PosUrl = _txtUrl.Text.Trim();
            s.ApiKey = _txtKey.Text.Trim();
            s.PrinterName = _cboPrinter.SelectedItem as string ?? s.PrinterName;
            L.LabelWidthMm = (double)_numW.Value;
            L.LabelHeightMm = (double)_numH.Value;
            L.Columns = (int)_numCols.Value;
            L.ColumnGapMm = (double)_numColGap.Value;
            L.RowGapMm = (double)_numRowGap.Value;
            L.LeftMarginMm = (double)_numLeft.Value;
            L.TopOffsetMm = (double)_numTop.Value;
            L.InnerMarginMm = (double)_numInner.Value;
            L.BarcodeHeightMm = (double)_numBarH.Value;
            L.Dpi = (int)_numDpi.Value;
            L.BarcodeNarrowDots = (int)_numNarrow.Value;
            L.Speed = (int)_numSpeed.Value;
            L.Density = (int)_numDensity.Value;
            L.ShowItemName = _chkName.Checked;
            L.ShowItemCode = _chkCode.Checked;
            L.ShowPrice = _chkPrice.Checked;
            L.ShowBarcodeText = _chkBarcodeText.Checked;
            L.CurrencyPrefix = _txtCurrency.Text;
            L.ShopLine = _txtShopLine.Text;

            bool agentChanged = s.AgentEnabled != _chkAgent.Checked || s.AgentPort != (int)_numPort.Value;
            s.AgentEnabled = _chkAgent.Checked;
            s.AgentPort = (int)_numPort.Value;

            UpdateTotals();

            if (toDisk)
            {
                string bad = L.Validate();
                if (bad != null)
                {
                    MessageBox.Show(this, bad, "Check the label settings", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }
                try
                {
                    s.Save();
                    Log("Settings saved to " + AppSettings.DefaultPath);
                    if (agentChanged) StartAgentIfWanted();
                }
                catch (Exception ex) { Fail("Could not save the settings", ex); }
            }
        }

        private void StartAgentIfWanted()
        {
            _agent?.Dispose();
            _agent = null;
            if (!_settings.AgentEnabled) { Log("Online test page turned off."); return; }
            try
            {
                _agent = new LocalAgent(() => _settings, Log,
                                        RawPrinter.CheckReady,
                                        RawPrinter.SendTspl);
                _agent.Start(_settings.AgentPort);
            }
            catch (Exception ex) { Log(ex.Message); }
        }

        protected override void OnFormClosing(FormClosingEventArgs e)
        {
            try { SaveSettingsFromUi(false); _settings.Save(); } catch { }
            _agent?.Dispose();
            base.OnFormClosing(e);
        }
    }
}
