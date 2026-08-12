<?php
/* Template: BURUNG303 - Replica 99% layout
 * Loader: data/data.json (sesuai pola T2 Pisang)
 * Layout: Identik dengan burung303.zip
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

/* ---------- BURUNG303 SPECIFIC VARIABLES ---------- */
// Alt domains (multi-domain strategy)

// Bank list (with online/offline status)
$bankList = $site['banks'] ?? [
    ['name' => 'BCA', 'method' => 'bca', 'status' => 'online'],
    ['name' => 'BRI', 'method' => 'bri', 'status' => 'online'],
    ['name' => 'BNI', 'method' => 'bni', 'status' => 'online'],
    ['name' => 'Mandiri', 'method' => 'mandiri', 'status' => 'online'],
    ['name' => 'DANA', 'method' => 'dana', 'status' => 'online'],
    ['name' => 'OVO', 'method' => 'ovo', 'status' => 'offline'],
    ['name' => 'GoPay', 'method' => 'gopay', 'status' => 'online'],
    ['name' => 'QRIS', 'method' => 'qris', 'status' => 'online'],
];

// Providers (slot + live + sport)
$providersSlot  = $site['providers_slot']  ?? ['PRAGMATIC','PGSOFT','HABANERO','JILI','JOKER','FASTSPIN','MICROGAMING','NETENT','PLAYTECH','SPADEGAMING','BNG','RED TIGER','FACHAI','EVOPLAY','CQ9','GGSOFT'];
$providersLive  = $site['providers_live']  ?? ['EVO','SA GAMING','AG','MG','PT','EBET','ION CASINO','BIG GAMING','SBO'];
$providersSport = $site['providers_sport'] ?? ['SBOBET','CMD368','IBCBET','WBET'];

// Currency (default IDR)
$currency = $site['currency'] ?? 'IDR';

// Football schedule (sample)
$footballSchedule = $site['football_schedule'] ?? [
    ['time' => '20:00', 'league' => 'Liga Inggris', 'home' => 'Liverpool', 'away' => 'Man City', 'home_score' => 2, 'away_score' => 1, 'status' => 'live'],
    ['time' => '22:30', 'league' => 'Liga Spanyol', 'home' => 'Real Madrid', 'away' => 'Barcelona', 'home_score' => null, 'away_score' => null, 'status' => 'upcoming'],
    ['time' => '01:00', 'league' => 'Liga Italia', 'home' => 'Juventus', 'away' => 'AC Milan', 'home_score' => null, 'away_score' => null, 'status' => 'upcoming'],
];

