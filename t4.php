<?php
/* Template: Asia  |  Project: Pisang  |  Router tunggal, membaca data/data.json
 *
 * Diport dari tata letak referensi Asia128. Urutan blok dan palet warna
 * mengikuti sumbernya; CSS ditulis ulang dengan prefix a- supaya tidak
 * bentrok dengan T1 (p-), T2 (g-), dan T3 (s-).
 *
 * SENGAJA TIDAK DIIKUTKAN dari referensi:
 *  - 192 berkas gambar hasil scrape dan 13 tautan gambar ke server aslinya
 *  - logo penyedia dan artwork game bermerek -> diganti chip teks dan ubin CSS
 *  - nomor kontak, ID LINE, serta tautan ke halaman milik situs sumber
 *  - jQuery, Swiper, dan skrip pelacak; slider memakai CSS scroll-snap
 */

declare(strict_types=1);

/* ---------- MUAT DATA ---------- */
$dataFile = __DIR__ . '/data/data.json';
if (!is_file($dataFile)) {
    http_response_code(503);
    exit('Data situs belum tersedia.');
}
$site = json_decode((string)file_get_contents($dataFile), true);
if (!is_array($site) || empty($site['pages']) || !is_array($site['pages'])) {
    http_response_code(503);
    exit('Data situs tidak valid.');
}

/* ---------- HELPER ---------- */
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function v(array $a, string $k, string $d = ''): string {
    return isset($a[$k]) && $a[$k] !== null && $a[$k] !== '' ? (string)$a[$k] : $d;
}
function hurufAwal(string $s): string {
    if ($s === '') { return '?'; }
    return function_exists('mb_substr') ? mb_substr($s, 0, 1, 'UTF-8') : substr($s, 0, 1);
}

$domainName = v($site, 'domain', (string)($_SERVER['HTTP_HOST'] ?? 'localhost'));
$baseUrl    = 'https://' . $domainName;

$namaSitus  = v($site, 'nama_situs', ucfirst(explode('.', $domainName)[0]));
$logo       = v($site, 'logo', '/img/logo.png');
$banner1    = v($site, 'banner1', '/img/banner1.jpg');
$banner2    = v($site, 'banner2', '/img/banner2.jpg');
$favicon    = v($site, 'favicon', '/img/favicon.png');
$ctaDaftar  = v($site, 'cta_daftar_url', '#');
$ctaLogin   = v($site, 'cta_login_url', '#');
$urlWa      = v($site, 'wa', '#');
$urlTele    = v($site, 'tele', '#');
$urlLc      = v($site, 'lc', '#');
$ampUrl     = v($site, 'amp_url', '');

/* ---------- HALAMAN TETAP ---------- */
$halamanTetap = [
    'tentang-kami' => 'Tentang Kami',
    'kontak'       => 'Kontak',
    'disclaimer'   => 'Disclaimer',
];

/* ---------- ROUTING ---------- */
$slugMinta = isset($_GET['id']) ? trim((string)$_GET['id'], "/ \t\n\r\0\x0B") : '';

$beranda = null;
foreach ($site['pages'] as $p) {
    if (($p['category'] ?? '') === 'homepage') { $beranda = $p; break; }
}
if ($beranda === null) { $beranda = $site['pages'][0]; }

$isTetap = false;
$page = null;
$is404 = false;

if ($slugMinta === '') {
    $page = $beranda;
} elseif (isset($halamanTetap[$slugMinta])) {
    $isTetap = true;
} else {
    foreach ($site['pages'] as $p) {
        if (trim((string)($p['slug'] ?? ''), '/') === $slugMinta) { $page = $p; break; }
    }
    if ($page === null) {
        http_response_code(404);
        $is404 = true;
        $page = [
            'slug' => $slugMinta,
            'category' => '404',
            'title' => 'Halaman tidak ditemukan - ' . $namaSitus,
            'description' => 'Halaman yang Anda cari tidak tersedia di ' . $namaSitus . '.',
            'h1' => 'Halaman tidak ditemukan',
            'content_html' => '<p>Alamat yang Anda buka tidak tersedia. Silakan kembali ke <a href="/">beranda ' . e($namaSitus) . '</a>.</p>',
            'lastmod' => v($site, 'generated_at', date('Y-m-d')),
        ];
    }
}

$isBeranda = (!$isTetap && !$is404 && ($page['category'] ?? '') === 'homepage');

