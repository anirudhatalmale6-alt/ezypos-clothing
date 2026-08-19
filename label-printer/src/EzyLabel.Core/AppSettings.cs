using System;
using System.IO;
using System.Text.Json;

namespace EzyLabel.Core
{
    /// <summary>
    /// Everything the program remembers between runs. Kept as plain readable
    /// JSON in the user's AppData folder, so it can be copied from one till to
    /// the next once one machine has been calibrated.
    /// </summary>
    public class AppSettings
    {
        public string PosUrl { get; set; } = "https://sub.asrenish.com";
        public string ApiKey { get; set; } = "";
        public string PrinterName { get; set; } = "";
        public LabelSpec Label { get; set; } = new LabelSpec();

        /// <summary>Local port the online test page talks to. 0 turns it off.</summary>
        public int AgentPort { get; set; } = 9110;
        public bool AgentEnabled { get; set; } = true;

        private static readonly JsonSerializerOptions Opts = new JsonSerializerOptions
        {
            WriteIndented = true,
            PropertyNameCaseInsensitive = true
        };

        public static string DefaultPath
        {
            get
            {
                string dir = Path.Combine(
                    Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData),
                    "EzyPOS Label Printer");
                return Path.Combine(dir, "settings.json");
            }
        }

        public static AppSettings Load(string path = null)
        {
            path ??= DefaultPath;
            try
            {
                if (File.Exists(path))
                {
                    var s = JsonSerializer.Deserialize<AppSettings>(File.ReadAllText(path), Opts);
                    if (s != null)
                    {
                        s.Label ??= new LabelSpec();
                        return s;
                    }
                }
            }
            catch
            {
                // A settings file that has been hand-edited into nonsense should
                // not stop the program opening. Fall back to the defaults; the
                // operator can fix them on the Settings tab and save over it.
            }
            return new AppSettings();
        }

        public void Save(string path = null)
        {
            path ??= DefaultPath;
            string dir = Path.GetDirectoryName(path);
            if (!string.IsNullOrEmpty(dir)) Directory.CreateDirectory(dir);
            File.WriteAllText(path, JsonSerializer.Serialize(this, Opts));
        }
    }
}
