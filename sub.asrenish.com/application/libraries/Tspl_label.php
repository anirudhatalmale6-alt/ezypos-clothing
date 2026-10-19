<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * TSPL for the TSC label printer, built on the server.
 *
 * This is a line-for-line port of TsplBuilder in the Windows label printer
 * (label-printer/src/EzyLabel.Core/TsplBuilder.cs). Same geometry, same fonts,
 * same barcode sizing, same rounding - so a job printed from the browser
 * through QZ Tray and a job printed from the Windows program are the same
 * bytes. That is checked, not assumed: tools/compare_tspl.sh feeds the same
 * queue to both and diffs them.
 *
 * Why the browser does not just "print the page" any more
 * -------------------------------------------------------
 * The old Print Barcodes button drew the labels as a web page and let Windows
 * print it. Windows then works out the page length from the DRIVER's paper
 * settings, not from the roll. If the two differ by a fraction of a
 * millimetre, every page is out by that much more than the last - the first
 * label is right and the twentieth has walked up the sticker. TSPL sent raw
 * goes to the printer's own command language and never through that page
 * logic at all.
 *
 * No CodeIgniter in here on purpose, so it can be run and tested on its own.
 */
class Tspl_label
{
    const CRLF = "\r\n";

    /** Every setting, and what it is when nobody has changed it. */
    public static function defaults()
    {
        return array(
            'width_mm'        => 38,    // one sticker
            'height_mm'       => 25,
            'columns'         => 2,     // stickers across the roll
            'col_gap_mm'      => 2,
            'row_gap_mm'      => 2,     // the die-cut gap the sensor sees
            'left_mm'         => 0,
            'top_mm'          => 0,     // vertical trim
            'inner_mm'        => 1.5,
            'dpi'             => 203,
            'speed'           => 3,
            'density'         => 8,
            'direction'       => 1,
            'mirror'          => 0,
            'show_name'       => 1,
            'show_code'       => 1,
            'show_price'      => 1,
            'show_bar_text'   => 1,
            'hide_dup_code'   => 1,
            'line_gap_mm'     => 0.4,
            'currency'        => 'Rs.',
            'shop_line'       => '',
            'bar_height_mm'   => 8,
            'narrow_dots'     => 2,
            'name_font'       => '2',
            'code_font'       => '1',
            'price_font'      => '3',
            'price_mult'      => 1,
            'tear'            => 0,
            // When ON, the job does not send SIZE and GAP at all and the
            // printer uses what it measured itself when the roll was taught.
            // See trust_printer() below for why this exists.
            'trust_printer'   => 0,
            'printer'         => ''
        );
    }

    private $s;

    public function __construct($settings = array())
    {
        $d = self::defaults();
        foreach ($d as $k => $v) {
            if (is_array($settings) && array_key_exists($k, $settings) && $settings[$k] !== '' && $settings[$k] !== null) {
                $d[$k] = self::cast($k, $settings[$k]);
            }
        }
        // currency and shop line may legitimately be blank
        if (isset($settings['shop_line'])) { $d['shop_line'] = (string)$settings['shop_line']; }
        if (isset($settings['currency']))  { $d['currency']  = (string)$settings['currency']; }
        $this->s = $d;
    }

    public function settings() { return $this->s; }

    /**
     * Each setting's real type, by NAME - not guessed from its default.
     *
     * The first version took the type from the default value. The label
     * sizes default to whole numbers (38, 25, 2), so a 25.5 mm sticker or a
     * 2.5 mm gap was quietly cut to 25 and 2. Half a millimetre per label is
     * precisely the kind of error that walks the printing up the roll.
     */
    private static function cast($k, $v)
    {
        static $ints = array('columns','dpi','speed','density','direction','mirror','show_name','show_code',
                             'show_price','show_bar_text','hide_dup_code','narrow_dots','price_mult','tear','trust_printer');
        static $strs = array('name_font','code_font','price_font','currency','shop_line','printer');
        if (in_array($k, $ints, true)) return (int)$v;
        if (in_array($k, $strs, true)) return (string)$v;
        return (float)$v;
    }

    /* ------------------------------------------------------------ geometry */

