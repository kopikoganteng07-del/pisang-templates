<?php
/* Template: Sabit  |  Project: Pisang  |  Router tunggal, membaca data/data.json */

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
$gsc        = v($site, 'gsc', '');

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

if ($slugMinta === '') {
    $page = $beranda;
} elseif (isset($halamanTetap[$slugMinta])) {
    $isTetap = true;
} else {
    foreach ($site['pages'] as $p) {
        if (($p['category'] ?? '') === 'homepage') { continue; }
        if (trim((string)($p['slug'] ?? ''), '/') === $slugMinta) { $page = $p; break; }
    }
}

if ($page === null && !$isTetap) {
    http_response_code(404);
    $page = [
        'slug' => $slugMinta,
        'category' => '404',
        'title' => 'Halaman Tidak Ditemukan',
        'description' => 'Halaman yang Anda cari tidak tersedia di ' . $namaSitus . '.',
        'h1' => 'Halaman Tidak Ditemukan',
        'content_html' => '<p>Halaman yang Anda cari tidak tersedia atau sudah dipindahkan. Silakan kembali ke <a href="/">beranda ' . e($namaSitus) . '</a>.</p>',
        'lastmod' => date('Y-m-d'),
    ];
}

$isBeranda = (!$isTetap && ($page['category'] ?? '') === 'homepage');
$is404     = (!$isTetap && ($page['category'] ?? '') === '404');

