using System;
using System.Collections.Generic;
using System.Drawing.Printing;
using System.Runtime.InteropServices;
using System.Text;

namespace EzyLabel.App
{
    public class PrinterException : Exception
    {
        public PrinterException(string message) : base(message) { }
    }

    /// <summary>
    /// Sends bytes to a Windows printer without Windows touching them.
    ///
    /// This matters. If TSPL is printed the ordinary way, through a printer
    /// driver, Windows renders it as a picture of text and the TSC prints the
    /// words "SIZE 78 mm, 25 mm" on your stickers. The commands have to go
    /// down as RAW, which is what the spooler calls a job whose data type it
    /// must not interpret.
    ///
    /// That is what OpenPrinter / StartDocPrinter with pDatatype "RAW" /
    /// WritePrinter does below. It is the same path the TSC utilities use.
    /// </summary>
    public static class RawPrinter
    {
        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Unicode)]
        private class DOCINFOW
        {
            [MarshalAs(UnmanagedType.LPWStr)] public string pDocName;
            [MarshalAs(UnmanagedType.LPWStr)] public string pOutputFile;
            [MarshalAs(UnmanagedType.LPWStr)] public string pDataType;
        }

        [DllImport("winspool.drv", CharSet = CharSet.Unicode, SetLastError = true)]
        private static extern bool OpenPrinterW(string src, out IntPtr hPrinter, IntPtr pd);

        [DllImport("winspool.drv", SetLastError = true)]
        private static extern bool ClosePrinter(IntPtr hPrinter);

        [DllImport("winspool.drv", CharSet = CharSet.Unicode, SetLastError = true)]
        private static extern bool StartDocPrinterW(IntPtr hPrinter, int level,
            [In, MarshalAs(UnmanagedType.LPStruct)] DOCINFOW di);

        [DllImport("winspool.drv", SetLastError = true)] private static extern bool EndDocPrinter(IntPtr hPrinter);
        [DllImport("winspool.drv", SetLastError = true)] private static extern bool StartPagePrinter(IntPtr hPrinter);
        [DllImport("winspool.drv", SetLastError = true)] private static extern bool EndPagePrinter(IntPtr hPrinter);

        [DllImport("winspool.drv", SetLastError = true)]
        private static extern bool WritePrinter(IntPtr hPrinter, IntPtr pBytes, int dwCount, out int dwWritten);

        /// <summary>Every printer this PC has installed.</summary>
        public static List<string> InstalledPrinters()
        {
            var list = new List<string>();
            foreach (string p in PrinterSettings.InstalledPrinters) list.Add(p);
            return list;
        }

        /// <summary>
        /// The first installed printer that looks like the TSC, so the right one
        /// is chosen by itself on a fresh install.
        /// </summary>
        public static string GuessTscPrinter()
        {
            var all = InstalledPrinters();
            foreach (string p in all)
            {
                string u = p.ToUpperInvariant();
                if (u.Contains("TTP-244") || u.Contains("TTP244")) return p;
            }
            foreach (string p in all)
            {
                string u = p.ToUpperInvariant();
                if (u.Contains("TSC") || u.Contains("TTP")) return p;
            }
            return null;
        }

        public static bool Exists(string printerName)
        {
            if (string.IsNullOrWhiteSpace(printerName)) return false;
            foreach (string p in InstalledPrinters())
                if (string.Equals(p, printerName, StringComparison.OrdinalIgnoreCase)) return true;
            return false;
        }

        /// <summary>
        /// Whether the printer is there AND ready. Returns null when all is
        /// well, or a sentence to show the operator when it is not - never a
        /// silent failure, because a label job that quietly goes nowhere is
        /// worse than one that refuses.
        /// </summary>
        public static string CheckReady(string printerName)
        {
            if (string.IsNullOrWhiteSpace(printerName))
                return "No printer has been chosen. Pick your TSC TTP-244 Pro on the Settings tab.";
            if (!Exists(printerName))
                return "Printer Not Connected - Windows has no printer called \"" + printerName + "\". "
                     + "Check the USB lead and that the printer is switched on, then press Refresh on the Settings tab.";

            try
            {
                var ps = new PrinterSettings { PrinterName = printerName };
                if (!ps.IsValid)
                    return "Printer Not Connected - Windows knows the name \"" + printerName
                         + "\" but cannot open it. Check the USB lead and that the printer is switched on.";
            }
            catch (Exception ex)
            {
                return "Printer Not Connected - " + ex.Message;
            }
            return null;
        }

        /// <summary>
        /// Push raw TSPL at the printer. The text goes out as single bytes,
        /// not UTF-16 - a TSC parses a byte stream, and sending wide characters
        /// puts a zero after every letter, which it reads as garbage.
        /// </summary>
        public static void SendTspl(string printerName, string tspl, string jobName = "EzyPOS Labels")
        {
            string problem = CheckReady(printerName);
            if (problem != null) throw new PrinterException(problem);

            // CODEPAGE UTF-8 is set in the job header, so UTF-8 bytes are right
            // for item names with accents while staying single-byte for ASCII.
            byte[] bytes = new UTF8Encoding(false).GetBytes(tspl);
            SendBytes(printerName, bytes, jobName);
        }

        public static void SendBytes(string printerName, byte[] bytes, string jobName)
        {
            IntPtr hPrinter;
            if (!OpenPrinterW(printerName, out hPrinter, IntPtr.Zero))
                throw new PrinterException("Could not open the printer \"" + printerName + "\". "
                    + "Windows said: " + new System.ComponentModel.Win32Exception(Marshal.GetLastWin32Error()).Message);

            IntPtr buffer = IntPtr.Zero;
            bool docStarted = false, pageStarted = false;
            try
            {
                var di = new DOCINFOW { pDocName = jobName, pDataType = "RAW" };
                if (!StartDocPrinterW(hPrinter, 1, di))
                    throw new PrinterException("The printer accepted the connection but refused the job. "
                        + new System.ComponentModel.Win32Exception(Marshal.GetLastWin32Error()).Message);
                docStarted = true;

                if (!StartPagePrinter(hPrinter))
                    throw new PrinterException("The printer refused to start the page. "
                        + new System.ComponentModel.Win32Exception(Marshal.GetLastWin32Error()).Message);
                pageStarted = true;

                buffer = Marshal.AllocCoTaskMem(bytes.Length);
                Marshal.Copy(bytes, 0, buffer, bytes.Length);

                if (!WritePrinter(hPrinter, buffer, bytes.Length, out int written))
                    throw new PrinterException("Sending the labels failed part way through. "
                        + new System.ComponentModel.Win32Exception(Marshal.GetLastWin32Error()).Message);

                if (written != bytes.Length)
                    throw new PrinterException("Only " + written + " of " + bytes.Length
                        + " bytes reached the printer. The job has been stopped - check the printer and the queue "
                        + "before printing again, as some labels may already have come out.");
            }
            finally
            {
                if (buffer != IntPtr.Zero) Marshal.FreeCoTaskMem(buffer);
                if (pageStarted) EndPagePrinter(hPrinter);
                if (docStarted) EndDocPrinter(hPrinter);
                ClosePrinter(hPrinter);
            }
        }
    }
}