    /** Millimetres to printer dots. Rounds half to even, exactly as .NET's
     *  Math.Round does, so the two builders never disagree by one dot. */
    public function mm($mm)
    {
        return (int)round($mm * ($this->s['dpi'] / 25.4), 0, PHP_ROUND_HALF_EVEN);
    }

    private function labelW()   { return $this->mm($this->s['width_mm']); }
    private function labelH()   { return $this->mm($this->s['height_mm']); }
    private function innerD()   { return $this->mm($this->s['inner_mm']); }
    private function usable()   { return $this->labelW() - 2 * $this->innerD(); }

    public function webWidthMm()
    {
        $s = $this->s;
        return $s['left_mm'] + $s['columns'] * $s['width_mm'] + ($s['columns'] - 1) * $s['col_gap_mm'];
    }

    private function columnOrigin($c)
    {
        return $this->mm($this->s['left_mm']) + $c * ($this->labelW() + $this->mm($this->s['col_gap_mm']));
    }

    public function validate()
    {
        $s = $this->s;
        if ($s['width_mm'] <= 0 || $s['height_mm'] <= 0) return 'Label width and height must both be more than zero.';
        if ($s['columns'] < 1 || $s['columns'] > 4) return 'Labels across must be between 1 and 4.';
        if ($s['col_gap_mm'] < 0 || $s['row_gap_mm'] < 0) return 'The gaps cannot be negative.';
        if ($s['dpi'] <= 0) return 'Printer resolution must be more than zero.';
        if ($s['inner_mm'] * 2 >= $s['width_mm']) return 'The inner margin is wider than the label itself.';
        if ($s['bar_height_mm'] <= 0 || $s['bar_height_mm'] >= $s['height_mm']) return 'The barcode height must fit inside the label.';
        if ($s['speed'] < 1 || $s['speed'] > 12) return 'Speed should be between 1 and 12.';
        if ($s['density'] < 0 || $s['density'] > 15) return 'Density should be between 0 and 15.';
        if ($s['narrow_dots'] < 1 || $s['narrow_dots'] > 6) return 'The narrow bar width should be between 1 and 6 dots.';
        if ($s['line_gap_mm'] < 0 || $s['line_gap_mm'] > 5) return 'The gap between lines should be between 0 and 5 mm.';
        return null;
    }

    /* ------------------------------------------------------------- fonts */

    public static function fontW($f)
    {
        $w = array('1' => 8, '2' => 12, '3' => 16, '4' => 24, '5' => 32);
        return isset($w[(string)$f]) ? $w[(string)$f] : 12;
    }
    public static function fontH($f)
    {
        $h = array('1' => 12, '2' => 20, '3' => 24, '4' => 32, '5' => 48);
        return isset($h[(string)$f]) ? $h[(string)$f] : 20;
    }

    public static function fit($text, $availableDots, $charW)
    {
        $text = (string)$text;
        if ($text === '' || $charW <= 0) return $text;
        $max = (int)floor($availableDots / $charW);
        if ($max <= 0) return '';
        $text = trim($text);
        return (mb_strlen($text) <= $max) ? $text : mb_substr($text, 0, $max);
    }

    public static function esc($t)
    {
        $t = (string)$t;
        return str_replace(array('\\', '"', "\r", "\n"), array('\\\\', '\\"', ' ', ' '), $t);
    }

    private static function text($x, $y, $font, $xm, $ym, $content)
    {
        return 'TEXT '.$x.','.$y.',"'.$font.'",0,'.$xm.','.$ym.',"'.self::esc($content).'"'.self::CRLF;
    }

    private static function centred($x0, $usable, $w)
    {
        return $x0 + max(0, intdiv($usable - $w, 2));
    }

    /** "78", "25", "0.5" - the way .NET writes ToString("0.##"). */
    private static function num($v)
    {
        $t = number_format((float)$v, 2, '.', '');
        $t = rtrim(rtrim($t, '0'), '.');
        return $t === '' || $t === '-0' ? '0' : $t;
    }

    /* ---------------------------------------------------------- Code 128 */

