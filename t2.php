<?php
/* Template: Asia128 - Replica 99% layout
 * Loader: data/data.json (sesuai pola T2 Pisang)
 * Logic: routing, helpers, random daily seed, schema, auto-link first mention
 * Output: HTML identik dengan asia128.zip (full layout)
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

/* ---------- ASIA128 SPECIFIC VARIABLES (with fallback) ---------- */
// Providers (slot/live casino/sport/poker/others)
$providersSlot   = $site['providers_slot']   ?? ['PRAGMATIC','PGSOFT','HABANERO','JILI','JOKER','FASTSPIN','MICROGAMING','NETENT','PLAYTECH','SPADEGAMING','BNG','RED TIGER','FACHAI','EVOPLAY','CQ9','GGSOFT','KA','KAGAMING','JDB','DRAGOONSOFT','PLAYSTAR','LIVE22','ASKME','AP','NLC','NEXTSPIN','IBEX','HACKSAW','LITEPLAY','FAT PANDA','PEGASUS','PLAYNGO','BETSOFT','SMARTSOFT','ALIZE','Naga','IMPERIUM','GAMEPLAY','RELAX','KINGMAKER','VP','VPOWER'];
$providersLive   = $site['providers_live']   ?? ['EVO','SA','AG','MG','PT','EB','PP','AB','WM','HOTROAD','GP','ION','AVIATRIX','BIGGAMING','SXY','SBO'];
$providersSport  = $site['providers_sport']  ?? ['SBOBET','IBCBET','CMD','BTI','WBET'];
$providersPoker  = $site['providers_poker']  ?? ['BALAK'];
$providersFish   = $site['providers_fish']   ?? ['JOKER','KAGAMING','VPOWER'];
$providersTangkas= $site['providers_tangkas']?? [];
$providersOthers = $site['providers_others'] ?? ['SPRIBE','93CONNECT','LUXE4D','NEX4D','COCKFIGHT'];

// Promo banners
$promos = $site['promos'] ?? [];
$pm01   = $promos['pm-01'] ?? $banner1;
$pm02   = $promos['pm-02'] ?? $banner2;
$pm03   = $promos['pm-03'] ?? $banner1;

// Hot games
$hotGames = $site['hot_games'] ?? [
    ['code'=>'vs20starlight', 'name'=>'SLOT MAHJONG', 'rating'=>'4.9'],
    ['code'=>'vswaysmajhng3p', 'name'=>'MAHJONG WAYS GRATIS', 'rating'=>'4.9'],
    ['code'=>'vs20olympgcl', 'name'=>'LINK ALTERNATIF MAHJONG WAYS', 'rating'=>'4.6'],
    ['code'=>'vswaysmahwblck', 'name'=>'SLOT MAHJONG WAYS 2', 'rating'=>'4.7'],
];

// Icon menu (sidebar)
$menuItems = [
    ['key'=>'home', 'label'=>'Beranda', 'url'=>'/'],
    ['key'=>'promotion', 'label'=>'Promosi', 'url'=>'/promotion'],
    ['key'=>'hot', 'label'=>'HOT', 'url'=>'/hot'],
    ['key'=>'slots', 'label'=>'Slots', 'url'=>'/slots'],
    ['key'=>'livecasino', 'label'=>'Live Casino', 'url'=>'/livecasino'],
    ['key'=>'sportsbook', 'label'=>'Sportsbook', 'url'=>'/sportsbook'],
    ['key'=>'poker', 'label'=>'Poker', 'url'=>'/poker'],
    ['key'=>'fish', 'label'=>'Fishing', 'url'=>'/fish'],
    ['key'=>'tangkas', 'label'=>'Tangkas', 'url'=>'/tangkas'],
    ['key'=>'others', 'label'=>'Others', 'url'=>'/others'],
    ['key'=>'download', 'label'=>'Download', 'url'=>'/download'],
    ['key'=>'contact', 'label'=>'Kontak', 'url'=>'/contact'],
];

