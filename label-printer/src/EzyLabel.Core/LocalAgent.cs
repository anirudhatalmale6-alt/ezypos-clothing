using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Text;
using System.Text.Json;
using System.Threading;
using System.Threading.Tasks;

namespace EzyLabel.Core
{
    /// <summary>
    /// A small web server on this PC that lets a page in the browser drive the
    /// label printer.
    ///
    /// A browser cannot talk to a USB thermal printer - no browser can, on any
    /// operating system. So the page posts the queue here instead, to
    /// http://127.0.0.1:9110, and this program does the printing. That is what
    /// makes the online test page possible.
    ///
    /// It only ever listens on 127.0.0.1, so nothing outside this PC can reach
    /// it, and the only things it will do are: say hello, build a preview, and
    /// print a queue. It cannot be asked to read a file or run anything.
    /// </summary>
    public class LocalAgent : IDisposable
    {
        private HttpListener _listener;
        private CancellationTokenSource _cts;
        private readonly Func<AppSettings> _settings;
        private readonly Action<string> _log;

        /// <summary>
        /// Returns null when the printer is ready, or a sentence explaining why
        /// it is not. Injected, so the agent knows nothing about Windows and can
        /// be exercised end to end without a printer attached.
        /// </summary>
        private readonly Func<string, string> _checkPrinter;

        /// <summary>Hand the TSPL to the printer. (printerName, tspl, jobName)</summary>
        private readonly Action<string, string, string> _print;

        public bool IsRunning => _listener != null && _listener.IsListening;
        public int Port { get; private set; }

        public LocalAgent(Func<AppSettings> settings,
                          Action<string> log,
                          Func<string, string> checkPrinter,
                          Action<string, string, string> print)
        {
            _settings = settings;
            _log = log ?? (_ => { });
            _checkPrinter = checkPrinter ?? (_ => null);
            _print = print ?? ((a, b, c) => { });
        }

        public void Start(int port)
        {
            Stop();
            Port = port;
            _listener = new HttpListener();
            // The plain 127.0.0.1 prefix needs no administrator rights, unlike
            // http://+:port/ - one less thing for the shop to get wrong.
            _listener.Prefixes.Add("http://127.0.0.1:" + port + "/");
            try
            {
                _listener.Start();
            }
            catch (HttpListenerException ex)
            {
                _listener = null;
                throw new InvalidOperationException(
                    "Could not listen on port " + port + ". Another program is probably already using it. "
                    + "Change the port on the Settings tab. (" + ex.Message + ")");
            }
            _cts = new CancellationTokenSource();
            _ = Task.Run(() => Loop(_cts.Token));
            _log("Print agent listening on http://127.0.0.1:" + port + "/");
        }

        public void Stop()
        {
            try { _cts?.Cancel(); } catch { }
            try { if (_listener != null && _listener.IsListening) _listener.Stop(); } catch { }
            _listener = null;
        }

        private async Task Loop(CancellationToken ct)
        {
            while (!ct.IsCancellationRequested && _listener != null && _listener.IsListening)
            {
                HttpListenerContext ctx;
                try { ctx = await _listener.GetContextAsync().ConfigureAwait(false); }
                catch { return; }       // listener stopped
                _ = Task.Run(() => Handle(ctx));
            }
        }

        private void Handle(HttpListenerContext ctx)
        {
            try
            {
                // The test page is served from the EzyPOS site, so its requests
                // come from a different origin. Allow it, but only for these
                // three harmless endpoints.
                ctx.Response.AddHeader("Access-Control-Allow-Origin", "*");
                ctx.Response.AddHeader("Access-Control-Allow-Headers", "Content-Type");
                ctx.Response.AddHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");

                if (ctx.Request.HttpMethod == "OPTIONS") { Json(ctx, 200, new { ok = true }); return; }

                string path = (ctx.Request.Url?.AbsolutePath ?? "/").TrimEnd('/').ToLowerInvariant();
                switch (path)
                {
                    case "":
                    case "/status":  Status(ctx);  break;
                    case "/print":   Print(ctx);   break;
                    case "/preview": Preview(ctx); break;
                    default: Json(ctx, 404, new { ok = false, error = "No such address on the print agent." }); break;
                }
            }
            catch (Exception ex)
            {
                try { Json(ctx, 500, new { ok = false, error = ex.Message }); } catch { }
            }
        }

        private void Status(HttpListenerContext ctx)
        {
            var s = _settings();
            string problem = _checkPrinter(s.PrinterName);
            Json(ctx, 200, new
            {
                ok = problem == null,
                agent = "EzyPOS Label Printer",
                version = "1.0",
                printer = s.PrinterName,
                printer_ready = problem == null,
                printer_message = problem ?? "Ready",
                label = new
                {
                    width_mm = s.Label.LabelWidthMm,
                    height_mm = s.Label.LabelHeightMm,
                    columns = s.Label.Columns,
                    column_gap_mm = s.Label.ColumnGapMm,
                    row_gap_mm = s.Label.RowGapMm
                }
            });
        }

        private List<LabelItem> ReadQueue(HttpListenerContext ctx)
        {
            string body;
            using (var sr = new StreamReader(ctx.Request.InputStream, Encoding.UTF8)) body = sr.ReadToEnd();
            var opts = new JsonSerializerOptions
            {
                PropertyNameCaseInsensitive = true,
                NumberHandling = System.Text.Json.Serialization.JsonNumberHandling.AllowReadingFromString
            };
            var parsed = JsonSerializer.Deserialize<BatchResponse>(body, opts);
            return parsed?.Labels ?? new List<LabelItem>();
        }

        private void Preview(HttpListenerContext ctx)
        {
            var queue = ReadQueue(ctx);
            var built = TsplBuilder.Build(queue, _settings().Label);
            Json(ctx, built.Error == null ? 200 : 400, new
            {
                ok = built.Error == null,
                error = built.Error,
                warnings = built.Warnings,
                labels = built.LabelCount,
                rows = built.RowCount,
                tspl = built.Tspl
            });
        }

        private void Print(HttpListenerContext ctx)
        {
            var s = _settings();
            var queue = ReadQueue(ctx);
            if (queue.Count == 0) { Json(ctx, 400, new { ok = false, error = "The queue that arrived was empty." }); return; }

            string problem = _checkPrinter(s.PrinterName);
            if (problem != null) { Json(ctx, 409, new { ok = false, error = problem }); return; }

            var built = TsplBuilder.Build(queue, s.Label);
            if (built.Error != null) { Json(ctx, 400, new { ok = false, error = built.Error }); return; }

            _print(s.PrinterName, built.Tspl, "EzyPOS Labels (web)");
            _log("Printed " + built.LabelCount + " labels in " + built.RowCount + " rows from the test page.");
            Json(ctx, 200, new
            {
                ok = true,
                labels = built.LabelCount,
                rows = built.RowCount,
                warnings = built.Warnings,
                message = "Sent " + built.LabelCount + " labels to " + s.PrinterName + "."
            });
        }

        private static void Json(HttpListenerContext ctx, int status, object payload)
        {
            byte[] bytes = Encoding.UTF8.GetBytes(JsonSerializer.Serialize(payload));
            ctx.Response.StatusCode = status;
            ctx.Response.ContentType = "application/json; charset=utf-8";
            ctx.Response.ContentLength64 = bytes.Length;
            ctx.Response.OutputStream.Write(bytes, 0, bytes.Length);
            ctx.Response.OutputStream.Close();
        }

        public void Dispose() => Stop();
    }
}