    public static function code128Symbols($data)
    {
        $data = (string)$data;
        if ($data === '') return 0;
        if (ctype_digit($data)) {
            $n = strlen($data);
            return ($n % 2 === 0) ? intdiv($n, 2) : intdiv($n - 1, 2) + 1;
        }
        return strlen($data);
    }
    public static function code128Width($data, $narrow) { return (11 * self::code128Symbols($data) + 35) * $narrow; }
    public static function fitNarrow($data, $avail, $preferred)
    {
        for ($n = $preferred; $n >= 1; $n--) {
            if (self::code128Width($data, $n) <= $avail) return $n;
        }
        return 0;
    }

    /* ----------------------------------------------------------- the job */

    /**
     * Is the separate item-code line worth printing? Not when it is the same
     * string as the barcode number already printed under the bars.
     */
    private function wantsCodeLine($item)
    {
        $s = $this->s;
        if (!$s['show_code'] || trim((string)$item['code']) === '') return false;
        if (!$s['hide_dup_code']) return true;
        if (!$s['show_bar_text']) return true;
        // The EFFECTIVE barcode - what actually goes under the bars, which
        // falls back to the item code. Comparing the raw barcode field instead
        // printed the code twice on every item that has no separate barcode.
        $bar = trim((string)$item['barcode']) !== '' ? trim((string)$item['barcode']) : trim((string)$item['code']);
        if ($bar === '') return true;
        return strcasecmp($bar, trim((string)$item['code'])) !== 0;
    }

    /**
     * SIZE and GAP, or nothing.
     *
     * Normally the job tells the printer how big the sticker is. But once the
     * printer has measured the roll itself (Teach the printer this roll), the
     * printer's figure is the more accurate one - and sending SIZE and GAP
     * would throw that measurement away and replace it with ours on the very
     * next job. With trust_printer on, the job leaves both out and the
     * printer keeps using what it measured.
     */
    private function media()
    {
        if (!empty($this->s['trust_printer'])) {
            return '; SIZE and GAP left to the printer - it measured this roll itself'.self::CRLF;
        }
        return 'SIZE '.self::num($this->webWidthMm()).' mm, '.self::num($this->s['height_mm']).' mm'.self::CRLF
             . 'GAP '.self::num($this->s['row_gap_mm']).' mm, 0 mm'.self::CRLF;
    }

    private function header($labels, $rows)
    {
        $s = $this->s;
        $o  = '; EzyPOS label job - '.$labels.' labels in '.$rows.' rows of '.$s['columns'].self::CRLF;
        $o .= '; media '.self::num($s['width_mm']).' x '.self::num($s['height_mm']).' mm, '
            . $s['columns'].'-up, column gap '.self::num($s['col_gap_mm']).' mm'.self::CRLF;
        $o .= $this->media();
        $o .= 'DIRECTION '.$s['direction'].','.$s['mirror'].self::CRLF;
        $o .= 'REFERENCE 0,'.$this->mm($s['top_mm']).self::CRLF;
        $o .= 'OFFSET 0 mm'.self::CRLF;
        $o .= 'SPEED '.$s['speed'].self::CRLF;
        $o .= 'DENSITY '.$s['density'].self::CRLF;
        $o .= 'SET COUNTER @0 1'.self::CRLF;
        // Off: with tear on the printer runs each label out to the tear bar and
        // pulls it back before the next, and over a batch the small difference
        // between the two adds up.
        $o .= ($s['tear'] ? 'SET TEAR ON' : 'SET TEAR OFF').self::CRLF;
        $o .= 'CODEPAGE UTF-8'.self::CRLF;
        return $o;
    }

