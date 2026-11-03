<?php
$id=(isset($_GET['id'])&&$_GET['id']!=='')?trim((string)$_GET['id'],"/ \t\n\r\0\x0B"):'homepage';if($id==='')$id='homepage';
$file=__DIR__.'/data/data.json';if(!is_file($file)){http_response_code(503);exit('Data situs belum tersedia.');}
$siteData=json_decode((string)file_get_contents($file),true);if(!is_array($siteData)||!isset($siteData['pages'])||!is_array($siteData['pages'])){http_response_code(503);exit('Data situs tidak valid.');}
$pages=[];foreach($siteData['pages'] as $p){if(!is_array($p))continue;$slug=trim((string)($p['slug']??''),'/');$key=(($p['category']??'')==='homepage'||$slug==='')?'homepage':$slug;$pages[$key]=$p;}
$page=$pages[$id]??null;if(!is_array($page)){http_response_code(404);exit('<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="robots" content="noindex,nofollow"><title>404</title></head><body><h1>404</h1><a href="/">Kembali ke Beranda</a></body></html>');}
$host=preg_replace('/:\\d+$/','',(string)($_SERVER['HTTP_HOST']??($siteData['domain']??'localhost')));$origin='https://'.$host;$esc=static fn($v)=>htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');$abs=static function($u)use($origin){$u=(string)$u;if($u==='')return '';return preg_match('~^https?://~i',$u)?$u:$origin.'/'.ltrim($u,'/');};
$isHome=$id==='homepage';$title=(string)($page['title']??'');$desc=(string)($page['description']??'');$h1=(string)($page['h1']??$title);$content=(string)($page['content_html']??$desc);$site=(string)($siteData['nama_situs']??preg_replace('/\\..*$/','',$host));$canonicalOverride=trim((string)($page['canonical_override']??''));$canonical=$canonicalOverride!==''?$canonicalOverride:($origin.($isHome?'/':'/'.$id));$robots=!empty($page['noindex'])?'noindex, nofollow':'index, follow';
$logo=$abs($siteData['logo']??'/img/logo.png');$favicon=$abs($siteData['favicon']??'/img/favicon.png');$banner=$abs($siteData['banner1']??'/img/banner1.jpg');$banner2=$abs($siteData['banner2']??($siteData['banner1']??'/img/banner1.jpg'));$daftar=trim((string)($siteData['cta_daftar_url']??''))?:'#';$login=trim((string)($siteData['cta_login_url']??''))?:'#';$wa=trim((string)($siteData['wa']??''))?:'#';$tele=trim((string)($siteData['tele']??''))?:'#';$lc=trim((string)($siteData['lc']??''))?:'#';
$gsc=trim((string)($siteData['gsc']??''));$gscMeta=$gsc!==''?'<meta name="google-site-verification" content="'.$esc($gsc).'">':'';$amp=trim((string)($siteData['amp_url']??''));$ampMeta=($amp!==''&&$amp!=='#')?'<link rel="amphtml" href="'.$esc($amp).'"><link rel="alternate" href="'.$esc($amp).'"><link rel="alternate" hreflang="id-id" href="'.$esc($amp).'"><link rel="alternate" hreflang="id" href="'.$esc($amp).'"><link rel="alternate" hreflang="en" href="'.$esc($amp).'"><link rel="alternate" hreflang="x-default" href="'.$esc($amp).'">':'';
$menu='<a class="nav-item'.($isHome?' active':'').'" href="'.$origin.'/"><span class="nav-label">HOME</span></a>';foreach($pages as $slug=>$mp){if($slug==='homepage')continue;$label=trim((string)($mp['menu_label']??$mp['h1']??ucwords(str_replace('-',' ',$slug))));$menu.='<a class="nav-item'.($id===$slug?' active':'').'" href="'.$origin.'/'.$esc($slug).'"><span class="nav-label">'.$esc($label).'</span></a>';}
$jsonld=json_encode(['@context'=>'https://schema.org','@graph'=>[['@type'=>'WebSite','url'=>$origin.'/','name'=>$site,'description'=>$desc],['@type'=>'WebPage','url'=>$canonical,'name'=>$title,'description'=>$desc,'inLanguage'=>'id-ID']]],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
$template=<<<'HTMLPAGE'
<!DOCTYPE html>
<html amp lang="id">
  <head>
    <meta charset="UTF-8">
    %%GSC_META%%
    %%AMP_META%%
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#6f3f1d">
        <title>%%TITLE%%</title>
    <meta name="description" content="%%DESCRIPTION%%">
    <meta name="keywords" content="%%SITE%%">
    <meta name="author" content="%%SITE%%">
    <meta name="robots" content="%%ROBOTS%%">
    
    <meta name="language" content="id">
    
    <link rel="canonical" href="%%CANONICAL%%">
    <link rel="icon" type="image/png" href="%%FAVICON%%">
    <link rel="apple-touch-icon" href="%%FAVICON%%">
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="%%TITLE%%">
    <meta property="og:description" content="%%DESCRIPTION%%">
    <meta property="og:url" content="%%CANONICAL%%">
    <meta property="og:site_name" content="%%SITE%%">
    <meta property="og:image" content="%%BANNER%%">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="%%SITE%%">
    <meta property="og:locale" content="id_ID">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="%%TITLE%%">
    <meta name="twitter:description" content="%%DESCRIPTION%%">
    <meta name="twitter:image" content="%%ORIGIN%%/banner.webp">
    <meta name="twitter:image:alt" content="%%SITE%%">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700;900&display=swap" rel="stylesheet">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    %%JSONLD%%
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
.dynamic-nav{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:6px!important;padding:8px 0!important;overflow:visible!important}
.dynamic-nav .nav-item{display:flex!important;align-items:center!important;justify-content:center!important;min-width:0!important;padding:10px 6px!important;text-align:center!important;font-size:12px!important;font-weight:800!important;border-radius:8px!important;color:var(--gold-light)!important}
.dynamic-nav .nav-item.active{background:var(--brown)!important;color:#fff!important}
@media(max-width:800px){.dynamic-nav{display:flex!important;overflow-x:auto!important;justify-content:flex-start!important;padding:7px 8px!important}.dynamic-nav .nav-item{flex:0 0 auto!important;min-width:116px!important}}
</style>
<noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

<style amp-custom>
:root{
--cream:#1e1e1d;
--cream-2:#9b8100;
--cream-3:#9b8100;
--brown:#cc7a04;
--brown-2:#1e1e1d;
--brown-3:#B8860B;
--brown-dark:#cc7a04;
--gold:#D4A900;
--gold-light:#9b8100;
--text:#cc7a04;
--white:#1e1e1d;
--shadow:0 7px 22px rgba(196,0,0,.10);
--radius:14px;}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:Arial,Helvetica,sans-serif;color:var(--text);background:radial-gradient(circle at 20% 10%,#B8860B,transparent 28%),linear-gradient(180deg,#1e1e1d 0%,#9b8100 100%);line-height:1.45}
a{color:inherit;text-decoration:none}
button{font:inherit}
.container{width:min(100% - 58px,1500px);margin:auto}

/* === DESKTOP ==== */
.topbar{min-height:42px;background:linear-gradient(90deg,var(--brown-dark),var(--brown));color:#FFF;display:flex;align-items:center}
.topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:18px}
.news{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;min-width:0}
.news-badge{display:flex;align-items:center;justify-content:center;gap:6px;background:#9b8100;color:var(--brown-dark);padding:10px 18px;clip-path:polygon(0 0,100% 0,88% 100%,0 100%);white-space:nowrap}
.news-icon{display:block;width:20px;height:20px;object-fit:contain;flex-shrink:0}
.news-marquee{min-width:0;overflow:hidden}
.news-track{display:flex;align-items:center;white-space:nowrap}
.news-text{display:inline-block;white-space:nowrap}
.news-text:nth-child(2){display:none}
.top-actions{display:flex;gap:10px;flex-shrink:0}
.top-action{display:flex;align-items:center;justify-content:center;gap:6px;border:1px solid rgba(255,235,199,.8);background:#1e1e1d;color:#9b8100;border-radius:20px;padding:5px 13px;font-size:12px;font-weight:700}
.top-action-icon{display:block;width:18px;height:18px;object-fit:contain;flex-shrink:0}

/* === MOBILE ==== */
@media(max-width:800px){
.topbar{width:100%;min-height:38px;overflow:hidden}
.topbar-inner{width:100%;display:flex;align-items:center;gap:4px;padding:0 5px;box-sizing:border-box}
.news{position:relative;flex:1 1 auto;min-width:0;height:38px;display:flex;align-items:center;overflow:hidden;padding:0}
.news-badge{position:relative;z-index:3;flex-shrink:0;margin-left:-5px;padding:8px 10px;gap:4px;font-size:10px}
.news-icon{width:17px;height:17px}
.news-marquee{position:absolute;left:0;right:0;top:0;bottom:0;z-index:1;display:flex;align-items:center;overflow:hidden;white-space:nowrap}
.news-track{display:flex;align-items:center;width:max-content;flex-shrink:0;white-space:nowrap;animation:newsMoveMobile 12s linear infinite}
.news-text{display:block;flex-shrink:0;white-space:nowrap;font-size:10px;padding-right:25px}
.news-text:nth-child(2){display:block}
.top-actions{display:flex;align-items:center;justify-content:flex-end;gap:4px;flex-shrink:0;margin:0}
.top-action{min-width:0;padding:5px 8px;gap:4px;border-radius:18px;font-size:9px;white-space:nowrap}
.top-action-icon{width:16px;height:16px}
@keyframes newsMoveMobile{
0%{transform:translateX(0)}
100%{transform:translateX(-50%)}}}

/* === DESKTOP ==== */
.header{background:#1e1e1d;border-bottom:1px solid #D4A900}
.header-inner{min-height:94px;display:flex;align-items:center;justify-content:space-between;gap:20px}
.brand{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0}
.brand-logo{display:block;width:350px;height:100px;object-fit:contain}
.brand-tag{margin-top:-9px;font-size:11px;letter-spacing:3px;font-weight:900;text-align:center;color:#9b8100}
.contact-group{display:flex;align-items:center;gap:12px}
.login-btn,.register-btn{display:flex;align-items:center;justify-content:center;min-width:110px;padding:11px 18px;box-sizing:border-box;border-radius:9px;font-size:14px;font-weight:900;line-height:1;text-align:center;text-decoration:none;cursor:pointer;transition:*background* .3s ease,color .3s ease,border-color .3s ease,box-shadow .3s ease}
.login-btn{background:linear-gradient(135deg,var(--white) 0%,var(--cream) 28%,var(--cream-2) 55%,var(--gold-light) 78%,var(--gold) 100%);color:#00ff87;border:1px solid var(--gold);box-shadow:0 4px 12px rgba(196,0,0,.12),inset 0 1px 0 rgba(255,255,255,.9)}
.login-btn:hover{background:linear-gradient(135deg,var(--gold-light) 0%,var(--gold) 30%,var(--brown-3) 62%,var(--brown-2) 100%);color:var(--white);border-color:var(--gold-light);box-shadow:0 0 8px rgba(201,153,85,.65),0 0 18px rgba(232,201,142,.45),0 6px 16px rgba(196,0,0,.2),inset 0 1px 0 rgba(255,255,255,.4)}
.register-btn{background:linear-gradient(135deg,var(--brown-dark) 0%,var(--brown) 28%,var(--brown-2) 52%,var(--brown-3) 76%,var(--gold) 100%);color:#9b8100;border:1px solid var(--gold);box-shadow:0 4px 12px rgba(196,0,0,.25),inset 0 1px 0 rgba(255,255,255,.18)}
.register-btn:hover{background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 28%,var(--brown-3) 55%,var(--brown-2) 78%,var(--brown) 100%);color:var(#9b8100);border-color:var(--gold-light);box-shadow:0 0 8px rgba(201,153,85,.8),0 0 20px rgba(232,201,142,.55),0 0 30px rgba(168,106,53,.3),0 6px 18px rgba(196,0,0,.3),inset 0 1px 0 rgba(255,255,255,.35)}

/* === MOBILE ==== */
@media(max-width:800px){
.header{width:100%;background:#1e1e1d;border-bottom:1px solid #D4A900}
.header-inner{width:100%;min-height:auto;padding:12px 10px 10px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px}
.brand{width:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0}
.brand-logo{display:block;width:350px;height:100px;object-fit:contain}
.header .brand .brand-tag{margin:0;position:relative;top:-5px;font-size:11px;line-height:1;letter-spacing:2.8px;font-weight:900;text-align:center;color:#D4A900}
.contact-group{width:100%;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));align-items:stretch;gap:6px}
.login-btn,.register-btn{width:100%;min-width:0;height:43px;padding:0 4px;border-radius:9px;font-size:14px}
.login-btn:hover{background:linear-gradient(135deg,var(--gold-light) 0%,var(--gold) 30%,var(--brown-3) 62%,var(--brown-2) 100%);color:#00ff87;border-color:var(--gold-light);box-shadow:0 0 8px rgba(201,153,85,.65),0 0 16px rgba(232,201,142,.45)}
.register-btn:hover{background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 28%,var(--brown-3) 55%,var(--brown-2) 78%,var(--brown) 100%);color:#00ff87;border-color:var(--gold-light);box-shadow:0 0 8px rgba(201,153,85,.8),0 0 18px rgba(232,201,142,.55),0 0 26px rgba(168,106,53,.3)}}

/* === SMALL MOBILE ==== */
@media(max-width:480px){
.header-inner{padding:8px 8px 9px;gap:6px}
.brand{gap:0}
.brand-logo{width:350px;height:100px}
.header .brand .brand-tag{margin:0;top:-7px;font-size:11px;line-height:1;letter-spacing:3px;font-weight:900}
.contact-group{grid-template-columns:repeat(2,minmax(0,1fr));gap:5px}
.login-btn,.register-btn{width:100%;min-width:0;height:40px;padding:0 4px;border-radius:8px;font-size:14px}}

/* === DESKTOP ==== */
.nav{background:linear-gradient(90deg,#cc7a04,#cc7a04);color:#fff}
.nav-inner{display:flex;align-items:stretch;justify-content:center;overflow:hidden}
.nav a{min-width:112px;height:72px;padding:7px 10px;text-align:center;color:#fff;font-size:12px;font-weight:900;border-right:1px solid rgba(255,255,255,.10);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;text-decoration:none;box-sizing:border-box;transition:.2s}
.nav a:hover,.nav a.active{background:rgba(255,255,255,.12)}
.nav a amp-img{display:block;width:30px;height:30px;object-fit:contain}
.nav a span{display:block;font-size:12px;font-weight:900;line-height:1.1;white-space:nowrap}
.nav-home{min-width:64px;height:72px}
.nav-home amp-img{width:28px;height:28px}

/* === MOBILE ==== */
@media(max-width:800px){
.nav{overflow-x:auto}
.nav-inner{width:max-content;justify-content:flex-start;overflow-x:auto;overflow-y:hidden;scrollbar-width:none;-webkit-overflow-scrolling:touch}
.nav-inner::-webkit-scrollbar{display:none}
.nav a{flex:0 0 92px;min-width:92px;height:72px;padding:7px 5px}
.nav a amp-img{width:28px;height:28px}
.nav a span{font-size:10px}
.nav-home{min-width:64px;height:72px}
.nav-home amp-img{width:28px;height:28px}}

/* === DESKTOP ==== */
.hero-wrap{padding:16px 0 18px}
.hero{position:relative;height:430px;width:100%;border:1px solid #D4A900;border-radius:15px;overflow:hidden;background:#9b8100;box-shadow:var(--shadow)}
.hero-bg{position:absolute;inset:0;z-index:0;width:100%;height:100%;object-fit:cover;object-position:center}
.banner-mobile{display:none}
.hero-overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(90deg,rgba(255,248,232,.94) 0%,rgba(255,248,232,.82) 35%,rgba(255,248,232,.30) 58%,rgba(255,248,232,0) 100%)}
.hero-content{position:absolute;left:0;top:0;z-index:2;width:51%;height:100%;padding:25px 30px;display:flex;flex-direction:column;justify-content:center}
.hero-label{display:inline-block;align-self:flex-start;color:#fff;background:linear-gradient(90deg,#B8860B,#cc7a04);padding:8px 19px;font-size:14px;font-weight:900;clip-path:polygon(0 0,100% 0,92% 100%,0 100%)}
.hero h1{margin:13px 0 7px;font-size:45px;line-height:1.02;font-weight:950;letter-spacing:-1px;color:#cc7a04}
.hero p{font-size:17px;margin-bottom:17px}
.hero-features{display:flex;align-items:center;justify-content:space-between;max-width:600px;border:1px solid #D4A900;background:rgba(255,251,240,.75);border-radius:12px;padding:15px 17px}
.feature{display:flex;align-items:center;gap:8px;font-size:10px;font-weight:900;text-transform:uppercase}
.feature-icon{font-size:27px}
.hero-apk{margin-top:10px;display:inline-flex;align-items:center;gap:15px;background:linear-gradient(135deg,#cc7a04 0%,#cc7a04 25%,#1e1e1d 48%,#B8860B 68%,#D4A900 100%);color:#1e1e1d;border:1px solid #9b8100;border-radius:12px;padding:11px 25px;min-width:270px;box-sizing:border-box;text-decoration:none;box-shadow:0 5px 14px rgba(196,0,0,.28),inset 0 1px 0 rgba(255,255,255,.25);transition:*background* .3s ease,border-color .3s ease,box-shadow .3s ease,color .3s ease}
.hero-apk .android{width:30px;height:30px;flex-shrink:0}
.hero-apk strong{display:block;font-size:22px;line-height:1.1;font-weight:900;letter-spacing:.3px}
.hero-apk small{font-size:12px;line-height:1.2;color:#9b8100}
.hero-apk:hover{background:linear-gradient(135deg,#D4A900 0%,#9b8100 25%,#B8860B 52%,#1e1e1d 76%,#cc7a04 100%);color:#1e1e1d;border-color:#1e1e1d;box-shadow:0 0 7px rgba(232,201,142,.85),0 0 16px rgba(201,153,85,.65),0 0 28px rgba(168,106,53,.4),0 7px 20px rgba(196,0,0,.3),inset 0 1px 0 rgba(255,255,255,.45)}
.hero-apk:hover small{color:#1e1e1d}

/* === MOBILE ==== */
@media(max-width:800px){
.hero-wrap .hero{width:100%;height:auto;min-height:0;padding:0;border-radius:10px;overflow:hidden;position:relative;box-sizing:border-box}
.hero-wrap .hero-bg{display:none}
.hero-wrap .hero-overlay{display:none}
.hero-wrap .hero-content{display:none}
.hero-wrap .banner-mobile{display:block;position:relative;width:100%;max-width:100%;height:auto;margin:0;padding:0;border:0;object-fit:contain}
.hero-apk{width:100%;max-width:270px;min-width:0;justify-content:center;padding:11px 20px;gap:12px}
.hero-apk strong{font-size:19px}.hero-apk small{font-size:11px}
.hero-apk .android{width:30px;height:30px}

/* === SMALL MOBILE ==== */
@media(max-width:480px){
.hero-wrap .hero{width:100%;height:auto;min-height:0;border-radius:8px}
.hero-wrap .banner-mobile{width:100%;max-width:100%;height:auto}}
.hero-apk{max-width:260px;padding:10px 16px;border-radius:10px;gap:10px}
.hero-apk strong{font-size:17px}
.hero-apk small{font-size:10px}
.hero-apk .android{width:28px;height:28px}}

/* === DESKTOP ==== */
.section{padding:0 0 17px}
.section-title{display:flex;align-items:center;justify-content:center;gap:12px;margin:0 0 13px;font-size:18px;font-weight:950;color:#9b8100;text-transform:uppercase;letter-spacing:.5px}
.section-title:before,.section-title:after{content:"◆";display:flex;align-items:center;width:65px;height:1px;color:#B8860B;font-size:7px;line-height:1;background:linear-gradient(90deg,transparent,#B8860B 45%,#B8860B 100%);}
.section-title:before{justify-content:flex-end;padding-right:5px}
.section-title:after{justify-content:flex-start;padding-left:5px;background:linear-gradient(90deg,#B8860B 0%,#B8860B 55%,transparent 100%)}

/* === MOBILE ==== */
@media(max-width:800px){
.section{padding:0 0 14px}
.section-title{gap:9px;margin:0 0 10px;font-size:15px;letter-spacing:.3px}
.section-title:before,.section-title:after{width:45px;font-size:6px}
.section-title:before{padding-right:4px}
.section-title:after{padding-left:4px}}

/* === SMALL MOBILE ==== */
@media(max-width:480px){
.section{padding:0 0 12px}
.section-title{gap:8px;margin:0 0 9px;font-size:14px}
.section-title:before,.section-title:after{width:38px;font-size:5px}}

/* === DESKTOP ==== */
.provider-box{position:relative;border:1px solid #D4A900;background:linear-gradient(145deg,#cc7a04,#cc7a04 45%,#cc7a04 100%);border-radius:16px;padding:12px;box-sizing:border-box;overflow:hidden;box-shadow:0 10px 30px rgba(196,0,0,.22),0 0 12px rgba(212,169,0,.12),inset 0 1px 0 rgba(255,239,194,.2)}
.provider-box::before{content:"";position:absolute;left:8%;right:8%;top:0;height:1px;background:linear-gradient(90deg,transparent,#9b8100,#9b8100,#9b8100,transparent);box-shadow:0 0 8px rgba(247,217,139,.6)}
.provider-box::after{content:"";position:absolute;left:0;right:0;bottom:0;height:2px;background:linear-gradient(90deg,transparent,#B8860B,#9b8100,#B8860B,transparent);box-shadow:0 0 10px rgba(242,201,111,.45)}
.providers{display:grid;grid-template-columns:repeat(8,minmax(150px,1fr));border:1px solid #B8860B;border-radius:11px;overflow-x:auto;overflow-y:hidden;background:linear-gradient(180deg,rgba(54,31,16,.95),rgba(29,15,8,.98));box-shadow:inset 0 1px 0 rgba(255,235,182,.16),inset 0 -2px 8px rgba(0,0,0,.35)}
.provider{min-width:150px;min-height:70px;height:70px;display:flex;align-items:center;justify-content:center;padding:7px 10px;border-right:1px solid rgba(201,154,82,.3);box-sizing:border-box;overflow:hidden;position:relative;background:linear-gradient(145deg,rgba(255,255,255,.045),rgba(0,0,0,.12));transition:all .3s ease}
.provider:last-child{border-right:0}
.provider::before{content:"";position:absolute;inset:5px;border:1px solid rgba(220,174,91,.08);border-radius:8px;pointer-events:none;transition:all .3s ease}
.provider::after{content:"";position:absolute;top:0;left:-100%;width:45%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,225,154,.18),transparent);transform:skewX(-20deg);transition:left .5s ease;pointer-events:none}
.provider amp-img{display:block;width:150px;height:50px;object-fit:contain;position:relative;z-index:1;filter:drop-shadow(0 2px 3px rgba(0,0,0,.55));transition:transform .3s ease,filter .3s ease}
.provider:hover{background:radial-gradient(circle at center,rgba(212,158,67,.18),rgba(43,24,12,.95) 70%);box-shadow:inset 0 0 18px rgba(212,169,0,.08),0 0 12px rgba(212,169,0,.18)}
.provider:hover::before{border-color:rgba(239,196,105,.42);box-shadow:inset 0 0 8px rgba(239,196,105,.08),0 0 8px rgba(239,196,105,.15)}
.provider:hover::after{left:140%}
.provider:hover amp-img{transform:scale(1.06);filter:drop-shadow(0 0 5px rgba(242,197,102,.55)) drop-shadow(0 2px 3px rgba(0,0,0,.6))}

/* === MOBILE ==== */
@media(max-width:800px){
.provider-box{padding:9px;border-radius:13px;box-shadow:0 7px 22px rgba(196,0,0,.2),0 0 10px rgba(212,169,0,.12),inset 0 1px 0 rgba(255,239,194,.2)}
.providers{display:flex;overflow-x:auto;overflow-y:hidden;scrollbar-width:none;border-radius:10px}
.providers::-webkit-scrollbar{display:none}
.provider{flex:0 0 150px;min-width:150px;height:64px;min-height:64px;padding:6px 9px}
.provider amp-img{width:140px;height:46px}
.provider::before{inset:4px}}

/* === SMALL MOBILE ==== */
@media(max-width:480px){
.provider-box{padding:8px;border-radius:12px}
.provider{flex:0 0 145px;min-width:145px;height:60px;min-height:60px;padding:5px 8px}
.provider amp-img{width:135px;height:44px}}

/* === GAMES PREMIUM DESKTOP ==== */
.games{display:grid;grid-template-columns:repeat(6,minmax(170px,1fr));gap:12px;width:100%;max-width:1150px;margin:0 auto;padding:12px;box-sizing:border-box;border:1px solid #D4A900;border-radius:16px;background:linear-gradient(135deg,#1e1e1d 0%,#9b8100 45%,#1e1e1d 100%);box-shadow:0 10px 30px rgba(196,0,0,.14),0 2px 6px rgba(196,0,0,.08),inset 3px 0 0 #D4A900,inset -3px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95);position:relative;overflow:hidden}
.games::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#9b8100,#B8860B,#9b8100);box-shadow:1px 0 4px rgba(184,134,11,.25);z-index:1}
.games::after{content:"";position:absolute;left:24px;right:24px;bottom:0;height:1px;background:linear-gradient(90deg,transparent,#D4A900,#9b8100,#D4A900,transparent);z-index:1}
.game{overflow:hidden;position:relative;border:1px solid #D4A900;background:linear-gradient(145deg,#1e1e1d 0%,#9b8100 50%,#1e1e1d 100%);border-radius:12px;box-shadow:0 5px 12px rgba(196,0,0,.12),inset 0 1px 0 rgba(255,255,255,.9);box-sizing:border-box}
.game-img{height:155px;position:relative;overflow:hidden;background:#cc7a04}
.game-img amp-img{display:block;width:100%;height:100%;object-fit:cover;object-position:center}
.game-body{padding:8px 10px 10px;text-align:center;background:linear-gradient(180deg,rgba(255,250,239,.8),rgba(247,226,190,.65))}
.game-name{font-size:12px;font-weight:950;color:#cc7a04;text-shadow:0 1px 0 rgba(255,255,255,.8)}
.play{display:block;width:100%;margin-top:6px;padding:7px 10px;box-sizing:border-box;border:1px solid #D4A900;border-radius:8px;color:#fff;background:linear-gradient(180deg,#9b8100 0%,#B8860B 45%,#cc7a04 100%);font-size:11px;font-weight:900;text-decoration:none;text-align:center;letter-spacing:.4px;text-shadow:0 1px 2px rgba(0,0,0,.5);box-shadow:inset 0 1px 0 rgba(255,255,255,.6),inset 0 -2px 4px rgba(65,31,8,.3),0 3px 7px rgba(196,0,0,.2),0 0 5px rgba(212,169,0,.18);transition:all .3s ease;position:relative;overflow:hidden}
.play::before{content:"";position:absolute;top:0;left:-120%;width:70%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.45),transparent);transform:skewX(-20deg);transition:left .5s ease}
.play:hover{color:#fff;background:linear-gradient(180deg,#9b8100 0%,#1e1e1d 45%,#cc7a04 100%);border-color:#9b8100;transform:translateY(-2px) scale(1.02);text-shadow:0 0 5px rgba(255,236,180,.8);box-shadow:inset 0 1px 0 rgba(255,255,255,.8),inset 0 -2px 4px rgba(65,31,8,.2),0 5px 12px rgba(196,0,0,.28),0 0 8px rgba(255,212,0,.65),0 0 18px rgba(255,212,0,.4),0 0 30px rgba(255,212,0,.18)}
.play:hover::before{left:140%}
.play:active{transform:translateY(0) scale(.98);box-shadow:inset 0 2px 5px rgba(65,31,8,.35),0 2px 5px rgba(196,0,0,.18),0 0 8px rgba(255,212,0,.35)}
.game:hover{box-shadow:0 7px 16px rgba(196,0,0,.16),inset 0 1px 0 rgba(255,255,255,.95)}
.game:hover .play{background:linear-gradient(180deg,#B8860B,#cc7a04)}

/* === MOBILE ==== */
@media(max-width:800px){
.games{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;width:100%;max-width:100%;margin:0;padding:8px 7px;box-sizing:border-box;border-radius:13px;box-shadow:0 7px 20px rgba(196,0,0,.13),inset 2px 0 0 #D4A900,inset -2px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95)}
.games::before{width:3px}
.games::after{left:14px;right:14px}
.game{width:100%;min-width:0;box-sizing:border-box;border-radius:10px;overflow:hidden}
.game-img{width:100%;height:auto;aspect-ratio:3/2;position:relative;overflow:hidden}
.game-img amp-img{display:block;width:100%;height:100%;object-fit:cover;object-position:center}
.game-body{padding:6px 5px 8px;text-align:center}
.game-name{font-size:10px;font-weight:900;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#cc7a04}
.play{margin-top:5px;padding:6px 4px;border-radius:7px;font-size:9px;letter-spacing:.25px}}

/* === SMALL MOBILE ==== */
@media(max-width:480px){
.games{grid-template-columns:repeat(3,minmax(0,1fr));gap:7px;padding:7px 6px}
.game-body{padding:6px 4px 7px}
.game-name{font-size:9px}
.play{padding:5px 2px;font-size:8px;border-radius:6px}}

/* === DESKTOP ==== */
.seo-box{width:100%;max-width:1150px;margin:0 auto;position:relative;box-sizing:border-box;overflow:hidden;border:1px solid #D4A900;border-radius:16px;padding:18px 24px;background:linear-gradient(135deg,#1e1e1d 0%,#9b8100 45%,#1e1e1d 100%);box-shadow:0 10px 30px rgba(196,0,0,.14),0 2px 6px rgba(196,0,0,.08),inset 3px 0 0 #D4A900,inset -3px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95);text-align:center}
.seo-box::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#9b8100,#B8860B,#9b8100);box-shadow:1px 0 4px rgba(184,134,11,.25)}
.seo-box::after{content:"";position:absolute;left:24px;right:24px;bottom:0;height:1px;background:linear-gradient(90deg,transparent,#D4A900,#9b8100,#D4A900,transparent)}
.seo-title{margin:0 0 9px;font-size:25px;line-height:1.4;font-weight:900;color:#cc7a04;text-align:center;text-shadow:0 1px 0 rgba(255,255,255,.8)}
.seo-description{margin:0;font-size:17px;line-height:1.6;color:#efebdf;text-align:center}

/* === MOBILE ==== */
@media(max-width:800px){
.seo-box{width:100%;max-width:none;margin:0;padding:14px 14px;border-radius:13px;box-shadow:0 7px 20px rgba(196,0,0,.13),inset 2px 0 0 #D4A900,inset -2px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95)}
.seo-box::before{width:3px}
.seo-box::after{left:14px;right:14px}
.seo-title{font-size:17px;line-height:1.35;margin:0 0 8px;text-align:center}
.seo-description{font-size:13px;line-height:1.55;text-align:center}}

/* === DESKTOP ==== */
.benefits{width:100%;max-width:1150px;margin:0 auto;display:flex;flex-direction:column;gap:0;padding:0;overflow:hidden;border:1px solid #D4A900;border-radius:16px;background:linear-gradient(135deg,#1e1e1d 0%,#9b8100 45%,#1e1e1d 100%);box-shadow:0 10px 30px rgba(196,0,0,.14),0 2px 6px rgba(196,0,0,.08),inset 3px 0 0 #D4A900,inset -3px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95)}
.benefit{width:100%;min-width:0;display:flex;align-items:center;gap:16px;padding:17px 22px;box-sizing:border-box;border-bottom:1px solid #D4A900;background:linear-gradient(90deg,rgba(255,255,255,.72),rgba(250,234,203,.55),rgba(255,255,255,.72));position:relative;transition:*background* .25s ease,box-shadow .25s ease}
.benefit::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#9b8100,#B8860B,#9b8100);box-shadow:1px 0 4px rgba(184,134,11,.25)}
.benefit:last-child{border-bottom:0}
.benefit:hover{background:linear-gradient(90deg,#1e1e1d,#9b8100,#1e1e1d);box-shadow:inset 0 1px 0 rgba(255,255,255,.9),inset 0 -1px 0 rgba(212,169,0,.12)}
.benefit-icon{width:58px;height:58px;min-width:58px;display:flex;align-items:center;justify-content:center;box-sizing:border-box;border:1px solid #D4A900;border-radius:13px;background:linear-gradient(145deg,#1e1e1d 0%,#9b8100 45%,#9b8100 100%);box-shadow:inset 0 1px 1px rgba(255,255,255,.95),inset 0 -2px 4px rgba(196,0,0,.12),0 5px 12px rgba(196,0,0,.14)}
.benefit-icon amp-img{display:block;width:38px;height:38px;object-fit:contain;filter:drop-shadow(0 2px 2px rgba(196,0,0,.18))}
.benefit>div:last-child{min-width:0;flex:1}
.benefit strong{display:block;margin:0;font-size:16px;line-height:1.25;font-weight:900;letter-spacing:.3px;color:#cc7a04;text-shadow:0 1px 0 rgba(255,255,255,.8)}
.benefit span{display:block;margin-top:5px;font-size:13px;line-height:1.4;color:#B8860B}
.benefit:not(:last-child)::after{content:"";position:absolute;left:22px;right:22px;bottom:0;height:1px;background:linear-gradient(90deg,transparent,#D4A900,#9b8100,#D4A900,transparent)}

/* === MOBILE ==== */
@media(max-width:800px){
.benefits{width:100%;display:flex;flex-direction:column;padding:0;border-radius:13px;box-shadow:0 7px 20px rgba(196,0,0,.13),inset 2px 0 0 #D4A900,inset -2px 0 0 #D4A900,inset 0 1px 0 rgba(255,255,255,.95)}
.benefit{width:100%;display:flex;align-items:center;gap:11px;padding:13px 13px;box-sizing:border-box;min-height:70px;border-right:0;border-bottom:1px solid #D4A900}
.benefit:last-child{border-bottom:0}
.benefit::before{width:3px}
.benefit-icon{width:44px;height:44px;min-width:44px;border-radius:10px}
.benefit-icon amp-img{width:29px;height:29px}
.benefit strong{font-size:12px;line-height:1.25;letter-spacing:.15px}
.benefit span{margin-top:3px;font-size:9px;line-height:1.35}
.benefit:not(:last-child)::after{left:14px;right:14px}}

/* === DESKTOP ==== */
.footer{margin-top:14px;background:linear-gradient(180deg,#1e1e1d,#9b8100);border-top:1px solid #D4A900}
.footer-main{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr 1.05fr;gap:24px;padding:26px 0 22px}
.footer-col{border-right:1px solid #D4A900;padding-right:18px}
.footer-col:last-child{border-right:0}
.footer-logo{display:flex;align-items:center;margin-bottom:4px}
.footer-logo amp-img{display:block;width:180px;height:60px;object-fit:contain}
.footer-tag{font-size:13px;letter-spacing:3px;font-weight:800;color:#9b8100}
.footer p{font-size:12px;line-height:1.55;margin-top:12px;color:var(--text)}
.footer h3{font-size:14px;margin:0 0 12px;color:#9b8100;font-weight:900}
.footer ul{list-style:none;margin:0;padding:0}
.footer li{font-size:12px;margin:7px 0;line-height:1.4;color:var(--text)}
.footer li a{display:inline-block;color:inherit;text-decoration:none;transition:.2s}
.footer li a:hover{color:var(--brown-3);transform:translateX(3px)}
.socials{display:flex;align-items:center;gap:8px;margin-top:14px}
.social{width:34px;height:34px;border:1px solid #D4A900;border-radius:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,#1e1e1d,#9b8100);box-shadow:0 3px 7px rgba(196,0,0,.08);transition:.2s}
.social amp-img{display:block;width:21px;height:21px;object-fit:contain}
.social:hover{transform:translateY(-2px);box-shadow:0 5px 10px rgba(196,0,0,.14)}
.footer-contact-icon{display:inline-flex;vertical-align:middle;width:22px;height:22px;margin-right:6px;object-fit:contain}
.footer-apk{border:1px solid #D4A900;border-radius:13px;padding:15px;background:linear-gradient(145deg,#1e1e1d,#9b8100);box-shadow:0 4px 12px rgba(196,0,0,.07)}
.footer-apk strong{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--brown-dark);font-weight:900}
.footer-apk strong amp-img{display:block;width:27px;height:27px;object-fit:contain}
.footer-apk p{font-size:13px;line-height:1.5;margin:8px 0 13px}
.footer-apk a{display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,#1e1e1d,#cc7a04);color:#fff;border-radius:8px;padding:9px;font-size:10px;font-weight:900;text-align:center;text-decoration:none;transition:.2s}
.footer-apk a:hover{background:linear-gradient(180deg,#B8860B,#cc7a04);transform:translateY(-1px)}
.copyright{background:#cc7a04;color:#fff;text-align:center;padding:11px;font-size:10px}

/* === MOBILE ==== */
@media(max-width:800px){
.footer-main{grid-template-columns:repeat(3,minmax(0,1fr));gap:0;padding:20px 0}
.footer-col{border-right:1px solid #D4A900;padding:0 10px;box-sizing:border-box}
.footer-col:nth-child(1){grid-column:1/-1;border-right:0;border-bottom:1px solid #D4A900;padding:0 0 16px;margin-bottom:0}
.footer-col:nth-child(2){grid-column:1;border-right:1px solid #D4A900;border-top:0;padding-top:14px}
.footer-col:nth-child(3){grid-column:2;border-right:1px solid #D4A900;border-top:0;padding-top:14px}
.footer-col:nth-child(4){grid-column:3;border-right:0;border-top:0;padding-top:14px}
.footer-col:nth-child(5){grid-column:1/-1;border-right:0;border-top:1px solid #D4A900;padding:14px 0 0;margin-top:0}
.footer-logo{justify-content:center}
.footer-logo amp-img{width:150px;height:50px}
.footer-tag{font-size:13px;text-align:center}
.footer p{font-size:11px;line-height:1.5;text-align:center}
.socials{justify-content:center;margin-top:12px}
.footer h3{font-size:12px;white-space:nowrap}
.footer li{font-size:11px;line-height:1.4;margin:7px 0}
.footer-apk{width:100%;min-height:175px;padding:15px 18px;box-sizing:border-box}
.footer-apk strong{font-size:12px;line-height:1.2}
.footer-apk strong amp-img{width:27px;height:27px}
.footer-apk p{font-size:12px;line-height:1.5;margin:10px 0 14px;text-align:left}
.footer-apk a{width:100%;min-height:43px;padding:9px;box-sizing:border-box;font-size:10px}}
</style>

</head>

<body>

<!-- TOP NEWS -->
<div class="topbar">
    <div class="container topbar-inner">
        <div class="news">
            <span class="news-badge">
                <amp-img class="news-icon" src="/images/ic-speaker.webp" width="20" height="20" alt=""></amp-img><span>NEWS:</span>
            </span>
            <div class="news-marquee">
                <div class="news-track">
                <span class="news-text">Selamat datang di %%TITLE%%</span>
                <span class="news-text">Selamat datang di %%TITLE%%</span>
            </div>
        </div>
    </div>
        <div class="top-actions">
            <a class="top-action" href="%%CTA_LOGIN%%">
                <amp-img class="top-action-icon" src="/images/ic-chat.webp" width="18" height="18" alt=""></amp-img>
                <span>Live Chat</span>
            </a>
            <a class="top-action" href="%%CTA_LOGIN%%">
                <amp-img class="top-action-icon" src="/images/ic-login.webp" width="18" height="18" alt=""></amp-img>
                <span>Masuk</span>
            </a>
        </div>
    </div>
</div>

<!-- HEADER -->
<header class="header">
    <div class="container header-inner">
        <a href="%%ORIGIN%%" class="brand">
            <amp-img class="brand-logo" src="%%LOGO%%" width="350" height="100" alt="%%SITE%%"></amp-img><div class="brand-tag">PLATFORM RESMI SITUS MAHJONG ONLINE ASIA</div>
        </a>
        <div class="contact-group">
            <a class="login-btn" href="%%CTA_LOGIN%%">LOGIN</a>
            <a class="register-btn" href="%%CTA_DAFTAR%%">DAFTAR</a>
        </div>
    </div>
</header>

<!-- NAVIGATION -->
<nav class="nav"><div class="container nav-inner dynamic-nav">%%DYNAMIC_MENU%%</div></nav>

<main>

<!-- HERO -->
<section class="hero-wrap" id="home">
    <div class="container">
        <div class="hero">
            <amp-img class="hero-bg" src="%%BANNER%%" width="1920" height="430" layout="fill" alt="%%SITE%%"></amp-img>
            <amp-img class="banner-mobile" src="%%BANNER2%%" width="750" height="400" layout="responsive" alt="%%SITE%%"></amp-img>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-label"></span>
                <h1>%%TITLE%%</h1>
                <p>%%DESCRIPTION%%</p>
                <div class="hero-features">
                    <div class="feature"><span class="feature-icon">♢</span>100%<br>AMAN</div>
                    <div class="feature"><span class="feature-icon">ϟ</span>CEPAT<br>&amp; STABIL</div>
                    <div class="feature"><span class="feature-icon">♜</span>ANTI<br>BLOKIR</div>
                    <div class="feature"><span class="feature-icon">🎁</span>BONUS<br>MENARIK</div>
                </div>
                <a class="hero-apk" href="%%CTA_DAFTAR%%">
                    <amp-img class="android" src="/images/ic-apk-2.webp" width="40" height="40" layout="fixed" alt="Download APK"></amp-img>
                    <span><strong>DOWNLOAD APK</strong>
                        <small>Versi Terbaru</small>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">PROVIDER TERPERCAYA</h2>
        <div class="provider-box">
            <div class="providers">
                <div class="provider">
                    <amp-img src="/images/provider-pragmatic.webp" width="180" height="60" layout="fixed" alt="Pragmatic Play"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-pgsoft.webp" width="180" height="60" layout="fixed" alt="PG Soft"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-sbobet.webp" width="180" height="60" layout="fixed" alt="Sbo bet"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-nex4d.webp" width="180" height="60" layout="fixed" alt="Nex 4D"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-joker.webp" width="180" height="60" layout="fixed" alt="Joker Gaming"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-cq9.webp" width="180" height="60" layout="fixed" alt="CQ9 Gaming"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-fastspin.webp" width="180" height="60" layout="fixed" alt="Fast Spin"></amp-img>
                </div>
                <div class="provider">
                    <amp-img src="/images/provider-ws168.webp" width="180" height="60" layout="fixed" alt="WS 168"></amp-img>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- POPULAR GAMES -->
<section class="section" id="promo">
    <div class="container">
        <h2 class="section-title">GAME TERPOPULER</h2>
        <div class="games">
            <article class="game" id="slot">
                <div class="game-img g1">
                    <amp-img src="/images/game-slot.webp" width="600" height="400" layout="responsive" alt="SLOT"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">SLOT</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
            <article class="game" id="casino">
                <div class="game-img g2">
                    <amp-img src="/images/game-casino.webp" width="600" height="400" layout="responsive" alt="LIVE CASINO"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">LIVE CASINO</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
            <article class="game" id="sportsbook">
                <div class="game-img g3">
                    <amp-img src="/images/game-sports.webp" width="600" height="400" layout="responsive" alt="SPORTSBOOK"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">SPORTSBOOK</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
            <article class="game" id="poker">
                <div class="game-img g4">
                    <amp-img src="/images/game-poker.webp" width="600" height="400" layout="responsive" alt="POKER"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">POKER</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
            <article class="game" id="totogl">
                <div class="game-img g5">
                    <amp-img src="/images/game-togel.webp" width="600" height="400" layout="responsive" alt="TOGEL"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">TOGEL</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
            <article class="game" id="livetv">
                <div class="game-img g6">
                    <amp-img src="/images/game-sabungayam.webp" width="600" height="400" layout="responsive" alt="Sabung Ayam"></amp-img>
                </div>
                <div class="game-body">
                    <div class="game-name">SABUNG AYAM</div>
                    <a class="play" href="%%CTA_DAFTAR%%">MAINKAN</a>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- SEO TITLE -->
<section class="section">
    <div class="container">
           <div class="seo-box">
               <h1 class="seo-title">%%H1%%</h1>
               <p class="seo-description">%%CONTENT%%</p>
           </div>
        </div>
</section>

<!-- BENEFITS -->
<section class="section">
    <div class="container">
        <div class="benefits">
            <div class="benefit">
                <div class="benefit-icon">
                    <amp-img src="/images/ic-transaksi.webp" width="34" height="34" alt="Transaksi Kilat"></amp-img>
                </div>
                <div>
                    <strong>TRANSAKSI KILAT</strong>
                    <span>Proses deposit &amp; withdraw instan tanpa gangguan.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">
                    <amp-img src="/images/ic-keamanan.webp" width="34" height="34" alt="Keamanan Terjamin"></amp-img>
                </div>
                <div>
                    <strong>KEAMANAN TERJAMIN</strong>
                    <span>Sistem aman &amp; data player terlindungi.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">
                    <amp-img src="/images/ic-customerservice.webp" width="34" height="34" alt="Customer Service"></amp-img>
                </div>
                <div>
                    <strong>CUSTOMER SERVICE 24/7</strong>
                    <span>Siap membantu semua player kapan saja.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">
                    <amp-img src="/images/ic-bonus.webp" width="34" height="34" alt="Bonus"></amp-img>
                </div>
                <div>
                    <strong>BONUS SETIAP HARI</strong>
                    <span>Banyak pilihan promo menarik untuk semua player.</span>
                </div>
            </div>
        </div>
    </div>
</section>
</main>

<!-- FOOTER -->
<footer class="footer" id="kontak">
    <div class="container footer-main">
        <div class="footer-col">
            <div class="footer-logo">
                <amp-img src="%%LOGO%%" width="180" height="60" alt="%%SITE%%"></amp-img>
            </div>
            <div class="footer-tag">PLATFORM RESMI ASIA</div>
            <p>%%DESCRIPTION%%</p>
            <div class="socials">
                <a class="social" href="%%FOOT_WA%%"><amp-img src="/images/whatsapp.webp" width="21" height="21" alt="WhatsApp"></amp-img></a>
                <a class="social" href="%%FOOT_TELE%%"><amp-img src="/images/telegram.webp" width="21" height="21" alt="Telegram"></amp-img></a>
                <a class="social" href="%%FOOT_LC%%"><amp-img src="/images/instagram.webp" width="21" height="21" alt="Instagram"></amp-img></a>
            </div>
        </div>
        <div class="footer-col">
            <h3>INFORMASI</h3>
            <ul>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Syarat &amp; Ketentuan</a></li>
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Kontak Kami</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>BANTUAN</h3>
            <ul>
                <li><a href="#">Cara Bermain</a></li>
                <li><a href="#">Deposit</a></li>
                <li><a href="#">Withdraw</a></li>
                <li><a href="#">Bonus &amp; Promo</a></li>
                <li><a href="#">Lupa Kata Sandi</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>HUBUNGI KAMI</h3>
            <ul>
                <li>
                    <amp-img class="footer-contact-icon" src="/images/whatsapp.webp" width="22" height="22" alt="WhatsApp"></amp-img>WhatsApp
                </li>
                <li>
                    <amp-img class="footer-contact-icon" src="/images/telegram.webp" width="22" height="22" alt="Telegram"></amp-img>Telegram
                </li>
                <li>
                    <amp-img class="footer-contact-icon" src="/images/livechat.webp" width="22" height="22" alt="Live Chat"></amp-img>Live Chat
                </li>
            </ul>
        </div>
        <div class="footer-col" id="download">
            <div class="footer-apk">
                <strong><amp-img src="/images/ic-apk.webp" width="27" height="27" alt="APK"></amp-img>DOWNLOAD APK</strong>
                <p>Unduh aplikasi resmi %%SITE%% untuk pengalaman bermain lebih maksimal.</p>
                <a href="#">DOWNLOAD SEKARANG</a>
            </div>
        </div>
    </div>
    <div class="copyright">© %%YEAR%% %%SITE%%. All Rights Reserved.</div>
</footer>

</body>
</html>

HTMLPAGE;
$tokens=['%%TITLE%%'=>$esc($title),'%%DESCRIPTION%%'=>$esc($desc),'%%H1%%'=>$esc($h1),'%%CONTENT%%'=>$content,'%%SITE%%'=>$esc($site),'%%CANONICAL%%'=>$esc($canonical),'%%ORIGIN%%'=>$esc($origin),'%%ROBOTS%%'=>$robots,'%%GSC_META%%'=>$gscMeta,'%%AMP_META%%'=>$ampMeta,'%%LOGO%%'=>$esc($logo),'%%FAVICON%%'=>$esc($favicon),'%%BANNER%%'=>$esc($banner),'%%BANNER2%%'=>$esc($banner2),'%%CTA_DAFTAR%%'=>$esc($daftar),'%%CTA_LOGIN%%'=>$esc($login),'%%FOOT_WA%%'=>$esc($wa),'%%FOOT_TELE%%'=>$esc($tele),'%%FOOT_LC%%'=>$esc($lc),'%%DYNAMIC_MENU%%'=>$menu,'%%JSONLD%%'=>'<script type="application/ld+json">'.$jsonld.'</script>','%%YEAR%%'=>date('Y')];echo strtr($template,$tokens);
