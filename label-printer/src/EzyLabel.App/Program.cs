using System;
using System.Windows.Forms;

namespace EzyLabel.App
{
    internal static class Program
    {
        [STAThread]
        private static void Main()
        {
            Application.SetHighDpiMode(HighDpiMode.SystemAware);
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            // A crash in a shop should say what went wrong, not vanish.
            Application.ThreadException += (s, e) =>
                MessageBox.Show(e.Exception.Message, "EzyPOS Label Printer",
                                MessageBoxButtons.OK, MessageBoxIcon.Error);
            AppDomain.CurrentDomain.UnhandledException += (s, e) =>
                MessageBox.Show((e.ExceptionObject as Exception)?.Message ?? "Unknown error",
                                "EzyPOS Label Printer", MessageBoxButtons.OK, MessageBoxIcon.Error);

            Application.Run(new MainForm());
        }
    }
}