if ($isTetap) {
    $judulTetap = $halamanTetap[$slugMinta];
    $isiTetap = '';
    $descTetap = '';
    if ($slugMinta === 'tentang-kami') {
        $isiTetap = '<p>' . e($namaSitus) . ' adalah situs informasi hiburan daring berbahasa Indonesia. Kami menyusun ulasan, penjelasan istilah, dan panduan singkat seputar permainan berbasis peluang agar pembaca punya gambaran yang jelas sebelum mengambil keputusan apa pun.</p>'
          . '<p>Seluruh isi bersifat informasional dan edukatif. Kami tidak menjanjikan kemenangan, tidak menjual jasa prediksi, dan tidak mengklaim memiliki data hasil permainan secara langsung. Angka jackpot yang tampil di situs ini adalah elemen tampilan yang bersifat ilustrasi.</p>'
          . '<p>Nama permainan dan penyedia yang disebut di halaman ini dipakai sebagai rujukan informasi. Kami tidak berafiliasi dengan pihak mana pun yang namanya disebutkan, dan tidak menampilkan logo maupun materi visual milik mereka.</p>'
          . '<p>Situs ini ditujukan bagi pembaca berusia 18 tahun ke atas. Bila Anda belum memenuhi batas usia tersebut, silakan tinggalkan halaman ini.</p>';
        $descTetap = 'Tentang ' . $namaSitus . ', situs informasi hiburan daring berbahasa Indonesia untuk pembaca 18 tahun ke atas.';
    } elseif ($slugMinta === 'kontak') {
        $isiTetap = '<p>Tim ' . e($namaSitus) . ' dapat dihubungi melalui kanal di bawah ini. Kami berusaha menjawab pada jam kerja, dan pertanyaan seputar isi halaman akan diteruskan ke tim redaksi.</p>'
          . '<ul><li>Live chat: tombol Livechat di bagian bawah setiap halaman</li><li>WhatsApp: tombol WhatsApp di bilah kontak atas</li><li>Telegram: tombol Telegram di bilah kontak atas</li></ul>'
          . '<p>Untuk koreksi informasi, sebutkan alamat halaman yang dimaksud beserta bagian yang menurut Anda keliru, supaya kami mudah menelusurinya.</p>';
        $descTetap = 'Cara menghubungi tim ' . $namaSitus . ' lewat live chat, WhatsApp, dan Telegram.';
    } else {
        $isiTetap = '<p><strong>Batas usia.</strong> Situs ini hanya ditujukan bagi pembaca berusia 18 tahun ke atas.</p>'
          . '<p><strong>Sifat konten.</strong> Seluruh isi ' . e($namaSitus) . ' bersifat informasional dan edukatif, bukan nasihat keuangan maupun jaminan hasil. Nilai jackpot yang muncul di halaman adalah <strong>elemen tampilan yang bersifat ilustrasi</strong>. Angka tersebut tidak diambil dari sumber data langsung mana pun dan tidak boleh dijadikan dasar keputusan apa pun.</p>'
          . '<p><strong>Nama pihak ketiga.</strong> Nama penyedia permainan yang disebut di situs ini dipakai sebagai rujukan informasi semata. Kami tidak berafiliasi dengan mereka dan tidak menampilkan logo, artwork, maupun materi visual milik mereka.</p>'
          . '<p><strong>Bermain bertanggung jawab.</strong> Permainan berbasis peluang dapat menimbulkan kebiasaan yang merugikan. Tetapkan batas waktu dan batas pengeluaran sendiri, jangan menggunakan dana kebutuhan pokok, dan berhenti bila permainan mulai mengganggu kehidupan sehari-hari. Bila Anda merasa kehilangan kendali, hentikan aktivitas dan cari bantuan dari orang terdekat atau tenaga profesional.</p>';
        $descTetap = 'Disclaimer, batas usia 18+, dan imbauan bermain bertanggung jawab di ' . $namaSitus . '.';
    }
    $page = [
        'slug' => $slugMinta,
        'category' => 'tetap',
        'title' => $judulTetap . ' - ' . $namaSitus,
        'description' => $descTetap,
        'h1' => $judulTetap,
        'content_html' => $isiTetap,
        'lastmod' => v($site, 'generated_at', date('Y-m-d')),
    ];
}

/* ---------- META ---------- */
$judul = v($page, 'title', $namaSitus);
$deskripsi = v($page, 'description', '');
$h1 = v($page, 'h1', $judul);
$isiArtikel = (string)($page['content_html'] ?? '');
$slugKini = trim((string)($page['slug'] ?? ''), '/');

$urlKanonik = v($page, 'canonical_override', $isBeranda ? $baseUrl . '/' : $baseUrl . '/' . $slugKini);
if ($isBeranda) { $urlKanonik = rtrim($urlKanonik, '/') . '/'; }

$lastmodRaw = v($page, 'lastmod', date('Y-m-d'));
$tsLastmod = strtotime(substr($lastmodRaw, 0, 10)) ?: time();
$lastmodIso = date('Y-m-d', $tsLastmod) . 'T00:00:00+07:00';
$lastmodTgl = date('Y-m-d', $tsLastmod);
$bulanId = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$lastmodTampil = date('j', $tsLastmod) . ' ' . $bulanId[(int)date('n', $tsLastmod)] . ' ' . date('Y', $tsLastmod);

/* ---------- ANGKA ILUSTRASI (acak harian, tetap sepanjang hari) ---------- */
$benih = (int)date('Ymd') + (int)sprintf('%u', crc32($domainName)) % 100000;
mt_srand($benih);
$nilaiJackpot = mt_rand(7_800_000_000, 16_500_000_000);
mt_srand();
$jackpotTampil = number_format($nilaiJackpot, 0, ',', '.');