// Hot games (top 4)
$hotGames = $site['hot_games'] ?? [
    ['code' => 'vs20starlight',    'name' => 'Starlight Princess',  'rating' => '4.9'],
    ['code' => 'vs20olympgate',    'name' => 'Gates of Olympus',    'rating' => '4.8'],
    ['code' => 'vswaysmahwblck',   'name' => 'Mahjong Ways 2',      'rating' => '4.7'],
    ['code' => 'vs10olymppop',     'name' => 'Sweet Bonanza',       'rating' => '4.6'],
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
        $isiTetap = '<p>' . e($namaSitus) . ' adalah situs informasi hiburan daring. Kami menyediakan ringkasan istilah permainan dan panduan dasar.</p>';
        $descTetap = 'Profil ' . $namaSitus . '.';
    } elseif ($slugMinta === 'kontak') {
        $isiTetap = '<p>Hubungi kami via:</p><ul><li>Live Chat: <a href="' . e($urlLc) . '">buka</a></li><li>WhatsApp: <a href="' . e($urlWa) . '">hubungi</a></li><li>Telegram: <a href="' . e($urlTele) . '">hubungi</a></li></ul>';
        $descTetap = 'Kontak ' . $namaSitus . '.';
    } else {
        $isiTetap = '<p>Disclaimer & batas usia 18+ berlaku di ' . e($namaSitus) . '.</p>';
        $descTetap = 'Disclaimer ' . $namaSitus . '.';
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

/* ---------- TAUTKAN NAMA SITUS ---------- */
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
                . '<a href="/" class="g-tautan-diri">' . e($asli) . '</a>'
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
$jsonLd = json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Random jackpot
$benih = (int)date('Ymd') + (int)sprintf('%u', crc32($domainName)) % 100000;
mt_srand($benih);
$jackpot = mt_rand(2_400_000_000, 9_800_000_000);
$jackpotTampil = number_format($jackpot, 0, ',', '.');
mt_srand();
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?= e($deskripsi) ?>">
<meta name="keywords" content="<?= e($namaSitus) ?>, <?= e($namaSitus) ?> Login, <?= e($namaSitus) ?> Slot, <?= e($namaSitus) ?> Gacor">
<meta name="robots" content="index,follow">
<meta name="og:site_name" content="<?= e($namaSitus) ?>">
<title><?= e($judul) ?></title>
<link rel="canonical" href="<?= e($urlKanonik) ?>">
<?php if ($ampUrl !== '' && $ampUrl !== '#'): ?>
<link rel="amphtml" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="id-id" href="<?= e($ampUrl) ?>">
<link rel="alternate" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="id" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="en" href="<?= e($ampUrl) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e($ampUrl) ?>">
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
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Roboto',sans-serif;background:#0a0a0a;color:#fff;line-height:1.5;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
img{max-width:100%;height:auto;display:block}
button{font-family:inherit;border:0;background:transparent;cursor:pointer}
input{font-family:inherit}

/* HEADER */
.main-header{background:linear-gradient(180deg,#1a1a2e 0,#0f0f1e 100%);position:sticky;top:0;z-index:100;box-shadow:0 2px 10px rgba(0,0,0,.5)}
.header-top{padding:8px 0;background:#000;border-bottom:1px solid rgba(255,255,255,.05)}
.header-top-inner{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;padding:0 15px;font-size:12px;color:#999}
.header-top-left{display:flex;align-items:center;gap:8px}
.header-flag{display:inline-flex;align-items:center;gap:5px}
.header-flag img{width:18px;height:12px;border-radius:2px}
.header-top-right{display:flex;align-items:center;gap:15px}
.nav-time{font-weight:700;color:#ffd700}
.header-mid{padding:12px 0;background:linear-gradient(90deg,#1a1a2e,#0f0f1e)}
.header-mid-inner{max-width:1200px;margin:0 auto;padding:0 15px;display:flex;align-items:center;justify-content:space-between;gap:15px}
.header-logo img{max-height:50px;width:auto;max-width:200px}
.header-right{display:flex;align-items:center;gap:8px}
.btn-currency{background:transparent;border:1px solid #444;color:#fff;padding:8px 12px;border-radius:5px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:5px}
.btn-login{background:#444;color:#fff;padding:8px 18px;border-radius:5px;font-weight:700;font-size:13px}
.btn-register{background:linear-gradient(90deg,#ff5722,#ff9800);color:#fff;padding:8px 18px;border-radius:5px;font-weight:700;font-size:13px;text-transform:uppercase}

/* NAVIGATION */
.header-navbar{background:#000;padding:10px 0;border-top:1px solid rgba(255,255,255,.05);overflow-x:auto}
.header-navbar::-webkit-scrollbar{display:none}
.nav-list{display:flex;list-style:none;gap:0;max-width:1200px;margin:0 auto;padding:0 15px;min-width:max-content}
.nav-item{padding:0}
.nav-item a{display:block;padding:10px 18px;color:#fff;font-size:13px;font-weight:700;text-transform:uppercase;transition:.2s;white-space:nowrap}
.nav-item a:hover,.nav-item a.active{background:#ff5722;color:#fff}

/* ANNOUNCEMENT BAR */
.announcement-bar{background:linear-gradient(90deg,#ff5722,#ff9800);padding:8px 0}
.announcement-inner{max-width:1200px;margin:0 auto;padding:0 15px;display:flex;align-items:center;gap:10px;color:#fff;font-size:13px;font-weight:600}
.announcement-title{background:#000;color:#ffd700;padding:4px 10px;border-radius:3px;font-size:11px;font-weight:900;text-transform:uppercase}
.announcement-content{flex:1;text-align:center}

/* HERO BANNER */
.hero-banner{position:relative;overflow:hidden;width:100%;margin-bottom:0}
.hero-banner img{width:100%;height:auto;display:block}
.hero-slider{position:relative}
.hero-slide{display:none;animation:fadeIn .8s}
.hero-slide.active{display:block}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}

/* JACKPOT */
.jackpot-ticker{background:linear-gradient(135deg,#cc0000,#ff5722);padding:15px;text-align:center;color:#fff;position:relative;overflow:hidden}
.jackpot-ticker::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent);animation:shimmer 2s infinite}
@keyframes shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
.jackpot-label{font-size:13px;color:#ffd700;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:5px}
.jackpot-amount{font-size:36px;font-weight:900;color:#fff;text-shadow:2px 2px 8px rgba(0,0,0,.5);font-family:'Roboto',sans-serif;letter-spacing:1px}

/* MAIN SECTIONS */
.main-section{padding:30px 15px;max-width:1200px;margin:0 auto}
.section-title{color:#ffd700;font-size:22px;font-weight:900;margin-bottom:20px;text-align:center;text-transform:uppercase;letter-spacing:1px}
.section-title::after{content:'';display:block;width:60px;height:3px;background:#ff5722;margin:10px auto 0}

/* BANK LIST */
.bank-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-bottom:20px}
.bank-item{background:#1a1a1e;border:1px solid #333;border-radius:8px;padding:12px;display:flex;align-items:center;gap:10px;transition:.2s}
.bank-item:hover{border-color:#ff5722}
.bank-icon{width:36px;height:36px;background:#222;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;color:#ffd700;flex-shrink:0}
.bank-info{flex:1;min-width:0}
.bank-name{font-size:13px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.bank-status{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;display:inline-block;padding:2px 6px;border-radius:3px;margin-top:3px}
.bank-status.online{background:#1a4d1a;color:#4ade80}
.bank-status.offline{background:#4d1a1a;color:#ff6b6b}

/* PROVIDERS */
.provider-section{margin-bottom:20px}
.provider-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px}
.provider-card{background:linear-gradient(135deg,#1a1a2e,#0f0f1e);border:1px solid #333;border-radius:8px;padding:15px 10px;text-align:center;color:#ffd700;font-weight:700;font-size:11px;text-transform:uppercase;cursor:pointer;transition:.2s}
.provider-card:hover{border-color:#ff5722;color:#fff;transform:translateY(-2px)}

/* HOT GAMES */
.games-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px}
.game-card{background:linear-gradient(135deg,#1a1a2e,#0f0f1e);border:1px solid #333;border-radius:8px;overflow:hidden;cursor:pointer;transition:.2s}
.game-card:hover{border-color:#ff5722;transform:translateY(-2px)}
.game-thumb{width:100%;height:140px;background:#222;overflow:hidden;display:flex;align-items:center;justify-content:center}
.game-thumb img{width:100%;height:100%;object-fit:cover}
.game-info{padding:10px}
.game-name{font-size:12px;color:#fff;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.game-rating{color:#ffd700;font-size:11px;margin-top:3px}

/* FOOTBALL SCHEDULE */
.football-schedule{margin-bottom:20px}
.schedule-list{display:flex;flex-direction:column;gap:8px}
.schedule-item{background:#1a1a1e;border:1px solid #333;border-radius:8px;padding:12px 15px;display:flex;align-items:center;justify-content:space-between;gap:15px}
.schedule-time{font-weight:700;color:#ffd700;font-size:14px;min-width:60px}
.schedule-info{flex:1;text-align:center}
.schedule-league{font-size:11px;color:#999;text-transform:uppercase;letter-spacing:.5px}
.schedule-match{font-size:14px;font-weight:700;margin-top:3px}
.schedule-score{font-size:18px;font-weight:900;color:#fff;min-width:60px;text-align:right}
.schedule-status{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;display:inline-block;padding:2px 8px;border-radius:3px;margin-top:3px}
.schedule-status.live{background:#cc0000;color:#fff;animation:pulse 1.5s infinite}
.schedule-status.upcoming{background:#333;color:#ccc}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}

/* ARTICLE */
.article-section{background:#1a1a1e;border:1px solid #333;border-radius:10px;padding:25px 20px;margin-bottom:20px;line-height:1.7;color:#ddd;font-size:14px}
.article-section h1{color:#ffd700;font-size:24px;margin-bottom:15px}
.article-section h2{color:#fff;font-size:18px;margin:25px 0 12px;padding-left:12px;border-left:4px solid #ff5722}
.article-section h3{color:#ffd700;font-size:15px;margin:20px 0 8px}
.article-section p{margin-bottom:15px;text-align:justify}
.article-section ul,.article-section ol{margin:0 0 15px 20px}
.article-section li{margin-bottom:5px}
.article-section strong{color:#fff}
.article-section a{color:#ff5722;text-decoration:underline}
.article-breadcrumb{font-size:12px;color:#999;margin-bottom:12px}
.article-breadcrumb a{color:#999}
.article-meta{font-size:12px;color:#999;margin-bottom:20px}

/* FLOATING CTA */
.floating-cta{position:fixed;bottom:0;left:0;right:0;z-index:90;background:linear-gradient(180deg,transparent 0,#000 30%);padding:12px 10px 10px;display:flex;gap:8px}
.floating-cta a{flex:1;text-align:center;padding:12px;border-radius:5px;font-weight:700;font-size:13px;text-transform:uppercase}
.cta-livechat{background:#cc0000;color:#fff}
.cta-login{background:#444;color:#fff}
.cta-daftar{background:linear-gradient(90deg,#ff5722,#ff9800);color:#fff}

/* LIVE CHAT WIDGET */
.livechat-widget{position:fixed;bottom:80px;right:15px;width:50px;height:50px;background:#ff5722;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;box-shadow:0 4px 12px rgba(255,87,34,.5);z-index:89;cursor:pointer}
.livechat-pulse{position:absolute;top:5px;right:5px;width:10px;height:10px;background:#4ade80;border-radius:50%;animation:pulse 1.5s infinite}

/* FOOTER */
.main-footer{background:#000;padding:30px 15px 100px;border-top:2px solid #ff5722;margin-top:30px}
.footer-wrapper{max-width:1200px;margin:0 auto}
.footer-top{display:flex;flex-direction:column;gap:20px;padding-bottom:20px;border-bottom:1px solid #333}
.footer-title{color:#ffd700;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:10px}
.footer-nav{display:flex;flex-wrap:wrap;gap:8px}
.footer-nav a{color:#999;font-size:13px;padding:4px 0}
.footer-nav a:hover{color:#ffd700}
.footer-service{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:8px}
.footer-service a{background:#1a1a1e;border:1px solid #333;padding:8px;text-align:center;color:#ffd700;font-size:11px;border-radius:5px;font-weight:700}
.footer-disclaimer{color:#999;font-size:12px;line-height:1.6;text-align:center;max-width:900px;margin:20px auto;padding:0 15px}
.footer-18{text-align:center;color:#ff5722;font-size:18px;font-weight:900;margin:20px 0;letter-spacing:2px}
.footer-copyright{text-align:center;color:#666;font-size:11px;padding-top:20px;border-top:1px solid #222;margin-top:20px}
.footer-copyright a{color:#ffd700}
.footer-mobile{padding:20px 15px;text-align:center;background:#000;border-top:1px solid #222;display:none}

/* RESPONSIVE */
@media(min-width:768px){
.hero-banner img{max-height:520px;object-fit:cover}
}
@media(max-width:767px){
.header-top-right .header-flag{display:none}
.header-top-inner{font-size:11px}
.btn-currency{display:none}
.header-mid-inner{flex-wrap:wrap}
.btn-login,.btn-register{padding:6px 12px;font-size:12px}
.nav-item a{padding:8px 12px;font-size:11px}
.bank-grid{grid-template-columns:repeat(2,1fr)}
.provider-grid{grid-template-columns:repeat(3,1fr)}
.games-grid{grid-template-columns:repeat(2,1fr)}
.schedule-item{flex-wrap:wrap}
}
@media(max-width:480px){
.provider-grid{grid-template-columns:repeat(2,1fr)}
.games-grid{grid-template-columns:repeat(2,1fr)}
}
</style>
</head>
<body>

<!-- HEADER TOP -->
<div class="header-top">
  <div class="header-top-inner">
    <div class="header-top-left">
      <span class="header-flag"><img src="<?= e($baseUrl) ?>/assets/flag-id.png" alt="ID" width="18" height="12"> ID</span>
      <span>Indonesia</span>
    </div>
    <div class="header-top-right">
      <span class="nav-time"><i class="fas fa-clock"></i> <span id="headerTime">--:--:--</span></span>
    </div>
  </div>
</div>

<!-- HEADER MID -->
<header class="main-header">
  <div class="header-mid">
    <div class="header-mid-inner">
      <a class="header-logo" href="/" aria-label="Beranda <?= e($namaSitus) ?>">
        <img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" loading="eager">
      </a>
      <div class="header-right">
        <button class="btn-currency"><i class="fas fa-coins"></i> <?= e($currency) ?></button>
        <a class="btn-login" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Login</a>
        <a class="btn-register" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
      </div>
    </div>
  </div>

  <!-- MAIN NAVIGATION -->
  <nav class="header-navbar" aria-label="Navigasi utama">
    <ul class="nav-list">
      <li class="nav-item"><a href="/"<?= $isBeranda ? ' class="active"' : '' ?>>Beranda</a></li>
      <li class="nav-item"><a href="/promotion">Promosi</a></li>
      <li class="nav-item"><a href="/slots">Slot</a></li>
      <li class="nav-item"><a href="/livecasino">Live Casino</a></li>
      <li class="nav-item"><a href="/sportsbook">Sportsbook</a></li>
      <li class="nav-item"><a href="/togel">Togel</a></li>
      <li class="nav-item"><a href="/fishing">Fishing</a></li>
      <li class="nav-item"><a href="/tentang-kami"<?= ($isTetap && $slugMinta === 'tentang-kami') ? ' class="active"' : '' ?>>Tentang Kami</a></li>
      <li class="nav-item"><a href="/kontak"<?= ($isTetap && $slugMinta === 'kontak') ? ' class="active"' : '' ?>>Kontak</a></li>
      <li class="nav-item"><a href="/disclaimer"<?= ($isTetap && $slugMinta === 'disclaimer') ? ' class="active"' : '' ?>>Disclaimer</a></li>
    </ul>
  </nav>
</header>

<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar">
  <div class="announcement-inner">
    <span class="announcement-title">INFO</span>
    <span class="announcement-content">🎉 PROMO SPESIAL — Bonus New Member 100%! Daftar sekarang &amp; klaim bonus Anda</span>
  </div>
</div>

<main>

<?php if ($isBeranda): ?>

<!-- HERO BANNER SLIDER -->
<section class="hero-banner">
  <div class="hero-slider">
    <div class="hero-slide active">
      <img src="<?= e($banner1) ?>" alt="Promo <?= e($namaSitus) ?>" width="1200" height="500" fetchpriority="high">
    </div>
    <div class="hero-slide">
      <img src="<?= e($banner2) ?>" alt="Promo <?= e($namaSitus) ?>" 2" width="1200" height="500" loading="lazy">
    </div>
  </div>
</section>

<!-- JACKPOT TICKER -->
<section class="jackpot-ticker">
  <div class="jackpot-label">🎰 TOTAL JACKPOT HARI INI 🎰</div>
  <div class="jackpot-amount">IDR <?= e($jackpotTampil) ?></div>
</section>

<!-- BANK LIST -->
<section class="main-section">
  <h2 class="section-title">💳 Metode Pembayaran</h2>
  <div class="bank-grid">
    <?php foreach ($bankList as $bank): ?>
      <div class="bank-item">
        <div class="bank-icon"><?= e(strtoupper(substr($bank['name'], 0, 2))) ?></div>
        <div class="bank-info">
          <div class="bank-name"><?= e($bank['name']) ?></div>
          <div class="bank-status <?= $bank['status'] ?>"><?= e(strtoupper($bank['status'])) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- SLOT PROVIDERS -->
<section class="main-section provider-section">
  <h2 class="section-title">🎰 Provider Slot</h2>
  <div class="provider-grid">
    <?php foreach ($providersSlot as $p): ?>
      <div class="provider-card"><?= e($p) ?></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- HOT GAMES -->
<section class="main-section">
  <h2 class="section-title">🔥 Game Populer</h2>
  <div class="games-grid">
    <?php foreach ($hotGames as $hg): ?>
      <div class="game-card">
        <div class="game-thumb">
          <img src="<?= e($banner1) ?>" alt="<?= e($hg['name']) ?>" loading="lazy">
        </div>
        <div class="game-info">
          <div class="game-name"><?= e($hg['name']) ?></div>
          <div class="game-rating">⭐ <?= e($hg['rating']) ?> / 5.0</div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- LIVE CASINO PROVIDERS -->
<section class="main-section provider-section">
  <h2 class="section-title">🎲 Live Casino</h2>
  <div class="provider-grid">
    <?php foreach ($providersLive as $p): ?>
      <div class="provider-card"><?= e($p) ?></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- SPORTSBOOK PROVIDERS -->
<section class="main-section provider-section">
  <h2 class="section-title">⚽ Sportsbook</h2>
  <div class="provider-grid">
    <?php foreach ($providersSport as $p): ?>
      <div class="provider-card"><?= e($p) ?></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- FOOTBALL SCHEDULE -->
<section class="main-section football-schedule">
  <h2 class="section-title">⚽ Jadwal Sepak Bola Hari Ini</h2>
  <div class="schedule-list">
    <?php foreach ($footballSchedule as $match): ?>
      <div class="schedule-item">
        <div class="schedule-time"><?= e($match['time']) ?></div>
        <div class="schedule-info">
          <div class="schedule-league"><?= e($match['league']) ?></div>
          <div class="schedule-match"><?= e($match['home']) ?> vs <?= e($match['away']) ?></div>
          <span class="schedule-status <?= e($match['status']) ?>"><?= e(strtoupper($match['status'])) ?></span>
        </div>
        <div class="schedule-score">
          <?php if ($match['status'] === 'live'): ?>
            <strong><?= e($match['home_score']) ?> - <?= e($match['away_score']) ?></strong>
          <?php else: ?>
            <small>vs</small>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php endif; ?>

<!-- ARTICLE CONTENT -->
<section class="main-section">
  <div class="article-section">
    <?php if (!$isBeranda): ?>
    <nav class="article-breadcrumb"><a href="/">Beranda</a> &rsaquo; <span><?= e($h1) ?></span></nav>
    <h1><?= e($h1) ?></h1>
    <?php endif; ?>
    <p class="article-meta">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
    <div class="article-content"><?= $isiArtikel ?></div>
  </div>
</section>

</main>

<!-- FOOTER -->
<footer class="main-footer">
  <div class="footer-wrapper">

    <div class="footer-top">
      <div>
        <h3 class="footer-title">Tentang Kami</h3>
        <p style="color:#999;font-size:13px;line-height:1.6"><?= e($namaSitus) ?> adalah situs informasi hiburan daring. Kami menyediakan ringkasan istilah, panduan, dan jadwal pertandingan untuk pembaca 18+.</p>
      </div>

      <div>
        <h3 class="footer-title">Halaman</h3>
        <div class="footer-nav">
          <a href="/">Beranda</a>
          <a href="/tentang-kami">Tentang Kami</a>
          <a href="/kontak">Kontak</a>
          <a href="/disclaimer">Disclaimer</a>
        </div>
      </div>

      <div>
        <h3 class="footer-title">Alt Domains</h3>
        <div class="footer-nav">
          <?php foreach ($altDomains as $ad): ?>
            <a href="<?= e($ad) ?>"><?= e(parse_url($ad, PHP_URL_HOST) ?: $ad) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <h3 class="footer-title" style="text-align:center;margin-top:20px">Hubungi Kami</h3>
    <div class="footer-service">
      <a href="<?= e($urlLc) ?>" rel="nofollow noopener" target="_blank">Live Chat</a>
      <a href="<?= e($urlWa) ?>" rel="nofollow noopener" target="_blank">WhatsApp</a>
      <a href="<?= e($urlTele) ?>" rel="nofollow noopener" target="_blank">Telegram</a>
      <a href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
      <a href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Login</a>
      <a href="<?= e($urlLc) ?>" rel="nofollow noopener" target="_blank">Bantuan</a>
    </div>

    <p class="footer-disclaimer">
      <strong style="color:#ffd700"><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Konten bersifat informasional & edukatif, bukan jaminan hasil maupun nasihat keuangan. Konten terakhir diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time>.
    </p>

    <div class="footer-18">🔞 18+</div>

    <div class="footer-copyright">
      © <?= e(date('Y')) ?> <?= e($namaSitus) ?>. All Rights Reserved. | <a href="<?= e($urlLc) ?>" rel="nofollow noopener">Live Chat</a> | <a href="<?= e($urlWa) ?>" rel="nofollow noopener">WhatsApp</a> | <a href="<?= e($urlTele) ?>" rel="nofollow noopener">Telegram</a>
    </div>
  </div>
</footer>

<!-- FLOATING LIVE CHAT -->
<div class="livechat-widget" onclick="window.open('<?= e($urlLc) ?>', '_blank')">
  <i class="fas fa-comments"></i>
  <span class="livechat-pulse"></span>
</div>

<!-- FLOATING CTA BOTTOM -->
<div class="floating-cta">
  <a class="cta-livechat" href="<?= e($urlLc) ?>" rel="nofollow noopener" target="_blank">Live Chat</a>
  <a class="cta-login" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Masuk</a>
  <a class="cta-daftar" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
</div>

<script type="application/ld+json"><?= $jsonLd ?></script>
<script>
// Hero slider auto-rotate
(function(){
  var slides = document.querySelectorAll('.hero-slide');
  if (slides.length > 1) {
    var i = 0;
    setInterval(function(){
      slides[i].classList.remove('active');
      i = (i + 1) % slides.length;
      slides[i].classList.add('active');
    }, 5000);
  }
  // Header clock
  function tick(){
    var d = new Date();
    var pad = function(n){ return n < 10 ? '0' + n : '' + n; };
    var s = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
    var el = document.getElementById('headerTime');
    if (el) el.textContent = s;
  }
  tick(); setInterval(tick, 1000);
})();
</script>
</body>
</html>