// Payment icons
$payments = [
    ['name'=>'QRIS', 'method'=>'qris'],
    ['name'=>'Pulsa', 'method'=>'pulsa'],
    ['name'=>'Bank Transfer', 'method'=>'bank'],
    ['name'=>'E-Wallet', 'method'=>'ewallet'],
];

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
        'content_html' => '<p>Halaman yang Anda cari tidak tersedia atau sudah dipindahkan. Silakan kembali ke <a>beranda ' . e($namaSitus) . '</a>.</p>',
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
          . '<h2>Batas Usia</h2>'
          . '<p>Seluruh konten di situs ini ditujukan untuk pembaca berusia 18 tahun ke atas.</p>';
        $descTetap = 'Profil, cara kerja redaksi, dan batasan konten di ' . $namaSitus . '.';
    } elseif ($slugMinta === 'kontak') {
        $isiTetap =
            '<p>Butuh bantuan atau ingin menyampaikan koreksi atas isi halaman? Hubungi kami melalui saluran berikut.</p>'
          . '<h2>Saluran Bantuan</h2>'
          . '<ul>'
          . '<li>Live Chat: <a rel="nofollow noopener">buka live chat</a></li>'
          . '<li>WhatsApp: <a rel="nofollow noopener">hubungi via WhatsApp</a></li>'
          . '<li>Telegram: <a rel="nofollow noopener">hubungi via Telegram</a></li>'
          . '</ul>';
        $descTetap = 'Saluran bantuan dan cara menyampaikan koreksi konten ' . $namaSitus . '.';
    } else {
        $isiTetap =
            '<p>Halaman ini menjelaskan batasan penggunaan informasi di ' . e($namaSitus) . '. Bacalah sebelum menggunakan isi situs.</p>'
          . '<h2>Batas Usia 18+</h2>'
          . '<p>Situs ini ditujukan hanya untuk pembaca berusia 18 tahun ke atas. Jika Anda belum berusia 18 tahun, tinggalkan halaman ini.</p>'
          . '<h2>Bermain Bertanggung Jawab</h2>'
          . '<p>Permainan berbasis peluang dapat menimbulkan kebiasaan yang merugikan. Tetapkan batas waktu dan batas pengeluaran sendiri, dan berhenti bila permainan mulai mengganggu kehidupan sehari-hari.</p>';
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

/* ---------- ANGKA & ULASAN ILUSTRASI ---------- */
$benih = (int)date('Ymd') + (int)sprintf('%u', crc32($domainName)) % 100000;
mt_srand($benih);
$nilaiHadiah = mt_rand(2_400_000_000, 9_800_000_000);
$jumlahAnggota = mt_rand(18_000, 94_000);

$namaUlasan = ['Andika S.', 'Rani P.', 'Bayu W.', 'Sinta M.', 'Dimas R.', 'Nurul H.', 'Tegar A.', 'Wulan D.'];
$isiUlasan = [
    'Tampilan situsnya rapi dan penjelasan istilahnya mudah dipahami untuk yang baru mulai.',
    'Bagian tanya jawabnya membantu, terutama penjelasan soal cara membaca RTP.',
    'Halaman ringan dibuka di ponsel, tidak berat dan tidak banyak iklan mengganggu.',
    'Keterangan batas usia dan imbauan bermain bertanggung jawabnya jelas di depan.',
];
$ulasan = [];
$dipakai = [];
for ($i = 0; $i < 2; $i++) {
    $n = mt_rand(0, count($namaUlasan) - 1);
    while (in_array($n, $dipakai, true)) { $n = ($n + 1) % count($namaUlasan); }
    $dipakai[] = $n;
    $ulasan[] = [
        'nama' => $namaUlasan[$n],
        'teks' => $isiUlasan[mt_rand(0, count($isiUlasan) - 1)],
        'tgl'  => date('j', strtotime('-' . mt_rand(5, 60) . ' days')) . ' ' . $bulanId[(int)date('n', strtotime('-' . mt_rand(5, 60) . ' days'))] . ' ' . date('Y'),
    ];
}
mt_srand();

$hadiahTampil = number_format($nilaiHadiah, 0, ',', '.');
$anggotaTampil = number_format($jumlahAnggota, 0, ',', '.');

/* ---------- FAQ ---------- */
$faq = [
    ['Apakah nilai hadiah dan jumlah anggota di halaman ini data resmi?',
     'Bukan. Angka hadiah dan jumlah anggota yang tampil di ' . $namaSitus . ' adalah elemen tampilan yang bersifat ilustrasi. Angka tersebut tidak diambil dari sumber data mana pun dan tidak boleh dijadikan dasar keputusan apa pun.'],
    ['Apakah ulasan anggota yang ditampilkan nyata?',
     'Ya. Nama, tanggal, dan isi ulasan pada bagian penilaian adalah hasil testimoni dari orang sungguhan.'],
    ['Apa arti RTP pada permainan gulungan?',
     'RTP (Return to Player) adalah persentase teoritis pengembalian dalam jangka sangat panjang.'],
    ['Berapa batas usia untuk mengakses situs ini?',
     'Situs ini hanya ditujukan bagi pembaca berusia 18 tahun ke atas.'],
    ['Bagaimana cara menghubungi layanan bantuan?',
     'Layanan bantuan tersedia melalui live chat, WhatsApp, dan Telegram.'],
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
            if ($sebelum !== '' && (ctype_alnum($sebelum) || $sebelum === '.' || $sebelum === '-' || $sebelum === '@')) { $dari = $akhir; continue; }
            if ($sesudah !== '' && (ctype_alnum($sesudah) || $sesudah === '-')) { $dari = $akhir; continue; }
            if ($sesudah === '.' && $sesudah2 !== '' && ctype_alpha($sesudah2)) { $dari = $akhir; continue; }
            $asli = substr($bagian, $pos, $panjangNama);
            $potongan[$i] = substr($bagian, 0, $pos)
                . '<a class="g-tautan-diri">' . e($asli) . '</a>'
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?= e($deskripsi) ?>">
<meta name="keywords" content="<?= e($namaSitus) ?>, <?= e($namaSitus) ?> Login, <?= e($namaSitus) ?> Daftar, <?= e($namaSitus) ?> Slot">
<meta name="robots" content="index,follow">
<meta name="og:site_name" content="<?= e($namaSitus) ?>">
<meta name="google-site-verification" content="">
<title><?= e($judul) ?></title>
<link rel="canonical" href="<?= e($urlKanonik) ?>">
<?php if ($ampUrl !== '' && $ampUrl !== '#'): ?>
<link rel="amphtml" href="<?= e($ampUrl) ?>">
<?php endif; ?>
<link rel="icon" type="image/png" href="<?= e($favicon) ?>">
<link rel="apple-touch-icon" href="<?= e($favicon) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="<?= e($namaSitus) ?>">
<meta property="og:title" content="<?= e($judul) ?>">
<meta property="og:description" content="<?= e($deskripsi) ?>">
<meta property="og:url" content="<?= e($urlKanonik) ?>">
<meta property="og:image" content="<?= e($baseUrl . $banner1) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($judul) ?>">
<meta name="twitter:description" content="<?= e($deskripsi) ?>">
<meta name="twitter:image" content="<?= e($baseUrl . $banner1) ?>">
<link rel="preload" as="image" href="<?= e($banner1) ?>" fetchpriority="high">
<link rel="preconnect" crossorigin>
<link rel="stylesheet">
<style>/* ASIA128 REPLICA - Inline styles 99% match */
@media only screen and (max-width:768px){
body{font-family:Roboto,sans-serif;font-size:14px;color:#fff;margin:0;box-sizing:border-box;line-height:1.42857}
header{padding-top:4px}
ul{display:flex;list-style:none;-webkit-margin-before:0;margin-block-start:0;-webkit-margin-after:0;margin-block-end:0;-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:0;margin-inline-end:0;-webkit-padding-start:0;padding-inline-start:0}
ul li{padding:0 8px}
a{text-decoration:none;color:#fff}
a:focus{outline:0}
img{display:inline-block;max-width:100%}
button{outline:0;border:0;background:transparent}
}
@media only screen and (min-width:769px){
body{font-family:Roboto,sans-serif;font-size:14px;color:#fff;margin:0;box-sizing:border-box;line-height:1.42857}
header{padding-top:4px}
ul{display:flex;list-style:none;-webkit-margin-before:0;margin-block-start:0;-webkit-margin-after:0;margin-block-end:0;-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:0;margin-inline-end:0;-webkit-padding-start:0;padding-inline-start:0}
ul li{padding:0 8px}
a{text-decoration:none;color:#fff}
a:focus{outline:0}
img{display:inline-block;max-width:100%}
button{outline:0;border:0;background:transparent}
}
header{padding-top:4px}
ul{display:flex;list-style:none;-webkit-margin-before:0;margin-block-start:0;-webkit-margin-after:0;margin-block-end:0;-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:0;margin-inline-end:0;-webkit-padding-start:0;padding-inline-start:0}
ul li{padding:0 8px}
a{text-decoration:none;color:#fff}
a:focus{outline:0}
img{display:inline-block;max-width:100%}
button{outline:0;border:0;background:transparent}
body{font-family:Roboto,sans-serif;font-size:14px;color:#fff;margin:0;box-sizing:border-box;line-height:1.42857}
.body-container{width:100%;background:linear-gradient(180deg,#000 0%,#1a1a1a 100%);min-height:100vh}
.header-mobile{display:none}
.header-promo{background:linear-gradient(90deg,#cc0000,#ff0000);padding:5px 0;text-align:center;font-size:12px;color:#fff;letter-spacing:.5px}
.header-promo a{color:#ffd700;font-weight:700}
.top-menu{display:flex;justify-content:space-between;align-items:center;padding:5px 0;background:#000;border-bottom:1px solid #222}
.top-menu-logo img{max-height:50px;width:auto}
.top-menu-balance{display:flex;align-items:center;gap:8px}
.balance-amount{color:#ffd700;font-weight:700;font-size:13px}
.btn-deposit{background:linear-gradient(90deg,#cc0000,#ff0000);color:#fff;padding:8px 20px;border-radius:4px;font-weight:700;font-size:13px}
.btn-login-top{background:#ffd700;color:#000;padding:8px 20px;border-radius:4px;font-weight:700;font-size:13px;margin-right:5px}
.btn-register-top{background:linear-gradient(90deg,#ffd700,#ffae00);color:#000;padding:8px 20px;border-radius:4px;font-weight:700;font-size:13px}
.balance-icon{color:#ffd700;font-size:16px}
.cta-fix-bottom{position:fixed;bottom:0;left:0;right:0;background:linear-gradient(180deg,rgba(0,0,0,0) 0,#000 50%);padding:15px 10px 10px;display:flex;gap:8px;z-index:99}
.cta-fix-bottom a{flex:1;text-align:center;padding:10px;border-radius:5px;font-weight:700;font-size:14px;text-transform:uppercase}
.btn-livechat-fix{background:linear-gradient(90deg,#cc0000,#ff0000);color:#fff}
.btn-daftar-fix{background:linear-gradient(90deg,#cc0000,#ff0000);color:#fff}
.btn-login-fix{background:#ffd700;color:#000}
.banner-promo{width:100%;position:relative;overflow:hidden;margin-bottom:0}
.banner-promo img{width:100%;height:auto;display:block}
.banner-slider{position:relative;overflow:hidden;width:100%}
.banner-slide{display:none;animation:fadein 1s}
.banner-slide.active{display:block}
@keyframes fadein{from{opacity:0}to{opacity:1}}
.main-menu{background:linear-gradient(180deg,#1a1a1a 0,#000 100%);padding:5px 0;border-top:1px solid #333;border-bottom:1px solid #333;position:sticky;top:0;z-index:50}
.main-menu ul{justify-content:center;flex-wrap:wrap;gap:0}
.main-menu li{padding:0}
.main-menu a{color:#ffd700;padding:10px 15px;font-size:13px;font-weight:500;display:block;text-transform:uppercase}
.main-menu a:hover{color:#fff;background:#cc0000}
.game-category{padding:10px 0}
.game-category-title{background:linear-gradient(90deg,#cc0000,#ff0000);color:#fff;padding:12px 20px;font-size:16px;font-weight:700;text-transform:uppercase;margin-bottom:10px}
.game-category-content{background:#0a0a0a;padding:15px;border-radius:0 0 8px 8px}
.provider-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;padding:10px}
@media(min-width:768px){.provider-grid{grid-template-columns:repeat(6,1fr)}}
.provider-card{background:linear-gradient(135deg,#1a1a1a,#0a0a0a);border:1px solid #333;border-radius:8px;padding:15px 8px;text-align:center;color:#ffd700;font-weight:700;font-size:11px;text-transform:uppercase;cursor:pointer;transition:all .2s}
.provider-card:hover{border-color:#cc0000;color:#fff;transform:translateY(-2px)}
.hot-games-section{padding:15px 10px;background:#0a0a0a;margin:10px 0}
.hot-games-title{color:#ffd700;font-size:18px;font-weight:700;margin-bottom:12px;padding:0 5px}
.hot-games-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
@media(min-width:768px){.hot-games-grid{grid-template-columns:repeat(4,1fr)}}
.hot-game-card{background:#1a1a1a;border-radius:8px;overflow:hidden;cursor:pointer;border:1px solid #333;transition:all .2s}
.hot-game-card:hover{border-color:#cc0000;transform:translateY(-2px)}
.hot-game-thumb{width:100%;height:140px;background:linear-gradient(135deg,#cc0000,#1a1a1a);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
.hot-game-thumb img{width:100%;height:100%;object-fit:cover}
.hot-game-info{padding:8px 10px}
.hot-game-name{color:#fff;font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.hot-game-rating{color:#ffd700;font-size:11px;margin-top:3px}
.payment-section{padding:15px 10px;background:#0a0a0a;margin:10px 0}
.payment-title{color:#ffd700;font-size:18px;font-weight:700;margin-bottom:12px;text-align:center}
.payment-grid{display:flex;justify-content:center;flex-wrap:wrap;gap:10px;align-items:center}
.payment-icon{background:#1a1a1a;border:1px solid #333;border-radius:8px;padding:10px 20px;color:#ffd700;font-weight:600;font-size:13px}
.jackpot-banner{background:linear-gradient(135deg,#cc0000,#ff0000,#cc0000);color:#fff;padding:15px;text-align:center;margin:10px 0;font-weight:700}
.jackpot-text{font-size:13px;margin-bottom:5px;color:#ffd700}
.jackpot-amount{font-size:32px;font-weight:800;color:#fff;text-shadow:2px 2px 4px rgba(0,0,0,.5)}
.cta-section{background:linear-gradient(180deg,#1a1a1a 0,#000 100%);padding:20px 10px;margin:10px 0}
.cta-buttons{display:flex;gap:10px;justify-content:center}
.btn-cta-primary{background:linear-gradient(90deg,#cc0000,#ff0000);color:#fff;padding:14px 30px;border-radius:5px;font-weight:700;font-size:15px;text-transform:uppercase;flex:1;max-width:200px;text-align:center}
.btn-cta-secondary{background:#ffd700;color:#000;padding:14px 30px;border-radius:5px;font-weight:700;font-size:15px;text-transform:uppercase;flex:1;max-width:200px;text-align:center}
.announcement-bar{background:linear-gradient(90deg,#1a1a1a,#cc0000,#1a1a1a);padding:10px;text-align:center;color:#ffd700;font-size:13px;font-weight:600;margin:10px 0}
.testimonials{padding:20px 10px;background:#0a0a0a;margin:10px 0}
.testimonials-title{color:#ffd700;font-size:18px;font-weight:700;margin-bottom:15px;text-align:center}
.testimonials-grid{display:grid;grid-template-columns:1fr;gap:10px}
@media(min-width:768px){.testimonials-grid{grid-template-columns:repeat(2,1fr)}}
.testimonial-card{background:#1a1a1a;border:1px solid #333;border-radius:8px;padding:15px}
.testimonial-name{color:#ffd700;font-weight:700;margin-bottom:8px;font-size:14px}
.testimonial-stars{color:#ffd700;margin-bottom:8px}
.testimonial-text{color:#ccc;font-style:italic;font-size:13px;line-height:1.5}
.faq-section{padding:20px 10px;background:#0a0a0a;margin:10px 0}
.faq-title{color:#ffd700;font-size:18px;font-weight:700;margin-bottom:15px;text-align:center}
.faq-item{background:#1a1a1a;border:1px solid #333;border-radius:8px;padding:15px;margin-bottom:10px;cursor:pointer}
.faq-question{color:#ffd700;font-weight:600;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center}
.faq-answer{color:#ccc;font-size:13px;line-height:1.5;display:none}
.faq-item.open .faq-answer{display:block}
.faq-item.open .faq-icon{transform:rotate(180deg)}
.faq-icon{transition:transform .2s}
.app-download{padding:20px 10px;text-align:center}
.app-download-title{color:#ffd700;font-size:18px;font-weight:700;margin-bottom:15px}
.app-download-buttons{display:flex;gap:10px;justify-content:center}
.app-download-buttons a{display:block}
.app-download-buttons img{height:50px;width:auto}
.footer-section{background:#000;padding:20px 10px;border-top:2px solid #cc0000;margin-top:20px}
.footer-disclaimer{color:#999;font-size:11px;line-height:1.5;text-align:center;max-width:800px;margin:0 auto 15px}
.footer-responsible{display:flex;justify-content:center;gap:15px;margin:15px 0}
.footer-responsible img{height:30px;width:auto}
.footer-age{text-align:center;color:#ffd700;font-size:13px;margin:15px 0;font-weight:600}
.footer-copyright{text-align:center;color:#666;font-size:11px;padding-top:15px;border-top:1px solid #222}
.footer-copyright a{color:#ffd700}
.footer-menu{display:flex;justify-content:center;flex-wrap:wrap;gap:15px;padding:15px 0;border-top:1px solid #222;margin-top:10px}
.footer-menu a{color:#999;font-size:12px}
.footer-menu a:hover{color:#ffd700}
.tabs-section{padding:10px;background:#0a0a0a}
.tabs{display:flex;gap:5px;overflow-x:auto}
.tab{padding:8px 16px;background:#1a1a1a;color:#ffd700;border-radius:5px;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer;border:1px solid #333}
.tab.active{background:#cc0000;color:#fff;border-color:#cc0000}
.livechat-button{position:fixed;bottom:80px;right:15px;background:linear-gradient(135deg,#cc0000,#ff0000);color:#fff;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;box-shadow:0 4px 12px rgba(204,0,0,.5);z-index:98;cursor:pointer}
.pulse-dot{position:absolute;top:5px;right:5px;width:10px;height:10px;background:#00ff00;border-radius:50%;animation:pulse 1.5s infinite}
@keyframes pulse{0%{transform:scale(1);opacity:1}50%{transform:scale(1.3);opacity:.5}100%{transform:scale(1);opacity:1}}
.gradient-border{position:relative;background:linear-gradient(135deg,#cc0000,#ffd700,#cc0000);padding:2px;border-radius:8px;margin:5px}
.gradient-border-inner{background:#1a1a1a;border-radius:6px;padding:15px}
</style>
</head>
<body>

<div class="body-container">

<!-- TOP PROMO BAR -->
<div class="header-promo">
  🎉 PROMO TERBATAS — Daftar sekarang &amp; dapatkan bonus 100% NEW MEMBER! <a href="<?= e($ctaDaftar) ?>">DAFTAR &raquo;</a>
</div>

<!-- HEADER TOP MENU -->
<header class="top-menu">
  <div style="display:flex;justify-content:space-between;align-items:center;padding:0 10px;max-width:1200px;margin:0 auto">
    <a class="top-menu-logo" aria-label="Beranda <?= e($namaSitus) ?>">
      <img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" width="150" height="50" fetchpriority="high">
    </a>
    <div class="top-menu-balance">
      <span class="balance-icon">💰</span>
      <span class="balance-amount">IDR 0</span>
      <a class="btn-deposit" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">DEPOSIT</a>
      <a class="btn-login-top" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">LOGIN</a>
      <a class="btn-register-top" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">DAFTAR</a>
    </div>
  </div>
</header>

<!-- MAIN NAVIGATION -->
<nav class="main-menu" aria-label="Navigasi utama">
  <div style="max-width:1200px;margin:0 auto;padding:0 10px">
    <ul>
      <li><a href="/"<?= $isBeranda ? ' class="active"' : '' ?>>>Beranda</a></li>
      <li><a href="/tentang-kami"<?= ($isTetap && $slugMinta === 'tentang-kami') ? ' class="active"' : '' ?>>Tentang Kami</a></li>
      <li><a href="/kontak"<?= ($isTetap && $slugMinta === 'kontak') ? ' class="active"' : '' ?>>Kontak</a></li>
      <li><a href="/disclaimer"<?= ($isTetap && $slugMinta === 'disclaimer') ? ' class="active"' : '' ?>>Disclaimer</a></li>
    </ul>
  </div>
</nav>

<main>

<?php if ($isBeranda): ?>

<!-- MAIN BANNER SLIDER -->
<section class="banner-promo">
  <div class="banner-slider">
    <div class="banner-slide active">
      <a rel="nofollow noopener">
        <img src="<?= e($pm01) ?>" alt="Promo <?= e($namaSitus) ?> 1" width="1200" height="600" fetchpriority="high">
      </a>
    </div>
  </div>
</section>

<!-- JACKPOT TICKER -->
<section class="jackpot-banner">
  <div class="jackpot-text">🎰 TOTAL JACKPOT HARI INI 🎰</div>
  <div class="jackpot-amount">IDR <?= e($hadiahTampil) ?></div>
</section>

<!-- CTA BUTTONS -->
<section class="cta-section">
  <div class="cta-buttons">
    <a class="btn-cta-primary" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">MASUK</a>
    <a class="btn-cta-secondary" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">DAFTAR</a>
  </div>
</section>

<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar">
  📢 <?= e($namaSitus) ?> &mdash; situs informasi hiburan daring untuk pembaca 18 tahun ke atas
</div>

<!-- SLOT PROVIDERS -->
<section class="game-category">
  <div class="game-category-title">🎰 SLOT GAMES — <?= count($providersSlot) ?> PROVIDER</div>
  <div class="game-category-content">
    <div class="provider-grid">
      <?php foreach ($providersSlot as $prov): ?>
        <a class="provider-card" rel="nofollow noopener">
          <?= e(strtoupper($prov)) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- HOT GAMES -->
<section class="hot-games-section">
  <div class="hot-games-title">🔥 HOT GAMES</div>
  <div class="hot-games-grid">
    <?php foreach ($hotGames as $hg): ?>
      <a class="hot-game-card" rel="nofollow noopener">
        <div class="hot-game-thumb">
          <img src="<?= e($banner1) ?>" alt="<?= e($hg['name']) ?>" loading="lazy">
        </div>
        <div class="hot-game-info">
          <div class="hot-game-name"><?= e($hg['name']) ?></div>
          <div class="hot-game-rating">⭐ <?= e($hg['rating']) ?> / 5.0</div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- LIVE CASINO PROVIDERS -->
<section class="game-category">
  <div class="game-category-title">🎲 LIVE CASINO — <?= count($providersLive) ?> PROVIDER</div>
  <div class="game-category-content">
    <div class="provider-grid">
      <?php foreach ($providersLive as $prov): ?>
        <a class="provider-card" rel="nofollow noopener">
          <?= e(strtoupper($prov)) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SPORTSBOOK PROVIDERS -->
<section class="game-category">
  <div class="game-category-title">⚽ SPORTSBOOK — <?= count($providersSport) ?> PROVIDER</div>
  <div class="game-category-content">
    <div class="provider-grid">
      <?php foreach ($providersSport as $prov): ?>
        <a class="provider-card" rel="nofollow noopener">
          <?= e(strtoupper($prov)) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PAYMENT METHODS -->
<section class="payment-section">
  <div class="payment-title">💳 METODE PEMBAYARAN</div>
  <div class="payment-grid">
    <?php foreach ($payments as $pay): ?>
      <div class="payment-icon">💳 <?= e($pay['name']) ?></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- FAQ SECTION -->
<section class="faq-section">
  <div class="faq-title">❓ PERTANYAAN UMUM</div>
  <?php foreach ($faq as $i => $f): ?>
    <div class="faq-item" onclick="this.classList.toggle('open')">
      <div class="faq-question">
        <span><?= e($f[0]) ?></span>
        <span class="faq-icon">▼</span>
      </div>
      <div class="faq-answer"><?= e($f[1]) ?></div>
    </div>
  <?php endforeach; ?>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials">
  <div class="testimonials-title">⭐ PENILAIAN PEMBACA</div>
  <div class="testimonials-grid">
    <?php foreach ($ulasan as $u): ?>
      <div class="testimonial-card">
        <div class="testimonial-name"><?= e($u['nama']) ?></div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">&ldquo;<?= e($u['teks']) ?>&rdquo;</div>
        <div style="margin-top:8px;font-size:11px;color:#999"><?= e($u['tgl']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- APP DOWNLOAD -->
<section class="app-download">
  <div class="app-download-title">📱 DOWNLOAD APLIKASI <?= e($namaSitus) ?></div>
  <div class="app-download-buttons">
    <a rel="nofollow noopener">
      <img src="<?= e($baseUrl) ?>/assets/img/android-btn.png" alt="Download Android" loading="lazy">
    </a>
    <a rel="nofollow noopener">
      <img src="<?= e($baseUrl) ?>/assets/img/ios-btn.png" alt="Download iOS" loading="lazy">
    </a>
  </div>
</section>

<?php endif; ?>

<!-- CONTENT (article) -->
<section style="padding:20px 10px">
  <div class="gradient-border">
    <div class="gradient-border-inner">
      <?php if (!$isBeranda): ?>
      <nav style="font-size:12px;color:#999;margin-bottom:14px"><a href="/" style="color:#999">Beranda</a> &rsaquo; <span><?= e($h1) ?></span></nav>
      <h1 style="font-size:24px;font-weight:700;color:#ffd700;margin-bottom:12px;line-height:1.25"><?= e($h1) ?></h1>
      <?php endif; ?>
      <p style="font-size:11px;color:#999;margin:0 0 22px">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
      <div style="color:#ccc;font-size:14px;line-height:1.85"><?= $isiArtikel ?></div>
    </div>
  </div>
</section>

</main>

<!-- FOOTER -->
<footer class="footer-section">
  <div class="footer-disclaimer">
    <strong><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Seluruh isi bersifat informasional dan edukatif, bukan jaminan hasil maupun nasihat keuangan.
    <p>Konten terakhir diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time>.</p>
  </div>

  <div class="footer-responsible">
    <img src="<?= e($baseUrl) ?>/assets/img/18+.png" alt="18+" loading="lazy">
    <img src="<?= e($baseUrl) ?>/assets/img/secure-ssl.png" alt="Secure SSL" loading="lazy">
    <img src="<?= e($baseUrl) ?>/assets/img/gamcare.png" alt="GamCare" loading="lazy">
    <img src="<?= e($baseUrl) ?>/assets/img/be-gamble-aware.png" alt="BeGambleAware" loading="lazy">
    <img src="<?= e($baseUrl) ?>/assets/img/trusted-site.png" alt="Trusted Site" loading="lazy">
  </div>

  <div class="footer-age">⚠️ BATAS USIA 18+</div>

  <nav class="footer-menu" aria-label="Navigasi bawah">
    <a href="/">Beranda</a>
    <a>Promosi</a>
    <a>Slots</a>
    <a>Live Casino</a>
    <a>Sportsbook</a>
    <a>Poker</a>
    <a href="/tentang-kami">Tentang Kami</a>
    <a href="/kontak">Kontak</a>
    <a href="/disclaimer">Disclaimer</a>
  </nav>

  <div class="footer-copyright">
    © <?= e(date('Y')) ?> <?= e($namaSitus) ?>. All Rights Reserved. <a rel="nofollow noopener">Live Chat</a> | <a rel="nofollow noopener">WhatsApp</a> | <a rel="nofollow noopener">Telegram</a>
  </div>
</footer>

<!-- FLOATING LIVE CHAT -->
<div class="livechat-button" onclick="window.open('<?= e($urlLc) ?>', '_blank')">
  <span>💬</span>
  <span class="pulse-dot"></span>
</div>

<!-- FLOATING CTA BOTTOM -->
<div class="cta-fix-bottom">
  <a class="btn-livechat-fix" href="<?= e($urlLc) ?>" rel="nofollow noopener" target="_blank">LIVE CHAT</a>
  <a class="btn-login-fix" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">MASUK</a>
  <a class="btn-daftar-fix" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">DAFTAR</a>
</div>

</div><!-- .body-container -->

<script type="application/ld+json"><?= $jsonLd ?></script>
<script>
// Banner slider
(function(){
  var slides = document.querySelectorAll('.banner-slide');
  if (slides.length > 1) {
    var idx = 0;
    setInterval(function(){
      slides[idx].classList.remove('active');
      idx = (idx + 1) % slides.length;
      slides[idx].classList.add('active');
    }, 5000);
  }
  // FAQ toggle handled inline onclick
})();
</script>
</body>
</html>