    private function oneLabel($item, $originX, $originY, $narrow)
    {
        $s = $this->s;
        $x0 = $originX + $this->innerD();
        $usable = $this->usable();

        $gap = $this->mm($s['line_gap_mm']);
        if ($gap < 1) $gap = 1;

        $code = trim((string)$item['barcode']) !== '' ? trim((string)$item['barcode']) : trim((string)$item['code']);
        $wantShop  = strlen($s['shop_line']) > 0;
        $wantName  = $s['show_name'] && trim((string)$item['name']) !== '';
        $wantCode  = $this->wantsCodeLine($item);
        $wantBars  = $code !== '' && $narrow > 0;
        $wantPrice = (bool)$s['show_price'];

        $shopF = '1'; $nameF = $s['name_font']; $codeF = $s['code_font']; $priceF = $s['price_font'];
        $pm = max(1, (int)$s['price_mult']);

        $shopH  = $wantShop  ? self::fontH($shopF)  : 0;
        $nameH  = $wantName  ? self::fontH($nameF)  : 0;
        $codeH  = $wantCode  ? self::fontH($codeF)  : 0;
        $barH   = $this->mm($s['bar_height_mm']);
        $readH  = $s['show_bar_text'] ? 20 : 0;
        $barsH  = $wantBars  ? $barH + $readH : 0;
        $priceH = $wantPrice ? self::fontH($priceF) * $pm : 0;

        $blocks = ($wantShop?1:0) + ($wantName?1:0) + ($wantCode?1:0) + ($wantBars?1:0) + ($wantPrice?1:0);
        $total  = $shopH + $nameH + $codeH + $barsH + $priceH + ($blocks > 1 ? ($blocks - 1) * $gap : 0);

        $inner = $this->labelH() - 2 * $this->innerD();
        $y = $originY + $this->innerD() + max(0, intdiv($inner - $total, 2));

        $o = '';
        if ($wantShop) {
            $cw = self::fontW($shopF);
            $t = self::fit($s['shop_line'], $usable, $cw);
            $o .= self::text(self::centred($x0, $usable, mb_strlen($t) * $cw), $y, $shopF, 1, 1, $t);
            $y += $shopH + $gap;
        }
        if ($wantName) {
            $cw = self::fontW($nameF);
            $t = self::fit($item['name'], $usable, $cw);
            $o .= self::text(self::centred($x0, $usable, mb_strlen($t) * $cw), $y, $nameF, 1, 1, $t);
            $y += $nameH + $gap;
        }
        if ($wantCode) {
            $cw = self::fontW($codeF);
            $t = self::fit($item['code'], $usable, $cw);
            $o .= self::text(self::centred($x0, $usable, mb_strlen($t) * $cw), $y, $codeF, 1, 1, $t);
            $y += $codeH + $gap;
        }
        if ($wantBars) {
            $bw = self::code128Width($code, $narrow);
            $o .= 'BARCODE '.self::centred($x0, $usable, $bw).','.$y.',"128",'.$barH.','
                . ($s['show_bar_text'] ? 1 : 0).',0,'.$narrow.','.($narrow * 2).',"'.self::esc($code).'"'.self::CRLF;
            $y += $barsH + $gap;
        }
        if ($wantPrice) {
            $price = $s['currency'].' '.number_format((float)$item['price'], 2, '.', ',');
            $cw = self::fontW($priceF) * $pm;
            $t = self::fit($price, $usable, $cw);
            $o .= self::text(self::centred($x0, $usable, mb_strlen($t) * $cw), $y, $priceF, $pm, $pm, $t);
        }
        return $o;
    }

