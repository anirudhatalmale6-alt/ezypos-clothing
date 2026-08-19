using System;
using System.Collections.Generic;
using System.Globalization;
using System.IO;
using System.Text;
using System.Text.Json;
using System.Threading.Tasks;
using EzyLabel.Core;

namespace EzyLabel.Cli
{
    /// <summary>
    /// The same label engine as the Windows program, with no window on it.
    ///
    /// It is here for two reasons. It is what produces the print proofs, so the
    /// proof comes out of the code that actually ships rather than being drawn
    /// by hand. And it lets a label job be sent from a batch file or a scheduled
    /// task without anyone sitting at the machine.
    /// </summary>
    public static class Program
    {
        public static async Task<int> Main(string[] args)
        {
            try { return await Run(args).ConfigureAwait(false); }
            catch (ApiException ex) { Console.Error.WriteLine(ex.Message); return 2; }
            catch (Exception ex) { Console.Error.WriteLine("Error: " + ex.Message); return 1; }
        }

        private static async Task<int> Run(string[] args)
        {
            if (args.Length == 0 || Has(args, "--help") || Has(args, "-h")) { Usage(); return 0; }

            string cmd = args[0].ToLowerInvariant();
            string settingsPath = Arg(args, "--settings");
            var settings = AppSettings.Load(settingsPath);

            ApplySpecOverrides(args, settings.Label);
            string urlOverride = Arg(args, "--url");
            string keyOverride = Arg(args, "--key");
            if (urlOverride != null) settings.PosUrl = urlOverride;
            if (keyOverride != null) settings.ApiKey = keyOverride;

            switch (cmd)
            {
                case "emit":       return Emit(args, settings);
                case "calibrate":  return Calibrate(args, settings);
                case "fetch":      return await Fetch(args, settings).ConfigureAwait(false);
                case "serve":      return Serve(args, settings);
                case "spec":       Console.WriteLine(JsonSerializer.Serialize(settings.Label,
                                       new JsonSerializerOptions { WriteIndented = true })); return 0;
                default:
                    Console.Error.WriteLine("Unknown command '" + cmd + "'.");
                    Usage();
                    return 1;
            }
        }

