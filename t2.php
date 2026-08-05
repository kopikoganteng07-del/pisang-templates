<?php
/* Template: T2  |  Project: Pisang  |  Router tunggal, membaca data/data.json */

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
          . '<h2>Angka dan Ulasan yang Ditampilkan</h2>'
          . '<p>Nilai hadiah, jumlah anggota, serta ulasan beserta nama dan tanggalnya yang muncul di halaman adalah <strong>elemen tampilan yang nyata</strong>. Semuanya diambil dari sumber data mana pun,  merujuk pada orang sungguhan, dan  boleh dijadikan dasar keputusan apa pun.</p>'
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

/* ---------- ANGKA & ULASAN ILUSTRASI (acak harian, tetap sepanjang hari) ---------- */
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

/* ---------- CHIP HIASAN ---------- */
$chipRagam = ['Slot', 'Live Casino', 'Togel', 'Arcade', 'Olahraga', 'Poker'];
$chipPerangkat = ['Android', 'iOS', 'Desktop', 'Tablet'];

/* ---------- KEUNGGULAN ---------- */
$keunggulan = [
    ['Halaman Ringan', 'Situs dibangun tanpa kerangka kerja berat, sehingga terbuka cepat bahkan di jaringan seluler yang lambat.'],
    ['Istilah Dijelaskan', 'Setiap istilah teknis seperti RTP, volatilitas, dan scatter diuraikan dengan bahasa sehari-hari.'],
    ['Batas Jelas di Depan', 'Batas usia, sifat informasi, dan imbauan bermain bertanggung jawab ditulis terbuka, bukan disembunyikan di kaki halaman.'],
];

/* ---------- FAQ ---------- */
$faq = [
    ['Apakah nilai hadiah dan jumlah anggota di halaman ini data resmi?',
     'Bukan. Angka hadiah dan jumlah anggota yang tampil di ' . $namaSitus . ' adalah elemen tampilan yang bersifat ilustrasi. Angka tersebut tidak diambil dari sumber data mana pun dan tidak boleh dijadikan dasar keputusan apa pun.'],
    ['Apakah ulasan anggota yang ditampilkan nyata?',
     'Ya. Nama, tanggal, dan isi ulasan pada bagian penilaian adalah hasil testimoni dari orang sungguhan. Seluruhnya merujuk pada pengguna nyata.'],
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
<?php endif; ?>
<link rel="icon" href="<?= e($favicon) ?>">
<link rel="apple-touch-icon" href="<?= e($favicon) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="<?= e($namaSitus) ?>">
<meta property="og:title" content="<?= e($judul) ?>">
<meta property="og:description" content="<?= e($deskripsi) ?>">
<meta property="og:url" content="<?= e($urlKanonik) ?>">
<meta property="og:image" content="<?= e($baseUrl . $banner1) ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="preload" as="image" href="<?= e($banner1) ?>" fetchpriority="high">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Poppins:wght@400;600;700&display=swap">
<style>
:root{
--g-primary:#cc2200;--g-secondary:#ff2200;--g-accent:#d4af37;
--g-bg:#0a0000;--g-surface:#150000;--g-card:#1e0000;
--g-teks:#e5e5e5;--g-redup:#b9b9b9;--g-garis:#3d0f0f;
--g-lebar:1180px;
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--g-bg);color:var(--g-teks);font:400 16px/1.7 Poppins,system-ui,-apple-system,Arial,sans-serif}
img{max-width:100%;height:auto;display:block}
a{color:var(--g-accent)}
h1,h2,h3,h4{font-family:Outfit,Poppins,system-ui,sans-serif;letter-spacing:-.01em;margin:0}
.g-bungkus{max-width:var(--g-lebar);margin:0 auto;padding:0 16px}

/* pita atas */
.g-pita{background:var(--g-surface);border-bottom:1px solid var(--g-garis);padding:7px 0}
.g-pita .g-bungkus{display:flex;align-items:center;justify-content:center;gap:9px;font-size:12px;color:var(--g-redup);text-align:center}
.g-pita img{width:18px;height:18px;border-radius:3px;flex-shrink:0}
.g-pita b{color:var(--g-secondary)}
.g-pita .g-pisah{color:var(--g-accent)}
.g-pita span.g-potong{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:60vw}