/* ---------- MENU IKON KATEGORI (hiasan, bukan tautan) ---------- */
$menuIkon = [
    ['Hot Games',   'M12 3c1.7 2.4 4.5 4 4.5 7a4.5 4.5 0 1 1-9 0c0-1.3.5-2.3 1.2-3.2.4 1 1.1 1.7 1.9 2 .3-2.4 1.4-4.4 1.4-5.8z'],
    ['Slot',        'M4 6h16v12H4zM9 9v6M15 9v6'],
    ['Live Casino', 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 4a4 4 0 1 1 0 8 4 4 0 0 1 0-8z'],
    ['Sportsbook',  'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 4 4 3-1.5 4.5h-5L8 10z'],
    ['Tembak Ikan', 'M3 12c3-4 7-6 11-6 3 0 5 1.5 7 6-2 4.5-4 6-7 6-4 0-8-2-11-6zm12-1h.01'],
    ['Togel',       'M5 5h6v6H5zm8 8h6v6h-6zM5 13h6v6H5zm8-8h6v6h-6z'],
    ['Poker',       'M7 4h7l4 4v12H7zm7 0v4h4M10 12h6M10 16h4'],
    ['Arcade',      'M4 9h16v9H4zm4-4h8v4H8zM8 13h2m4 0h2'],
];

/* ---------- DUA BARIS KARTU GAME (nama generik, ubin CSS) ---------- */
$barisGame = [
    ['Hot Games', [
        ['Gerbang Petir', 'a'], ['Manisan Musim', 'b'], ['Naga Zamrud', 'c'],
        ['Ratu Bintang', 'd'], ['Peti Kapten', 'e'], ['Panen Emas', 'f'],
    ]],
    ['Best Games', [
        ['Kipas Giok', 'c'], ['Roda Fortuna', 'a'], ['Lentera Merah', 'e'],
        ['Samudra Biru', 'b'], ['Menara Pasir', 'f'], ['Bulan Sabit', 'd'],
    ]],
];

/* ---------- PENYEDIA (chip teks, tanpa logo pihak mana pun) ---------- */
$penyedia = [
    'Slot' => ['Pragmatic Play', 'PG Soft', 'Habanero', 'Spadegaming', 'Microgaming', 'Joker', 'CQ9', 'Red Tiger'],
    'Live Casino' => ['Evolution', 'Sexy Gaming', 'Pretty Gaming', 'Dream Gaming', 'Big Gaming', 'Asia Gaming'],
    'Sportsbook' => ['SBOBET', 'Saba Sports', 'CMD Sports', 'UG Sports'],
    'Lainnya' => ['Tembak Ikan', 'Arcade', 'Togel', 'Poker'],
];

/* ---------- METODE PEMBAYARAN (kategori umum, tanpa nama bank) ---------- */
$metodeBayar = [
    ['Transfer Bank', 'Pemindahan dana antar rekening bank dalam negeri.'],
    ['E-Wallet', 'Aplikasi uang elektronik yang terdaftar di Indonesia.'],
    ['Pulsa', 'Pembayaran memakai pulsa operator seluler.'],
    ['QRIS', 'Pemindaian kode standar pembayaran nasional.'],
];

/* ---------- FOOTER EMPAT KOLOM (mengikuti susunan referensi) ---------- */
$kolomKaki = [
    ['Informasi', ['Tentang Kami', 'Ketentuan Layanan', 'Kebijakan Privasi', 'Bermain Bertanggung Jawab']],
    ['Produk', ['Sportsbook', 'Live Casino', 'Game Slot', 'Tembak Ikan']],
    ['Pusat Info', ['Pusat Bantuan', 'Cara Pembayaran', 'Hubungi Kami', 'Pertanyaan Umum']],
    ['Panduan', ['Istilah Permainan', 'Membaca RTP', 'Manajemen Modal', 'Keamanan Akun']],
];

/* ---------- FAQ ---------- */
$faq = [
    ['Apakah angka jackpot di halaman ini data resmi?',
     'Bukan. Nilai jackpot yang tampil di ' . $namaSitus . ' adalah elemen tampilan yang bersifat ilustrasi. Angka tersebut tidak diambil dari sumber data langsung mana pun dan tidak boleh dijadikan dasar keputusan apa pun.'],
    ['Apakah nama permainan yang ditampilkan produk resmi?',
     'Tidak. Seluruh nama dan tampilan permainan pada halaman ini adalah contoh generik yang kami susun sendiri, bukan produk resmi penyedia mana pun.'],
    ['Kenapa nama penyedia ditulis sebagai teks, bukan logo?',
     'Nama penyedia dipakai sebagai rujukan informasi. Logo dan materi visual mereka adalah milik masing-masing pemegang merek, sehingga tidak kami tampilkan di halaman ini.'],
    ['Apa arti RTP pada permainan gulungan?',
     'RTP (Return to Player) adalah persentase teoritis pengembalian dalam jangka sangat panjang. RTP 96% berarti secara teori 96 dari setiap 100 satuan taruhan kembali ke pemain dalam jutaan putaran. Angka ini tidak memprediksi hasil satu sesi.'],
    ['Berapa batas usia untuk mengakses situs ini?',
     'Situs ini hanya ditujukan bagi pembaca berusia 18 tahun ke atas. Pembaca di bawah usia tersebut diminta meninggalkan halaman ini.'],
    ['Bagaimana cara menghubungi layanan bantuan?',
     'Layanan bantuan tersedia melalui live chat, WhatsApp, dan Telegram yang tautannya ada di bagian bawah setiap halaman serta di halaman Kontak.'],
];

/* ---------- TAUTKAN NAMA SITUS PERTAMA KE BERANDA ---------- */
$potongan = preg_split('/(<[^>]*>)/', $isiArtikel, -1, PREG_SPLIT_DELIM_CAPTURE);
if (is_array($potongan) && $namaSitus !== '') {
    $sudah = false;
    $dalamTautan = false;
    foreach ($potongan as $i => $bagian) {
        if ($bagian === '') { continue; }
        if ($bagian[0] === '<') {
            $tl = strtolower($bagian);
            if (strncmp($tl, '<a', 2) === 0) { $dalamTautan = true; }
            elseif (strncmp($tl, '</a', 3) === 0) { $dalamTautan = false; }
            continue;
        }
        if ($sudah || $dalamTautan) { continue; }
        $panjangNama = strlen($namaSitus);
        $dari = 0;
        while (($pos = stripos($bagian, $namaSitus, $dari)) !== false) {
            $akhir = $pos + $panjangNama;
            $sebelum  = $pos > 0 ? $bagian[$pos - 1] : '';
            $sesudah  = isset($bagian[$akhir]) ? $bagian[$akhir] : '';
            $sesudah2 = isset($bagian[$akhir + 1]) ? $bagian[$akhir + 1] : '';
            if ($sebelum !== '' && (ctype_alnum($sebelum) || $sebelum === '.' || $sebelum === '-' || $sebelum === '@')) { $dari = $akhir; continue; }
            if ($sesudah !== '' && (ctype_alnum($sesudah) || $sesudah === '-')) { $dari = $akhir; continue; }
            if ($sesudah === '.' && $sesudah2 !== '' && ctype_alpha($sesudah2)) { $dari = $akhir; continue; }
            $asli = substr($bagian, $pos, $panjangNama);
            $potongan[$i] = substr($bagian, 0, $pos)
                . '<a href="/" class="a-tautan-diri">' . e($asli) . '</a>'
                . substr($bagian, $akhir);
            $sudah = true;
            break;
        }
    }
    $isiArtikel = implode('', $potongan);
}

/* ---------- SCHEMA ---------- */
$graph = [];
$graph[] = [
    '@type' => 'Article',
    '@id' => $urlKanonik . '#article',
    'headline' => $judul,
    'description' => $deskripsi,
    'inLanguage' => 'id-ID',
    'datePublished' => $lastmodIso,
    'dateModified' => $lastmodIso,
    'author' => ['@type' => 'Organization', 'name' => $namaSitus, 'url' => $baseUrl . '/'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $namaSitus,
        'url' => $baseUrl . '/',
        'logo' => ['@type' => 'ImageObject', 'url' => $baseUrl . $logo],
    ],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $urlKanonik],
];
$remah = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => $baseUrl . '/']];
if (!$isBeranda) {
    $remah[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $h1, 'item' => $urlKanonik];
}
$graph[] = ['@type' => 'BreadcrumbList', '@id' => $urlKanonik . '#remah', 'itemListElement' => $remah];
if ($isBeranda) {
    $graph[] = ['@type' => 'WebSite', '@id' => $baseUrl . '/#situs', 'name' => $namaSitus, 'url' => $baseUrl . '/', 'inLanguage' => 'id-ID'];
}
if ($isBeranda) {
    $graph[] = [
        '@type' => 'FAQPage',
        '@id' => $urlKanonik . '#faq',
        'mainEntity' => array_map(static function ($f) {
            return ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]];
        }, $faq),
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?></title>
<meta name="description" content="<?= e($deskripsi) ?>">
<link rel="canonical" href="<?= e($urlKanonik) ?>">
<?php if ($ampUrl !== '' && $ampUrl !== '#'): ?>
<link rel="amphtml" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="id-id" href="<?= e($ampUrl) ?>">
<link rel="alternate" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="id" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="en" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e($ampUrl) ?>">
<?php endif; ?>
<link rel="icon" href="<?= e($favicon) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="<?= e($namaSitus) ?>">
<meta property="og:title" content="<?= e($judul) ?>">
<meta property="og:description" content="<?= e($deskripsi) ?>">
<meta property="og:url" content="<?= e($urlKanonik) ?>">
<meta property="og:image" content="<?= e($baseUrl . $banner1) ?>">
<meta name="twitter:card" content="summary_large_image">
<style>
:root{
--a-bg:#0d0b24;--a-navy:#171342;--a-navy2:#251e6a;--a-navy3:#301d6e;
--a-panel:#141034;--a-panel2:#1c1748;--a-garis:#2e2864;
--a-hijau:#06a351;--a-hijau2:#04c463;--a-biru:#1da0cb;--a-biru2:#105a72;
--a-merah:#d42848;--a-emas:#e3a43b;
--a-teks:#fff;--a-redup:#9f9eb1;--a-abu:#e6e5e5;
--a-lebar:1180px;
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--a-bg);color:var(--a-teks);font:400 16px/1.7 Roboto,system-ui,-apple-system,"Segoe UI",Arial,sans-serif}
img{max-width:100%;height:auto;display:block}
a{color:var(--a-biru)}
h1,h2,h3{font-weight:700;line-height:1.3;margin:0}
.a-bungkus{max-width:var(--a-lebar);margin:0 auto;padding:0 12px}