        private static void Usage()
        {
            Console.WriteLine(@"
EzyPOS label printer - command line

  ezylabel emit      --queue <file.json> [--out job.tspl] [--placement placement.json]
                     Build the TSPL for a queue. Prints it to the screen unless
                     --out is given.

  ezylabel calibrate [--out cal.tspl]
                     Build the calibration sheet: rulers and a box round where
                     the software thinks each sticker is.

  ezylabel fetch     --items 1:10,2:20 [--out queue.json]
                     Ask EzyPOS for the label data for those item ids and
                     quantities, and write it as a queue file.

  ezylabel serve     [--port 9110] [--spool <folder>]
                     Run the print agent - the small web server the online test
                     page talks to. With --spool, every job is written into that
                     folder as a .tspl file instead of going to a printer, which
                     is how the whole chain can be tried out with no printer
                     attached.

  ezylabel spec      Show the label measurements currently in use.

Common options
  --settings <path>  Use a particular settings.json.
  --url <address>    EzyPOS address, overriding the settings file.
  --key <api key>    API key, overriding the settings file.

Label overrides (all in millimetres unless it says otherwise)
  --label-width --label-height --columns --column-gap --row-gap
  --left-margin --top-offset --inner-margin
  --barcode-height --narrow-dots (in DOTS) --dpi --speed --density
  --no-price --no-name --no-code --no-barcode-text --shop-line <text>

A queue file is the same shape the API returns:
  { ""labels"": [ { ""item_code"":""SH-1001"", ""item_name"":""Cotton Shirt"",
                    ""selling_price"":2450, ""label_count"":10 } ] }
");
        }

        // ---------------------------------------------------------------- emit
        private static int Emit(string[] args, AppSettings settings)
        {
            string queuePath = Arg(args, "--queue");
            if (queuePath == null) { Console.Error.WriteLine("--queue is required."); return 1; }

            var queue = LoadQueue(queuePath);
            var result = TsplBuilder.Build(queue, settings.Label);

            foreach (var w in result.Warnings) Console.Error.WriteLine("Note: " + w);
            if (result.Error != null) { Console.Error.WriteLine(result.Error); return 3; }

            Console.Error.WriteLine(result.LabelCount + " labels in " + result.RowCount
                                    + " rows of " + settings.Label.Columns + ".");

            string outPath = Arg(args, "--out");
            if (outPath != null)
            {
                // No BOM, and CRLF as TSPL expects - a BOM at the front of the
                // stream is enough to make the first command be ignored.
                File.WriteAllText(outPath, result.Tspl, new UTF8Encoding(false));
                Console.Error.WriteLine("Written to " + outPath);
            }
            else
            {
                Console.Write(result.Tspl);
            }

            string placement = Arg(args, "--placement");
            if (placement != null)
            {
                var rows = new List<object>();
                foreach (var p in result.Placed)
                {
                    rows.Add(new
                    {
                        row = p.Row,
                        column = p.Column,
                        item_code = p.Item.ItemCode,
                        item_name = p.Item.ItemName,
                        price = p.Item.SellingPrice,
                        barcode = p.Item.EffectiveBarcode,
                        narrow_dots = p.NarrowDots
                    });
                }
                File.WriteAllText(placement, JsonSerializer.Serialize(new
                {
                    labels = result.LabelCount,
                    rows = result.RowCount,
                    columns = settings.Label.Columns,
                    placement = rows
                }, new JsonSerializerOptions { WriteIndented = true }));
                Console.Error.WriteLine("Placement written to " + placement);
            }
            return 0;
        }

        private static int Calibrate(string[] args, AppSettings settings)
        {
            string tspl = TsplBuilder.BuildCalibrationSheet(settings.Label);
            string outPath = Arg(args, "--out");
            if (outPath != null)
            {
                File.WriteAllText(outPath, tspl, new UTF8Encoding(false));
                Console.Error.WriteLine("Written to " + outPath);
            }
            else Console.Write(tspl);
            return 0;
        }

        /// <summary>
        /// The same print agent the Windows program runs, driven from a
        /// terminal. With --spool it writes each job to a file rather than to a
        /// printer, so the online test page can be tried end to end before
        /// anybody plugs a TSC in.
        /// </summary>
        private static int Serve(string[] args, AppSettings settings)
        {
            int port = Int(args, "--port") ?? settings.AgentPort;
            string spool = Arg(args, "--spool");
            int jobNo = 0;

            Func<string, string> check;
            Action<string, string, string> print;

            if (spool != null)
            {
                Directory.CreateDirectory(spool);
                check = _ => null;                       // a folder is always ready
                print = (printer, tspl, job) =>
                {
                    jobNo++;
                    string file = Path.Combine(spool, "job-" + jobNo.ToString("000") + ".tspl");
                    File.WriteAllText(file, tspl, new UTF8Encoding(false));
                    Console.Error.WriteLine("spooled " + file);
                };
                settings.PrinterName = "Spool folder: " + spool;
            }
            else
            {
                check = _ => "This is the command line build, which has no printer attached. "
                           + "Use the Windows program, or pass --spool <folder> to write the jobs to files.";
                print = (a, b, c) => throw new InvalidOperationException("No printer in the command line build.");
            }

            using var agent = new LocalAgent(() => settings, m => Console.Error.WriteLine(m), check, print);
            agent.Start(port);
            Console.Error.WriteLine("Print agent on http://127.0.0.1:" + port + "/  - press Ctrl+C to stop.");

            var stop = new System.Threading.ManualResetEventSlim(false);
            Console.CancelKeyPress += (s, e) => { e.Cancel = true; stop.Set(); };
            stop.Wait();
            agent.Stop();
            return 0;
        }

        private static async Task<int> Fetch(string[] args, AppSettings settings)
        {
            string spec = Arg(args, "--items");
            if (spec == null) { Console.Error.WriteLine("--items is required, e.g. --items 1:10,2:20"); return 1; }

            var queue = new List<LabelItem>();
            foreach (string pair in spec.Split(','))
            {
                var bits = pair.Split(':');
                if (bits.Length != 2) continue;
                if (!int.TryParse(bits[0].Trim(), out int id)) continue;
                if (!int.TryParse(bits[1].Trim(), out int qty)) qty = 1;
                queue.Add(new LabelItem { ItemId = id, Quantity = qty });
            }
            if (queue.Count == 0) { Console.Error.WriteLine("No usable item:quantity pairs in --items."); return 1; }

            using var api = new PosApi(settings.PosUrl, settings.ApiKey);
            var fresh = await api.RefreshQueueAsync(queue).ConfigureAwait(false);

            string json = JsonSerializer.Serialize(new { labels = fresh },
                            new JsonSerializerOptions { WriteIndented = true });
            string outPath = Arg(args, "--out");
            if (outPath != null) { File.WriteAllText(outPath, json); Console.Error.WriteLine("Written to " + outPath); }
            else Console.WriteLine(json);
            return 0;
        }

        // -------------------------------------------------------------- helpers
        private static List<LabelItem> LoadQueue(string path)
        {
            string text = File.ReadAllText(path);
            var opts = new JsonSerializerOptions
            {
                PropertyNameCaseInsensitive = true,
                NumberHandling = System.Text.Json.Serialization.JsonNumberHandling.AllowReadingFromString
            };
            // Accept either the API's {"labels":[...]} or a bare array.
            text = text.TrimStart();
            if (text.StartsWith("["))
                return JsonSerializer.Deserialize<List<LabelItem>>(text, opts) ?? new List<LabelItem>();
            var wrapped = JsonSerializer.Deserialize<BatchResponse>(text, opts);
            return wrapped?.Labels ?? new List<LabelItem>();
        }

        private static bool Has(string[] a, string name)
        {
            foreach (string s in a) if (string.Equals(s, name, StringComparison.OrdinalIgnoreCase)) return true;
            return false;
        }

        private static string Arg(string[] a, string name)
        {
            for (int i = 0; i < a.Length - 1; i++)
                if (string.Equals(a[i], name, StringComparison.OrdinalIgnoreCase)) return a[i + 1];
            return null;
        }

        private static void ApplySpecOverrides(string[] args, LabelSpec s)
        {
            double? d;
            int? n;
            if ((d = Dbl(args, "--label-width"))   != null) s.LabelWidthMm    = d.Value;
            if ((d = Dbl(args, "--label-height"))  != null) s.LabelHeightMm   = d.Value;
            if ((n = Int(args, "--columns"))       != null) s.Columns         = n.Value;
            if ((d = Dbl(args, "--column-gap"))    != null) s.ColumnGapMm     = d.Value;
            if ((d = Dbl(args, "--row-gap"))       != null) s.RowGapMm        = d.Value;
            if ((d = Dbl(args, "--left-margin"))   != null) s.LeftMarginMm    = d.Value;
            if ((d = Dbl(args, "--top-offset"))    != null) s.TopOffsetMm     = d.Value;
            if ((d = Dbl(args, "--inner-margin"))  != null) s.InnerMarginMm   = d.Value;
            if ((d = Dbl(args, "--barcode-height"))!= null) s.BarcodeHeightMm = d.Value;
            if ((n = Int(args, "--narrow-dots"))   != null) s.BarcodeNarrowDots = n.Value;
            if ((n = Int(args, "--dpi"))           != null) s.Dpi             = n.Value;
            if ((n = Int(args, "--speed"))         != null) s.Speed           = n.Value;
            if ((n = Int(args, "--density"))       != null) s.Density         = n.Value;
            string shop = Arg(args, "--shop-line");
            if (shop != null) s.ShopLine = shop;
            if (Has(args, "--no-price"))        s.ShowPrice = false;
            if (Has(args, "--no-name"))         s.ShowItemName = false;
            if (Has(args, "--no-code"))         s.ShowItemCode = false;
            if (Has(args, "--no-barcode-text")) s.ShowBarcodeText = false;
        }

        private static double? Dbl(string[] a, string name)
        {
            string v = Arg(a, name);
            if (v != null && double.TryParse(v, NumberStyles.Float, CultureInfo.InvariantCulture, out double d)) return d;
            return null;
        }
        private static int? Int(string[] a, string name)
        {
            string v = Arg(a, name);
            if (v != null && int.TryParse(v, out int n)) return n;
            return null;
        }
    }
}
