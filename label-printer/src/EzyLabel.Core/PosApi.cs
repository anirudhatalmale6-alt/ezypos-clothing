using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Text;
using System.Text.Json;
using System.Threading;
using System.Threading.Tasks;

namespace EzyLabel.Core
{
    public class ApiException : Exception
    {
        public ApiException(string message) : base(message) { }
    }

    /// <summary>
    /// Talks to the barcode API already built into EzyPOS.
    ///
    ///   GET  /barcode-api/items?search=...    the item list, searchable
    ///   POST /barcode-api/batch               the label data for a queue
    ///   GET  /barcode-api/info                used to test the connection
    ///
    /// The API key goes in the X-API-Key header. It is the same key the
    /// Barcode/LabelJoy settings page in EzyPOS shows you.
    /// </summary>
    public class PosApi : IDisposable
    {
        private readonly HttpClient _http;
        private static readonly JsonSerializerOptions Json = new JsonSerializerOptions
        {
            PropertyNameCaseInsensitive = true,
            NumberHandling = System.Text.Json.Serialization.JsonNumberHandling.AllowReadingFromString
        };

        public string BaseUrl { get; }
        public string ApiKey { get; }

        public PosApi(string baseUrl, string apiKey, int timeoutSeconds = 30)
        {
            if (string.IsNullOrWhiteSpace(baseUrl))
                throw new ApiException("The EzyPOS address has not been filled in. Put it in on the Settings tab.");
            if (string.IsNullOrWhiteSpace(apiKey))
                throw new ApiException("The API key has not been filled in. Copy it from Masters > Barcode Settings in EzyPOS.");

            BaseUrl = baseUrl.Trim().TrimEnd('/');
            ApiKey = apiKey.Trim();

            _http = new HttpClient { Timeout = TimeSpan.FromSeconds(timeoutSeconds) };
            _http.DefaultRequestHeaders.Add("X-API-Key", ApiKey);
            _http.DefaultRequestHeaders.Add("User-Agent", "EzyPOS-LabelPrinter/1.0");
        }

        private string Url(string path) => BaseUrl + "/" + path.TrimStart('/');

        private static string Friendly(Exception ex, string what)
        {
            if (ex is TaskCanceledException)
                return "EzyPOS did not answer in time while " + what + ". Check the address and that the site is up.";
            if (ex is HttpRequestException)
                return "Could not reach EzyPOS while " + what + ". Check the address, and that this PC is on the internet. ("
                       + ex.Message + ")";
            return "Something went wrong while " + what + ": " + ex.Message;
        }

        private async Task<string> GetStringAsync(string path, CancellationToken ct)
        {
            HttpResponseMessage resp;
            try { resp = await _http.GetAsync(Url(path), ct).ConfigureAwait(false); }
            catch (Exception ex) { throw new ApiException(Friendly(ex, "reading from EzyPOS")); }

            string body = await resp.Content.ReadAsStringAsync().ConfigureAwait(false);
            ThrowIfBad(resp, body);
            return body;
        }

        private static void ThrowIfBad(HttpResponseMessage resp, string body)
        {
            if (resp.IsSuccessStatusCode) return;
            int code = (int)resp.StatusCode;
            if (code == 401 || code == 403)
                throw new ApiException("EzyPOS refused the API key. Check it against Masters > Barcode Settings in EzyPOS.");
            if (code == 404)
                throw new ApiException("EzyPOS answered, but that address is not there. Check the EzyPOS address on the Settings tab - it should be the site address, with no /index.php on the end.");
            throw new ApiException("EzyPOS answered with an error (" + code + "). " + Trim(body));
        }

        private static string Trim(string s)
        {
            if (string.IsNullOrEmpty(s)) return "";
            s = s.Trim();
            return s.Length > 300 ? s.Substring(0, 300) + "..." : s;
        }

        public async Task<string> TestConnectionAsync(CancellationToken ct = default)
        {
            string body = await GetStringAsync("barcode-api/info", ct).ConfigureAwait(false);
            try
            {
                using var doc = JsonDocument.Parse(body);
                if (doc.RootElement.TryGetProperty("company", out var c)) return c.GetString() ?? "connected";
                if (doc.RootElement.TryGetProperty("shop_name", out var s)) return s.GetString() ?? "connected";
            }
            catch { /* the call worked; the shape of the reply does not matter here */ }
            return "connected";
        }

        public async Task<List<LabelItem>> SearchItemsAsync(string search, CancellationToken ct = default)
        {
            string path = "barcode-api/items";
            if (!string.IsNullOrWhiteSpace(search))
                path += "?search=" + Uri.EscapeDataString(search.Trim());

            string body = await GetStringAsync(path, ct).ConfigureAwait(false);
            ItemsResponse parsed;
            try { parsed = JsonSerializer.Deserialize<ItemsResponse>(body, Json); }
            catch (Exception ex) { throw new ApiException("EzyPOS sent something this program could not read: " + ex.Message); }

            if (parsed == null) throw new ApiException("EzyPOS sent an empty reply.");
            if (!string.IsNullOrEmpty(parsed.Error)) throw new ApiException(parsed.Error);
            return parsed.Items ?? new List<LabelItem>();
        }

        /// <summary>
        /// Re-read the queue straight from EzyPOS just before printing, so the
        /// price on the sticker is the price in the system now, not the price
        /// that was on screen when the item was added an hour ago.
        /// </summary>
        public async Task<List<LabelItem>> RefreshQueueAsync(IEnumerable<LabelItem> queue, CancellationToken ct = default)
        {
            var payload = new List<object>();
            var wanted = new List<LabelItem>();
            foreach (var q in queue)
            {
                payload.Add(new { item_id = q.ItemId, quantity = q.Quantity });
                wanted.Add(q);
            }
            if (payload.Count == 0) return new List<LabelItem>();

            string json = JsonSerializer.Serialize(new { items = payload });
            HttpResponseMessage resp;
            try
            {
                using var content = new StringContent(json, Encoding.UTF8, "application/json");
                resp = await _http.PostAsync(Url("barcode-api/batch"), content, ct).ConfigureAwait(false);
            }
            catch (Exception ex) { throw new ApiException(Friendly(ex, "fetching the label data")); }

            string body = await resp.Content.ReadAsStringAsync().ConfigureAwait(false);
            ThrowIfBad(resp, body);

            BatchResponse parsed;
            try { parsed = JsonSerializer.Deserialize<BatchResponse>(body, Json); }
            catch (Exception ex) { throw new ApiException("EzyPOS sent something this program could not read: " + ex.Message); }

            if (parsed?.Labels == null || parsed.Labels.Count == 0)
                throw new ApiException("EzyPOS did not return label data for any of the queued items. "
                                     + "They may have been deleted or made inactive since they were added.");

            // Keep the queue's own order and quantities - the API returns the
            // items it found, which may be fewer, and in its own order.
            var byId = new Dictionary<int, LabelItem>();
            foreach (var l in parsed.Labels) byId[l.ItemId] = l;

            var outList = new List<LabelItem>();
            foreach (var q in wanted)
            {
                if (byId.TryGetValue(q.ItemId, out var fresh))
                {
                    var copy = fresh.Clone();
                    copy.Quantity = q.Quantity;
                    outList.Add(copy);
                }
                else
                {
                    var copy = q.Clone();
                    copy.Status = "Not found in EzyPOS - skipped";
                    outList.Add(copy);
                }
            }
            return outList;
        }

        public void Dispose() => _http?.Dispose();
    }
}