/* bilah kontak atas */
.a-atas{background:var(--a-navy);border-bottom:1px solid var(--a-garis)}
.a-atas .a-bungkus{display:flex;align-items:center;gap:4px;flex-wrap:wrap;min-height:44px}
.a-kontak{display:flex;gap:2px;flex-wrap:wrap}
.a-kontak a{display:inline-flex;align-items:center;gap:6px;min-height:44px;padding:0 10px;color:var(--a-redup);text-decoration:none;font-size:13px;font-weight:500}
.a-kontak a:hover{color:var(--a-biru)}
.a-kontak svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.a-bahasa{margin-left:auto;display:inline-flex;align-items:center;gap:6px;color:var(--a-redup);font-size:13px;min-height:44px}
.a-bendera{width:20px;height:14px;border-radius:2px;overflow:hidden;display:block}

/* header logo + tombol */
.a-kepala{background:var(--a-navy2);position:sticky;top:0;z-index:70}
.a-kepala .a-bungkus{display:flex;align-items:center;gap:14px;flex-wrap:wrap;min-height:70px}
.a-logo{display:inline-flex;align-items:center;min-height:48px}
.a-logo img{max-height:48px;width:auto}
.a-aksi{margin-left:auto;display:flex;gap:8px}
.a-tbl{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 22px;border-radius:22px;font-weight:700;font-size:13px;text-decoration:none;text-transform:uppercase;letter-spacing:.04em}
.a-tbl-garis{border:1px solid var(--a-biru);color:var(--a-biru)}
.a-tbl-hijau{background:linear-gradient(180deg,var(--a-hijau2),var(--a-hijau));color:#fff}
.a-tbl:hover{filter:brightness(1.12)}

/* menu */
.a-menu{background:var(--a-navy3);border-top:1px solid var(--a-garis);border-bottom:3px solid var(--a-hijau)}
.a-menu .a-bungkus{display:flex;flex-wrap:wrap;gap:0}
.a-menu a{display:inline-flex;align-items:center;min-height:48px;padding:0 18px;color:var(--a-abu);text-decoration:none;font-size:14px;font-weight:600;text-transform:uppercase}
.a-menu a:hover,.a-menu a[aria-current="page"]{color:var(--a-emas)}

/* banner */
.a-sorot{background:var(--a-panel)}
.a-rel{display:flex;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none}
.a-rel::-webkit-scrollbar{display:none}
.a-rel>figure{margin:0;flex:0 0 100%;scroll-snap-align:center}
.a-titik{display:flex;justify-content:center;gap:8px;padding:10px 0;background:var(--a-panel)}
.a-titik button{width:11px;height:11px;padding:0;border-radius:50%;border:0;background:var(--a-garis);cursor:pointer}
.a-titik button[aria-current="true"]{background:var(--a-hijau2)}

/* marquee */
.a-kabar{background:var(--a-navy);border-top:1px solid var(--a-garis);border-bottom:1px solid var(--a-garis)}
.a-kabar .a-bungkus{display:flex;align-items:center;gap:12px;min-height:44px;overflow:hidden}
.a-kabar-ikon{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;background:var(--a-hijau);flex:0 0 auto}
.a-kabar-ikon svg{width:16px;height:16px;stroke:#fff;fill:none;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round}
.a-kabar-isi{flex:1;overflow:hidden;white-space:nowrap}
.a-kabar-isi span{display:inline-block;padding-left:100%;font-size:14px;color:var(--a-abu);animation:a-jalan 30s linear infinite}
@keyframes a-jalan{to{transform:translateX(-100%)}}

/* ikon kategori */
.a-ikon{margin:0;padding:14px 10px;list-style:none;display:grid;grid-template-columns:repeat(4,1fr);gap:8px;background:var(--a-panel2)}
@media(min-width:760px){.a-ikon{grid-template-columns:repeat(8,1fr)}}
.a-ikon li{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;min-height:70px;padding:8px 4px;border:1px solid var(--a-garis);border-radius:8px;background:var(--a-navy);font-size:11px;font-weight:700;text-transform:uppercase;color:var(--a-abu);text-align:center}
.a-ikon svg{width:24px;height:24px;stroke:var(--a-biru);fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}

/* jackpot */
.a-jack{background:linear-gradient(180deg,var(--a-navy3),var(--a-navy));border-top:1px solid var(--a-hijau);border-bottom:1px solid var(--a-hijau)}
.a-jack .a-bungkus{padding-top:18px;padding-bottom:18px;text-align:center}
.a-jack-label{font-size:12px;font-weight:700;letter-spacing:.24em;text-transform:uppercase;color:var(--a-emas);margin:0 0 6px}
.a-jack-nilai{font-size:clamp(24px,6vw,46px);font-weight:700;color:#fff;font-variant-numeric:tabular-nums;text-shadow:0 0 16px rgba(6,163,81,.55)}
.a-jack-ket{font-size:12px;color:var(--a-redup);margin:8px 0 0}
.a-tanda{display:inline-block;background:rgba(227,164,59,.16);border:1px solid var(--a-emas);color:var(--a-emas);border-radius:3px;font-size:10px;font-weight:700;letter-spacing:.08em;padding:2px 7px;margin-right:8px;text-transform:uppercase}

/* isi utama */
.a-utama{padding:18px 0 26px}
.a-judul-baris{display:flex;align-items:center;gap:10px;margin:0 0 10px;font-size:16px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#fff}
.a-judul-baris::before{content:"";width:4px;height:18px;background:var(--a-hijau2);display:block;border-radius:2px}
.a-kotak{background:var(--a-panel);border:1px solid var(--a-garis);border-radius:10px;margin-bottom:20px}

/* kartu game */
.a-game{margin:0;padding:10px;list-style:none;display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
@media(min-width:760px){.a-game{grid-template-columns:repeat(6,1fr)}}
.a-game li{border:1px solid var(--a-garis);border-radius:8px;overflow:hidden;background:var(--a-panel2)}
.a-ubin{aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;text-transform:uppercase}
.a-ubin-a{background:linear-gradient(135deg,#04c463,#04703a)}
.a-ubin-b{background:linear-gradient(135deg,#1da0cb,#105a72)}
.a-ubin-c{background:linear-gradient(135deg,#e3a43b,#8a5f11)}
.a-ubin-d{background:linear-gradient(135deg,#d42848,#71142a)}
.a-ubin-e{background:linear-gradient(135deg,#7b5bd6,#301d6e)}
.a-ubin-f{background:linear-gradient(135deg,#5d6b7a,#2c343c)}
.a-nama-game{display:block;padding:7px 6px;font-size:11px;font-weight:700;text-align:center;color:var(--a-abu);text-transform:uppercase;letter-spacing:.03em}
.a-catatan{margin:0;padding:0 12px 12px;font-size:12px;color:var(--a-redup);line-height:1.6}

/* penyedia */
.a-penyedia{padding:12px}
.a-penyedia h3{font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:var(--a-biru);margin:10px 0 8px}
.a-penyedia h3:first-child{margin-top:0}
.a-chip{display:flex;flex-wrap:wrap;gap:8px;margin:0;padding:0;list-style:none}
.a-chip li{border:1px solid var(--a-garis);border-radius:6px;background:var(--a-navy);padding:9px 14px;font-size:12px;font-weight:700;color:var(--a-abu);letter-spacing:.03em}

/* pembayaran */
.a-bayar{margin:0;padding:12px;display:grid;grid-template-columns:1fr;gap:12px}
@media(min-width:720px){.a-bayar{grid-template-columns:repeat(4,1fr)}}
.a-bayar fieldset{margin:0;border:1px solid var(--a-garis);border-radius:8px;padding:10px 14px 14px;background:var(--a-panel2)}
.a-bayar legend{font-size:12px;font-weight:700;padding:0 6px;color:var(--a-hijau2);text-transform:uppercase;letter-spacing:.06em}
.a-bayar p{margin:0;font-size:13px;color:var(--a-redup);line-height:1.6}

/* artikel */
.a-artikel{background:var(--a-panel);border:1px solid var(--a-garis);border-radius:10px;padding:18px 16px 22px;margin-bottom:20px}
.a-remah{font-size:13px;color:var(--a-redup);margin-bottom:8px}
.a-remah a{color:var(--a-redup)}
.a-artikel h1{font-size:clamp(23px,4.4vw,34px);margin:4px 0 10px;color:#fff}
.a-diperbarui{font-size:13px;color:var(--a-redup);margin:0 0 18px;padding-bottom:14px;border-bottom:1px solid var(--a-garis)}
.a-artikel h2{font-size:clamp(19px,3vw,25px);margin:28px 0 11px;color:var(--a-hijau2)}
.a-artikel h3{font-size:18px;margin:22px 0 8px;color:var(--a-abu)}
.a-artikel p{margin:0 0 16px;text-align:justify;hyphens:auto;color:#dcdbe6}
.a-artikel ul,.a-artikel ol{margin:0 0 16px;padding-left:22px}
.a-artikel li{margin-bottom:7px}
.a-artikel table{width:100%;border-collapse:collapse;margin:0 0 18px;font-size:14px;display:block;overflow-x:auto}
.a-artikel th,.a-artikel td{border:1px solid var(--a-garis);padding:9px 10px;text-align:left}
.a-artikel th{background:var(--a-panel2);color:var(--a-hijau2)}

/* faq */
.a-faq{padding:12px}
.a-faq details{border:1px solid var(--a-garis);border-radius:8px;margin-bottom:8px;background:var(--a-panel2)}
.a-faq summary{cursor:pointer;padding:13px 14px;font-weight:600;font-size:15px;min-height:48px;display:flex;align-items:center;color:#fff}
.a-faq p{margin:0;padding:0 14px 14px;color:var(--a-redup);font-size:15px}

/* footer */
.a-kaki{background:var(--a-navy);border-top:3px solid var(--a-hijau);padding:26px 0 96px}
@media(min-width:760px){.a-kaki{padding-bottom:26px}}
.a-kaki-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-bottom:22px}
@media(min-width:760px){.a-kaki-grid{grid-template-columns:repeat(4,1fr)}}
.a-kaki-grid h2{font-size:13px;text-transform:uppercase;letter-spacing:.07em;color:var(--a-emas);margin:0 0 10px}
.a-kaki-grid ul{margin:0;padding:0;list-style:none}
.a-kaki-grid li{font-size:13px;color:var(--a-redup);margin-bottom:7px}
.a-kaki-nav{display:flex;flex-wrap:wrap;gap:2px;margin-bottom:12px}
.a-kaki-nav a{display:inline-flex;align-items:center;min-height:48px;padding:0 12px;color:var(--a-abu);text-decoration:none;font-size:14px;font-weight:600}
.a-kaki-nav a:hover{color:var(--a-hijau2)}
.a-kaki p{font-size:13px;color:var(--a-redup);margin:0 0 10px;line-height:1.7}
.a-usia{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:2px solid var(--a-emas);border-radius:50%;color:var(--a-emas);font-weight:700;font-size:14px;margin-bottom:10px}
.a-hakcipta{border-top:1px solid var(--a-garis);margin-top:14px;padding-top:14px;font-size:13px;color:var(--a-redup);text-align:center}

/* dock mobile */
.a-dock{position:fixed;left:0;right:0;bottom:0;display:grid;grid-template-columns:repeat(4,1fr);background:var(--a-navy2);border-top:1px solid var(--a-garis);z-index:90}
@media(min-width:760px){.a-dock{display:none}}
.a-dock a{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;min-height:58px;color:var(--a-redup);text-decoration:none;font-size:11px;font-weight:600}
.a-dock a svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.a-dock a.a-aktif{color:var(--a-hijau2)}

/* melayang desktop */
.a-melayang{display:none}
@media(min-width:760px){
.a-melayang{display:flex;flex-direction:column;gap:8px;position:fixed;right:12px;bottom:18px;z-index:80}
.a-melayang a{display:inline-flex;align-items:center;justify-content:center;min-width:52px;min-height:52px;border-radius:50%;background:var(--a-navy2);border:1px solid var(--a-hijau);color:var(--a-hijau2);text-decoration:none;font-size:11px;font-weight:700;text-align:center;line-height:1.2;padding:4px}
}

@media(prefers-reduced-motion:reduce){
*{animation-duration:.001ms !important;animation-iteration-count:1 !important;transition-duration:.001ms !important}
.a-kabar-isi span{padding-left:0}
.a-rel{scroll-behavior:auto}
}
</style>
</head>
<body>

<div class="a-atas">
  <div class="a-bungkus">
    <div class="a-kontak">
      <a href="<?= e($urlWa) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-4.2-7.6L21 3l-1.4 4.2A8.9 8.9 0 0 1 21 12zM8 10c.4 2.6 3.4 5.6 6 6"/></svg>Whatsapp</a>
      <a href="<?= e($urlTele) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 4-3 16-6-5-3 4-1-6L3 10z"/></svg>Telegram</a>
      <a href="<?= e($urlLc) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/></svg>Livechat</a>
    </div>
    <span class="a-bahasa">
      <svg class="a-bendera" width="20" height="14" viewBox="0 0 20 14" aria-hidden="true"><rect width="20" height="7" fill="#ce1126"/><rect y="7" width="20" height="7" fill="#fff"/></svg>
      Indonesia
    </span>
  </div>
</div>

<header class="a-kepala">
  <div class="a-bungkus">
    <a class="a-logo" href="/" aria-label="Beranda <?= e($namaSitus) ?>">
      <img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" width="180" height="48" fetchpriority="high">
    </a>
    <div class="a-aksi">
      <a class="a-tbl a-tbl-garis" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Login</a>
      <a class="a-tbl a-tbl-hijau" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
    </div>
  </div>
</header>

<nav class="a-menu" aria-label="Navigasi utama">
  <div class="a-bungkus">
    <a href="/"<?= $isBeranda ? ' aria-current="page"' : '' ?>>Beranda</a>
    <?php foreach ($halamanTetap as $s => $t): ?>
    <a href="/<?= e($s) ?>"<?= ($isTetap && $slugMinta === $s) ? ' aria-current="page"' : '' ?>><?= e($t) ?></a>
    <?php endforeach; ?>
  </div>
</nav>

<div class="a-sorot">
  <div class="a-rel" id="a-rel">
    <figure><img src="<?= e($banner1) ?>" alt="Banner <?= e($namaSitus) ?>" width="1200" height="400" fetchpriority="high"></figure>
    <figure><img src="<?= e($banner2) ?>" alt="Informasi <?= e($namaSitus) ?>" width="1200" height="400" loading="lazy"></figure>
  </div>
  <div class="a-titik">
    <button type="button" data-ke="0" aria-current="true" aria-label="Banner 1"></button>
    <button type="button" data-ke="1" aria-current="false" aria-label="Banner 2"></button>
  </div>
</div>

<div class="a-kabar">
  <div class="a-bungkus">
    <span class="a-kabar-ikon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 11v2a1 1 0 0 0 1 1h3l5 4V6L7 10H4a1 1 0 0 0-1 1zM17 9a4 4 0 0 1 0 6"/></svg></span>
    <span class="a-kabar-isi"><span>Selamat datang di <?= e($namaSitus) ?>. Seluruh konten bersifat informasional dan ditujukan untuk pembaca 18 tahun ke atas. Angka jackpot yang tampil di halaman ini adalah ilustrasi tampilan, bukan data resmi.</span></span>
  </div>
</div>

<ul class="a-ikon" aria-label="Ragam permainan">
  <?php foreach ($menuIkon as $mi): ?>
  <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?= e($mi[1]) ?>"/></svg><?= e($mi[0]) ?></li>
  <?php endforeach; ?>
</ul>

<div class="a-jack">
  <div class="a-bungkus">
    <p class="a-jack-label">Progressive Jackpot</p>
    <div class="a-jack-nilai">IDR <span id="a-angka"><?= e($jackpotTampil) ?></span></div>
    <p class="a-jack-ket"><span class="a-tanda">Ilustrasi</span> Angka ini elemen tampilan, bukan data dari sumber mana pun.</p>
  </div>
</div>

<main class="a-utama">
  <div class="a-bungkus">

    <?php foreach ($barisGame as $br): ?>
    <section aria-label="<?= e($br[0]) ?>">
      <h2 class="a-judul-baris"><?= e($br[0]) ?></h2>
      <div class="a-kotak">
        <ul class="a-game">
          <?php foreach ($br[1] as $g): ?>
          <li>
            <div class="a-ubin a-ubin-<?= e($g[1]) ?>" aria-hidden="true"><?= e(hurufAwal($g[0])) ?></div>
            <span class="a-nama-game"><?= e($g[0]) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
    <?php endforeach; ?>
    <p class="a-catatan" style="padding:0 2px 16px">Nama dan tampilan permainan di atas adalah contoh generik yang kami susun sendiri, bukan produk resmi penyedia mana pun.</p>

    <section aria-label="Penyedia permainan">
      <h2 class="a-judul-baris">Penyedia Permainan</h2>
      <div class="a-kotak a-penyedia">
        <?php foreach ($penyedia as $kel => $daftar): ?>
        <h3><?= e($kel) ?></h3>
        <ul class="a-chip">
          <?php foreach ($daftar as $nm): ?>
          <li><?= e($nm) ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
        <p class="a-catatan" style="padding:12px 0 0">Nama penyedia dipakai sebagai rujukan informasi. Kami tidak berafiliasi dengan mereka dan tidak menampilkan logo maupun materi visual milik mereka.</p>
      </div>
    </section>

    <article class="a-artikel">
      <?php if (!$isBeranda): ?>
      <p class="a-remah"><a href="/">Beranda</a> &rsaquo; <?= e($h1) ?></p>
      <?php endif; ?>
      <h1><?= e($h1) ?></h1>
      <p class="a-diperbarui">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
      <?= $isiArtikel ?>
    </article>

    <section aria-label="Metode pembayaran">
      <h2 class="a-judul-baris">Metode Pembayaran</h2>
      <div class="a-kotak">
        <div class="a-bayar">
          <?php foreach ($metodeBayar as $mb): ?>
          <fieldset>
            <legend><?= e($mb[0]) ?></legend>
            <p><?= e($mb[1]) ?></p>
          </fieldset>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <?php if ($isBeranda): ?>
    <section aria-label="Pertanyaan yang sering diajukan">
      <h2 class="a-judul-baris">Pertanyaan Umum</h2>
      <div class="a-kotak a-faq">
        <?php foreach ($faq as $f): ?>
        <details>
          <summary><?= e($f[0]) ?></summary>
          <p><?= e($f[1]) ?></p>
        </details>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

  </div>
</main>

<footer class="a-kaki">
  <div class="a-bungkus">
    <div class="a-kaki-grid">
      <?php foreach ($kolomKaki as $kk): ?>
      <div>
        <h2><?= e($kk[0]) ?></h2>
        <ul>
          <?php foreach ($kk[1] as $item): ?>
          <li><?= e($item) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>

    <nav class="a-kaki-nav" aria-label="Navigasi bawah">
      <a href="/">Beranda</a>
      <?php foreach ($halamanTetap as $s => $t): ?>
      <a href="/<?= e($s) ?>"><?= e($t) ?></a>
      <?php endforeach; ?>
    </nav>

    <span class="a-usia">18+</span>
    <p><strong><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Seluruh isi bersifat informasional dan edukatif, bukan jaminan hasil maupun nasihat keuangan.</p>
    <p>Angka jackpot dan nama permainan yang tampil di situs ini adalah elemen tampilan yang bersifat ilustrasi. Permainan berbasis peluang dapat menimbulkan kebiasaan yang merugikan &mdash; tetapkan batas waktu dan pengeluaran, dan berhenti bila mulai mengganggu kehidupan sehari-hari.</p>
    <div class="a-hakcipta">&copy;<?= e(date('Y', $tsLastmod)) ?> <?= e($namaSitus) ?>. All rights reserved | 18+ &mdash; diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time></div>
  </div>
</footer>

<div class="a-melayang">
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener">Live<br>Chat</a>
  <a href="<?= e($urlWa) ?>" rel="nofollow noopener" aria-label="WhatsApp">WA</a>
</div>

<nav class="a-dock" aria-label="Navigasi cepat">
  <a href="/" class="a-aktif">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11 12 4l8 7v8a1 1 0 0 1-1 1h-4v-6h-6v6H5a1 1 0 0 1-1-1z"/></svg>Beranda</a>
  <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Daftar</a>
  <a href="<?= e($urlWa) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-4.2-7.6L21 3l-1.4 4.2A8.9 8.9 0 0 1 21 12zM8 10c.4 2.6 3.4 5.6 6 6"/></svg>Whatsapp</a>
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/></svg>Livechat</a>
</nav>

<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script>
(function(){
  var rel=document.getElementById('a-rel');
  if(rel){
    var titik=document.querySelectorAll('.a-titik button');
    var diam=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function ke(i){rel.scrollTo({left:rel.clientWidth*i,behavior:diam?'auto':'smooth'});}
    titik.forEach(function(b){b.addEventListener('click',function(){ke(+b.dataset.ke);});});
    rel.addEventListener('scroll',function(){
      var i=Math.round(rel.scrollLeft/rel.clientWidth);
      titik.forEach(function(b,n){b.setAttribute('aria-current',n===i?'true':'false');});
    },{passive:true});
    if(!diam){var n=0;setInterval(function(){n=(n+1)%titik.length;ke(n);},7000);}
  }
  var el=document.getElementById('a-angka');
  if(el&&!window.matchMedia('(prefers-reduced-motion: reduce)').matches){
    var v=parseInt(el.textContent.replace(/\D/g,''),10)||0;
    setInterval(function(){v+=Math.floor(Math.random()*90000)+1000;el.textContent=v.toLocaleString('id-ID');},4000);
  }
})();
</script>
</body>
</html>