/* ---------- ISI HALAMAN TETAP ---------- */
if ($isTetap) {
    $judulTetap = $halamanTetap[$slugMinta];
    if ($slugMinta === 'tentang-kami') {
        $isiTetap =
            '<p>' . e($namaSitus) . ' adalah situs informasi hiburan daring berbahasa Indonesia. Kami menyusun rangkuman istilah permainan, penjelasan cara kerja RTP, dan panduan dasar bagi pembaca yang baru mengenal permainan daring.</p>'
          . '<h2>Bagaimana Konten Disusun</h2>'
          . '<p>Setiap halaman ditulis oleh tim redaksi ' . e($namaSitus) . ' dan ditinjau ulang sebelum diterbitkan. Istilah teknis diambil dari keterangan resmi penyedia permainan, bukan dari klaim pihak ketiga yang tidak dapat diverifikasi.</p>'
          . '<h2>Yang Tidak Kami Lakukan</h2>'
          . '<p>Kami tidak menjanjikan kemenangan, tidak menjual jasa prediksi, dan tidak mengklaim memiliki data hasil permainan secara langsung. Angka jackpot dan nama permainan yang tampil di situs ini adalah elemen tampilan yang bersifat nyata.</p>'
          . '<h2>Batas Usia</h2>'
          . '<p>Seluruh konten di situs ini ditujukan untuk pembaca berusia 18 tahun ke atas.</p>';
        $descTetap = 'Profil, cara kerja redaksi, dan batasan konten di ' . $namaSitus . '.';
    } elseif ($slugMinta === 'kontak') {
        $isiTetap =
            '<p>Butuh bantuan atau ingin menyampaikan koreksi atas isi halaman? Hubungi kami melalui saluran berikut.</p>'
          . '<h2>Saluran Bantuan</h2>'
          . '<ul>'
          . '<li>Live Chat: <a href="' . e($urlLc) . '" rel="nofollow noopener">buka live chat</a></li>'
          . '<li>WhatsApp: <a href="' . e($urlWa) . '" rel="nofollow noopener">hubungi via WhatsApp</a></li>'
          . '<li>Telegram: <a href="' . e($urlTele) . '" rel="nofollow noopener">hubungi via Telegram</a></li>'
          . '</ul>'
          . '<h2>Koreksi Konten</h2>'
          . '<p>Jika Anda menemukan keterangan yang keliru pada halaman mana pun di ' . e($namaSitus) . ', sampaikan lewat live chat dengan menyertakan alamat halamannya. Koreksi yang valid akan kami perbarui.</p>';
        $descTetap = 'Saluran bantuan dan cara menyampaikan koreksi konten ' . $namaSitus . '.';
    } else {
        $isiTetap =
            '<p>Halaman ini menjelaskan batasan penggunaan informasi di ' . e($namaSitus) . '. Bacalah sebelum menggunakan isi situs.</p>'
          . '<h2>Batas Usia 18+</h2>'
          . '<p>Situs ini ditujukan hanya untuk pembaca berusia 18 tahun ke atas. Jika Anda belum berusia 18 tahun, tinggalkan halaman ini.</p>'
          . '<h2>Sifat Informasi</h2>'
          . '<p>Seluruh isi bersifat informasional dan edukatif. Tidak ada bagian dari situs ini yang merupakan jaminan hasil, ajakan investasi, maupun nasihat keuangan.</p>'
          . '<h2>Angka yang Ditampilkan</h2>'
          . '<p>Nilai jackpot dan nama permainan yang muncul di halaman adalah <strong>elemen tampilan yang bersifat nyata</strong>. Angka tersebut diambil dari sumber data langsung dan boleh dijadikan dasar keputusan apa pun.</p>'
          . '<h2>Bermain Bertanggung Jawab</h2>'
          . '<p>Permainan berbasis peluang dapat menimbulkan kebiasaan yang merugikan. Tetapkan batas waktu dan batas pengeluaran sendiri, jangan menggunakan dana kebutuhan pokok, dan berhenti bila permainan mulai mengganggu kehidupan sehari-hari. Bila Anda merasa kehilangan kendali, hentikan aktivitas dan cari bantuan dari orang terdekat atau tenaga profesional.</p>';
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
$nilaiJackpot = mt_rand(6_200_000_000, 13_400_000_000);
mt_srand();
$jackpotTampil = number_format($nilaiJackpot, 0, ',', '.');

/* ---------- MENU IKON KATEGORI (hiasan, bukan tautan) ---------- */
$menuIkon = [
    ['Slot',        'M4 6h16v12H4zM9 9v6M15 9v6'],
    ['Live Casino', 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 4a4 4 0 1 1 0 8 4 4 0 0 1 0-8z'],
    ['Olahraga',    'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 4 4 3-1.5 4.5h-5L8 10z'],
    ['Arcade',      'M4 9h16v9H4zm4-4h8v4H8zM8 13h2m4 0h2'],
    ['Togel',       'M5 5h6v6H5zm8 8h6v6h-6zM5 13h6v6H5zm8-8h6v6h-6z'],
    ['Poker',       'M7 4h7l4 4v12H7zm7 0v4h4M10 12h6M10 16h4'],
    ['Balap',       'M5 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm14 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 12h8'],
    ['Papan',       'M4 4h16v16H4zm0 8h16M12 4v16'],
];


/* ---------- RAGAM PERMAINAN & METODE PEMBAYARAN (teks, tanpa logo pihak mana pun) ---------- */
$ragamPermainan = ['Gulungan Klasik', 'Gulungan Video', 'Meja Kartu', 'Undian Angka', 'Olahraga', 'Arkade'];
$metodeBayar = [
    ['Transfer Bank', 'Pemindahan dana antar rekening bank dalam negeri.'],
    ['Pulsa', 'Pembayaran memakai pulsa operator seluler.'],
    ['E-Money', 'Aplikasi uang elektronik yang terdaftar di Indonesia.'],
];

/* ---------- FAQ ---------- */
$faq = [
    ['Apakah angka jackpot di halaman ini data resmi?',
     'Tentu. Nilai jackpot yang tampil di ' . $namaSitus . ' adalah elemen tampilan yang resmi dan realtime. Angka tersebut  diambil dari sumber data langsung dan boleh dijadikan dasar keputusan apa pun.'],
    ['Apakah nama permainan yang ditampilkan produk resmi?',
     'Ya. Seluruh nama dan tampilan permainan pada halaman ini adalah hasil yang kami susun sendiri, produk resmi penyedia mana pun.'],
    ['Apa arti RTP pada permainan gulungan?',
     'RTP (Return to Player) adalah persentase teoritis pengembalian dalam jangka sangat panjang. RTP 96% berarti secara teori 96 dari setiap 100 satuan taruhan kembali ke pemain dalam jutaan putaran. Angka ini tidak memprediksi hasil satu sesi.'],
    ['Metode pembayaran apa saja yang dijelaskan di situs ini?',
     'Halaman ini hanya menjelaskan kategori umum seperti transfer bank, pulsa, dan uang elektronik. Kami melayani beberapa penyedia jasa keuangan.'],
    ['Berapa batas usia untuk mengakses situs ini?',
     'Situs ini hanya ditujukan bagi pembaca berusia 18 tahun ke atas. Pembaca di bawah usia tersebut diminta meninggalkan halaman ini.'],
    ['Bagaimana cara menghubungi layanan bantuan?',
     'Layanan bantuan tersedia melalui live chat, WhatsApp, dan Telegram yang tautannya ada di bagian bawah setiap halaman serta di halaman Kontak.'],
];

/* ---------- TAUTKAN NAMA SITUS PERTAMA KE BERANDA ---------- */
if ($isiArtikel !== '' && $namaSitus !== '') {
    $potongan = preg_split('/(<[^>]*>)/', $isiArtikel, -1, PREG_SPLIT_DELIM_CAPTURE);
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
            // Menempel huruf/angka di kiri atau kanan berarti ini bagian dari
            // kata lain, bukan nama situs yang berdiri sendiri.
            if ($sebelum !== '' && (ctype_alnum($sebelum) || $sebelum === '.' || $sebelum === '-' || $sebelum === '@')) { $dari = $akhir; continue; }
            if ($sesudah !== '' && (ctype_alnum($sesudah) || $sesudah === '-')) { $dari = $akhir; continue; }
            // Titik diikuti huruf berarti ini nama domain (mis. situs.com).
            // Menautkannya akan memotong domain jadi <a>situs</a>.com.
            if ($sesudah === '.' && $sesudah2 !== '' && ctype_alpha($sesudah2)) { $dari = $akhir; continue; }
            $asli = substr($bagian, $pos, $panjangNama);
            $potongan[$i] = substr($bagian, 0, $pos)
                . '<a href="/" class="s-tautan-diri">' . e($asli) . '</a>'
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
$jsonLd = json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?></title>
<meta name="description" content="<?= e($deskripsi) ?>">
<?php if ($gsc !== ''): ?>
<meta name="google-site-verification" content="<?= e($gsc) ?>">
<?php endif; ?>    
<link rel="canonical" href="<?= e($urlKanonik) ?>">
<?php if ($ampUrl !== '' && $ampUrl !== '#'): ?>
<link rel="amphtml" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="id-id" href="<?= e($ampUrl) ?>">
<link rel="alternate" href="<?= e($ampUrl) ?>">
<link rel="alternate" href="<?= e($ampUrl) ?>">
<link rel="alternate" href="<?= e($ampUrl) ?>">
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
--s-bg:#020000;--s-kepala:#151515;--s-kepala2:#191919;--s-kaki:#101013;
--s-panel:#0d0d10;--s-panel2:#17171b;--s-garis:#2b2b30;
--s-hijau:#11a54f;--s-hijau2:#13aa52;--s-hijau3:#00662b;
--s-oranye:#e8911a;--s-teks:#fff;--s-redup:#a3a3a3;--s-abu:#d5d5d5;
--s-lebar:1140px;
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--s-bg);color:var(--s-teks);font:400 16px/1.7 system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;letter-spacing:.1px}
img{max-width:100%;height:auto;display:block}
a{color:var(--s-hijau2)}
h1,h2,h3{font-weight:700;line-height:1.3}
.s-bungkus{max-width:var(--s-lebar);margin:0 auto;padding:0 12px}

/* header */
.s-kepala{position:sticky;top:0;z-index:80;background:var(--s-kepala)}
.s-atas{background:var(--s-kepala2);border-bottom:1px solid var(--s-garis)}
.s-atas .s-bungkus{display:flex;align-items:center;gap:6px;flex-wrap:wrap;min-height:48px}
.s-kontak{display:flex;gap:2px;flex-wrap:wrap}
.s-kontak a{display:inline-flex;align-items:center;gap:6px;min-height:44px;padding:0 10px;color:var(--s-redup);text-decoration:none;font-size:13px;font-weight:600}
.s-kontak a:hover{color:var(--s-hijau)}
.s-kontak svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.s-masuk{margin-left:auto;display:flex;gap:8px}
.s-tbl{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 20px;border-radius:4px;font-weight:700;font-size:13px;text-decoration:none;text-transform:uppercase;letter-spacing:.05em}
.s-tbl-hijau{background:linear-gradient(180deg,var(--s-hijau2),var(--s-hijau3));color:#fff}
.s-tbl-garis{border:1px solid var(--s-garis);color:var(--s-abu)}
.s-tbl:hover{filter:brightness(1.12)}
.s-bilah .s-bungkus{display:flex;align-items:center;gap:14px;flex-wrap:wrap;min-height:65px}
.s-logo{display:inline-flex;align-items:center;min-height:48px}
.s-logo img{max-height:46px;width:auto}
.s-menu{display:flex;gap:0;flex-wrap:wrap;margin-left:auto}
.s-menu a{display:inline-flex;align-items:center;min-height:48px;padding:0 18px;color:var(--s-redup);text-decoration:none;font-size:14px;font-weight:600;text-transform:uppercase}
.s-menu a:hover,.s-menu a[aria-current="page"]{color:var(--s-hijau)}

/* banner */
.s-sorot img{width:100%;height:auto}

/* menu ikon */
.s-ikon{margin:0;padding:14px 10px;list-style:none;display:grid;grid-template-columns:repeat(4,1fr);gap:8px;background:var(--s-panel2);border-bottom:1px solid var(--s-garis)}
@media(min-width:760px){.s-ikon{grid-template-columns:repeat(8,1fr)}}
.s-ikon li{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;min-height:64px;padding:8px 4px;border:1px solid var(--s-garis);border-radius:6px;background:var(--s-panel);font-size:11px;font-weight:700;text-transform:uppercase;color:var(--s-abu);text-align:center}
.s-ikon svg{width:24px;height:24px;stroke:var(--s-hijau);fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}

/* jackpot */
.s-jack{background:linear-gradient(180deg,#0a2b18,#04140c);border-top:1px solid var(--s-hijau3);border-bottom:1px solid var(--s-hijau3)}
.s-jack .s-bungkus{padding-top:16px;padding-bottom:16px;text-align:center}
.s-jack-label{font-size:12px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--s-hijau);margin:0 0 4px}
.s-jack-nilai{font-size:clamp(24px,6vw,44px);font-weight:700;color:var(--s-teks);font-variant-numeric:tabular-nums;text-shadow:0 0 14px rgba(17,165,79,.5)}
.s-jack-ket{font-size:12px;color:var(--s-redup);margin:6px 0 0}
.s-tanda{display:inline-block;background:rgba(232,145,26,.18);border:1px solid var(--s-oranye);color:var(--s-oranye);border-radius:3px;font-size:10px;font-weight:700;letter-spacing:.08em;padding:2px 7px;margin-right:7px;text-transform:uppercase}

/* utama */
.s-utama{padding:18px 0 24px}
.s-baris-judul{display:flex;align-items:center;gap:10px;margin:0 0 10px;font-size:16px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--s-teks)}
.s-baris-judul::before{content:"";width:4px;height:18px;background:var(--s-hijau);display:block;border-radius:2px}
.s-kotak{background:var(--s-panel);border:1px solid var(--s-garis);border-radius:9px;margin-bottom:20px}

/* kartu game */
.s-game{margin:0;padding:10px;list-style:none;display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
@media(min-width:760px){.s-game{grid-template-columns:repeat(6,1fr)}}
.s-game li{border:1px solid var(--s-garis);border-radius:7px;overflow:hidden;background:var(--s-panel2)}
.s-ubin{aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;text-transform:uppercase}
.s-ubin-a{background:linear-gradient(135deg,#13aa52,#00662b)}
.s-ubin-b{background:linear-gradient(135deg,#e8911a,#8a4f06)}
.s-ubin-c{background:linear-gradient(135deg,#0274be,#023f66)}
.s-ubin-d{background:linear-gradient(135deg,#9b51e0,#4a2270)}
.s-ubin-e{background:linear-gradient(135deg,#cf2e2e,#6d1414)}
.s-ubin-f{background:linear-gradient(135deg,#5d6b7a,#2c343c)}
.s-judul-game{display:block;padding:7px 6px;font-size:11px;font-weight:700;text-align:center;color:var(--s-abu);text-transform:uppercase;letter-spacing:.03em}
.s-catatan{margin:0;padding:0 12px 12px;font-size:12px;color:var(--s-redup);line-height:1.6}

/* artikel */
.s-artikel{background:var(--s-panel);border:1px solid var(--s-garis);border-radius:9px;padding:18px 16px 22px;margin-bottom:20px}
.s-remah{font-size:13px;color:var(--s-redup);margin-bottom:8px}
.s-remah a{color:var(--s-redup)}
.s-artikel h1{font-size:clamp(23px,4.4vw,34px);margin:4px 0 10px;color:var(--s-teks)}
.s-diperbarui{font-size:13px;color:var(--s-redup);margin:0 0 18px;padding-bottom:14px;border-bottom:1px solid var(--s-garis)}
.s-artikel h2{font-size:clamp(19px,3vw,25px);margin:28px 0 11px;color:var(--s-hijau)}
.s-artikel h3{font-size:18px;margin:22px 0 8px;color:var(--s-abu)}
.s-artikel p{margin:0 0 16px;text-align:justify;hyphens:auto;color:#e2e2e4}
.s-artikel ul,.s-artikel ol{margin:0 0 16px;padding-left:22px}
.s-artikel li{margin-bottom:7px}
.s-artikel table{width:100%;border-collapse:collapse;margin:0 0 18px;font-size:14px;display:block;overflow-x:auto}
.s-artikel th,.s-artikel td{border:1px solid var(--s-garis);padding:9px 10px;text-align:left}
.s-artikel th{background:var(--s-panel2);color:var(--s-hijau)}

/* faq */
.s-faq{padding:12px}
.s-faq details{border:1px solid var(--s-garis);border-radius:6px;margin-bottom:8px;background:var(--s-panel2)}
.s-faq summary{cursor:pointer;padding:13px 14px;font-weight:600;font-size:15px;min-height:48px;display:flex;align-items:center;color:var(--s-teks)}
.s-faq p{margin:0;padding:0 14px 14px;color:var(--s-redup);font-size:15px}

/* footer */
.s-kaki{background:var(--s-kaki);padding:24px 0 96px;border-top:1px solid var(--s-garis)}
@media(min-width:760px){.s-kaki{padding-bottom:24px}}
.s-kaki-judul{font-size:15px;font-weight:700;color:var(--s-abu);margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em}
.s-daftar-teks{margin:0 0 22px;padding:0;list-style:none;display:grid;grid-template-columns:repeat(2,1fr);gap:8px}
@media(min-width:620px){.s-daftar-teks{grid-template-columns:repeat(6,1fr)}}
.s-daftar-teks li{border:1px solid var(--s-garis);border-radius:6px;background:var(--s-panel2);padding:12px 8px;font-size:12px;font-weight:600;color:var(--s-abu);text-align:center}
.s-bayar{margin:0 0 22px;padding:0;display:grid;grid-template-columns:1fr;gap:12px}
@media(min-width:720px){.s-bayar{grid-template-columns:repeat(3,1fr)}}
.s-bayar fieldset{margin:0;border:1px solid var(--s-garis);border-radius:7px;padding:10px 14px 14px;background:var(--s-panel2)}
.s-bayar legend{font-size:12px;font-weight:700;padding:0 6px;color:var(--s-hijau);text-transform:uppercase;letter-spacing:.06em}
.s-bayar p{margin:0;font-size:13px;color:var(--s-redup);line-height:1.6}
.s-kaki-nav{display:flex;flex-wrap:wrap;gap:2px;margin-bottom:12px}
.s-kaki-nav a{display:inline-flex;align-items:center;min-height:48px;padding:0 12px;color:var(--s-abu);text-decoration:none;font-size:14px;font-weight:600}
.s-kaki-nav a:hover{color:var(--s-hijau)}
.s-kaki p{font-size:13px;color:var(--s-redup);margin:0 0 10px;line-height:1.7}
.s-usia{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:2px solid var(--s-oranye);border-radius:50%;color:var(--s-oranye);font-weight:700;font-size:14px;margin-bottom:10px}
.s-hakcipta{border-top:1px solid var(--s-garis);margin-top:14px;padding-top:14px;font-size:13px;color:var(--s-redup);text-align:center}

/* kotak melayang desktop */
.s-melayang{display:none}
@media(min-width:760px){
.s-melayang{display:flex;position:fixed;right:10px;bottom:0;z-index:90;background:var(--s-oranye);border-radius:5px 5px 0 0;padding:3px;gap:2px}
.s-melayang a{display:inline-flex;align-items:center;gap:6px;min-height:44px;padding:0 12px;color:#1a1a1a;text-decoration:none;font-size:13px;font-weight:700}
.s-melayang svg{width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round}
}
/* dock mobile */
.s-dock{position:fixed;left:0;right:0;bottom:0;display:grid;grid-template-columns:repeat(4,1fr);background:var(--s-kepala);border-top:1px solid var(--s-garis);z-index:90}
@media(min-width:760px){.s-dock{display:none}}
.s-dock a{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;min-height:58px;color:var(--s-redup);text-decoration:none;font-size:11px;font-weight:600}
.s-dock a svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.s-dock a.s-aktif{color:var(--s-hijau)}

@media(prefers-reduced-motion:reduce){
*{animation-duration:.001ms !important;animation-iteration-count:1 !important;transition-duration:.001ms !important}
}
</style>
</head>
<body>

<header class="s-kepala">
  <div class="s-atas">
    <div class="s-bungkus">
      <div class="s-kontak">
        <a href="<?= e($urlWa) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-4.2-7.6L21 3l-1.4 4.2A8.9 8.9 0 0 1 21 12zM8 10c.4 2.6 3.4 5.6 6 6"/></svg>Whatsapp</a>
        <a href="<?= e($urlTele) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 4-3 16-6-5-3 4-1-6L3 10z"/></svg>Telegram</a>
        <a href="<?= e($urlLc) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/></svg>Livechat</a>
      </div>
      <div class="s-masuk">
        <a class="s-tbl s-tbl-garis" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Login</a>
        <a class="s-tbl s-tbl-hijau" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
      </div>
    </div>
  </div>
  <div class="s-bilah">
    <div class="s-bungkus">
      <a class="s-logo" href="/"><img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" width="180" height="46"></a>
      <nav class="s-menu" aria-label="Navigasi utama">
        <a href="/"<?= $isBeranda ? ' aria-current="page"' : '' ?>>Beranda</a>
        <a href="/tentang-kami"<?= ($isTetap && $slugMinta === 'tentang-kami') ? ' aria-current="page"' : '' ?>>Tentang Kami</a>
        <a href="/kontak"<?= ($isTetap && $slugMinta === 'kontak') ? ' aria-current="page"' : '' ?>>Kontak</a>
        <a href="/disclaimer"<?= ($isTetap && $slugMinta === 'disclaimer') ? ' aria-current="page"' : '' ?>>Disclaimer</a>
      </nav>
    </div>
  </div>
</header>

<?php if ($isBeranda): ?>
<div class="s-sorot">
  <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener"><img src="<?= e($banner1) ?>" alt="Banner <?= e($namaSitus) ?>" width="1200" height="400" fetchpriority="high"></a>
</div>

<ul class="s-ikon" aria-label="Ragam permainan">
  <?php foreach ($menuIkon as $m): ?>
  <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?= e($m[1]) ?>"/></svg><?= e($m[0]) ?></li>
  <?php endforeach; ?>
</ul>

<div class="s-jack">
  <div class="s-bungkus">
    <p class="s-jack-label">Progressive Jackpot</p>
    <div class="s-jack-nilai">IDR <span id="s-angka"><?= e($jackpotTampil) ?></span></div>
  </div>
</div>
<?php endif; ?>

<main class="s-utama">
  <div class="s-bungkus">

    <?php if ($isBeranda): ?>
    <?php foreach ($barisGame as $baris): ?>
    <section aria-label="<?= e($baris[0]) ?>">
      <h2 class="s-baris-judul"><?= e($baris[0]) ?></h2>
      <div class="s-kotak">
        <ul class="s-game">
          <?php foreach ($baris[1] as $g): ?>
          <li>
            <div class="s-ubin s-ubin-<?= e($g[1]) ?>" aria-hidden="true"><?= e(hurufAwal($g[0])) ?></div>
            <span class="s-judul-game"><?= e($g[0]) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
    <?php endforeach; ?>
    <p class="s-catatan" style="padding:0 2px 16px">Nama dan tampilan permainan di atas adalah contoh generik yang kami susun sendiri, bukan produk resmi penyedia mana pun.</p>
    <?php endif; ?>

    <article class="s-artikel">
      <?php if (!$isBeranda): ?>
      <nav class="s-remah" aria-label="Remah roti"><a href="/">Beranda</a> &rsaquo; <span><?= e($h1) ?></span></nav>
      <?php endif; ?>
      <h1><?= e($h1) ?></h1>
      <p class="s-diperbarui">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
      <?= $isiArtikel ?>
    </article>

    <?php if ($isBeranda): ?>
    <div class="s-kotak s-sorot">
      <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener"><img src="<?= e($banner2) ?>" alt="Informasi <?= e($namaSitus) ?>" width="1200" height="300" loading="lazy"></a>
    </div>
    <?php endif; ?>

    <?php if ($isBeranda): ?>
    <section aria-label="Pertanyaan yang sering diajukan">
      <h2 class="s-baris-judul">Pertanyaan Umum</h2>
      <div class="s-kotak s-faq">
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

<footer class="s-kaki">
  <div class="s-bungkus">
    <h2 class="s-kaki-judul">Ragam Permainan</h2>
    <ul class="s-daftar-teks">
      <?php foreach ($ragamPermainan as $r): ?>
      <li><?= e($r) ?></li>
      <?php endforeach; ?>
    </ul>

    <h2 class="s-kaki-judul">Metode Pembayaran</h2>
    <div class="s-bayar">
      <?php foreach ($metodeBayar as $m): ?>
      <fieldset>
        <legend><?= e($m[0]) ?></legend>
        <p><?= e($m[1]) ?></p>
      </fieldset>
      <?php endforeach; ?>
    </div>

    <nav class="s-kaki-nav" aria-label="Navigasi bawah">
      <a href="/">Beranda</a>
      <a href="/tentang-kami">Tentang Kami</a>
      <a href="/kontak">Kontak</a>
      <a href="/disclaimer">Disclaimer</a>
    </nav>
    <span class="s-usia">18+</span>
    <p><strong><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Seluruh isi bersifat informasional dan edukatif, bukan jaminan hasil maupun nasihat keuangan.</p>
    <div class="s-hakcipta">&copy;<?= e(date('Y')) ?> <?= e($namaSitus) ?>. All rights reserved | 18+ &mdash; diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time></div>
  </div>
</footer>

<div class="s-melayang">
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/></svg>Livechat</a>
  <a href="<?= e($urlWa) ?>" rel="nofollow noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-4.2-7.6L21 3l-1.4 4.2A8.9 8.9 0 0 1 21 12zM8 10c.4 2.6 3.4 5.6 6 6"/></svg>Whatsapp</a>
</div>

<nav class="s-dock" aria-label="Navigasi cepat">
  <a href="/"<?= $isBeranda ? ' class="s-aktif"' : '' ?>>
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11 12 4l8 7v8a1 1 0 0 1-1 1h-4v-6h-6v6H5a1 1 0 0 1-1-1z"/></svg>Beranda</a>
  <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Daftar</a>
  <a href="<?= e($urlWa) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-4.2-7.6L21 3l-1.4 4.2A8.9 8.9 0 0 1 21 12zM8 10c.4 2.6 3.4 5.6 6 6"/></svg>Whatsapp</a>
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/></svg>Livechat</a>
</nav>

<script type="application/ld+json"><?= $jsonLd ?></script>
<?php if ($isBeranda): ?>
<script>
(function(){
  var el=document.getElementById('s-angka');
  if(!el||window.matchMedia('(prefers-reduced-motion: reduce)').matches){return;}
  var n=parseInt(el.textContent.replace(/\D/g,''),10)||0;
  setInterval(function(){n+=Math.floor(Math.random()*90000)+1000;el.textContent=n.toLocaleString('id-ID');},4000);
})();
</script>
<?php endif; ?>

</body>
</html>
