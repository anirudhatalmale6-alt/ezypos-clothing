using System;

namespace EzyLabel.Core
{
    /// <summary>
    /// How wide a Code 128 barcode will actually be on the paper.
    ///
    /// This exists for one reason: a barcode that is one millimetre too wide
    /// runs off the sticker and onto the next one, and you only find out after
    /// you have printed a hundred of them. So the width is worked out before
    /// anything is sent to the printer, and a code that will not fit is
    /// reported by name instead of being printed wrong.
    ///
    /// Code 128 geometry: every symbol character is 11 modules wide, except the
    /// stop pattern which is 13. A barcode is start + data + checksum + stop,
    /// so
    ///     modules = 11 * (1 + dataSymbols + 1) + 13
    ///             = 11 * dataSymbols + 35
    /// In subset C a pair of digits shares one symbol, so an all-numeric code of
    /// even length is roughly half as wide.
    /// </summary>
    public static class Code128
    {
        /// <summary>
        /// Symbols the data occupies. Digits pack two to a symbol in subset C,
        /// but only in even-length runs, and the printer needs a switch symbol
        /// to get in and out of subset C - so a mixed code is measured the
        /// pessimistic way, one symbol per character. Being pessimistic here is
        /// deliberate: it is better to shrink a barcode that would have fitted
        /// than to overflow one that would not.
        /// </summary>
        public static int DataSymbols(string data)
        {
            if (string.IsNullOrEmpty(data)) return 0;

            bool allDigits = true;
            foreach (char c in data)
            {
                if (c < '0' || c > '9') { allDigits = false; break; }
            }

            if (allDigits && data.Length % 2 == 0)
            {
                return data.Length / 2;         // pure subset C
            }
            if (allDigits)
            {
                // Odd length: one digit has to be sent on its own.
                return (data.Length - 1) / 2 + 1;
            }
            return data.Length;                  // subset B, one symbol each
        }

        public static int Modules(string data) => 11 * DataSymbols(data) + 35;

        public static int WidthDots(string data, int narrowDots) => Modules(data) * narrowDots;

        // ---------------------------------------------------------------
        // Full symbol table, so the on-screen preview draws the same bars the
        // printer will. Each string is the module widths of one symbol,
        // alternating bar, space, bar, space...
        // ---------------------------------------------------------------
        private static readonly string[] Patterns =
        {
            "212222","222122","222221","121223","121322","131222","122213","122312",
            "132212","221213","221312","231212","112232","122132","122231","113222",
            "123122","123221","223211","221132","221231","213212","223112","312131",
            "311222","321122","321221","312212","322112","322211","212123","212321",
            "232121","111323","131123","131321","112313","132113","132311","211313",
            "231113","231311","112133","112331","132131","113123","113321","133121",
            "313121","211331","231131","213113","213311","213131","311123","311321",
            "331121","312113","312311","332111","314111","221411","431111","111224",
            "111422","121124","121421","141122","141221","112214","112412","122114",
            "122411","142112","142211","241211","221114","413111","241112","134111",
            "111242","121142","121241","114212","124112","124211","411212","421112",
            "421211","212141","214121","412121","111143","111341","131141","114113",
            "114311","411113","411311","113141","114131","311141","411131","211412",
            "211214","211232","2331112"
        };

        private const int StartB = 104;
        private const int StartC = 105;
        private const int Stop = 106;

        /// <summary>Symbol values for the data, including start, checksum and stop.</summary>
        public static System.Collections.Generic.List<int> SymbolValues(string data)
        {
            data ??= "";
            bool allDigits = data.Length > 0;
            foreach (char c in data) if (c < '0' || c > '9') { allDigits = false; break; }

            var values = new System.Collections.Generic.List<int>();
            if (allDigits && data.Length % 2 == 0)
            {
                values.Add(StartC);
                for (int i = 0; i < data.Length; i += 2)
                    values.Add(int.Parse(data.Substring(i, 2)));
            }
            else
            {
                values.Add(StartB);
                foreach (char c in data) values.Add(c - 32);
            }

            long sum = values[0];
            for (int i = 1; i < values.Count; i++) sum += (long)values[i] * i;
            values.Add((int)(sum % 103));
            values.Add(Stop);
            return values;
        }

        /// <summary>(isBar, moduleCount) pairs across the whole symbol.</summary>
        public static System.Collections.Generic.List<(bool IsBar, int Modules)> Bars(string data)
        {
            var outList = new System.Collections.Generic.List<(bool, int)>();
            foreach (int v in SymbolValues(data))
            {
                if (v < 0 || v >= Patterns.Length) continue;
                bool isBar = true;
                foreach (char c in Patterns[v])
                {
                    outList.Add((isBar, c - '0'));
                    isBar = !isBar;
                }
            }
            return outList;
        }

        /// <summary>
        /// The widest narrow-bar setting, up to <paramref name="preferred"/>,
        /// that still leaves the barcode inside <paramref name="availableDots"/>.
        /// Returns 0 when even the thinnest bar overflows, which means the item
        /// code is simply too long for a 38 mm sticker and has to be shortened.
        /// </summary>
        public static int FitNarrowDots(string data, int availableDots, int preferred)
        {
            for (int n = preferred; n >= 1; n--)
            {
                if (WidthDots(data, n) <= availableDots) return n;
            }
            return 0;
        }
    }
}