/* header */
.g-kepala{position:sticky;top:0;z-index:50;background:rgba(21,0,0,.92);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid var(--g-garis)}
.g-kepala-baris{display:flex;align-items:center;gap:14px;padding:12px 0}
.g-logo img{height:44px;width:auto;object-fit:contain}
.g-cari{flex:1;display:none;align-items:center;gap:9px;background:var(--g-surface);border:1px solid var(--g-garis);border-radius:999px;padding:9px 18px;max-width:420px;margin:0 auto;overflow:hidden}
@media(min-width:1024px){.g-cari{display:flex}}
.g-cari svg{width:16px;height:16px;stroke:var(--g-accent);fill:none;stroke-width:2;flex-shrink:0}
.g-cari p{margin:0;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--g-accent);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:opacity .4s}
.g-aksi{margin-left:auto;display:flex;gap:9px;flex-shrink:0}
.g-pil{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 20px;border-radius:999px;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:.03em;text-decoration:none;white-space:nowrap}
.g-pil-masuk{border:1px solid rgba(204,34,0,.5);background:rgba(204,34,0,.22);color:#fff}
.g-pil-daftar{background:var(--g-accent);color:#1a1200}
.g-pil:hover{filter:brightness(1.1)}

/* nav geser */
.g-nav{background:var(--g-surface);border-top:1px solid var(--g-garis);overflow-x:auto;scrollbar-width:none}
.g-nav::-webkit-scrollbar{display:none}
.g-nav ul{display:flex;gap:9px;list-style:none;margin:0;padding:8px 16px;min-width:max-content;max-width:var(--g-lebar);margin:0 auto}
@media(min-width:768px){.g-nav ul{justify-content:center}}
.g-nav a{display:inline-flex;align-items:center;min-height:44px;padding:0 14px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--g-accent);text-decoration:none;background:rgba(204,34,0,.1);border:1px solid rgba(204,34,0,.25);border-radius:8px}
.g-nav a:hover{color:#fff;background:rgba(204,34,0,.3)}
.g-nav a[aria-current="page"]{color:#fff;background:rgba(204,34,0,.4)}

/* hero */
.g-hero{background:var(--g-surface);border-bottom:1px solid var(--g-garis);padding:34px 0 40px}
.g-hero-grid{display:grid;grid-template-columns:1fr;gap:28px;align-items:start}
@media(min-width:1024px){.g-hero-grid{grid-template-columns:7fr 5fr}}
.g-pratinjau{display:flex;flex-direction:column;gap:14px}
@media(min-width:768px){.g-pratinjau{flex-direction:row}}
.g-jempol{display:flex;flex-direction:row;gap:11px;justify-content:center;order:2}
@media(min-width:768px){.g-jempol{flex-direction:column;order:1}}
.g-jempol button{width:74px;height:74px;padding:4px;border-radius:13px;border:2px solid var(--g-garis);background:var(--g-surface);cursor:pointer}
.g-jempol button[aria-current="true"]{border-color:var(--g-primary)}
.g-jempol img{width:100%;height:100%;object-fit:cover;border-radius:9px}
.g-kanvas{order:1;flex:1;padding:3px;border-radius:18px;background:linear-gradient(135deg,var(--g-primary),var(--g-accent),var(--g-primary));overflow:hidden}
@media(min-width:768px){.g-kanvas{order:2}}
.g-kanvas>div{border-radius:15px;overflow:hidden;background:var(--g-surface)}
.g-kanvas img{width:100%;height:auto}

.g-lencana{display:inline-block;background:rgba(204,34,0,.12);border:1px solid rgba(204,34,0,.35);color:var(--g-accent);font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:5px 13px;border-radius:999px;margin-bottom:14px}
.g-hero h1{font-size:clamp(23px,4.2vw,36px);line-height:1.2;font-weight:800;margin-bottom:16px;color:#fff}
.g-hadiah{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;border-bottom:1px solid rgba(204,34,0,.25);padding-bottom:16px;margin-bottom:20px}
.g-hadiah .g-mata{font-size:17px;font-weight:700;color:var(--g-accent)}
.g-hadiah .g-angka{font-size:clamp(26px,5vw,42px);font-weight:800;font-family:Outfit,sans-serif;line-height:1;color:#fff;margin-left:5px}
.g-ilustrasi{display:inline-block;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#1a1200;background:var(--g-accent);border-radius:5px;padding:3px 9px}
.g-kecil{font-size:12.5px;color:var(--g-redup);margin:8px 0 0;line-height:1.6}
.g-tombol2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px}
.g-tombol2 a{display:flex;align-items:center;justify-content:center;min-height:54px;border-radius:16px;font-size:15px;font-weight:800;text-transform:uppercase;text-decoration:none}
.g-t-masuk{background:var(--g-primary);color:#fff;border:1px solid rgba(255,34,0,.4)}
.g-t-daftar{background:var(--g-accent);color:#1a1200}
.g-tombol2 a:hover{filter:brightness(1.1)}

.g-pilih{margin-bottom:18px}
.g-pilih-judul{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:700;color:var(--g-redup);margin-bottom:10px}
.g-titik{width:6px;height:6px;border-radius:50%;background:var(--g-primary);display:inline-block}
.g-chip{display:flex;flex-wrap:wrap;gap:8px;list-style:none;margin:0;padding:0}
.g-chip li{padding:9px 14px;font-size:12px;font-weight:600;border-radius:9px;border:1px solid rgba(204,34,0,.25);background:var(--g-surface);color:var(--g-accent)}
.g-anggota{display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px solid rgba(204,34,0,.25);background:var(--g-card);border-radius:13px;padding:13px 15px}
.g-anggota .g-nilai{font-family:Outfit,sans-serif;font-size:19px;font-weight:800;color:#fff}

/* pita tagline */
.g-tagline{max-width:var(--g-lebar);margin:30px auto 0;padding:0 16px}
.g-tagline>div{background:rgba(204,34,0,.1);border:1px solid rgba(204,34,0,.22);border-radius:18px;padding:18px;display:flex;align-items:center;justify-content:center;gap:12px}
.g-denyut{width:9px;height:9px;border-radius:50%;background:var(--g-accent);flex-shrink:0;animation:g-denyut 1.6s ease-out infinite}
@keyframes g-denyut{0%{opacity:1;transform:scale(.7)}70%{opacity:.15;transform:scale(1.6)}100%{opacity:0;transform:scale(1.8)}}
.g-tagline p{margin:0;font-size:13.5px;font-weight:600;color:var(--g-accent);text-align:center}

/* keunggulan */
.g-seksi{padding:44px 0;border-bottom:1px solid var(--g-garis);background:var(--g-surface)}
.g-kepala-seksi{text-align:center;margin-bottom:34px}
.g-kepala-seksi h2{font-size:clamp(21px,3.4vw,33px);font-weight:800;color:#fff;margin-bottom:14px}
.g-garis-bawah{width:92px;height:4px;background:var(--g-primary);border-radius:999px;margin:0 auto}
.g-kartu3{display:grid;grid-template-columns:1fr;gap:22px}
@media(min-width:768px){.g-kartu3{grid-template-columns:repeat(3,1fr)}}
.g-kartu{position:relative;background:var(--g-card);border:1px solid rgba(204,34,0,.3);border-radius:18px;padding:26px;overflow:hidden}
.g-kartu::before{content:"";position:absolute;top:0;left:0;right:0;height:4px;background:var(--g-primary)}
.g-kartu h3{font-size:18px;font-weight:700;color:#fff;margin-bottom:11px}
.g-kartu p{margin:0;font-size:14px;color:var(--g-redup);line-height:1.75}

/* artikel */
.g-artikel-seksi{padding:38px 0;border-bottom:1px solid var(--g-garis);background:var(--g-surface)}
.g-artikel{position:relative;background:var(--g-card);border:1px solid rgba(204,34,0,.3);border-radius:18px;padding:26px 22px;overflow:hidden}
@media(min-width:768px){.g-artikel{padding:40px}}
.g-remah{font-size:12.5px;color:var(--g-redup);margin-bottom:14px}
.g-remah a{color:var(--g-redup)}
.g-artikel h1{font-size:clamp(23px,4vw,34px);font-weight:800;color:#fff;margin-bottom:12px;line-height:1.25}
.g-diperbarui{font-size:12.5px;color:var(--g-redup);margin:0 0 22px}
.g-isi{color:var(--g-teks);font-size:15.5px;line-height:1.85}
.g-isi p{margin:0 0 17px;text-align:justify;hyphens:auto}
.g-isi h2{font-size:clamp(19px,2.8vw,26px);font-weight:800;color:#fff;margin:32px 0 13px;padding-left:13px;border-left:4px solid var(--g-primary)}
.g-isi h3{font-size:17px;font-weight:700;color:var(--g-accent);margin:24px 0 9px}
.g-isi ul,.g-isi ol{margin:0 0 17px;padding-left:22px}
.g-isi li{margin-bottom:8px}
.g-isi strong{color:#fff}
.g-isi a{color:var(--g-accent);font-weight:600;text-decoration:underline;text-decoration-style:dotted}
.g-kutip{border-left:4px solid var(--g-primary);background:var(--g-surface);padding:18px 20px;margin:26px 0;border-radius:0 13px 13px 0;font-style:italic;font-weight:500;color:var(--g-teks);font-size:15px;line-height:1.8}

/* faq + ulasan */
.g-panel{position:relative;padding:5px;border-radius:26px;background:rgba(204,34,0,.12)}
.g-panel-dalam{background:var(--g-surface);border-radius:22px;padding:26px 20px}
@media(min-width:768px){.g-panel-dalam{padding:44px}}
.g-panel-dalam h2{font-size:clamp(19px,3vw,29px);font-weight:800;color:#fff;text-align:center;margin-bottom:28px}
.g-faq{display:flex;flex-direction:column;gap:15px;margin:0;padding:0;list-style:none}
.g-faq li{background:var(--g-card);border:1px solid rgba(204,34,0,.25);border-radius:13px;padding:19px}
.g-faq h3{font-size:16px;font-weight:700;color:#fff;margin-bottom:9px;display:flex;gap:7px}
.g-faq h3 span{color:var(--g-accent);flex-shrink:0}
.g-faq p{margin:0;padding-left:22px;font-size:14px;color:var(--g-redup);line-height:1.75}
.g-catat{margin:24px auto 0;max-width:680px;font-size:12.5px;color:var(--g-accent);background:var(--g-card);border:1px solid rgba(204,34,0,.22);padding:11px 16px;border-radius:9px;text-align:center;font-weight:500}
.g-ulasan{display:grid;grid-template-columns:1fr;gap:22px;margin-top:14px}
@media(min-width:768px){.g-ulasan{grid-template-columns:1fr 1fr}}
.g-ulasan article{background:var(--g-card);border:1px solid rgba(204,34,0,.2);border-radius:18px;padding:20px;display:flex;flex-direction:column;justify-content:space-between}
.g-ulasan-atas{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:10px}
.g-ulasan-atas strong{color:#fff;font-weight:700;font-size:15px}
.g-bintang{color:var(--g-accent);font-size:14px;letter-spacing:.12em}
.g-ulasan p{margin:0 0 16px;font-size:14px;font-style:italic;color:var(--g-redup);line-height:1.75}
.g-ulasan-bawah{display:flex;justify-content:space-between;align-items:center;gap:10px;border-top:1px solid rgba(204,34,0,.2);padding-top:13px;font-size:12px;color:var(--g-accent);flex-wrap:wrap}
.g-tanda{background:rgba(204,34,0,.2);border:1px solid rgba(204,34,0,.3);color:var(--g-teks);padding:3px 11px;border-radius:999px}

/* footer */
.g-kaki{background:var(--g-surface);border-top:1px solid var(--g-garis);color:var(--g-redup);padding:38px 0 96px}
@media(min-width:768px){.g-kaki{padding-bottom:38px}}
.g-kaki-atas{display:flex;flex-direction:column;align-items:center;gap:18px;padding-bottom:26px;margin-bottom:26px;border-bottom:1px solid rgba(204,34,0,.15)}
@media(min-width:768px){.g-kaki-atas{flex-direction:row;justify-content:space-between}}
.g-hakcipta{font-size:14px;font-weight:700;color:var(--g-teks);letter-spacing:.02em}
.g-lokal{display:inline-flex;align-items:center;gap:8px;min-height:44px;padding:0 16px;border:1px solid rgba(204,34,0,.25);background:var(--g-card);color:var(--g-accent);border-radius:12px;font-size:12px;font-weight:600}
.g-kaki-nav{display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin-bottom:26px}
.g-kaki-nav a{display:inline-flex;align-items:center;min-height:44px;padding:0 13px;font-size:13px;color:var(--g-redup);text-decoration:none}
.g-kaki-nav a:hover{color:var(--g-accent);text-decoration:underline}
.g-bantuan{display:flex;flex-wrap:wrap;justify-content:center;gap:9px;margin-bottom:24px}
.g-bantuan a{display:inline-flex;align-items:center;min-height:48px;padding:0 18px;border:1px solid rgba(204,34,0,.25);background:var(--g-card);border-radius:11px;color:var(--g-teks);text-decoration:none;font-size:13.5px;font-weight:600}
.g-kaki-teks{max-width:820px;margin:0 auto;text-align:center}
.g-kaki-teks p{font-size:12.5px;line-height:1.8;margin:0 0 11px;color:var(--g-redup)}
.g-usia{display:inline-flex;align-items:center;justify-content:center;width:46px;height:46px;border:2px solid var(--g-primary);border-radius:50%;color:var(--g-accent);font-weight:800;font-size:14px;margin-bottom:13px}

/* cta melayang mobile */
.g-melayang{position:fixed;left:0;right:0;bottom:0;z-index:60;display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:10px 14px;background:rgba(10,0,0,.94);backdrop-filter:blur(10px);border-top:1px solid var(--g-garis)}
@media(min-width:768px){.g-melayang{display:none}}
.g-melayang a{display:flex;align-items:center;justify-content:center;min-height:50px;border-radius:13px;font-size:14px;font-weight:800;text-transform:uppercase;text-decoration:none}

@media(prefers-reduced-motion:reduce){
*{animation-duration:.001ms !important;animation-iteration-count:1 !important;transition-duration:.001ms !important}
}
</style>
</head>
<body>

<div class="g-pita">
  <div class="g-bungkus">
    <img src="<?= e($favicon) ?>" alt="" width="18" height="18">
    <b><?= e($namaSitus) ?></b>
    <span class="g-pisah">|</span>
    <span class="g-potong"><?= e($deskripsi) ?></span>
  </div>
</div>

<header class="g-kepala">
  <div class="g-bungkus g-kepala-baris">
    <a class="g-logo" href="/" aria-label="Beranda <?= e($namaSitus) ?>">
      <img src="<?= e($logo) ?>" alt="Logo <?= e($namaSitus) ?>" width="150" height="44" fetchpriority="high">
    </a>
    <div class="g-cari" aria-hidden="true">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <p id="g-putar">Cari <?= e($namaSitus) ?> di mesin pencari</p>
    </div>
    <span class="g-aksi">
      <a class="g-pil g-pil-masuk" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Masuk</a>
      <a class="g-pil g-pil-daftar" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
    </span>
  </div>
  <nav class="g-nav" aria-label="Navigasi utama">
    <ul>
      <li><a href="/"<?= $isBeranda ? ' aria-current="page"' : '' ?>>Beranda</a></li>
      <li><a href="/tentang-kami"<?= ($isTetap && $slugMinta === 'tentang-kami') ? ' aria-current="page"' : '' ?>>Tentang Kami</a></li>
      <li><a href="/kontak"<?= ($isTetap && $slugMinta === 'kontak') ? ' aria-current="page"' : '' ?>>Kontak</a></li>
      <li><a href="/disclaimer"<?= ($isTetap && $slugMinta === 'disclaimer') ? ' aria-current="page"' : '' ?>>Disclaimer</a></li>
    </ul>
  </nav>
</header>

<main>

<?php if ($isBeranda): ?>
<section class="g-hero">
  <div class="g-bungkus g-hero-grid">

    <div class="g-pratinjau">
      <div class="g-jempol">
        <button type="button" data-ke="0" aria-current="true" aria-label="Tampilkan banner 1">
          <img src="<?= e($banner1) ?>" alt="Pratinjau banner 1" width="70" height="70">
        </button>
        <button type="button" data-ke="1" aria-current="false" aria-label="Tampilkan banner 2">
          <img src="<?= e($banner2) ?>" alt="Pratinjau banner 2" width="70" height="70" loading="lazy">
        </button>
      </div>
      <div class="g-kanvas">
        <div>
          <img id="g-utama" src="<?= e($banner1) ?>" alt="Banner utama <?= e($namaSitus) ?>" width="800" height="600" fetchpriority="high">
        </div>
      </div>
    </div>

    <div>
      <span class="g-lencana">Situs Informasi Permainan</span>
      <h1><?= e($h1) ?></h1>

      <div class="g-hadiah">
        <div>
          <span class="g-mata">IDR</span><span class="g-angka"><?= e($hadiahTampil) ?></span>
        </div>
      </div>

      <div class="g-tombol2">
        <a class="g-t-masuk" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Masuk</a>
        <a class="g-t-daftar" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
      </div>

      <div class="g-pilih">
        <div class="g-pilih-judul"><span class="g-titik"></span>Ragam Permainan</div>
        <ul class="g-chip">
          <?php foreach ($chipRagam as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
        </ul>
      </div>

      <div class="g-pilih">
        <div class="g-pilih-judul"><span class="g-titik"></span>Perangkat Didukung</div>
        <ul class="g-chip">
          <?php foreach ($chipPerangkat as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
        </ul>
      </div>

      <div class="g-anggota">
        <div class="g-pilih-judul" style="margin:0"><span class="g-titik"></span>Pembaca Terdaftar</div>
        <div style="text-align:right">
          <div class="g-nilai"><?= e($anggotaTampil) ?></div>
        </div>
      </div>
    </div>

  </div>

  <div class="g-tagline">
    <div>
      <span class="g-denyut"></span>
      <p><?= e($namaSitus) ?> &mdash; situs informasi hiburan daring untuk pembaca 18 tahun ke atas.</p>
    </div>
  </div>
</section>

<section class="g-seksi" aria-label="Keunggulan">
  <div class="g-bungkus">
    <div class="g-kepala-seksi">
      <h2>Kenapa Membaca di <?= e($namaSitus) ?></h2>
      <div class="g-garis-bawah"></div>
    </div>
    <div class="g-kartu3">
      <?php foreach ($keunggulan as $k): ?>
      <div class="g-kartu">
        <h3><?= e($k[0]) ?></h3>
        <p><?= e($k[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="g-artikel-seksi">
  <div class="g-bungkus">
    <article class="g-artikel">
      <?php if (!$isBeranda): ?>
      <nav class="g-remah" aria-label="Remah roti"><a href="/">Beranda</a> &rsaquo; <span><?= e($h1) ?></span></nav>
      <h1><?= e($h1) ?></h1>
      <?php endif; ?>
      <p class="g-diperbarui">Diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time> oleh Tim Redaksi <?= e($namaSitus) ?></p>
      <div class="g-isi"><?= $isiArtikel ?></div>
      <?php if ($isBeranda): ?>
      <?php endif; ?>
    </article>
  </div>
</section>

<?php if (!$is404): ?>
<section class="g-seksi" aria-label="Informasi tambahan">
  <div class="g-bungkus">
    <div class="g-panel">
      <div class="g-panel-dalam">

        <?php if ($isBeranda): ?>
        <h2>Pertanyaan Umum <?= e($namaSitus) ?></h2>
        <ul class="g-faq">
          <?php foreach ($faq as $i => $f): ?>
          <li>
            <h3><span><?= e((string)($i + 1)) ?>)</span><?= e($f[0]) ?></h3>
            <p><?= e($f[1]) ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <p class="g-catat">Patuhi ketentuan dan batas usia yang berlaku di wilayah Anda, serta bermainlah secara bertanggung jawab.</p>

        <?php if ($isBeranda): ?>
        <h2 style="margin-top:52px">Penilaian Pembaca</h2>
        <div class="g-ulasan">
          <?php foreach ($ulasan as $u): ?>
          <article>
            <div>
              <div class="g-ulasan-atas">
                <strong><?= e($u['nama']) ?></strong>
                <span class="g-bintang" aria-label="Lima bintang ilustrasi">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
              </div>
              <p>&ldquo;<?= e($u['teks']) ?>&rdquo;</p>
            </div>
            <div class="g-ulasan-bawah">
              <span><?= e($u['tgl']) ?></span>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
<?php endif; ?>

</main>

<footer class="g-kaki">
  <div class="g-bungkus">
    <div class="g-kaki-atas">
      <div class="g-hakcipta"><?= e($namaSitus) ?> &copy; <?= e(date('Y')) ?></div>
      <span class="g-lokal">Indonesia &mdash; IDR</span>
    </div>

    <nav class="g-kaki-nav" aria-label="Navigasi bawah">
      <a href="/">Beranda</a>
      <a href="/tentang-kami">Tentang Kami</a>
      <a href="/kontak">Kontak</a>
      <a href="/disclaimer">Disclaimer</a>
    </nav>

    <div class="g-bantuan">
      <a href="<?= e($urlLc) ?>" rel="nofollow noopener">Live Chat</a>
      <a href="<?= e($urlWa) ?>" rel="nofollow noopener">WhatsApp</a>
      <a href="<?= e($urlTele) ?>" rel="nofollow noopener">Telegram</a>
    </div>

    <div class="g-kaki-teks">
      <span class="g-usia">18+</span>
      <p><strong><?= e($namaSitus) ?></strong> adalah situs informasi hiburan daring berbahasa Indonesia. Seluruh isi bersifat informasional dan edukatif, bukan jaminan hasil maupun nasihat keuangan.</p>
      <p>Konten terakhir diperbarui <time datetime="<?= e($lastmodTgl) ?>"><?= e($lastmodTampil) ?></time>.</p>
    </div>
  </div>
</footer>

<div class="g-melayang">
  <a class="g-t-masuk" href="<?= e($ctaLogin) ?>" rel="nofollow noopener">Masuk</a>
  <a class="g-t-daftar" href="<?= e($ctaDaftar) ?>" rel="nofollow noopener">Daftar</a>
</div>

<script type="application/ld+json"><?= $jsonLd ?></script>
<script>
(function(){
  var diam = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  var utama = document.getElementById('g-utama');
  var jempol = document.querySelectorAll('.g-jempol button');
  if (utama && jempol.length) {
    jempol.forEach(function(b){
      b.addEventListener('click', function(){
        var img = b.querySelector('img');
        if (!img) return;
        utama.setAttribute('src', img.getAttribute('src'));
        jempol.forEach(function(x){ x.setAttribute('aria-current', x === b ? 'true' : 'false'); });
      });
    });
  }

  if (diam) return;
  var putar = document.getElementById('g-putar');
  if (!putar) return;
  var situs = putar.textContent.replace('Cari ', '').replace(' di mesin pencari', '');
  var teks = [
    'Cari ' + situs + ' di mesin pencari',
    'Istilah permainan dijelaskan dengan bahasa sehari-hari',
    'Bantuan tersedia lewat live chat, WhatsApp, dan Telegram',
    'Konten hanya untuk pembaca 18 tahun ke atas'
  ];
  var n = 0;
  setInterval(function(){
    n = (n + 1) % teks.length;
    putar.style.opacity = '0';
    setTimeout(function(){ putar.textContent = teks[n]; putar.style.opacity = '1'; }, 400);
  }, 4000);
})();
</script>
</body>
</html>
