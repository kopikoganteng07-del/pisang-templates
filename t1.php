<?php
/* Template: Panen  |  Project: Pisang  |  Router tunggal, membaca data/data.json */

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
$scheme     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'https';
$baseUrl    = $scheme . '://' . $domainName;

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

if ($slugMinta === '' ) {
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
$angkaUndian = str_pad((string)mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
$nilaiJackpot = mt_rand(8_500_000_000, 14_900_000_000);
$namaContoh = ['bud','rin','dwi','hen','sar','tio','ayu','faj','nur','gil','wan','lis'];
$gameContoh = ['Gulungan Emas','Naga Merah','Panen Raya','Kota Pasir','Bintang Timur','Rimba Batu'];
$daftarPemenang = [];
for ($i = 0; $i < 5; $i++) {
    $daftarPemenang[] = [
        'nama' => $namaContoh[mt_rand(0, count($namaContoh) - 1)] . str_repeat('*', 4) . mt_rand(1, 9),
        'nilai' => number_format(mt_rand(120_000, 4_800_000), 0, ',', '.'),
        'game' => $gameContoh[mt_rand(0, count($gameContoh) - 1)],
    ];
}
mt_srand();

$jackpotTampil = number_format($nilaiJackpot, 0, ',', '.');

/* ---------- KATEGORI HIASAN (bukan tautan) ---------- */
$kategoriHias = [
    ['Hot Games', 'M12 3c1.7 2.4 4.5 4 4.5 7a4.5 4.5 0 1 1-9 0c0-1.3.5-2.3 1.2-3.2.4 1 1.1 1.7 1.9 2 .3-2.4 1.4-4.4 1.4-5.8z'],
    ['Slots',     'M4 6h16v12H4zM8 9v6M12 9v6M16 9v6'],
    ['Live Casino', 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 4a4 4 0 1 1 0 8 4 4 0 0 1 0-8z'],
    ['Race',      'M5 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm14 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 12h8'],
    ['Togel',     'M5 5h6v6H5zm8 8h6v6h-6zM5 13h6v6H5zm8-8h6v6h-6z'],
    ['Olahraga',  'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 4 4 3-1.5 4.5h-5L8 10z'],
    ['Arcade',    'M4 9h16v9H4zm4-4h8v4H8zM8 13h2m4 0h2'],
    ['Poker',     'M7 4h7l4 4v12H7zm7 0v4h4'],
];

/* ---------- KARTU GAME ILUSTRASI ---------- */
$kartuGame = [
    ['Gulungan Emas', 'Gulungan klasik tiga baris', 'a'],
    ['Naga Merah', 'Tema oriental lima gulungan', 'b'],
    ['Panen Raya', 'Tema pertanian dengan pengganda', 'c'],
    ['Kota Pasir', 'Petualangan gurun', 'd'],
    ['Bintang Timur', 'Tema langit malam', 'e'],
    ['Rimba Batu', 'Tema hutan purba', 'f'],
    ['Ombak Biru', 'Tema bawah laut', 'g'],
    ['Roda Pesta', 'Roda putar sederhana', 'h'],
];

/* ---------- FAQ ---------- */
$faq = [
    ['Apakah angka jackpot dan hasil undian di halaman ini data resmi?',
     'Bukan. Nilai jackpot dan angka undian yang tampil di ' . $namaSitus . ' adalah elemen tampilan yang bersifat ilustrasi. Angka tersebut tidak diambil dari sumber data langsung mana pun dan tidak boleh dijadikan dasar keputusan apa pun.'],
    ['Apakah daftar pemenang yang ditampilkan nyata?',
     'Tidak. Daftar pemenang beserta nominalnya adalah contoh tampilan, bukan catatan transaksi nyata. Nama sengaja disamarkan karena memang bukan data pengguna sungguhan.'],
    ['Apa arti RTP pada permainan gulungan?',
     'RTP (Return to Player) adalah persentase teoritis pengembalian dalam jangka sangat panjang. RTP 96% berarti secara teori 96 dari setiap 100 satuan taruhan kembali ke pemain dalam jutaan putaran. Angka ini tidak memprediksi hasil satu sesi.'],
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
                . '<a href="/" class="p-tautan-diri">' . e($asli) . '</a>'
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
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@500;700&display=swap">
<style>
:root{
--p-bg:#1c1c1e;--p-bg2:#262629;--p-bg3:#2f2f33;--p-line:#3d3d42;
--p-emas:#f2a43a;--p-emas2:#ffcf7a;--p-emas-gelap:#c97d15;
--p-teks:#ececee;--p-redup:#b3b3b8;--p-putih:#fff;
--p-radius:8px;--p-lebar:1180px;
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--p-bg);color:var(--p-teks);font:400 16px/1.75 system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;padding-bottom:0}
img{max-width:100%;height:auto;display:block}
a{color:var(--p-emas2)}
h1,h2,h3,.p-display{font-family:"Chakra Petch",system-ui,sans-serif;letter-spacing:.01em}
.p-wrap{max-width:var(--p-lebar);margin:0 auto;padding:0 14px}

/* bilah utilitas */
.p-utilitas{background:var(--p-bg2);border-bottom:1px solid var(--p-line)}
.p-utilitas .p-wrap{display:flex;align-items:center;gap:10px;min-height:52px;flex-wrap:wrap}
.p-ikonbtn{display:inline-flex;align-items:center;justify-content:center;min-width:48px;min-height:48px;color:var(--p-redup);text-decoration:none}
.p-ikonbtn:hover{color:var(--p-emas2)}
.p-tele{display:inline-flex;align-items:center;gap:7px;min-height:48px;padding:0 14px;border-radius:6px;background:#2b6ea8;color:#fff;text-decoration:none;font-weight:600;font-size:14px}
.p-bahasa{display:inline-flex;align-items:center;gap:6px;color:var(--p-redup);font-size:14px;min-height:48px}
.p-bendera{width:22px;height:15px;border-radius:2px;overflow:hidden;display:block}
.p-aksi{margin-left:auto;display:flex;gap:8px}
.p-btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 26px;border-radius:24px;font-weight:700;text-decoration:none;font-size:14px;letter-spacing:.04em}
.p-btn-abu{background:var(--p-bg3);color:var(--p-teks);border:1px solid var(--p-line)}
.p-btn-emas{background:linear-gradient(180deg,var(--p-emas2),var(--p-emas-gelap));color:#231703}
.p-btn:hover{filter:brightness(1.08)}

/* header */
.p-kepala{background:var(--p-bg);border-bottom:3px solid var(--p-emas)}
.p-kepala .p-wrap{display:flex;align-items:center;gap:18px;min-height:78px;flex-wrap:wrap}
.p-logo img{max-height:56px;width:auto}
.p-nav{display:flex;gap:4px;flex-wrap:wrap}
.p-nav a{display:inline-flex;align-items:center;min-height:48px;padding:0 14px;color:var(--p-teks);text-decoration:none;font-weight:600;font-size:14px;border-radius:6px}
.p-nav a:hover{background:var(--p-bg3);color:var(--p-emas2)}
.p-nav a[aria-current="page"]{color:var(--p-emas2)}

/* banner */
.p-banner{position:relative;background:var(--p-bg2)}
.p-rel{display:flex;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none}
.p-rel::-webkit-scrollbar{display:none}
.p-rel>figure{margin:0;flex:0 0 100%;scroll-snap-align:center}
.p-rel img{width:100%;height:auto}
.p-titik{display:flex;justify-content:center;gap:8px;padding:10px 0;background:var(--p-bg2)}
.p-titik button{width:12px;height:12px;padding:0;border-radius:50%;border:0;background:var(--p-line);cursor:pointer}
.p-titik button[aria-current="true"]{background:var(--p-emas)}

/* marquee */
.p-kabar{background:var(--p-emas);color:#231703}
.p-kabar .p-wrap{display:flex;align-items:center;gap:12px;min-height:46px;overflow:hidden}
.p-kabar-label{font-weight:700;font-size:13px;white-space:nowrap}
.p-kabar-isi{flex:1;overflow:hidden;white-space:nowrap}
.p-kabar-isi span{display:inline-block;padding-left:100%;font-size:14px;animation:p-jalan 26s linear infinite}
@keyframes p-jalan{to{transform:translateX(-100%)}}
.p-kabar time{font-size:13px;font-weight:700;white-space:nowrap}

/* panel */
.p-blok{padding:22px 0}
.p-panel{background:var(--p-bg2);border:1px solid var(--p-line);border-radius:var(--p-radius)}
.p-judulblok{display:inline-block;background:var(--p-emas);color:#231703;font-weight:700;font-size:14px;padding:7px 22px;border-radius:var(--p-radius) var(--p-radius) 0 0;letter-spacing:.05em;text-transform:uppercase}

.p-baris{display:grid;grid-template-columns:1fr;gap:16px}
@media(min-width:900px){.p-baris{grid-template-columns:2.6fr 1fr}}

.p-jackpot{border:2px solid var(--p-emas-gelap);border-radius:14px;background:linear-gradient(180deg,#3a2a10,#1a1206);padding:16px;text-align:center}
.p-jackpot-judul{font-weight:700;font-size:18px;letter-spacing:.06em;color:var(--p-emas2)}
.p-jackpot-angka{margin:10px 0 6px;font-family:"Chakra Petch",monospace;font-size:clamp(22px,5.4vw,40px);font-weight:700;color:var(--p-putih);background:#0d0a04;border:2px solid var(--p-emas-gelap);border-radius:30px;padding:8px 10px;letter-spacing:.06em;word-break:break-all}
.p-ilustrasi{display:inline-block;font-size:12px;font-weight:700;letter-spacing:.05em;color:#231703;background:var(--p-emas2);border-radius:4px;padding:3px 9px;text-transform:uppercase}
.p-catatan-kecil{font-size:13px;color:var(--p-redup);margin:8px 0 0}

.p-undian{padding:14px;text-align:center}
.p-undian h2{margin:0 0 8px;font-size:16px;color:var(--p-emas2)}
.p-digit{display:flex;justify-content:center;gap:6px;margin:8px 0}
.p-digit span{display:flex;align-items:center;justify-content:center;width:46px;height:56px;background:#0d0a04;border:2px solid var(--p-line);border-radius:6px;font-family:"Chakra Petch",monospace;font-size:28px;font-weight:700;color:var(--p-putih)}

/* kategori hiasan */
.p-kategori{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;padding:14px}
@media(min-width:720px){.p-kategori{grid-template-columns:repeat(8,1fr)}}
.p-kategori li{list-style:none;text-align:center;color:var(--p-redup);font-size:12px;font-weight:600}
.p-kategori svg{width:26px;height:26px;margin:0 auto 4px;display:block;stroke:var(--p-emas);fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}

/* kartu game */
.p-game{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;padding:14px;margin:0;list-style:none}
@media(min-width:560px){.p-game{grid-template-columns:repeat(4,1fr)}}
.p-game li{background:var(--p-bg3);border:1px solid var(--p-line);border-radius:var(--p-radius);overflow:hidden}
.p-ubin{aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;font-family:"Chakra Petch",sans-serif;font-size:34px;font-weight:700;color:#231703;text-transform:uppercase}
.p-ubin-a{background:linear-gradient(135deg,#f2a43a,#c97d15)}
.p-ubin-b{background:linear-gradient(135deg,#e05a4a,#8e2a20)}
.p-ubin-c{background:linear-gradient(135deg,#6aa84f,#2f5c22)}
.p-ubin-d{background:linear-gradient(135deg,#d9b64a,#8a6a12)}
.p-ubin-e{background:linear-gradient(135deg,#5b7fd4,#2a3d7a)}
.p-ubin-f{background:linear-gradient(135deg,#8f6ad4,#432a7a)}
.p-ubin-g{background:linear-gradient(135deg,#3fa9b5,#17545c)}
.p-ubin-h{background:linear-gradient(135deg,#d47ab0,#7a2a58)}
.p-game b{display:block;padding:8px 10px 2px;font-size:14px;color:var(--p-teks)}
.p-game small{display:block;padding:0 10px 10px;font-size:12px;color:var(--p-redup)}

/* pemenang */
.p-menang{margin:0;padding:8px 14px 14px;list-style:none}
.p-menang li{display:flex;justify-content:space-between;gap:10px;padding:9px 0;border-bottom:1px solid var(--p-line);font-size:14px}
.p-menang li:last-child{border-bottom:0}
.p-menang b{color:var(--p-emas2);font-weight:700}
.p-menang small{display:block;color:var(--p-redup);font-size:12px}

/* artikel */
.p-artikel{padding:6px 0 26px}
.p-artikel h1{font-size:clamp(24px,4.6vw,38px);line-height:1.25;margin:18px 0 14px;color:var(--p-putih)}
.p-artikel h2{font-size:clamp(19px,3vw,26px);margin:30px 0 12px;color:var(--p-emas2);border-left:4px solid var(--p-emas);padding-left:12px}
.p-artikel h3{font-size:18px;margin:22px 0 8px;color:var(--p-teks)}
.p-artikel p{margin:0 0 16px;text-align:justify;hyphens:auto;color:var(--p-teks)}
.p-artikel ul,.p-artikel ol{margin:0 0 16px;padding-left:22px}
.p-artikel li{margin-bottom:7px}
.p-artikel table{width:100%;border-collapse:collapse;margin:0 0 18px;font-size:14px;display:block;overflow-x:auto}
.p-artikel th,.p-artikel td{border:1px solid var(--p-line);padding:9px 10px;text-align:left}
.p-artikel th{background:var(--p-bg3);color:var(--p-emas2)}
.p-remah{font-size:13px;color:var(--p-redup);padding:12px 0 0}
.p-remah a{color:var(--p-redup)}
.p-diperbarui{font-size:13px;color:var(--p-redup);margin:0 0 18px}

/* faq */
.p-faq{padding:14px}
.p-faq details{border:1px solid var(--p-line);border-radius:6px;margin-bottom:8px;background:var(--p-bg3)}
.p-faq summary{cursor:pointer;padding:14px;font-weight:600;font-size:15px;min-height:48px;display:flex;align-items:center}
.p-faq p{margin:0;padding:0 14px 14px;color:var(--p-redup);font-size:15px}

/* footer */
.p-kaki{background:var(--p-bg2);border-top:3px solid var(--p-emas);padding:26px 0 90px;margin-top:24px}
@media(min-width:720px){.p-kaki{padding-bottom:26px}}
.p-kaki-nav{display:flex;flex-wrap:wrap;gap:4px;margin-bottom:16px}
.p-kaki-nav a{display:inline-flex;align-items:center;min-height:48px;padding:0 12px;color:var(--p-teks);text-decoration:none;font-size:14px;font-weight:600}
.p-kaki-nav a:hover{color:var(--p-emas2)}
.p-kaki-grid{display:grid;grid-template-columns:1fr;gap:18px}
@media(min-width:720px){.p-kaki-grid{grid-template-columns:1.4fr 1fr}}
.p-kaki p{font-size:13px;color:var(--p-redup);margin:0 0 10px;line-height:1.7}
.p-usia{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:2px solid var(--p-emas);border-radius:50%;color:var(--p-emas2);font-weight:700;font-size:14px;margin-bottom:10px}
.p-bantuan{display:flex;gap:8px;flex-wrap:wrap}
.p-bantuan a{display:inline-flex;align-items:center;min-height:48px;padding:0 16px;border:1px solid var(--p-line);border-radius:6px;background:var(--p-bg3);color:var(--p-teks);text-decoration:none;font-size:14px;font-weight:600}

/* dock mobile */
.p-dock{position:fixed;left:0;right:0;bottom:0;display:grid;grid-template-columns:repeat(4,1fr);background:var(--p-bg2);border-top:1px solid var(--p-line);z-index:40}
@media(min-width:720px){.p-dock{display:none}}
.p-dock a{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;min-height:58px;color:var(--p-redup);text-decoration:none;font-size:11px;font-weight:600}
.p-dock a svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.p-dock a.aktif{color:var(--p-emas2)}

/* pintasan melayang desktop */
.p-melayang{display:none}
@media(min-width:1100px){
.p-melayang{display:flex;flex-direction:column;gap:10px;position:fixed;right:12px;bottom:18px;z-index:30}
.p-melayang a{display:inline-flex;align-items:center;justify-content:center;min-width:52px;min-height:52px;border-radius:50%;background:var(--p-bg3);border:1px solid var(--p-line);color:var(--p-emas2);text-decoration:none;font-size:11px;font-weight:700;text-align:center;line-height:1.2;padding:4px}
}

@media(prefers-reduced-motion:reduce){
*{animation-duration:.001ms !important;animation-iteration-count:1 !important;transition-duration:.001ms !important}
.p-kabar-isi span{padding-left:0}
.p-rel{scroll-behavior:auto}
}
</style>
</head>
<body>

<div class="p-utilitas">
  <div class="p-wrap">
    <a class="p-ikonbtn" href="<?= e($urlWa) ?>" rel="nofollow noopener" aria-label="Hubungi via WhatsApp">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.6 7.3L3 21l2.3-5.3A8.4 8.4 0 1 1 21 11.5z"/></svg>
    </a>
    <a class="p-tele" href="<?= e($urlTele) ?>" rel="nofollow noopener">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 4 3 11l6 2 2 6z"/></svg>
      Main di Telegram
    </a>
    <span class="p-bahasa">
      <svg class="p-bendera" width="22" height="15" viewBox="0 0 22 15" aria-hidden="true"><rect width="22" height="7.5" fill="#ce1126"/><rect y="7.5" width="22" height="7.5" fill="#fff"/></svg>
      Indonesia
    </span>
    <span class="p-aksi">
      <a class="p-btn p-btn-abu" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">MASUK</a>
      <a class="p-btn p-btn-emas" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">DAFTAR</a>
    </span>
  </div>
</div>

<header class="p-kepala">
  <div class="p-wrap">
    <a class="p-logo" href="/" aria-label="Beranda <?= e($namaSitus) ?>">
      <img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" width="200" height="56" fetchpriority="high">
    </a>
    <nav class="p-nav" aria-label="Navigasi utama">
      <a href="/"<?= $isBeranda ? ' aria-current="page"' : '' ?>>Beranda</a>
      <a href="/tentang-kami"<?= ($isTetap && $slugMinta === 'tentang-kami') ? ' aria-current="page"' : '' ?>>Tentang Kami</a>
      <a href="/kontak"<?= ($isTetap && $slugMinta === 'kontak') ? ' aria-current="page"' : '' ?>>Kontak</a>
      <a href="/disclaimer"<?= ($isTetap && $slugMinta === 'disclaimer') ? ' aria-current="page"' : '' ?>>Disclaimer</a>
    </nav>
  </div>
</header>

<div class="p-banner">
  <div class="p-rel" id="p-rel">
    <figure><img src="<?= e($banner1) ?>" alt="Banner promosi <?= e($namaSitus) ?>" width="1200" height="400" fetchpriority="high"></figure>
    <figure><img src="<?= e($banner2) ?>" alt="Banner informasi <?= e($namaSitus) ?>" width="1200" height="400" loading="lazy"></figure>
  </div>
  <div class="p-titik">
    <button type="button" data-ke="0" aria-current="true" aria-label="Banner 1"></button>
    <button type="button" data-ke="1" aria-current="false" aria-label="Banner 2"></button>
  </div>
</div>

<div class="p-kabar">
  <div class="p-wrap">
    <span class="p-kabar-label">Pemberitahuan</span>
    <span class="p-kabar-isi"><span>Selamat datang di <?= e($namaSitus) ?>. Seluruh konten bersifat informasional dan ditujukan untuk pembaca 18 tahun ke atas. Angka jackpot, hasil undian, dan daftar pemenang yang tampil di halaman ini adalah ilustrasi tampilan, bukan data resmi.</span></span>
    <time datetime="<?= e(date('Y-m-d')) ?>"><?= e(date('d-m-Y')) ?> WIB</time>
  </div>
</div>

<main>
  <div class="p-wrap">

    <?php if ($isBeranda): ?>
    <section class="p-blok" aria-label="Panel ilustrasi">
      <div class="p-baris">
        <div class="p-jackpot">
          <p class="p-jackpot-judul">JACKPOT PLAY</p>
          <p class="p-jackpot-angka">IDR <?= e($jackpotTampil) ?></p>
        </div>
        <div class="p-panel p-undian">
          <h2>Papan Undian Harian</h2>
          <div class="p-digit" aria-label="Angka ilustrasi <?= e($angkaUndian) ?>">
            <?php foreach (str_split($angkaUndian) as $d): ?><span><?= e($d) ?></span><?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <section class="p-blok" aria-label="Ragam permainan">
      <span class="p-judulblok">Ragam Permainan</span>
      <div class="p-panel">
        <ul class="p-kategori">
          <?php foreach ($kategoriHias as $k): ?>
          <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?= e($k[1]) ?>"/></svg><?= e($k[0]) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section class="p-blok" aria-label="Contoh permainan">
      <span class="p-judulblok">Game Populer</span>
      <div class="p-panel">
        <ul class="p-game">
          <?php foreach ($kartuGame as $g): ?>
          <li>
            <div class="p-ubin p-ubin-<?= e($g[2]) ?>" aria-hidden="true"><?= e(hurufAwal($g[0])) ?></div>
            <b><?= e($g[0]) ?></b>
            <small><?= e($g[1]) ?></small>
          </li>
          <?php endforeach; ?>
        </ul>
        <p class="p-catatan-kecil" style="padding:0 14px 14px">Nama dan tampilan permainan di atas adalah contoh generik, bukan produk resmi penyedia mana pun.</p>
      </div>
    </section>

    <section class="p-blok" aria-label="Contoh daftar pemenang">
      <span class="p-judulblok">Contoh Daftar Pemenang</span>
      <div class="p-panel">
        <ul class="p-menang">
          <?php foreach ($daftarPemenang as $m): ?>
          <li>
            <span><?= e($m['nama']) ?><small><?= e($m['game']) ?></small></span>
            <b>IDR <?= e($m['nilai']) ?></b>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
    <?php endif; ?>

    <article class="p-artikel">
      <?php if (!$isBeranda): ?>
      <nav class="p-remah" aria-label="Remah roti"><a href="/">Beranda</a> &rsaquo; <span><?= e($h1) ?></span></nav>
      <?php endif; ?>
      <h1><?= e($h1) ?></h1>
      <p class="p-diperbarui">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
      <?= $isiArtikel ?>
    </article>

    <?php if ($isBeranda): ?>
    <section class="p-blok" aria-label="Pertanyaan yang sering diajukan">
      <span class="p-judulblok">Pertanyaan Umum</span>
      <div class="p-panel p-faq">
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

<footer class="p-kaki">
  <div class="p-wrap">
    <nav class="p-kaki-nav" aria-label="Navigasi bawah">
      <a href="/">Beranda</a>
      <a href="/tentang-kami">Tentang Kami</a>
      <a href="/kontak">Kontak</a>
      <a href="/disclaimer">Disclaimer</a>
    </nav>
    <div class="p-kaki-grid">
      <div>
        <span class="p-usia">18+</span>
        <p><strong><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Seluruh isi bersifat informasional dan edukatif, bukan jaminan hasil maupun nasihat keuangan.</p>
        <p>Permainan berbasis peluang dapat menimbulkan kebiasaan yang merugikan &mdash; tetapkan batas waktu dan pengeluaran, dan berhenti bila mulai mengganggu kehidupan sehari-hari.</p>
        <p>&copy; <?= e(date('Y')) ?> <?= e($namaSitus) ?>. Konten terakhir diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time>.</p>
      </div>
      <div>
        <p><strong>Butuh bantuan?</strong></p>
        <div class="p-bantuan">
          <a href="<?= e($urlLc) ?>" rel="nofollow noopener">Live Chat</a>
          <a href="<?= e($urlWa) ?>" rel="nofollow noopener">WhatsApp</a>
          <a href="<?= e($urlTele) ?>" rel="nofollow noopener">Telegram</a>
        </div>
      </div>
    </div>
  </div>
</footer>

<nav class="p-dock" aria-label="Navigasi cepat">
  <a href="/"<?= $isBeranda ? ' class="aktif"' : '' ?>>
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11 12 4l8 7v8a1 1 0 0 1-1 1h-4v-6h-6v6H5a1 1 0 0 1-1-1z"/></svg>Beranda</a>
  <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Daftar</a>
  <a href="<?= e($ctaLogin) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M14 3h6v18h-6"/></svg>Masuk</a>
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 1 1-3.2-6.4M21 5v5h-5"/></svg>Live Chat</a>
</nav>

<div class="p-melayang">
  <a href="<?= e($urlLc) ?>" rel="nofollow noopener">Live<br>Chat</a>
  <a href="<?= e($urlWa) ?>" rel="nofollow noopener" aria-label="WhatsApp">WA</a>
</div>

<script type="application/ld+json"><?= $jsonLd ?></script>
<script>
(function(){
  var rel=document.getElementById('p-rel');
  if(!rel)return;
  var titik=document.querySelectorAll('.p-titik button');
  var diam=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function ke(i){rel.scrollTo({left:rel.clientWidth*i,behavior:diam?'auto':'smooth'});}
  titik.forEach(function(b){b.addEventListener('click',function(){ke(+b.dataset.ke);});});
  rel.addEventListener('scroll',function(){
    var i=Math.round(rel.scrollLeft/rel.clientWidth);
    titik.forEach(function(b,n){b.setAttribute('aria-current',n===i?'true':'false');});
  },{passive:true});
  if(diam)return;
  var n=0;setInterval(function(){n=(n+1)%titik.length;ke(n);},7000);
})();
</script>
</body>
</html>