    /**
     * Build a job.
     *
     * @param array $queue  each: code, barcode (optional), name, price, qty
     * @return array  tspl, labels, rows, warnings[], error
     */
    public function build($queue)
    {
        $out = array('tspl' => '', 'labels' => 0, 'rows' => 0, 'warnings' => array(), 'error' => null);
        $bad = $this->validate();
        if ($bad) { $out['error'] = $bad; return $out; }

        $flat = array();
        foreach ((array)$queue as $it) {
            $item = array(
                'code'    => isset($it['code'])    ? (string)$it['code']    : '',
                'barcode' => isset($it['barcode']) ? (string)$it['barcode'] : '',
                'name'    => isset($it['name'])    ? (string)$it['name']    : '',
                'price'   => isset($it['price'])   ? (float)$it['price']    : 0,
            );
            $n = max(0, (int)(isset($it['qty']) ? $it['qty'] : 0));
            for ($i = 0; $i < $n; $i++) { $flat[] = $item; }
        }
        if (count($flat) === 0) { $out['error'] = 'There is nothing in the print queue.'; return $out; }

        $usable = $this->usable();
        $want = (int)$this->s['narrow_dots'];
        $narrowFor = array(); $tooLong = array(); $shrunk = array();
        foreach ($flat as $it) {
            $code = trim($it['barcode']) !== '' ? trim($it['barcode']) : trim($it['code']);
            if (array_key_exists($code, $narrowFor)) continue;
            if ($code === '') { $narrowFor[$code] = 0; continue; }
            $n = self::fitNarrow($code, $usable, $want);
            $narrowFor[$code] = $n;
            if ($n === 0) $tooLong[] = $code; elseif ($n < $want) $shrunk[] = $code;
        }
        if ($tooLong) {
            $out['error'] = 'These item codes are too long to fit a barcode inside a '.self::num($this->s['width_mm'])
                          .' mm sticker, even at the thinnest bar width: '.implode(', ', $tooLong)
                          .'. Shorten the item code, or use a wider label. Nothing has been printed.';
            return $out;
        }
        if ($shrunk) {
            $out['warnings'][] = 'Thinner bars used so the barcode fits the sticker for: '.implode(', ', $shrunk)
                               .'. These still scan, but check one on your scanner before printing a large run.';
        }

        $cols = (int)$this->s['columns'];
        $rows = intdiv(count($flat) + $cols - 1, $cols);
        if (count($flat) % $cols !== 0) {
            $out['warnings'][] = 'The total is an odd number, so the very last sticker on the roll is left blank. '
                               . 'Add one more label to fill it if you would rather not waste it.';
        }

        $t = $this->header(count($flat), $rows);
        for ($r = 0; $r < $rows; $r++) {
            $t .= 'CLS'.self::CRLF;
            for ($c = 0; $c < $cols; $c++) {
                $idx = $r * $cols + $c;
                if ($idx >= count($flat)) break;
                $it = $flat[$idx];
                $code = trim($it['barcode']) !== '' ? trim($it['barcode']) : trim($it['code']);
                $t .= $this->oneLabel($it, $this->columnOrigin($c), 0, $narrowFor[$code]);
            }
            $t .= 'PRINT 1,1'.self::CRLF;
        }
        $out['tspl'] = $t; $out['labels'] = count($flat); $out['rows'] = $rows;
        return $out;
    }

    /** One row of rulers and boxes, to measure where the printer really prints. */
    public function calibrationSheet()
    {
        $s = $this->s;
        $t = $this->header(1, 1).'CLS'.self::CRLF;
        $w = $this->labelW(); $h = $this->labelH();
        for ($c = 0; $c < $s['columns']; $c++) {
            $ox = $this->columnOrigin($c);
            $t .= 'BOX '.$ox.',0,'.($ox + $w - 1).','.($h - 1).',2'.self::CRLF;
            for ($m = 0; $m <= (int)$s['width_mm']; $m += 5) {
                $x = $ox + $this->mm($m);
                $t .= 'BAR '.$x.',0,2,'.($m % 10 === 0 ? 24 : 12).self::CRLF;
                if ($m % 10 === 0) $t .= self::text($x + 3, 26, '1', 1, 1, (string)$m);
            }
            for ($m = 0; $m <= (int)$s['height_mm']; $m += 5) {
                $t .= 'BAR '.$ox.','.$this->mm($m).','.($m % 10 === 0 ? 24 : 12).',2'.self::CRLF;
            }
            $t .= self::text($ox + 8, intdiv($h, 2) - 10, '2', 1, 1, 'COL '.($c + 1));
            $t .= self::text($ox + 8, intdiv($h, 2) + 12, '1', 1, 1,
                    rtrim(rtrim(number_format($s['width_mm'], 1, '.', ''), '0'), '.').'x'
                   .rtrim(rtrim(number_format($s['height_mm'], 1, '.', ''), '0'), '.').'mm');
        }
        return $t.'PRINT 1,1'.self::CRLF;
    }

    /**
     * Make the printer measure the loaded roll itself and keep the result.
     * Feeds two or three blank labels. Once per kind of label, not per job.
     */
    public function teachRoll()
    {
        $s = $this->s;
        return '; EzyPOS - teach the printer this roll'.self::CRLF
             . '; feeds two or three blank labels while it measures'.self::CRLF
             . 'SIZE '.self::num($this->webWidthMm()).' mm, '.self::num($s['height_mm']).' mm'.self::CRLF
             . 'GAP '.self::num($s['row_gap_mm']).' mm, 0 mm'.self::CRLF
             . 'DIRECTION '.$s['direction'].','.$s['mirror'].self::CRLF
             . 'SET TEAR OFF'.self::CRLF
             . 'CLS'.self::CRLF
             . 'GAPDETECT'.self::CRLF
             . 'HOME'.self::CRLF;
    }
}
