<?php
$id=(isset($_GET['id'])&&$_GET['id']!=='')?trim((string)$_GET['id'],'/'):'homepage';if($id==='')$id='homepage';
$file=__DIR__.'/data/data.json';if(!is_file($file)){http_response_code(500);exit('data.json tidak ditemukan.');}$data=json_decode((string)file_get_contents($file),true);if(!is_array($data)){http_response_code(500);exit('data.json tidak valid.');}

/* Adapter kontrak data Project Pisang: pages[] -> homepage+slug map. */
if (isset($data['pages']) && is_array($data['pages'])) {
    $rawPisang = $data;
    $hostPisang = preg_replace('/:\\d+$/', '', $_SERVER['HTTP_HOST'] ?? ($rawPisang['domain'] ?? 'localhost'));
    $originPisang = 'https://' . $hostPisang;
    $normalizedPisang = [];
    foreach ($rawPisang['pages'] as $pPisang) {
        if (!is_array($pPisang)) continue;
        $slugPisang = trim((string)($pPisang['slug'] ?? ''), '/');
        $keyPisang = $slugPisang === '' ? 'homepage' : $slugPisang;
        $canonicalPisang = trim((string)($pPisang['canonical_override'] ?? ''));
        if ($canonicalPisang === '') $canonicalPisang = $originPisang . ($keyPisang === 'homepage' ? '/' : '/' . $slugPisang);
        $normalizedPisang[$keyPisang] = [
            'title' => (string)($pPisang['title'] ?? ''),
            'meta_description' => (string)($pPisang['description'] ?? ''),
            'h1' => (string)($pPisang['h1'] ?? ($pPisang['title'] ?? '')),
            'content_html' => (string)($pPisang['content_html'] ?? ''),
            'canonical_url' => $canonicalPisang,
            'amp_url' => $keyPisang === 'homepage' ? (string)($rawPisang['amp_url'] ?? '') : '',
            'image_src' => (string)($rawPisang['banner1'] ?? '/img/banner1.jpg'),
            'banner_src' => (string)($rawPisang['banner1'] ?? '/img/banner1.jpg'),
            'noindex' => false,
        ];
    }
    if (!isset($normalizedPisang['homepage'])) $normalizedPisang['homepage'] = [];
    $normalizedPisang['homepage'] = array_merge($normalizedPisang['homepage'], [
        'keyword' => (string)($rawPisang['nama_situs'] ?? preg_replace('/\\..*$/', '', $hostPisang)),
        'logo_src' => (string)($rawPisang['logo'] ?? '/img/logo.png'),
        'favicon_src' => (string)($rawPisang['favicon'] ?? '/img/favicon.png'),
        'banner_src' => (string)($rawPisang['banner1'] ?? '/img/banner1.jpg'),
        'image_src' => (string)($rawPisang['banner1'] ?? '/img/banner1.jpg'),
        'cta_daftar_url' => (string)($rawPisang['cta_daftar_url'] ?? '#'),
        'cta_login_url' => (string)($rawPisang['cta_login_url'] ?? '#'),
        'gsc_token' => (string)($rawPisang['gsc'] ?? ''),
        'foot_lc' => (string)($rawPisang['lc'] ?? '#'),
        'foot_tele' => (string)($rawPisang['tele'] ?? '#'),
        'foot_wa' => (string)($rawPisang['wa'] ?? '#'),
    ]);
    $data = $normalizedPisang;
}
$page=$data[$id]??null;if(!is_array($page)){http_response_code(404);exit('<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="robots" content="noindex,nofollow"><title>404</title></head><body><h1>404</h1></body></html>');}
$home=(isset($data['homepage'])&&is_array($data['homepage']))?$data['homepage']:[];$host=preg_replace('/:\\d+$/','',$_SERVER['HTTP_HOST']??'localhost');$origin='https://'.$host;
$h=static fn($v)=>htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');$abs=static function($u)use($origin){$u=(string)$u;return $u===''?'':(preg_match('~^https?://~i',$u)?$u:$origin.'/'.ltrim($u,'/'));};
$title=$page['title']??'';$desc=$page['meta_description']??'';$h1=$page['h1']??$title;$content=$page['content_html']??'';$site=$home['keyword']??explode('.',$host)[0];$canonical=$page['canonical_url']??($origin.'/');$amp=(!empty($page['amp_url'])&&$page['amp_url']!=='#')?$page['amp_url']:'';$robots=!empty($page['noindex'])?'noindex, nofollow':'index, follow';$logo=$abs($home['logo_src']??'');$favicon=$abs($home['favicon_src']??'');$image=$abs($page['image_src']??($page['banner_src']??($home['banner_src']??'')));$gsc=$home['gsc_token']??'';$daftar=$home['cta_daftar_url']??'#';$login=$home['cta_login_url']??'#';$livechat=$home['foot_lc']??'#';
$template=<<<'HTMLPAGE'
<!DOCTYPE html>

<html lang="id"><head prefix="og: %%ORIGIN_SLASH%% fb: %%ORIGIN_SLASH%% product: %%CANONICAL%%"><meta charset="utf-8"/>
<script data-breeze="1">var LOCALE='id\u002DID';var BASE_URL='https\u003A\u002F\u002Fwww.mothercare.co.id\u002F';var VIEW_URL='https\u003A\u002F\u002Fwww.mothercare.co.id\u002Fstatic\u002Fversion1741963345\u002Ffrontend\u002FCodilar\u002Fmothercare\u002Fid_ID';var required=[];var require=(deps,callback)=>required.push([deps,callback]);</script> <script type="text/javascript">(window.NREUM||(NREUM={})).init={ajax:{deny_list:["bam.nr-data.net"]}};(window.NREUM||(NREUM={})).loader_config={licenseKey:"NRJS-3796f39e9070f5da519",applicationID:"662568768"};;/*! For license information please see nr-loader-rum-1.284.1.min.js.LICENSE.txt */
(()=>{var e,t,r={122:(e,t,r)=>{"use strict";r.d(t,{a:()=>i});var n=r(944);function i(e,t){try{if(!e||"object"!=typeof e)return(0,n.R)(3);if(!t||"object"!=typeof t)return(0,n.R)(4);const r=Object.create(Object.getPrototypeOf(t),Object.getOwnPropertyDescriptors(t)),o=0===Object.keys(r).length?e:r;for(let a in o)if(void 0!==e[a])try{if(null===e[a]){r[a]=null;continue}Array.isArray(e[a])&&Array.isArray(t[a])?r[a]=Array.from(new Set([...e[a],...t[a]])):"object"==typeof e[a]&&"object"==typeof t[a]?r[a]=i(e[a],t[a]):r[a]=e[a]}catch(e){(0,n.R)(1,e)}return r}catch(e){(0,n.R)(2,e)}}},555:(e,t,r)=>{"use strict";r.d(t,{Vp:()=>c,fn:()=>s,x1:()=>u});var n=r(384),i=r(122);const o={beacon:n.NT.beacon,errorBeacon:n.NT.errorBeacon,licenseKey:void 0,applicationID:void 0,sa:void 0,queueTime:void 0,applicationTime:void 0,ttGuid:void 0,user:void 0,account:void 0,product:void 0,extra:void 0,jsAttributes:{},userAttributes:void 0,atts:void 0,transactionName:void 0,tNamePlain:void 0},a={};function s(e){try{const t=c(e);return!!t.licenseKey&&!!t.errorBeacon&&!!t.applicationID}catch(e){return!1}}function c(e){if(!e)throw new Error("All info objects require an agent identifier!");if(!a[e])throw new Error("Info for ".concat(e," was never set"));return a[e]}function u(e,t){if(!e)throw new Error("All info objects require an agent identifier!");a[e]=(0,i.a)(t,o);const r=(0,n.nY)(e);r&&(r.info=a[e])}},217:(e,t,r)=>{"use strict";r.d(t,{D0:()=>m,gD:()=>v,xN:()=>h});r(860).K7.genericEvents;const n="experimental.marks",i="experimental.measures",o="experimental.resources",a=e=>{if(!e||"string"!=typeof e)return!1;try{document.createDocumentFragment().querySelector(e)}catch{return!1}return!0};var s=r(614),c=r(944),u=r(384),l=r(122);const d="[data-nr-mask]",f=()=>{const e={feature_flags:[],experimental:{marks:!1,measures:!1,resources:!1},mask_selector:"*",block_selector:"[data-nr-block]",mask_input_options:{color:!1,date:!1,"datetime-local":!1,email:!1,month:!1,number:!1,range:!1,search:!1,tel:!1,text:!1,time:!1,url:!1,week:!1,textarea:!1,select:!1,password:!0}};return{ajax:{deny_list:void 0,block_internal:!0,enabled:!0,autoStart:!0},distributed_tracing:{enabled:void 0,exclude_newrelic_header:void 0,cors_use_newrelic_header:void 0,cors_use_tracecontext_headers:void 0,allowed_origins:void 0},get feature_flags(){return e.feature_flags},set feature_flags(t){e.feature_flags=t},generic_events:{enabled:!0,autoStart:!0},harvest:{interval:30},jserrors:{enabled:!0,autoStart:!0},logging:{enabled:!0,autoStart:!0},metrics:{enabled:!0,autoStart:!0},obfuscate:void 0,page_action:{enabled:!0},page_view_event:{enabled:!0,autoStart:!0},page_view_timing:{enabled:!0,autoStart:!0},performance:{get capture_marks(){return e.feature_flags.includes(n)||e.experimental.marks},set capture_marks(t){e.experimental.marks=t},get capture_measures(){return e.feature_flags.includes(i)||e.experimental.measures},set capture_measures(t){e.experimental.measures=t},capture_detail:!0,resources:{get enabled(){return e.feature_flags.includes(o)||e.experimental.resources},set enabled(t){e.experimental.resources=t},asset_types:[],first_party_domains:[],ignore_newrelic:!0}},privacy:{cookies_enabled:!0},proxy:{assets:void 0,beacon:void 0},session:{expiresMs:s.wk,inactiveMs:s.BB},session_replay:{autoStart:!0,enabled:!1,preload:!1,sampling_rate:10,error_sampling_rate:100,collect_fonts:!1,inline_images:!1,fix_stylesheets:!0,mask_all_inputs:!0,get mask_text_selector(){return e.mask_selector},set mask_text_selector(t){a(t)?e.mask_selector="".concat(t,",").concat(d):""===t||null===t?e.mask_selector=d:(0,c.R)(5,t)},get block_class(){return"nr-block"},get ignore_class(){return"nr-ignore"},get mask_text_class(){return"nr-mask"},get block_selector(){return e.block_selector},set block_selector(t){a(t)?e.block_selector+=",".concat(t):""!==t&&(0,c.R)(6,t)},get mask_input_options(){return e.mask_input_options},set mask_input_options(t){t&&"object"==typeof t?e.mask_input_options={...t,password:!0}:(0,c.R)(7,t)}},session_trace:{enabled:!0,autoStart:!0},soft_navigations:{enabled:!0,autoStart:!0},spa:{enabled:!0,autoStart:!0},ssl:void 0,user_actions:{enabled:!0,elementAttributes:["id","className","tagName","type"]}}},g={},p="All configuration objects require an agent identifier!";function m(e){if(!e)throw new Error(p);if(!g[e])throw new Error("Configuration for ".concat(e," was never set"));return g[e]}function h(e,t){if(!e)throw new Error(p);g[e]=(0,l.a)(t,f());const r=(0,u.nY)(e);r&&(r.init=g[e])}function v(e,t){if(!e)throw new Error(p);var r=m(e);if(r){for(var n=t.split("."),i=0;i<n.length-1;i++)if("object"!=typeof(r=r[n[i]]))return;r=r[n[n.length-1]]}return r}},371:(e,t,r)=>{"use strict";r.d(t,{V:()=>f,f:()=>d});var n=r(122),i=r(384),o=r(154),a=r(324);let s=0;const c={buildEnv:a.F3,distMethod:a.Xs,version:a.xv,originTime:o.WN},u={customTransaction:void 0,disabled:!1,isolatedBacklog:!1,loaderType:void 0,maxBytes:3e4,onerror:void 0,ptid:void 0,releaseIds:{},appMetadata:{},session:void 0,denyList:void 0,timeKeeper:void 0,obfuscator:void 0,harvester:void 0},l={};function d(e){if(!e)throw new Error("All runtime objects require an agent identifier!");if(!l[e])throw new Error("Runtime for ".concat(e," was never set"));return l[e]}function f(e,t){if(!e)throw new Error("All runtime objects require an agent identifier!");l[e]={...(0,n.a)(t,u),...c},Object.hasOwnProperty.call(l[e],"harvestCount")||Object.defineProperty(l[e],"harvestCount",{get:()=>++s});const r=(0,i.nY)(e);r&&(r.runtime=l[e])}},324:(e,t,r)=>{"use strict";r.d(t,{F3:()=>i,Xs:()=>o,xv:()=>n});const n="1.284.1",i="PROD",o="CDN"},154:(e,t,r)=>{"use strict";r.d(t,{OF:()=>c,RI:()=>i,WN:()=>l,bv:()=>o,gm:()=>a,mw:()=>s,sb:()=>u});var n=r(863);const i="undefined"!=typeof window&&!!window.document,o="undefined"!=typeof WorkerGlobalScope&&("undefined"!=typeof self&&self instanceof WorkerGlobalScope&&self.navigator instanceof WorkerNavigator||"undefined"!=typeof globalThis&&globalThis instanceof WorkerGlobalScope&&globalThis.navigator instanceof WorkerNavigator),a=i?window:"undefined"!=typeof WorkerGlobalScope&&("undefined"!=typeof self&&self instanceof WorkerGlobalScope&&self||"undefined"!=typeof globalThis&&globalThis instanceof WorkerGlobalScope&&globalThis),s=Boolean("hidden"===a?.document?.visibilityState),c=/iPad|iPhone|iPod/.test(a.navigator?.userAgent),u=c&&"undefined"==typeof SharedWorker,l=((()=>{const e=a.navigator?.userAgent?.match(/Firefox[/\s](\d+\.\d+)/);Array.isArray(e)&&e.length>=2&&e[1]})(),Date.now()-(0,n.t)())},687:(e,t,r)=>{"use strict";r.d(t,{Ak:()=>c,Ze:()=>d,x3:()=>u});var n=r(836),i=r(606),o=r(860),a=r(646);const s={};function c(e,t){const r={staged:!1,priority:o.P3[t]||0};l(e),s[e].get(t)||s[e].set(t,r)}function u(e,t){e&&s[e]&&(s[e].get(t)&&s[e].delete(t),g(e,t,!1),s[e].size&&f(e))}function l(e){if(!e)throw new Error("agentIdentifier required");s[e]||(s[e]=new Map)}function d(e="",t="feature",r=!1){if(l(e),!e||!s[e].get(t)||r)return g(e,t);s[e].get(t).staged=!0,f(e)}function f(e){const t=Array.from(s[e]);t.every((([e,t])=>t.staged))&&(t.sort(((e,t)=>e[1].priority-t[1].priority)),t.forEach((([t])=>{s[e].delete(t),g(e,t)})))}function g(e,t,r=!0){const o=e?n.ee.get(e):n.ee,s=i.i.handlers;if(!o.aborted&&o.backlog&&s){if(r){const e=o.backlog[t],r=s[t];if(r){for(let t=0;e&&t<e.length;++t)p(e[t],r);Object.entries(r).forEach((([e,t])=>{Object.values(t||{}).forEach((t=>{t[0]?.on&&t[0]?.context()instanceof a.y&&t[0].on(e,t[1])}))}))}}o.isolatedBacklog||delete s[t],o.backlog[t]=null,o.emit("drain-"+t,[])}}function p(e,t){var r=e[1];Object.values(t[r]||{}).forEach((t=>{var r=e[0];if(t[0]===r){var n=t[1],i=e[3],o=e[2];n.apply(i,o)}}))}},836:(e,t,r)=>{"use strict";r.d(t,{P:()=>c,ee:()=>u});var n=r(384),i=r(990),o=r(371),a=r(646),s=r(607);const c="nr@context:".concat(s.W),u=function e(t,r){var n={},s={},l={},d=!1;try{d=16===r.length&&(0,o.f)(r).isolatedBacklog}catch(e){}var f={on:p,addEventListener:p,removeEventListener:function(e,t){var r=n[e];if(!r)return;for(var i=0;i<r.length;i++)r[i]===t&&r.splice(i,1)},emit:function(e,r,n,i,o){!1!==o&&(o=!0);if(u.aborted&&!i)return;t&&o&&t.emit(e,r,n);for(var a=g(n),c=m(e),l=c.length,d=0;d<l;d++)c[d].apply(a,r);var p=v()[s[e]];p&&p.push([f,e,r,a]);return a},get:h,listeners:m,context:g,buffer:function(e,t){const r=v();if(t=t||"feature",f.aborted)return;Object.entries(e||{}).forEach((([e,n])=>{s[n]=t,t in r||(r[t]=[])}))},abort:function(){f._aborted=!0,Object.keys(f.backlog).forEach((e=>{delete f.backlog[e]}))},isBuffering:function(e){return!!v()[s[e]]},debugId:r,backlog:d?{}:t&&"object"==typeof t.backlog?t.backlog:{},isolatedBacklog:d};return Object.defineProperty(f,"aborted",{get:()=>{let e=f._aborted||!1;return e||(t&&(e=t.aborted),e)}}),f;function g(e){return e&&e instanceof a.y?e:e?(0,i.I)(e,c,(()=>new a.y(c))):new a.y(c)}function p(e,t){n[e]=m(e).concat(t)}function m(e){return n[e]||[]}function h(t){return l[t]=l[t]||e(f,t)}function v(){return f.backlog}}(void 0,"globalEE"),l=(0,n.Zm)();l.ee||(l.ee=u)},646:(e,t,r)=>{"use strict";r.d(t,{y:()=>n});class n{constructor(e){this.contextId=e}}},908:(e,t,r)=>{"use strict";r.d(t,{d:()=>n,p:()=>i});var n=r(836).ee.get("handle");function i(e,t,r,i,o){o?(o.buffer([e],i),o.emit(e,t,r)):(n.buffer([e],i),n.emit(e,t,r))}},606:(e,t,r)=>{"use strict";r.d(t,{i:()=>o});var n=r(908);o.on=a;var i=o.handlers={};function o(e,t,r,o){a(o||n.d,i,e,t,r)}function a(e,t,r,i,o){o||(o="feature"),e||(e=n.d);var a=t[o]=t[o]||{};(a[r]=a[r]||[]).push([e,i])}},878:(e,t,r)=>{"use strict";function n(e,t){return{capture:e,passive:!1,signal:t}}function i(e,t,r=!1,i){window.addEventListener(e,t,n(r,i))}function o(e,t,r=!1,i){document.addEventListener(e,t,n(r,i))}r.d(t,{DD:()=>o,jT:()=>n,sp:()=>i})},607:(e,t,r)=>{"use strict";r.d(t,{W:()=>n});const n=(0,r(566).bz)()},566:(e,t,r)=>{"use strict";r.d(t,{LA:()=>s,bz:()=>a});var n=r(154);const i="xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx";function o(e,t){return e?15&e[t]:16*Math.random()|0}function a(){const e=n.gm?.crypto||n.gm?.msCrypto;let t,r=0;return e&&e.getRandomValues&&(t=e.getRandomValues(new Uint8Array(30))),i.split("").map((e=>"x"===e?o(t,r++).toString(16):"y"===e?(3&o()|8).toString(16):e)).join("")}function s(e){const t=n.gm?.crypto||n.gm?.msCrypto;let r,i=0;t&&t.getRandomValues&&(r=t.getRandomValues(new Uint8Array(e)));const a=[];for(var s=0;s<e;s++)a.push(o(r,i++).toString(16));return a.join("")}},614:(e,t,r)=>{"use strict";r.d(t,{BB:()=>a,H3:()=>n,g:()=>u,iL:()=>c,tS:()=>s,uh:()=>i,wk:()=>o});const n="NRBA",i="SESSION",o=144e5,a=18e5,s={STARTED:"session-started",PAUSE:"session-pause",RESET:"session-reset",RESUME:"session-resume",UPDATE:"session-update"},c={SAME_TAB:"same-tab",CROSS_TAB:"cross-tab"},u={OFF:0,FULL:1,ERROR:2}},863:(e,t,r)=>{"use strict";function n(){return Math.floor(performance.now())}r.d(t,{t:()=>n})},944:(e,t,r)=>{"use strict";function n(e,t){"function"==typeof console.debug&&console.debug("New Relic Warning: https://github.com/newrelic/newrelic-browser-agent/blob/main/docs/warning-codes.md#".concat(e),t)}r.d(t,{R:()=>n})},284:(e,t,r)=>{"use strict";r.d(t,{t:()=>c,B:()=>s});var n=r(836),i=r(154);const o="newrelic";const a=new Set,s={};function c(e,t){const r=n.ee.get(t);s[t]??={},e&&"object"==typeof e&&(a.has(t)||(r.emit("rumresp",[e]),s[t]=e,a.add(t),function(e={}){try{i.gm.dispatchEvent(new CustomEvent(o,{detail:e}))}catch(e){}}({loaded:!0})))}},990:(e,t,r)=>{"use strict";r.d(t,{I:()=>i});var n=Object.prototype.hasOwnProperty;function i(e,t,r){if(n.call(e,t))return e[t];var i=r();if(Object.defineProperty&&Object.keys)try{return Object.defineProperty(e,t,{value:i,writable:!0,enumerable:!1}),i}catch(e){}return e[t]=i,i}},389:(e,t,r)=>{"use strict";function n(e,t=500,r={}){const n=r?.leading||!1;let i;return(...r)=>{n&&void 0===i&&(e.apply(this,r),i=setTimeout((()=>{i=clearTimeout(i)}),t)),n||(clearTimeout(i),i=setTimeout((()=>{e.apply(this,r)}),t))}}function i(e){let t=!1;return(...r)=>{t||(t=!0,e.apply(this,r))}}r.d(t,{J:()=>i,s:()=>n})},289:(e,t,r)=>{"use strict";r.d(t,{GG:()=>o,sB:()=>a});var n=r(878);function i(){return"undefined"==typeof document||"complete"===document.readyState}function o(e,t){if(i())return e();(0,n.sp)("load",e,t)}function a(e){if(i())return e();(0,n.DD)("DOMContentLoaded",e)}},384:(e,t,r)=>{"use strict";r.d(t,{NT:()=>o,US:()=>l,Zm:()=>a,bQ:()=>c,dV:()=>s,nY:()=>u,pV:()=>d});var n=r(154),i=r(863);const o={beacon:"bam.nr-data.net",errorBeacon:"bam.nr-data.net"};function a(){return n.gm.NREUM||(n.gm.NREUM={}),void 0===n.gm.newrelic&&(n.gm.newrelic=n.gm.NREUM),n.gm.NREUM}function s(){let e=a();return e.o||(e.o={ST:n.gm.setTimeout,SI:n.gm.setImmediate,CT:n.gm.clearTimeout,XHR:n.gm.XMLHttpRequest,REQ:n.gm.Request,EV:n.gm.Event,PR:n.gm.Promise,MO:n.gm.MutationObserver,FETCH:n.gm.fetch,WS:n.gm.WebSocket}),e}function c(e,t){let r=a();r.initializedAgents??={},t.initializedAt={ms:(0,i.t)(),date:new Date},r.initializedAgents[e]=t}function u(e){let t=a();return t.initializedAgents?.[e]}function l(e,t){a()[e]=t}function d(){return function(){let e=a();const t=e.info||{};e.info={beacon:o.beacon,errorBeacon:o.errorBeacon,...t}}(),function(){let e=a();const t=e.init||{};e.init={...t}}(),s(),function(){let e=a();const t=e.loader_config||{};e.loader_config={...t}}(),a()}},843:(e,t,r)=>{"use strict";r.d(t,{u:()=>i});var n=r(878);function i(e,t=!1,r,i){(0,n.DD)("visibilitychange",(function(){if(t)return void("hidden"===document.visibilityState&&e());e(document.visibilityState)}),r,i)}},434:(e,t,r)=>{"use strict";r.d(t,{Jt:()=>o,YM:()=>c});var n=r(836),i=r(607);const o="nr@original:".concat(i.W);var a=Object.prototype.hasOwnProperty,s=!1;function c(e,t){return e||(e=n.ee),r.inPlace=function(e,t,n,i,o){n||(n="");const a="-"===n.charAt(0);for(let s=0;s<t.length;s++){const c=t[s],u=e[c];l(u)||(e[c]=r(u,a?c+n:n,i,c,o))}},r.flag=o,r;function r(t,r,n,s,c){return l(t)?t:(r||(r=""),nrWrapper[o]=t,function(e,t,r){if(Object.defineProperty&&Object.keys)try{return Object.keys(e).forEach((function(r){Object.defineProperty(t,r,{get:function(){return e[r]},set:function(t){return e[r]=t,t}})})),t}catch(e){u([e],r)}for(var n in e)a.call(e,n)&&(t[n]=e[n])}(t,nrWrapper,e),nrWrapper);function nrWrapper(){var o,a,l,d;try{a=this,o=[...arguments],l="function"==typeof n?n(o,a):n||{}}catch(t){u([t,"",[o,a,s],l],e)}i(r+"start",[o,a,s],l,c);try{return d=t.apply(a,o)}catch(e){throw i(r+"err",[o,a,e],l,c),e}finally{i(r+"end",[o,a,d],l,c)}}}function i(r,n,i,o){if(!s||t){var a=s;s=!0;try{e.emit(r,n,i,t,o)}catch(t){u([t,r,n,i],e)}s=a}}}function u(e,t){t||(t=n.ee);try{t.emit("internal-error",e)}catch(e){}}function l(e){return!(e&&"function"==typeof e&&e.apply&&!e[o])}},993:(e,t,r)=>{"use strict";r.d(t,{A$:()=>o,ET:()=>a,p_:()=>i});var n=r(860);const i={ERROR:"ERROR",WARN:"WARN",INFO:"INFO",DEBUG:"DEBUG",TRACE:"TRACE"},o={OFF:0,ERROR:1,WARN:2,INFO:3,DEBUG:4,TRACE:5},a="log";n.K7.logging},773:(e,t,r)=>{"use strict";r.d(t,{z_:()=>o,XG:()=>s,TZ:()=>n,rs:()=>i,xV:()=>a});r(154),r(566),r(384);const n=r(860).K7.metrics,i="sm",o="cm",a="storeSupportabilityMetrics",s="storeEventMetrics"},630:(e,t,r)=>{"use strict";r.d(t,{T:()=>n});const n=r(860).K7.pageViewEvent},782:(e,t,r)=>{"use strict";r.d(t,{T:()=>n});const n=r(860).K7.pageViewTiming},344:(e,t,r)=>{"use strict";r.d(t,{G4:()=>i});var n=r(614);r(860).K7.sessionReplay;const i={RECORD:"recordReplay",PAUSE:"pauseReplay",REPLAY_RUNNING:"replayRunning",ERROR_DURING_REPLAY:"errorDuringReplay"};n.g.ERROR,n.g.FULL,n.g.OFF},234:(e,t,r)=>{"use strict";r.d(t,{W:()=>o});var n=r(836),i=r(687);class o{constructor(e,t){this.agentIdentifier=e,this.ee=n.ee.get(e),this.featureName=t,this.blocked=!1}deregisterDrain(){(0,i.x3)(this.agentIdentifier,this.featureName)}}},603:(e,t,r)=>{"use strict";r.d(t,{j:()=>K});var n=r(860),i=r(555),o=r(371),a=r(908),s=r(836),c=r(687),u=r(289),l=r(154),d=r(944),f=r(773),g=r(384),p=r(344);const m=["setErrorHandler","finished","addToTrace","addRelease","recordCustomEvent","addPageAction","setCurrentRouteName","setPageViewName","setCustomAttribute","interaction","noticeError","setUserId","setApplicationVersion","start",p.G4.RECORD,p.G4.PAUSE,"log","wrapLogger"],h=["setErrorHandler","finished","addToTrace","addRelease"];var v=r(863),b=r(614),y=r(993);var w=r(646),R=r(434);const A=new Map;function E(e,t,r,n){if("object"!=typeof t||!t||"string"!=typeof r||!r||"function"!=typeof t[r])return(0,d.R)(29);const i=function(e){return(e||s.ee).get("logger")}(e),o=(0,R.YM)(i),a=new w.y(s.P);a.level=n.level,a.customAttributes=n.customAttributes;const c=t[r]?.[R.Jt]||t[r];return A.set(c,a),o.inPlace(t,[r],"wrap-logger-",(()=>A.get(c))),i}function _(){const e=(0,g.pV)();m.forEach((t=>{e[t]=(...r)=>function(t,...r){let n=[];return Object.values(e.initializedAgents).forEach((e=>{e&&e.api?e.exposed&&e.api[t]&&n.push(e.api[t](...r)):(0,d.R)(38,t)})),n.length>1?n:n[0]}(t,...r)}))}const x={};function N(e,t,g=!1){t||(0,c.Ak)(e,"api");const m={};var w=s.ee.get(e),R=w.get("tracer");x[e]=b.g.OFF,w.on(p.G4.REPLAY_RUNNING,(t=>{x[e]=t}));var A="api-",_=A+"ixn-";function N(t,r,n,o){const a=(0,i.Vp)(e);return null===r?delete a.jsAttributes[t]:(0,i.x1)(e,{...a,jsAttributes:{...a.jsAttributes,[t]:r}}),j(A,n,!0,o||null===r?"session":void 0)(t,r)}function k(){}m.log=function(e,{customAttributes:t={},level:r=y.p_.INFO}={}){(0,a.p)(f.xV,["API/log/called"],void 0,n.K7.metrics,w),function(e,t,r={},i=y.p_.INFO){(0,a.p)(f.xV,["API/logging/".concat(i.toLowerCase(),"/called")],void 0,n.K7.metrics,e),(0,a.p)(y.ET,[(0,v.t)(),t,r,i],void 0,n.K7.logging,e)}(w,e,t,r)},m.wrapLogger=(e,t,{customAttributes:r={},level:i=y.p_.INFO}={})=>{(0,a.p)(f.xV,["API/wrapLogger/called"],void 0,n.K7.metrics,w),E(w,e,t,{customAttributes:r,level:i})},h.forEach((e=>{m[e]=j(A,e,!0,"api")})),m.addPageAction=j(A,"addPageAction",!0,n.K7.genericEvents),m.recordCustomEvent=j(A,"recordCustomEvent",!0,n.K7.genericEvents),m.setPageViewName=function(t,r){if("string"==typeof t)return"/"!==t.charAt(0)&&(t="/"+t),(0,o.f)(e).customTransaction=(r||"http://custom.transaction")+t,j(A,"setPageViewName",!0)()},m.setCustomAttribute=function(e,t,r=!1){if("string"==typeof e){if(["string","number","boolean"].includes(typeof t)||null===t)return N(e,t,"setCustomAttribute",r);(0,d.R)(40,typeof t)}else(0,d.R)(39,typeof e)},m.setUserId=function(e){if("string"==typeof e||null===e)return N("enduser.id",e,"setUserId",!0);(0,d.R)(41,typeof e)},m.setApplicationVersion=function(e){if("string"==typeof e||null===e)return N("application.version",e,"setApplicationVersion",!1);(0,d.R)(42,typeof e)},m.start=()=>{try{(0,a.p)(f.xV,["API/start/called"],void 0,n.K7.metrics,w),w.emit("manual-start-all")}catch(e){(0,d.R)(23,e)}},m[p.G4.RECORD]=function(){(0,a.p)(f.xV,["API/recordReplay/called"],void 0,n.K7.metrics,w),(0,a.p)(p.G4.RECORD,[],void 0,n.K7.sessionReplay,w)},m[p.G4.PAUSE]=function(){(0,a.p)(f.xV,["API/pauseReplay/called"],void 0,n.K7.metrics,w),(0,a.p)(p.G4.PAUSE,[],void 0,n.K7.sessionReplay,w)},m.interaction=function(e){return(new k).get("object"==typeof e?e:{})};const T=k.prototype={createTracer:function(e,t){var r={},i=this,o="function"==typeof t;return(0,a.p)(f.xV,["API/createTracer/called"],void 0,n.K7.metrics,w),g||(0,a.p)(_+"tracer",[(0,v.t)(),e,r],i,n.K7.spa,w),function(){if(R.emit((o?"":"no-")+"fn-start",[(0,v.t)(),i,o],r),o)try{return t.apply(this,arguments)}catch(e){const t="string"==typeof e?new Error(e):e;throw R.emit("fn-err",[arguments,this,t],r),t}finally{R.emit("fn-end",[(0,v.t)()],r)}}}};function j(e,t,r,i){return function(){return(0,a.p)(f.xV,["API/"+t+"/called"],void 0,n.K7.metrics,w),i&&(0,a.p)(e+t,[r?(0,v.t)():performance.now(),...arguments],r?null:this,i,w),r?void 0:this}}function I(){r.e(296).then(r.bind(r,778)).then((({setAPI:t})=>{t(e),(0,c.Ze)(e,"api")})).catch((e=>{(0,d.R)(27,e),w.abort()}))}return["actionText","setName","setAttribute","save","ignore","onEnd","getContext","end","get"].forEach((e=>{T[e]=j(_,e,void 0,g?n.K7.softNav:n.K7.spa)})),m.setCurrentRouteName=g?j(_,"routeName",void 0,n.K7.softNav):j(A,"routeName",!0,n.K7.spa),m.noticeError=function(t,r){"string"==typeof t&&(t=new Error(t)),(0,a.p)(f.xV,["API/noticeError/called"],void 0,n.K7.metrics,w),(0,a.p)("err",[t,(0,v.t)(),!1,r,!!x[e]],void 0,n.K7.jserrors,w)},l.RI?(0,u.GG)((()=>I()),!0):I(),m}var k=r(217),T=r(122);const j={accountID:void 0,trustKey:void 0,agentID:void 0,licenseKey:void 0,applicationID:void 0,xpid:void 0},I={};var O=r(284);const S=e=>{const t=e.startsWith("http");e+="/",r.p=t?e:"https://"+e};let P=!1;function K(e,t={},r,n){let{init:a,info:c,loader_config:u,runtime:d={},exposed:f=!0}=t;d.loaderType=r;const p=(0,g.pV)();c||(a=p.init,c=p.info,u=p.loader_config),(0,k.xN)(e.agentIdentifier,a||{}),function(e,t){if(!e)throw new Error("All loader-config objects require an agent identifier!");I[e]=(0,T.a)(t,j);const r=(0,g.nY)(e);r&&(r.loader_config=I[e])}(e.agentIdentifier,u||{}),c.jsAttributes??={},l.bv&&(c.jsAttributes.isWorker=!0),(0,i.x1)(e.agentIdentifier,c);const m=(0,k.D0)(e.agentIdentifier),h=[c.beacon,c.errorBeacon];P||(m.proxy.assets&&(S(m.proxy.assets),h.push(m.proxy.assets)),m.proxy.beacon&&h.push(m.proxy.beacon),_(),(0,g.US)("activatedFeatures",O.B),e.runSoftNavOverSpa&&=!0===m.soft_navigations.enabled&&m.feature_flags.includes("soft_nav")),d.denyList=[...m.ajax.deny_list||[],...m.ajax.block_internal?h:[]],d.ptid=e.agentIdentifier,(0,o.V)(e.agentIdentifier,d),e.ee=s.ee.get(e.agentIdentifier),void 0===e.api&&(e.api=N(e.agentIdentifier,n,e.runSoftNavOverSpa)),void 0===e.exposed&&(e.exposed=f),P=!0}},374:(e,t,r)=>{r.nc=(()=>{try{return document?.currentScript?.nonce}catch(e){}return""})()},860:(e,t,r)=>{"use strict";r.d(t,{$J:()=>u,K7:()=>s,P3:()=>c,XX:()=>i,qY:()=>n,v4:()=>a});const n="events",i="jserrors",o="browser/blobs",a="rum",s={ajax:"ajax",genericEvents:"generic_events",jserrors:i,logging:"logging",metrics:"metrics",pageAction:"page_action",pageViewEvent:"page_view_event",pageViewTiming:"page_view_timing",sessionReplay:"session_replay",sessionTrace:"session_trace",softNav:"soft_navigations",spa:"spa"},c={[s.pageViewEvent]:1,[s.pageViewTiming]:2,[s.metrics]:3,[s.jserrors]:4,[s.spa]:5,[s.ajax]:6,[s.sessionTrace]:7,[s.softNav]:8,[s.sessionReplay]:9,[s.logging]:10,[s.genericEvents]:11},u={[s.pageViewEvent]:a,[s.pageViewTiming]:n,[s.ajax]:n,[s.spa]:n,[s.softNav]:n,[s.metrics]:i,[s.jserrors]:i,[s.sessionTrace]:o,[s.sessionReplay]:o,[s.logging]:"browser/logs",[s.genericEvents]:"ins"}}},n={};function i(e){var t=n[e];if(void 0!==t)return t.exports;var o=n[e]={exports:{}};return r[e](o,o.exports,i),o.exports}i.m=r,i.d=(e,t)=>{for(var r in t)i.o(t,r)&&!i.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},i.f={},i.e=e=>Promise.all(Object.keys(i.f).reduce(((t,r)=>(i.f[r](e,t),t)),[])),i.u=e=>"nr-rum-1.284.1.min.js",i.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),e={},t="NRBA-1.284.1.PROD:",i.l=(r,n,o,a)=>{if(e[r])e[r].push(n);else{var s,c;if(void 0!==o)for(var u=document.getElementsByTagName("script"),l=0;l<u.length;l++){var d=u[l];if(d.getAttribute("src")==r||d.getAttribute("data-webpack")==t+o){s=d;break}}if(!s){c=!0;var f={296:"sha512-I5tV79u0jhQi7aouG2M+kaxUnC8otPe2WXnytHYva1pERqoP8GhxHAi7OJZyNzSvBOojrdGEBSfIO5Sy7scrmA=="};(s=document.createElement("script")).charset="utf-8",s.timeout=120,i.nc&&s.setAttribute("nonce",i.nc),s.setAttribute("data-webpack",t+o),s.src=r,0!==s.src.indexOf(window.location.origin+"/")&&(s.crossOrigin="anonymous"),f[a]&&(s.integrity=f[a])}e[r]=[n];var g=(t,n)=>{s.onerror=s.onload=null,clearTimeout(p);var i=e[r];if(delete e[r],s.parentNode&&s.parentNode.removeChild(s),i&&i.forEach((e=>e(n))),t)return t(n)},p=setTimeout(g.bind(null,void 0,{type:"timeout",target:s}),12e4);s.onerror=g.bind(null,s.onerror),s.onload=g.bind(null,s.onload),c&&document.head.appendChild(s)}},i.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.p="https://js-agent.newrelic.com/",(()=>{var e={374:0,840:0};i.f.j=(t,r)=>{var n=i.o(e,t)?e[t]:void 0;if(0!==n)if(n)r.push(n[2]);else{var o=new Promise(((r,i)=>n=e[t]=[r,i]));r.push(n[2]=o);var a=i.p+i.u(t),s=new Error;i.l(a,(r=>{if(i.o(e,t)&&(0!==(n=e[t])&&(e[t]=void 0),n)){var o=r&&("load"===r.type?"missing":r.type),a=r&&r.target&&r.target.src;s.message="Loading chunk "+t+" failed.\n("+o+": "+a+")",s.name="ChunkLoadError",s.type=o,s.request=a,n[1](s)}}),"chunk-"+t,t)}};var t=(t,r)=>{var n,o,[a,s,c]=r,u=0;if(a.some((t=>0!==e[t]))){for(n in s)i.o(s,n)&&(i.m[n]=s[n]);if(c)c(i)}for(t&&t(r);u<a.length;u++)o=a[u],i.o(e,o)&&e[o]&&e[o][0](),e[o]=0},r=self["webpackChunk:NRBA-1.284.1.PROD"]=self["webpackChunk:NRBA-1.284.1.PROD"]||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})(),(()=>{"use strict";i(374);var e=i(944),t=i(344),r=i(566);class n{agentIdentifier;constructor(){this.agentIdentifier=(0,r.LA)(16)}#e(t,...r){if("function"==typeof this.api?.[t])return this.api[t](...r);(0,e.R)(35,t)}addPageAction(e,t){return this.#e("addPageAction",e,t)}recordCustomEvent(e,t){return this.#e("recordCustomEvent",e,t)}setPageViewName(e,t){return this.#e("setPageViewName",e,t)}setCustomAttribute(e,t,r){return this.#e("setCustomAttribute",e,t,r)}noticeError(e,t){return this.#e("noticeError",e,t)}setUserId(e){return this.#e("setUserId",e)}setApplicationVersion(e){return this.#e("setApplicationVersion",e)}setErrorHandler(e){return this.#e("setErrorHandler",e)}addRelease(e,t){return this.#e("addRelease",e,t)}log(e,t){return this.#e("log",e,t)}}class o extends n{#e(t,...r){if("function"==typeof this.api?.[t])return this.api[t](...r);(0,e.R)(35,t)}start(){return this.#e("start")}finished(e){return this.#e("finished",e)}recordReplay(){return this.#e(t.G4.RECORD)}pauseReplay(){return this.#e(t.G4.PAUSE)}addToTrace(e){return this.#e("addToTrace",e)}setCurrentRouteName(e){return this.#e("setCurrentRouteName",e)}interaction(){return this.#e("interaction")}wrapLogger(e,t,r){return this.#e("wrapLogger",e,t,r)}}var a=i(860),s=i(217);const c=Object.values(a.K7);function u(e){const t={};return c.forEach((r=>{t[r]=function(e,t){return!0===(0,s.gD)(t,"".concat(e,".enabled"))}(r,e)})),t}var l=i(603);var d=i(687),f=i(234),g=i(289),p=i(154),m=i(384);const h=e=>p.RI&&!0===(0,s.gD)(e,"privacy.cookies_enabled");function v(e){return!!(0,m.dV)().o.MO&&h(e)&&!0===(0,s.gD)(e,"session_trace.enabled")}var b=i(389);class y extends f.W{constructor(e,t,r=!0){super(e.agentIdentifier,t),this.auto=r,this.abortHandler=void 0,this.featAggregate=void 0,this.onAggregateImported=void 0,!1===e.init[this.featureName].autoStart&&(this.auto=!1),this.auto?(0,d.Ak)(e.agentIdentifier,t):this.ee.on("manual-start-all",(0,b.J)((()=>{(0,d.Ak)(e.agentIdentifier,this.featureName),this.auto=!0,this.importAggregator(e)})))}importAggregator(t,r={}){if(this.featAggregate||!this.auto)return;let n;this.onAggregateImported=new Promise((e=>{n=e}));const o=async()=>{let o;try{if(h(this.agentIdentifier)){const{setupAgentSession:e}=await i.e(296).then(i.bind(i,861));o=e(t)}}catch(t){(0,e.R)(20,t),this.ee.emit("internal-error",[t]),this.featureName===a.K7.sessionReplay&&this.abortHandler?.()}try{if(!this.#t(this.featureName,o))return(0,d.Ze)(this.agentIdentifier,this.featureName),void n(!1);const{lazyFeatureLoader:e}=await i.e(296).then(i.bind(i,103)),{Aggregate:a}=await e(this.featureName,"aggregate");this.featAggregate=new a(t,r),t.runtime.harvester.initializedAggregates.push(this.featAggregate),n(!0)}catch(t){(0,e.R)(34,t),this.abortHandler?.(),(0,d.Ze)(this.agentIdentifier,this.featureName,!0),n(!1),this.ee&&this.ee.abort()}};p.RI?(0,g.GG)((()=>o()),!0):o()}#t(e,t){switch(e){case a.K7.sessionReplay:return v(this.agentIdentifier)&&!!t;case a.K7.sessionTrace:return!!t;default:return!0}}}var w=i(630);class R extends y{static featureName=w.T;constructor(e,t=!0){super(e,w.T,t),this.importAggregator(e)}}var A=i(908),E=i(843),_=i(878),x=i(782),N=i(863);class k extends y{static featureName=x.T;constructor(e,t=!0){super(e,x.T,t),p.RI&&((0,E.u)((()=>(0,A.p)("docHidden",[(0,N.t)()],void 0,x.T,this.ee)),!0),(0,_.sp)("pagehide",(()=>(0,A.p)("winPagehide",[(0,N.t)()],void 0,x.T,this.ee))),this.importAggregator(e))}}var T=i(773);class j extends y{static featureName=T.TZ;constructor(e,t=!0){super(e,T.TZ,t),p.RI&&document.addEventListener("securitypolicyviolation",(e=>{(0,A.p)(T.xV,["Generic/CSPViolation/Detected"],void 0,this.featureName,this.ee)})),this.importAggregator(e)}}new class extends o{constructor(t){super(),p.gm?(this.features={},(0,m.bQ)(this.agentIdentifier,this),this.desiredFeatures=new Set(t.features||[]),this.desiredFeatures.add(R),this.runSoftNavOverSpa=[...this.desiredFeatures].some((e=>e.featureName===a.K7.softNav)),(0,l.j)(this,t,t.loaderType||"agent"),this.run()):(0,e.R)(21)}get config(){return{info:this.info,init:this.init,loader_config:this.loader_config,runtime:this.runtime}}run(){try{const t=u(this.agentIdentifier),r=[...this.desiredFeatures];r.sort(((e,t)=>a.P3[e.featureName]-a.P3[t.featureName])),r.forEach((r=>{if(!t[r.featureName]&&r.featureName!==a.K7.pageViewEvent)return;if(this.runSoftNavOverSpa&&r.featureName===a.K7.spa)return;if(!this.runSoftNavOverSpa&&r.featureName===a.K7.softNav)return;const n=function(e){switch(e){case a.K7.ajax:return[a.K7.jserrors];case a.K7.sessionTrace:return[a.K7.ajax,a.K7.pageViewEvent];case a.K7.sessionReplay:return[a.K7.sessionTrace];case a.K7.pageViewTiming:return[a.K7.pageViewEvent];default:return[]}}(r.featureName).filter((e=>!(e in this.features)));n.length>0&&(0,e.R)(36,{targetFeature:r.featureName,missingDependencies:n}),this.features[r.featureName]=new r(this)}))}catch(t){(0,e.R)(22,t);for(const e in this.features)this.features[e].abortHandler?.();const r=(0,m.Zm)();delete r.initializedAgents[this.agentIdentifier]?.api,delete r.initializedAgents[this.agentIdentifier]?.features,delete this.sharedAggregator;return r.ee.get(this.agentIdentifier).abort(),!1}}}({features:[R,k,j],loaderType:"lite"})})()})();</script>
<meta content="%%SITE%%: Link Slot Gacor Hari Ini Main Slot777 Gacor Online Resmi Gampang Maxwin" name="title"/>
<meta content="%%DESCRIPTION%%" name="description"/>
<meta content="%%SITE%%, situs slot, slot gacor, slot777, apk slot777, link slot, apk slot, slot maxwin, slot trik jitu" name="keywords"/>
<meta content="Global" name="distribution">
<meta content="general" name="rating">
<meta content="ID" name="geo.region">
<meta content="Indonesia" name="geo.placename">
<meta content="SLOT GACOR" name="author"/>
<meta content="SLOT GACOR" name="publisher"/>
<meta content="SLOT GACOR" name="copyright"/>
<meta content="%%ROBOTS%%" name="robots"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
%%GSC_META%%
<meta content="telephone=no" name="format-detection"/>
<title>%%TITLE%%</title>
<link href="%%ORIGIN_SLASH%%" media="all and (min-width: 1px)" rel="stylesheet" type="text/css"/>
<link href="https://www.mothercare.co.id/static/version1741963345/_cache/merged/56b5914bf926abed304351798d035de2.min.css" media="all" rel="stylesheet" type="text/css"/>
<link href="https://www.mothercare.co.id/static/version1741963345/_cache/merged/fac5fbb731d8a9118c7561e0e8b1fc67.min.css" media="screen, print" rel="stylesheet" type="text/css"/>
<!--[if IE 11]>
<script  type="text/javascript"  src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Klevu_Search/js/klevu/ieUrlPolyfill.min.js"></script>
<![endif]-->
<script defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/js/kanmo_custom.min.js" type="text/javascript"></script>
<script src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Kanmo_BookingAppointment/js/product_detail_handler.min.js" type="text/javascript"></script>
<link as="font" crossorigin="anonymous" href="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/fonts/mothercare_2020-demi-webfont.woff2" rel="preload"/>
<link as="font" crossorigin="anonymous" href="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/fonts/mothercare_2020-regular-webfont.woff2" rel="preload"/>
<link as="font" crossorigin="anonymous" href="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/fonts/mothercare_2020-thin-webfont.woff2" rel="preload"/>
%%AMPHTML%%
%%ALT_ID_ID%%
%%ALT_PLAIN%%
%%ALT_ID%%
%%ALT_EN%%
%%ALT_X_DEFAULT%%
<link href="%%FAVICON%%" rel="icon" type="image/x-icon"/>
<link href="%%FAVICON%%" rel="shortcut icon" type="image/x-icon"/>
<link href="%%CANONICAL%%" rel="canonical"/>

<link as="style" href="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/css/default.min.css" media="all" onload="this.rel='stylesheet'" rel="stylesheet" type="text/css">
<link href="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/css/deferred-default.min.css" media="all" onload="media='all'" rel="stylesheet" type="text/css">
<meta content="product" property="og:type"/>
<meta content="%%TITLE%%" property="og:title"/>
<meta content="%%IMAGE%%" property="og:image"/>
<meta content="%%DESCRIPTION%%" property="og:description"/>
<meta content="%%CANONICAL%%" property="og:url"/>
<meta content="10000" property="product:price:amount"/>
<meta content="IDR" property="product:price:currency"/>
<script id="klevu_initsessiondata" type="text/javascript">
    var nowUnixtime = parseInt(Date.now() / 1000);

    function klevufejs_getCookie(name) {
        if (typeof (name) === "undefined") {
            name = "klv_mage";
        }
        var c = "",
            ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i++) {
            c = ca[i];
            if (typeof c !== "string") {
                continue;
            }
            var cookiePair = c.split("=");

            if (name === cookiePair[0].trim()) {
                try {
                    return JSON.parse(decodeURIComponent(cookiePair[1]));
                } catch (err) {
                    // this is fine, data will be regenerated
                }
            }
        }
        return {
            expire_sections: {}
        };
    }

    document.addEventListener('klevu.customerData.loaded', function (e) {
        var klevufejs_cookie = klevufejs_getCookie();
        klevufejs_cookie.expire_sections.customerData = nowUnixtime + 600;

        document.cookie = "klv_mage=" + JSON.stringify(klevufejs_cookie) + ";" + (new Date((nowUnixtime + 3600) * 1000).toUTCString()) + ";path=/;SameSite=Strict";
    });

    var klevufejs_cookie = klevufejs_getCookie();
    var klevuData = {
        ...{
            customerData: {
                revalidate_after: -1
            }
        },
        ...JSON.parse(window.localStorage.getItem('klv_mage') || '{}')
    };

    const klevuCustomerDataLoadedEvent = document.createEvent('CustomEvent');
    klevuCustomerDataLoadedEvent.initEvent('klevu.customerData.loaded', false, true);
    const klevuCustomerDataLoadErrorEvent = document.createEvent('CustomEvent');
    klevuCustomerDataLoadErrorEvent.initEvent('klevu.customerData.loadError', false, true);

    if (typeof klevufejs_cookie.expire_sections !== "object"
        || (klevufejs_cookie.expire_sections.customerData || -1) < nowUnixtime
        || klevuData.customerData.revalidate_after < nowUnixtime
    ) {
        var xhttp = new XMLHttpRequest();
        xhttp.onerror = function (request) {
            document.dispatchEvent(klevuCustomerDataLoadErrorEvent);
        };
        xhttp.ontimeout = function (request) {
            this.onerror(request);
        };
        xhttp.onload = function (request) {
            if (this.status >= 400 || this.timeout) {
                this.onerror(request);
                return;
            }

            var klevuData = JSON.parse(window.localStorage.getItem('klv_mage') || '{}');
            klevuData.customerData = JSON.parse(this.response);
            window.localStorage.setItem('klv_mage', JSON.stringify(klevuData));

            document.dispatchEvent(klevuCustomerDataLoadedEvent);
        };
        xhttp.open('GET', 'https://www.mothercare.co.id/rest/V1/klevu/customerData', false);
        xhttp.send();
    } else {
        document.dispatchEvent(klevuCustomerDataLoadedEvent);
    }
</script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/knockout-3.5.1.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/extend-ko.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/turbolinks-5.2.0.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/underscore-1.13.1.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/tabbable-5.2.1.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/focus-trap-6.7.1.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/cash-8.1.2.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/extend-cash.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/swiped-events-1.1.4.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/js.cookie-3.0.1.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/class.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/double-tap.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/globals.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/load-script.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/async.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/request.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/translate.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/validator.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/component.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/cookie.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/storage.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/sections.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/main.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/turbo.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/core/define.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/common/common.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/common/url.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/common/theme.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/js/breeze/theme-blank.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/js/breeze/extend.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/js/breeze/custom.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/validators/core.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/form.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/lite-yt-embed.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/dropdown.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/dropdown-dialog.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/collapsible.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/tabs.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/accordion.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/toggle-advanced.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/modal.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/confirm.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/ui/alert.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/customer-data.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/customer.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/form-key.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/page-cache.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/messages.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/block-loader.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/data-post.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/menu.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/minicart.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/proceed-to-checkout.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/catalog-add-to-cart.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/product-list-toolbar-form.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/compare-products.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/validation.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/redirect-url.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/quick-search.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/msrp.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/google-tag-manager-universal.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/google-tag-manager.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/wishlist.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/recent-products.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/form-provider.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/login-as-customer.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/captcha.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/google-recaptcha.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/modal-manager.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/breeze/ajaxpro.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/breeze/modal.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/breeze/data-post.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/breeze/catalog-add-to-cart.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Ajaxpro/js/breeze/catalog-product-view.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Easytabs/js/breeze/tabs.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Easytabs/js/tabs-toolbar.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Easytabs/js/tabs-updater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/validate-product.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/register-viewed-products.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/common/price-utils.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/price-box.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/price-options.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/breadcrumbs.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/gallery.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/lib/panzoom-4.5.0.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/gallery-panzoom.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/reviews.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/common/shuffle-and-reveal.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/upsell-products.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/related-products.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/validators/cart.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/product-configure.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/bundle.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/configurable.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/swatch-renderer.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/configurable-configure.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/validators/customer.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/address.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/change-email-password.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/region-updater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/address-validation.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/customer-assistance.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/last-ordered-items.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/logout-redirect.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Kanmo_CustomerName/js/breeze/customer-name.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_CustomerCustomAttributes/js/breeze/cityUpdater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_CustomerCustomAttributes/js/breeze/districtUpdater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_CustomerCustomAttributes/js/breeze/postalcodeUpdater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_CustomerCustomAttributes/js/breeze/subdistrictUpdater.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/pagebuilder/slider.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/pagebuilder/carousel.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/pagebuilder/tabs.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/pagebuilder/map.min.js"></script>
<script data-breeze="" defer="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Swissup_Breeze/js/components/pagebuilder/initializer.min.js"></script>

<style id="%%SITE%%-genos-theme">
:root{
    --genos-black:#050505;
    --genos-deep:#0a0a0b;
    --genos-panel:#111113;
    --genos-metal:#1a1a1d;
    --genos-metal2:#242428;
    --genos-gold:#ffb300;
    --genos-yellow:#ffd84d;
    --genos-orange:#ff7200;
    --genos-fire:#ff4300;
    --genos-text:#f5f5f5;
    --genos-soft:#c9c9ce;
    --genos-muted:#96969d;
}

html,
body,
.page-wrapper{
    background:
        radial-gradient(circle at 18% 8%,rgba(255,179,0,.10),transparent 28%),
        radial-gradient(circle at 82% 30%,rgba(255,74,0,.07),transparent 30%),
        linear-gradient(180deg,#050505 0%,#0b0b0d 48%,#030303 100%)!important;
    color:var(--genos-text)!important;
}

#maincontent,
#maincontent .columns,
#maincontent .column.main,
#maincontent .product-info-wrapper,
#maincontent .product-info-main{
    background:transparent!important;
    color:var(--genos-text)!important;
}

#maincontent{
    position:relative;
    background:
        radial-gradient(circle at 12% 12%,rgba(255,190,25,.10),transparent 25%),
        radial-gradient(circle at 88% 28%,rgba(255,76,0,.08),transparent 27%),
        repeating-linear-gradient(125deg,transparent 0,transparent 45px,rgba(255,180,0,.015) 46px,transparent 47px),
        linear-gradient(180deg,#050506 0%,#0c0c0e 45%,#050505 100%)!important;
    border-top:1px solid rgba(255,184,0,.28);
}

#maincontent .product-info-wrapper{
    position:relative;
    padding:18px!important;
    border:1px solid rgba(255,180,0,.28);
    border-radius:20px;
    background:linear-gradient(145deg,rgba(24,24,27,.97),rgba(7,7,8,.98))!important;
    box-shadow:
        0 22px 55px rgba(0,0,0,.65),
        0 0 28px rgba(255,145,0,.06),
        inset 0 1px 0 rgba(255,255,255,.07),
        inset 0 0 25px rgba(255,166,0,.025);
    overflow:hidden;
}

#maincontent .product-info-wrapper:before{
    content:"";
    position:absolute;
    top:0;
    left:7%;
    right:7%;
    height:2px;
    background:linear-gradient(90deg,transparent,#ff7300,#ffd84d,#fff3a5,#ffd84d,#ff7300,transparent);
    box-shadow:
        0 0 8px #ff9d00,
        0 0 18px rgba(255,115,0,.65);
    pointer-events:none;
}

#maincontent .page-title-wrapper.product h1.page-title{
    position:relative;
    margin:0 0 14px!important;
    padding:17px 20px;
    overflow:hidden;
    border:1px solid rgba(255,185,35,.45);
    border-left:4px solid var(--genos-orange);
    border-radius:8px 18px 8px 18px;
    background:linear-gradient(110deg,#090909 0%,#191919 47%,#0a0a0a 100%)!important;
    box-shadow:
        0 10px 25px rgba(0,0,0,.55),
        inset 0 1px 0 rgba(255,255,255,.07),
        0 0 16px rgba(255,157,0,.08);
}

#maincontent .page-title-wrapper.product h1.page-title:after{
    content:"";
    position:absolute;
    width:150px;
    height:2px;
    right:16px;
    bottom:7px;
    background:linear-gradient(90deg,transparent,#ff6a00,#ffd43b);
    box-shadow:
        0 0 6px #ff9d00,
        0 0 12px rgba(255,102,0,.7);
}

#maincontent .page-title .base,
#maincontent h1.page-title .base{
    background:none!important;
    -webkit-text-fill-color:#fff4c2!important;
    color:#fff4c2!important;
    font-weight:900;
    letter-spacing:.5px;
    text-shadow:
        0 2px 2px #000,
        0 0 8px rgba(255,186,39,.25),
        0 0 20px rgba(255,100,0,.12);
}

#maincontent h2,
#maincontent h3,
#maincontent h4,
#maincontent h5{
    color:var(--genos-yellow)!important;
    font-weight:800;
    text-shadow:0 0 9px rgba(255,168,0,.16)!important;
}

#maincontent p,
#maincontent li,
#maincontent td,
#maincontent th,
#maincontent label,
#maincontent .product.attribute.description,
#maincontent .product.attribute.overview{
    color:#d4d4d8!important;
}

#maincontent a:not(.action):not(.product-item-link){
    color:#ffbd35!important;
    text-decoration-color:rgba(255,189,53,.35);
}

#maincontent a:not(.action):not(.product-item-link):hover{
    color:#ffe682!important;
    text-shadow:0 0 8px rgba(255,174,0,.40);
}

#maincontent .product-info-price{
    margin:10px 0 12px!important;
    padding:14px 17px!important;
    border:1px solid rgba(255,181,32,.32)!important;
    border-radius:7px 15px 7px 15px;
    background:linear-gradient(135deg,#0c0c0d,#1b1b1e)!important;
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.06),
        0 7px 18px rgba(0,0,0,.35);
}

#maincontent .price,
#maincontent .price-box .price{
    color:#ffd442!important;
    font-weight:900;
    text-shadow:0 0 7px rgba(255,163,0,.32);
}

#maincontent .price-label{
    color:#aaaab0!important;
}

#maincontent .kc-cashback{
    margin:10px 0!important;
    padding:9px 11px!important;
    border:1px solid rgba(255,165,0,.32)!important;
    border-radius:8px;
    background:linear-gradient(135deg,#0a0a0b,#19191b)!important;
}

#maincontent .kc-points-lable{
    display:inline-flex!important;
    align-items:center;
    gap:9px;
    padding:8px 11px!important;
    border:1px solid rgba(255,199,62,.52)!important;
    border-radius:7px;
    background:linear-gradient(180deg,#19140a,#090909)!important;
    color:#ffd75b!important;
    font-weight:900;
    text-decoration:none!important;
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.08),
        0 0 12px rgba(255,148,0,.07);
}

#maincontent .kcpoints-img{
    border-radius:6px;
    filter:drop-shadow(0 0 6px rgba(255,170,0,.45));
}

#maincontent .kredivo-label{
    margin:10px 0!important;
    padding:11px 14px!important;
    border:1px solid rgba(255,171,0,.18)!important;
    border-radius:8px;
    background:#101012!important;
    color:#aaaab0!important;
}

#maincontent .available-block{
    margin:12px 0!important;
    padding:15px 17px!important;
    border:1px solid rgba(255,175,20,.26)!important;
    border-radius:8px 16px 8px 16px;
    background:linear-gradient(135deg,#0c0c0e,#19191c)!important;
    box-shadow:
        0 9px 24px rgba(0,0,0,.38),
        inset 0 1px 0 rgba(255,255,255,.05);
}

#maincontent .available-block .title,
#maincontent .avail-text{
    color:#ffc43d!important;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.8px;
}

#maincontent .available-block .lables{
    display:flex;
    flex-wrap:wrap;
    gap:9px;
    margin-top:9px;
}

#maincontent .available-block .home-div,
#maincontent .available-block .store-div{
    display:flex;
    align-items:center;
    gap:8px;
    padding:10px 13px;
    border:1px solid rgba(255,182,29,.20);
    border-radius:7px;
    background:linear-gradient(180deg,#161618,#090909)!important;
    color:#d6d6da!important;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.04);
}

#maincontent .available-block img{
    filter:
        brightness(0)
        saturate(100%)
        invert(78%)
        sepia(98%)
        saturate(1430%)
        hue-rotate(345deg)
        brightness(103%);
}

#maincontent .product.attribute.description,
#maincontent .product-options-wrapper,
#maincontent .box-tocart,
#maincontent .product-add-form{
    background:linear-gradient(145deg,#111113,#080809)!important;
    border-color:rgba(255,176,20,.22)!important;
    color:#d4d4d8!important;
}

#maincontent input,
#maincontent select,
#maincontent textarea{
    background:#0b0b0d!important;
    border:1px solid #4c3a16!important;
    color:#f6f6f6!important;
    box-shadow:inset 0 2px 6px rgba(0,0,0,.45);
}

#maincontent input:focus,
#maincontent select:focus,
#maincontent textarea:focus{
    border-color:#ffb522!important;
    box-shadow:
        0 0 0 2px rgba(255,178,24,.10),
        0 0 13px rgba(255,127,0,.12)!important;
    outline:none!important;
}

#maincontent .action.primary,
#maincontent button.action.primary,
#maincontent .action.tocart.primary{
    position:relative;
    overflow:hidden;
    background:linear-gradient(180deg,#ffe36c 0%,#ffb20e 44%,#e36c00 100%)!important;
    border:1px solid #ffd458!important;
    border-radius:7px!important;
    color:#100b02!important;
    box-shadow:
        0 8px 20px rgba(0,0,0,.45),
        0 0 13px rgba(255,130,0,.20),
        inset 0 1px 0 rgba(255,255,255,.60),
        inset 0 -2px 4px rgba(113,42,0,.25)!important;
    font-weight:900;
    letter-spacing:.5px;
    text-shadow:0 1px rgba(255,255,255,.25);
    transition:
        transform .18s ease,
        filter .18s ease,
        box-shadow .18s ease;
}

#maincontent .action.primary:before,
#maincontent button.action.primary:before,
#maincontent .action.tocart.primary:before{
    content:"";
    position:absolute;
    top:-40%;
    left:-45%;
    width:28%;
    height:180%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.72),transparent);
    transform:skewX(-25deg);
    animation:genosEnergyScan 3.2s ease-in-out infinite;
    pointer-events:none;
}

@keyframes genosEnergyScan{
    0%,60%{
        left:-45%;
        opacity:0;
    }
    68%{
        opacity:1;
    }
    88%{
        left:125%;
        opacity:.8;
    }
    100%{
        left:125%;
        opacity:0;
    }
}

#maincontent .action.primary:hover,
#maincontent button.action.primary:hover,
#maincontent .action.tocart.primary:hover{
    filter:brightness(1.13) saturate(1.12);
    transform:translateY(-2px);
    box-shadow:
        0 10px 25px rgba(0,0,0,.50),
        0 0 12px rgba(255,196,40,.45),
        0 0 27px rgba(255,93,0,.22),
        inset 0 1px 0 rgba(255,255,255,.68)!important;
}

#maincontent .action.primary:active,
#maincontent button.action.primary:active,
#maincontent .action.tocart.primary:active{
    transform:translateY(2px) scale(.985);
    filter:brightness(.92);
    box-shadow:
        0 2px 7px rgba(0,0,0,.55),
        inset 0 3px 7px rgba(82,25,0,.32)!important;
}

@media(max-width:767px){
    #maincontent .product-info-wrapper{
        padding:10px!important;
        border-radius:12px;
    }

    #maincontent .page-title-wrapper.product h1.page-title{
        padding:13px 14px!important;
        font-size:22px!important;
        border-radius:7px 13px 7px 13px;
    }

    #maincontent .available-block{
        padding:12px!important;
    }

    #maincontent .action.primary,
    #maincontent button.action.primary,
    #maincontent .action.tocart.primary{
        min-height:48px;
        font-size:16px!important;
    }
}

@media(prefers-reduced-motion:reduce){
    #maincontent .action.primary:before,
    #maincontent button.action.primary:before,
    #maincontent .action.tocart.primary:before{
        animation:none!important;
    }
}
</style>

<style id="%%SITE%%-genos-ticker">
.idx-news-ticker{
  position:relative;
  width:100%;
  height:44px;
  display:flex;
  align-items:center;
  overflow:hidden;
  box-sizing:border-box;
  isolation:isolate;

  background:
    radial-gradient(circle at 18% 50%,rgba(255,180,0,.13),transparent 28%),
    radial-gradient(circle at 82% 50%,rgba(255,77,0,.08),transparent 25%),
    linear-gradient(90deg,#050505 0%,#15110a 48%,#050505 100%);

  border-top:1px solid rgba(255,192,50,.48);
  border-bottom:1px solid rgba(255,116,0,.40);

  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.07),
    inset 0 -1px 0 rgba(0,0,0,.8),
    inset 0 0 22px rgba(255,122,0,.035),
    0 6px 22px rgba(0,0,0,.48);
}

.idx-news-ticker::before{
  content:"";
  position:absolute;
  z-index:4;
  top:0;
  left:-40%;
  width:40%;
  height:2px;

  background:linear-gradient(
    90deg,
    transparent,
    #ff5a00,
    #ffb300,
    #fff09b,
    #ffb300,
    #ff5a00,
    transparent
  );

  box-shadow:
    0 0 5px #ff9d00,
    0 0 12px rgba(255,102,0,.8),
    0 0 22px rgba(255,164,0,.35);

  animation:genosTickerEnergy 3.8s linear infinite;
}

.idx-news-ticker::after{
  content:"";
  position:absolute;
  z-index:3;
  inset:0;
  pointer-events:none;

  background:
    linear-gradient(
      90deg,
      #050505 0%,
      rgba(5,5,5,.88) 2%,
      transparent 8%,
      transparent 92%,
      rgba(5,5,5,.88) 98%,
      #050505 100%
    );
}

.idx-news-track{
  position:relative;
  z-index:2;

  display:flex;
  align-items:center;

  width:max-content;
  min-width:max-content;

  white-space:nowrap;
  will-change:transform;

  animation:genosTickerMove 23s linear infinite;
}

.idx-news-track span{
  position:relative;
  flex:none;

  display:flex;
  align-items:center;
  gap:14px;

  margin-right:20px;
  padding:7px 18px 7px 12px;

  color:#f4f4f4;

  font-size:13px;
  font-weight:800;
  letter-spacing:1px;

  text-transform:uppercase;

  text-shadow:
    0 1px 2px #000,
    0 0 8px rgba(255,174,0,.18);

  border-right:1px solid rgba(255,184,25,.20);
}

.idx-news-track span::before{
  content:"⚡";

  display:flex;
  align-items:center;
  justify-content:center;

  width:23px;
  height:23px;

  flex:0 0 23px;

  color:#1b1000;
  font-size:12px;

  background:
    linear-gradient(
      145deg,
      #fff09a 0%,
      #ffc22e 38%,
      #ff8500 75%,
      #b94300 100%
    );

  border:1px solid #ffd454;

  border-radius:5px 10px 5px 10px;

  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.65),
    inset 0 -2px 4px rgba(98,32,0,.28),
    0 0 7px rgba(255,175,0,.50),
    0 0 15px rgba(255,83,0,.18);

  transform:skewX(-6deg);

  animation:genosBoltPulse 1.7s ease-in-out infinite;
}

.idx-news-track span::after{
  content:"CORE ACTIVE";

  position:relative;

  display:inline-flex;
  align-items:center;

  padding:4px 10px 4px 18px;

  border:1px solid rgba(255,178,30,.40);

  border-radius:4px 10px 4px 10px;

  background:
    linear-gradient(
      180deg,
      rgba(55,39,9,.88),
      rgba(18,14,7,.94)
    );

  color:#ffd85d;

  font-size:9px;
  font-weight:900;
  letter-spacing:1px;

  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.07),
    0 0 10px rgba(255,145,0,.08);

  text-shadow:
    0 0 6px rgba(255,185,30,.35);
}

.idx-news-track span:nth-child(odd)::after{
  content:"SYSTEM ONLINE";
}

.idx-news-track span:nth-child(even)::after{
  content:"CORE ACTIVE";
}

@keyframes genosTickerMove{
  0%{
    transform:translate3d(0,0,0);
  }

  100%{
    transform:translate3d(-50%,0,0);
  }
}

@keyframes genosTickerEnergy{
  0%{
    left:-40%;
    opacity:0;
  }

  8%{
    opacity:1;
  }

  85%{
    opacity:1;
  }

  100%{
    left:115%;
    opacity:0;
  }
}

@keyframes genosBoltPulse{
  0%,100%{
    filter:brightness(1);
    box-shadow:
      inset 0 1px 0 rgba(255,255,255,.65),
      inset 0 -2px 4px rgba(98,32,0,.28),
      0 0 6px rgba(255,175,0,.42),
      0 0 12px rgba(255,83,0,.15);
  }

  50%{
    filter:brightness(1.22);
    box-shadow:
      inset 0 1px 0 rgba(255,255,255,.75),
      inset 0 -2px 4px rgba(98,32,0,.28),
      0 0 10px rgba(255,198,50,.85),
      0 0 22px rgba(255,83,0,.35);
  }
}

.idx-news-ticker:hover .idx-news-track{
  animation-play-state:paused;
}

@media(max-width:768px){
  .idx-news-ticker{
    height:38px;

    border-top-color:rgba(255,192,50,.38);
    border-bottom-color:rgba(255,116,0,.34);
  }

  .idx-news-track{
    animation-duration:18s;
  }

  .idx-news-track span{
    gap:9px;
    margin-right:10px;
    padding:5px 12px 5px 7px;

    font-size:10.5px;
    letter-spacing:.65px;
  }

  .idx-news-track span::before{
    width:20px;
    height:20px;
    flex-basis:20px;
    font-size:10px;
  }

  .idx-news-track span::after{
    padding:3px 7px 3px 13px;
    font-size:7.5px;
    letter-spacing:.65px;
  }
}

@media(prefers-reduced-motion:reduce){
  .idx-news-track,
  .idx-news-ticker::before,
  .idx-news-track span::before{
    animation:none!important;
  }
}
</style>


<style id="%%SITE%%-genos-product-action">
#maincontent .page-title-wrapper.product h1.page-title{
  position:relative;
  overflow:hidden;
  padding:19px 22px!important;
  border:1px solid rgba(255,183,28,.42)!important;
  border-left:4px solid #ff6a00!important;
  border-radius:8px 18px 8px 18px!important;
  background:
    radial-gradient(circle at 92% 5%,rgba(255,111,0,.15),transparent 34%),
    radial-gradient(circle at 15% 100%,rgba(255,201,46,.07),transparent 32%),
    linear-gradient(135deg,#080808 0%,#191919 52%,#090909 100%)!important;
  box-shadow:
    0 15px 35px rgba(0,0,0,.55),
    0 0 20px rgba(255,129,0,.07),
    inset 0 1px 0 rgba(255,255,255,.07)!important;
}

#maincontent .page-title-wrapper.product h1.page-title:before{
  content:"";
  position:absolute;
  top:0;
  left:-40%;
  width:35%;
  height:2px;
  background:linear-gradient(90deg,transparent,#ff5a00,#ffd54a,#fff2a0,#ffd54a,transparent);
  box-shadow:
    0 0 7px #ff9200,
    0 0 16px rgba(255,81,0,.65);
  animation:genosTitleEnergy 4s linear infinite;
}

#maincontent .page-title-wrapper.product h1.page-title:after{
  content:"";
  position:absolute;
  left:20px;
  right:20px;
  bottom:0;
  height:1px;
  background:linear-gradient(90deg,transparent,#ff6a00 20%,#ffd94d 50%,#ff6a00 80%,transparent);
  opacity:.8;
  box-shadow:0 0 7px rgba(255,137,0,.5);
}

#maincontent .page-title .base{
  position:relative;
  z-index:2;
  font-size:25px!important;
  line-height:1.45!important;
  letter-spacing:.1px;
  color:#fff1bd!important;
  -webkit-text-fill-color:#fff1bd!important;
  font-weight:900!important;
  text-shadow:
    0 2px 2px #000,
    0 0 9px rgba(255,188,35,.20);
}

#maincontent .product-config{
  position:relative;
  align-self:flex-start;
  overflow:hidden;
  padding:18px!important;
  border:1px solid rgba(255,173,19,.27)!important;
  border-radius:9px 20px 9px 20px!important;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,111,0,.12),transparent 38%),
    radial-gradient(circle at 0% 100%,rgba(255,199,41,.05),transparent 30%),
    linear-gradient(160deg,#151516 0%,#080809 60%,#050505 100%)!important;
  box-shadow:
    0 20px 45px rgba(0,0,0,.58),
    0 0 25px rgba(255,110,0,.04),
    inset 0 1px 0 rgba(255,255,255,.06)!important;
}

#maincontent .product-config:before{
  content:"";
  position:absolute;
  top:0;
  left:10%;
  right:10%;
  height:2px;
  background:linear-gradient(90deg,transparent,#ff6a00,#ffd747,#ff6a00,transparent);
  box-shadow:
    0 0 8px rgba(255,155,0,.75),
    0 0 17px rgba(255,76,0,.28);
  pointer-events:none;
}

#maincontent .product-config:after{
  content:"";
  position:absolute;
  width:90px;
  height:90px;
  right:-45px;
  bottom:-45px;
  border:1px solid rgba(255,173,19,.12);
  border-radius:50%;
  box-shadow:
    0 0 0 12px rgba(255,173,19,.015),
    0 0 0 24px rgba(255,173,19,.012);
  pointer-events:none;
}

#maincontent .product-add-form,
#maincontent .product-options-wrapper,
#maincontent .box-tocart{
  position:relative;
  z-index:2;
  background:transparent!important;
  border:0!important;
}

#maincontent .box-tocart .fieldset.fieldset-cust{
  display:flex!important;
  flex-direction:column!important;
  gap:12px!important;
}

#maincontent .box-tocart .field.qty{
  position:relative;
  display:flex!important;
  align-items:center!important;
  justify-content:space-between!important;
  gap:14px!important;
  margin:0 0 6px!important;
  padding:11px 13px!important;
  border:1px solid rgba(255,174,20,.24);
  border-radius:6px 13px 6px 13px;
  background:
    linear-gradient(145deg,#161616,#090909)!important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.05),
    inset 0 -8px 18px rgba(0,0,0,.22);
}

#maincontent .box-tocart .field.qty:before{
  content:"";
  position:absolute;
  left:0;
  top:25%;
  bottom:25%;
  width:2px;
  background:#ff9d00;
  box-shadow:0 0 7px #ff7900;
}

#maincontent .box-tocart .field.qty .label{
  margin:0!important;
  color:#d1b86f!important;
  font-size:12px!important;
  font-weight:800!important;
  text-transform:uppercase;
  letter-spacing:.8px;
}

#maincontent #qty{
  width:64px!important;
  height:38px!important;
  margin:0!important;
  padding:0 11px!important;
  border:1px solid #755319!important;
  border-radius:5px 9px 5px 9px!important;
  background:linear-gradient(180deg,#19160e,#090909)!important;
  color:#ffe889!important;
  font-weight:900!important;
  text-align:center;
  box-shadow:
    inset 0 2px 7px rgba(0,0,0,.55),
    0 0 8px rgba(255,163,0,.05);
}

#maincontent #qty:focus{
  border-color:#ffb820!important;
  outline:none!important;
  box-shadow:
    0 0 0 2px rgba(255,177,23,.09),
    0 0 13px rgba(255,112,0,.16)!important;
}

#maincontent .box-tocart .actions{
  display:grid!important;
  grid-template-columns:1fr!important;
  gap:11px!important;
  width:100%!important;
}

#maincontent .box-tocart .actions > a{
  width:100%!important;
  margin:0!important;
  text-decoration:none!important;
}

#maincontent #product-addtocart-button{
  position:relative;
  overflow:hidden;
  width:100%!important;
  min-height:56px!important;
  margin:0!important;
  border:1px solid #ffe378!important;
  border-radius:6px 13px 6px 13px!important;
  background:
    linear-gradient(
      180deg,
      #fff08c 0%,
      #ffd343 19%,
      #ffad0d 51%,
      #e96800 100%
    )!important;
  color:#160c00!important;
  font-size:14px!important;
  font-weight:900!important;
  letter-spacing:.9px!important;
  text-transform:uppercase!important;
  text-shadow:0 1px rgba(255,255,255,.35);
  box-shadow:
    0 10px 25px rgba(0,0,0,.45),
    0 0 12px rgba(255,150,0,.25),
    inset 0 1px 0 rgba(255,255,255,.75),
    inset 0 -3px 6px rgba(122,42,0,.22)!important;
  transition:
    transform .18s ease,
    filter .18s ease,
    box-shadow .18s ease;
}

#maincontent #product-addtocart-button:before{
  content:"⚡";
  display:inline-block;
  margin-right:9px;
  font-size:14px;
  filter:drop-shadow(0 0 3px rgba(255,255,255,.4));
}

#maincontent #product-addtocart-button:after{
  content:"";
  position:absolute;
  top:-45%;
  left:-45%;
  width:25%;
  height:190%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.8),transparent);
  transform:skewX(-24deg);
  animation:genosButtonScan 3.1s ease-in-out infinite;
  pointer-events:none;
}

#maincontent #product-addtocart-button:hover{
  transform:translateY(-2px)!important;
  filter:brightness(1.12) saturate(1.08);
  box-shadow:
    0 14px 30px rgba(0,0,0,.50),
    0 0 15px rgba(255,196,40,.52),
    0 0 30px rgba(255,74,0,.23),
    inset 0 1px 0 rgba(255,255,255,.8)!important;
}

#maincontent #product-addtocart-button:active{
  transform:translateY(2px) scale(.985)!important;
  filter:brightness(.92);
  box-shadow:
    0 3px 8px rgba(0,0,0,.6),
    inset 0 4px 8px rgba(109,35,0,.30)!important;
}

#maincontent .action.towishlist{
  position:relative;
  overflow:hidden;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  min-height:54px!important;
  padding:0 16px!important;
  border:1px solid rgba(255,179,28,.46)!important;
  border-radius:6px 13px 6px 13px!important;
  background:
    radial-gradient(circle at 50% 120%,rgba(255,117,0,.12),transparent 48%),
    linear-gradient(180deg,#201a0d,#0e0d09)!important;
  color:#ffd866!important;
  font-size:13px!important;
  font-weight:900!important;
  letter-spacing:.55px!important;
  text-transform:uppercase!important;
  box-shadow:
    inset 0 1px rgba(255,255,255,.07),
    0 7px 18px rgba(0,0,0,.30)!important;
  transition:
    transform .18s ease,
    border-color .18s ease,
    box-shadow .18s ease,
    color .18s ease;
}

#maincontent .action.towishlist:before{
  content:none!important;
}

#maincontent .action.towishlist .idx-register-icon{
  width:25px!important;
  height:25px!important;
  object-fit:contain!important;
  margin-right:10px!important;
  flex:0 0 25px!important;
  background:transparent!important;
  border:0!important;
  box-shadow:none!important;
  filter:
    brightness(0)
    saturate(100%)
    invert(81%)
    sepia(76%)
    saturate(1094%)
    hue-rotate(340deg)
    brightness(104%);
}

#maincontent .action.towishlist:hover{
  color:#fff0a6!important;
  border-color:#ffd54e!important;
  transform:translateY(-2px);
  box-shadow:
    inset 0 1px rgba(255,255,255,.09),
    0 9px 20px rgba(0,0,0,.38),
    0 0 17px rgba(255,137,0,.16)!important;
}

#maincontent .action.towishlist:active{
  transform:translateY(2px) scale(.988);
  background:linear-gradient(180deg,#120e06,#1d1305)!important;
}

#maincontent #giftreg-message-pdp{
  width:100%!important;
  margin:0!important;
}

#maincontent #giftreg-message-pdp a{
  position:relative;
  overflow:hidden;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  width:100%!important;
  min-height:52px!important;
  margin:0!important;
  padding:0 16px!important;
  box-sizing:border-box!important;
  border:1px solid rgba(255,140,15,.40)!important;
  border-radius:6px 13px 6px 13px!important;
  background:
    linear-gradient(135deg,#100d08,#211607)!important;
  color:#ffbe39!important;
  font-size:13px!important;
  font-weight:900!important;
  letter-spacing:.5px!important;
  text-transform:uppercase!important;
  box-shadow:
    inset 0 1px rgba(255,255,255,.05),
    0 6px 17px rgba(0,0,0,.28)!important;
  transition:
    transform .18s ease,
    border-color .18s ease,
    color .18s ease,
    box-shadow .18s ease;
}

#maincontent #giftreg-message-pdp a:before{
  content:"";
  position:absolute;
  top:0;
  left:-50%;
  width:28%;
  height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,193,47,.08),transparent);
  transform:skewX(-20deg);
  transition:left .5s ease;
}

#maincontent #giftreg-message-pdp a:after{
  content:"›";
  margin-left:auto;
  color:#ffd951;
  font-size:24px;
  line-height:1;
  text-shadow:0 0 7px rgba(255,151,0,.55);
}

#maincontent #giftreg-message-pdp a:hover{
  border-color:#ffd14a!important;
  color:#fff0a6!important;
  transform:translateY(-2px);
  box-shadow:
    inset 0 1px rgba(255,255,255,.07),
    0 9px 20px rgba(0,0,0,.38),
    0 0 15px rgba(255,112,0,.15)!important;
}

#maincontent #giftreg-message-pdp a:hover:before{
  left:120%;
}

#maincontent #giftreg-message-pdp a:active{
  transform:translateY(2px) scale(.988);
}

#maincontent #giftreg-message-pdp img{
  width:18px!important;
  height:18px!important;
  margin-right:9px!important;
  filter:
    brightness(0)
    saturate(100%)
    invert(79%)
    sepia(94%)
    saturate(1200%)
    hue-rotate(341deg)
    brightness(105%);
}

#maincontent .sharing-buttons{
  position:relative;
  overflow:hidden;
  margin-top:14px!important;
  padding:14px 14px 12px!important;
  border:1px solid rgba(255,173,19,.20);
  border-radius:6px 13px 6px 13px;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,121,0,.07),transparent 40%),
    linear-gradient(145deg,rgba(20,20,21,.96),rgba(7,7,8,.96));
  box-shadow:
    inset 0 1px rgba(255,255,255,.045),
    0 8px 20px rgba(0,0,0,.25);
}

#maincontent .sharing-buttons:before{
  content:"";
  position:absolute;
  left:14px;
  right:65%;
  top:0;
  height:1px;
  background:linear-gradient(90deg,#ff7300,#ffd54a,transparent);
  box-shadow:0 0 6px rgba(255,143,0,.45);
}

#maincontent .sharing-buttons label{
  display:block!important;
  margin:0 0 10px!important;
  color:#c7a95c!important;
  font-size:11px!important;
  font-weight:900!important;
  text-transform:uppercase;
  letter-spacing:1px;
}

#maincontent .sharethis-inline-share-buttons{
  min-height:40px;
}

#maincontent .st-btn{
  border:1px solid rgba(255,181,32,.26)!important;
  box-shadow:
    0 7px 16px rgba(0,0,0,.35),
    inset 0 1px rgba(255,255,255,.04)!important;
  transition:
    transform .18s ease,
    filter .18s ease,
    box-shadow .18s ease!important;
}

#maincontent .st-btn:hover{
  transform:translateY(-2px)!important;
  filter:brightness(1.12);
  box-shadow:
    0 9px 20px rgba(0,0,0,.42),
    0 0 12px rgba(255,143,0,.13)!important;
}

@keyframes genosTitleEnergy{
  0%{
    left:-40%;
    opacity:0;
  }
  10%{
    opacity:1;
  }
  85%{
    opacity:1;
  }
  100%{
    left:115%;
    opacity:0;
  }
}

@keyframes genosButtonScan{
  0%,58%{
    left:-45%;
    opacity:0;
  }
  65%{
    opacity:1;
  }
  88%{
    left:125%;
    opacity:.9;
  }
  100%{
    left:125%;
    opacity:0;
  }
}

@media(max-width:767px){
  #maincontent .page-title-wrapper.product h1.page-title{
    padding:15px 16px!important;
    border-radius:7px 14px 7px 14px!important;
  }

  #maincontent .page-title .base{
    font-size:20px!important;
    line-height:1.42!important;
  }

  #maincontent .product-config{
    padding:13px!important;
    border-radius:8px 15px 8px 15px!important;
  }

  #maincontent .box-tocart .field.qty{
    padding:9px 11px!important;
  }

  #maincontent #product-addtocart-button,
  #maincontent .action.towishlist{
    min-height:50px!important;
  }

  #maincontent #giftreg-message-pdp a{
    min-height:48px!important;
  }
}

@media(prefers-reduced-motion:reduce){
  #maincontent .page-title-wrapper.product h1.page-title:before,
  #maincontent #product-addtocart-button:after{
    animation:none!important;
  }
}
</style>


<style id="%%SITE%%-footer-genos">
.page-footer{
  position:relative!important;
  overflow:hidden!important;
  margin-top:42px!important;
  padding:0!important;
  background:
    radial-gradient(circle at 14% 0%,rgba(255,174,0,.12),transparent 30%),
    radial-gradient(circle at 88% 100%,rgba(255,74,0,.08),transparent 28%),
    linear-gradient(180deg,#111113 0%,#080809 48%,#030303 100%)!important;
  border-top:1px solid rgba(255,183,28,.38)!important;
  box-shadow:
    0 -15px 42px rgba(0,0,0,.48),
    inset 0 1px 0 rgba(255,255,255,.03)!important;
}

.page-footer:before{
  content:"";
  position:absolute;
  z-index:2;
  top:0;
  left:-35%;
  width:35%;
  height:2px;
  background:linear-gradient(
    90deg,
    transparent,
    #ff5b00,
    #ffad00,
    #ffe26a,
    #fff3ae,
    #ffe26a,
    #ff8a00,
    transparent
  );
  box-shadow:
    0 0 7px #ff9700,
    0 0 16px rgba(255,95,0,.55),
    0 0 26px rgba(255,178,0,.25);
  animation:genosFooterEnergy 4.5s linear infinite;
}

.page-footer:after{
  content:"";
  position:absolute;
  z-index:0;
  inset:0;
  pointer-events:none;
  background:
    repeating-linear-gradient(
      125deg,
      transparent 0,
      transparent 52px,
      rgba(255,175,0,.012) 53px,
      transparent 54px
    );
}

.page-footer .footer-content-mckanmo-desktop{
  position:relative!important;
  z-index:1!important;
  width:min(1420px,calc(100% - 44px))!important;
  margin:0 auto!important;
  padding:42px 0 38px!important;
  background:transparent!important;
}

.page-footer .footer-main-column-mc,
.page-footer .pagebuilder-column-line{
  background:transparent!important;
}

.page-footer .pagebuilder-column-line{
  display:grid!important;
  grid-template-columns:1.05fr 1.05fr 1.05fr 1.35fr!important;
  gap:18px!important;
  align-items:stretch!important;
}

.page-footer .pagebuilder-column-line > .pagebuilder-column{
  position:relative!important;
  overflow:hidden!important;
  width:auto!important;
  min-width:0!important;
  padding:24px 22px!important;
  border:1px solid rgba(255,174,19,.22)!important;
  border-radius:7px 18px 7px 18px!important;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,109,0,.07),transparent 34%),
    linear-gradient(145deg,rgba(25,25,27,.95),rgba(7,7,8,.97))!important;
  box-shadow:
    0 15px 32px rgba(0,0,0,.38),
    inset 0 1px 0 rgba(255,255,255,.055),
    inset 0 -20px 35px rgba(0,0,0,.12)!important;
  transition:
    transform .22s ease,
    border-color .22s ease,
    box-shadow .22s ease!important;
}

.page-footer .pagebuilder-column-line > .pagebuilder-column:hover{
  transform:translateY(-3px);
  border-color:rgba(255,191,44,.40)!important;
  box-shadow:
    0 18px 38px rgba(0,0,0,.48),
    0 0 18px rgba(255,115,0,.06),
    inset 0 1px 0 rgba(255,255,255,.07)!important;
}

.page-footer .pagebuilder-column-line > .pagebuilder-column:before{
  content:"";
  position:absolute;
  left:18px;
  right:30%;
  top:0;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff6500,
    #ffb20d,
    #ffe16a,
    transparent
  );
  box-shadow:
    0 0 6px rgba(255,147,0,.7),
    0 0 13px rgba(255,86,0,.25);
}

.page-footer .pagebuilder-column-line > .pagebuilder-column:after{
  content:"";
  position:absolute;
  width:60px;
  height:60px;
  right:-31px;
  bottom:-31px;
  border:1px solid rgba(255,173,18,.09);
  border-radius:50%;
  box-shadow:
    0 0 0 9px rgba(255,173,18,.015),
    0 0 0 18px rgba(255,173,18,.01);
  pointer-events:none;
}

.page-footer h4{
  position:relative!important;
  margin:0 0 19px!important;
  padding:0 0 11px!important;
  color:#fff1bd!important;
  font-size:15px!important;
  line-height:1.35!important;
  font-weight:900!important;
  letter-spacing:.9px!important;
  text-transform:uppercase!important;
  text-align:left!important;
  text-shadow:
    0 1px 2px #000,
    0 0 8px rgba(255,174,0,.16)!important;
}

.page-footer h4:after{
  content:"";
  position:absolute;
  left:0;
  bottom:0;
  width:52px;
  height:2px;
  border-radius:99px;
  background:linear-gradient(90deg,#ff6700,#ffbc20,#ffe36b);
  box-shadow:0 0 7px rgba(255,135,0,.45);
}

.page-footer p{
  margin:0 0 11px!important;
  color:#bcbcc1!important;
  font-size:13px!important;
  line-height:1.65!important;
}

.page-footer a{
  color:#ffc345!important;
  text-decoration:none!important;
  transition:
    color .2s ease,
    transform .2s ease,
    background .2s ease,
    border-color .2s ease!important;
}

.page-footer .shop-with-us-column-mc p a,
.page-footer .need-help-column-mc p a{
  position:relative;
  display:block!important;
  padding:7px 10px 7px 23px!important;
  border:1px solid transparent;
  border-radius:5px 10px 5px 10px;
  color:#c8c8cd!important;
}

.page-footer .shop-with-us-column-mc p a:before,
.page-footer .need-help-column-mc p a:before{
  content:"◆";
  position:absolute;
  left:7px;
  top:9px;
  color:#ffad0b;
  font-size:7px;
  text-shadow:
    0 0 5px rgba(255,167,0,.8),
    0 0 10px rgba(255,81,0,.30);
}

.page-footer .shop-with-us-column-mc p a:hover,
.page-footer .need-help-column-mc p a:hover{
  color:#ffe98f!important;
  border-color:rgba(255,173,19,.14)!important;
  background:linear-gradient(
    90deg,
    rgba(255,142,0,.08),
    rgba(255,83,0,.025)
  )!important;
  transform:translateX(4px);
}

.page-footer .footer-desktop > p:first-child{
  margin-top:17px!important;
  padding-top:14px!important;
  border-top:1px solid rgba(255,173,19,.15);
  color:#e4e4e6!important;
  font-weight:800!important;
}

.page-footer .footer-desktop div[style*="display:flex"]{
  gap:9px!important;
}

.page-footer .footer-desktop .item{
  position:relative;
  overflow:hidden;
  width:36px;
  height:36px;
  display:flex!important;
  align-items:center;
  justify-content:center;
  border:1px solid rgba(255,177,23,.28);
  border-radius:5px 10px 5px 10px;
  background:
    linear-gradient(145deg,#1a1812,#090909);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.05),
    0 5px 12px rgba(0,0,0,.25);
  transition:
    transform .2s ease,
    border-color .2s ease,
    box-shadow .2s ease!important;
}

.page-footer .footer-desktop .item:hover{
  transform:translateY(-3px);
  border-color:#ffc52f;
  box-shadow:
    0 7px 15px rgba(0,0,0,.38),
    0 0 12px rgba(255,139,0,.17);
}

.page-footer .footer-desktop .item img{
  max-width:18px!important;
  max-height:18px!important;
  object-fit:contain;
  filter:drop-shadow(0 0 4px rgba(255,170,0,.18));
}

.page-footer .download-ourapps-column-mc{
  text-align:center!important;
}

.page-footer .download-ourapps-column-mc h4{
  text-align:left!important;
}

.page-footer .footer-apple-store-img,
.page-footer .footer-google-play-img{
  width:100%!important;
  margin:0 0 10px!important;
  text-align:center!important;
}

.page-footer .footer-apple-store-img a,
.page-footer .footer-google-play-img > a{
  position:relative;
  overflow:hidden;
  display:flex!important;
  min-height:68px;
  align-items:center;
  justify-content:center;
  padding:8px 12px;
  border:1px solid rgba(255,174,19,.20);
  border-radius:6px 13px 6px 13px;
  background:
    linear-gradient(145deg,#161616,#080808);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.045),
    0 7px 16px rgba(0,0,0,.25);
}

.page-footer .footer-apple-store-img a:hover,
.page-footer .footer-google-play-img > a:hover{
  border-color:rgba(255,199,53,.46)!important;
  background:
    linear-gradient(145deg,#211a0c,#0c0a06)!important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.06),
    0 8px 18px rgba(0,0,0,.34),
    0 0 13px rgba(255,123,0,.08);
}

.page-footer .footer-apple-store-img img{
  max-width:190px!important;
  height:auto!important;
  object-fit:contain!important;
}

.page-footer .footer-google-play-img > a img{
  max-width:185px!important;
  height:auto!important;
  object-fit:contain!important;
}

.page-footer .accept-pay .footer-promo{
  position:relative;
  display:flex!important;
  align-items:center!important;
  gap:14px!important;
  margin:0 0 16px!important;
  padding:13px!important;
  border:1px solid rgba(255,174,19,.18);
  border-radius:6px 13px 6px 13px;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,111,0,.06),transparent 38%),
    linear-gradient(145deg,#151515,#080808);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.04),
    0 7px 18px rgba(0,0,0,.24);
}

.page-footer .accept-pay .footer-promo span:first-child{
  flex:1;
  min-width:0;
}

.page-footer .accept-pay .footer-promo span:first-child img{
  display:block!important;
  width:100%!important;
  max-width:270px!important;
  height:auto!important;
  object-fit:contain!important;
}

.page-footer .accept-pay .footer-promo span:last-child img{
  width:58px!important;
  height:58px!important;
  object-fit:contain!important;
  filter:
    drop-shadow(0 0 6px rgba(255,176,0,.24))
    drop-shadow(0 0 12px rgba(255,83,0,.08));
}

.page-footer .accept-pay .footer-promo + div{
  position:relative;
  padding:14px 15px!important;
  border-left:3px solid #ff9e00!important;
  border-radius:0 9px 9px 0;
  background:
    linear-gradient(
      90deg,
      rgba(255,142,0,.07),
      rgba(15,15,16,.72)
    )!important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.025);
}

.page-footer .accept-pay .footer-promo + div span{
  color:#bdbdc2!important;
  font-size:12px!important;
  line-height:1.75!important;
}

.page-footer .back-to-top{
  position:relative;
  overflow:hidden;
  right:22px!important;
  bottom:22px!important;
  border:1px solid rgba(255,185,28,.42)!important;
  border-radius:5px 11px 5px 11px!important;
  background:
    linear-gradient(
      180deg,
      #2a210d 0%,
      #151006 48%,
      #090806 100%
    )!important;
  color:#ffd85a!important;
  box-shadow:
    0 10px 25px rgba(0,0,0,.45),
    0 0 12px rgba(255,128,0,.10),
    inset 0 1px 0 rgba(255,255,255,.07)!important;
  transition:
    transform .2s ease,
    filter .2s ease,
    box-shadow .2s ease!important;
}

.page-footer .back-to-top:hover{
  transform:translateY(-3px);
  filter:brightness(1.14);
  border-color:#ffd44a!important;
  box-shadow:
    0 13px 28px rgba(0,0,0,.50),
    0 0 16px rgba(255,145,0,.20)!important;
}

.page-footer + strong + script + div,
.page-footer ~ div[data-content-type="html"]{
  border-color:rgba(255,173,19,.17)!important;
}

@keyframes genosFooterEnergy{
  0%{
    left:-35%;
    opacity:0;
  }

  8%{
    opacity:1;
  }

  88%{
    opacity:1;
  }

  100%{
    left:110%;
    opacity:0;
  }
}

@media(max-width:1050px){
  .page-footer .pagebuilder-column-line{
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
  }
}

@media(max-width:650px){
  .page-footer{
    margin-top:28px!important;
  }

  .page-footer .footer-content-mckanmo-desktop{
    width:calc(100% - 24px)!important;
    padding:26px 0!important;
  }

  .page-footer .pagebuilder-column-line{
    grid-template-columns:1fr!important;
    gap:12px!important;
  }

  .page-footer .pagebuilder-column-line > .pagebuilder-column{
    padding:20px 17px!important;
    border-radius:7px 14px 7px 14px!important;
  }

  .page-footer h4{
    font-size:14px!important;
  }

  .page-footer .accept-pay .footer-promo{
    align-items:flex-start!important;
  }

  .page-footer .shop-with-us-column-mc p a,
  .page-footer .need-help-column-mc p a{
    padding-top:8px!important;
    padding-bottom:8px!important;
  }
}

@media(prefers-reduced-motion:reduce){
  .page-footer:before{
    animation:none!important;
  }

  .page-footer .pagebuilder-column-line > .pagebuilder-column,
  .page-footer a,
  .page-footer .footer-desktop .item,
  .page-footer .back-to-top{
    transition:none!important;
  }
}
</style>

</head>

</div>
<body class="breeze breeze-theme page-product-configurable catalog-product-view product-nordic-naturals-baby-s-dha-011110180-JH5201 page-layout-1column" data-breeze-processed="true" data-container="body" data-mage-init='{"loaderAjax": {}, "loader": { "icon": "%%FAVICON%%"}}' id="html-body">
<noscript>
<div class="message global noscript">
<div class="content">
<p>
<strong>sepertinya JavaScript tidak aktif pada browser anda </strong>
<span>
                        Untuk pengalaman terbaik di situs kami, pastikan untuk mengaktifkan Javascript di browser Anda.                    </span>
</p>
</div>
</div>
</noscript>
<script data-breeze="">var cookiesConfig={expires:null,path:'\u002F',domain:'.mothercare.co.id',secure:true,lifetime:'3600'};</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "*": {
            "Magento_GoogleTagManager/js/google-tag-manager-cart": {
                "blockNames": ["category.products.list","product.info.upsell","catalog.product.related","checkout.cart.crosssell","search_result_list"],
                "cookieAddToCart": "add_to_cart",
                "cookieRemoveFromCart":
                    "remove_from_cart",
                "advancedAddToCart": "add_to_cart_advanced"
            }
        }
    }
</script>
<script>var googleMapsConfig={src:'https\u003A\u002F\u002Fmaps.googleapis.com\u002Fmaps\u002Fapi\u002Fjs\u003Fv\u003D3\u0026key\u003D',style:'',apiKey:''}</script>
<div class="page-wrapper"><header class="page-header idx-palace-header">
<div class="idx-skybar">
<div class="idx-skybar-inner">
<div class="idx-status">
<span class="idx-crest">✦</span>
<span class="idx-palace-name">%%SITE%% <b>AGEN SLOT</b></span>
<span class="idx-divider"></span>
<span class="idx-live"><em></em> ONLINE 24 JAM</span>
<span class="idx-divider idx-hide-mobile"></span>
<span class="idx-tagline idx-hide-mobile">◆ NEXUS SLOT PLATFORM ◆</span>
</div>
<div class="idx-actions">
<a class="idx-contact" href="%%LIVECHAT%%"><span>✧</span> LIVECHAT</a>
<a class="idx-login" href="%%CTA_LOGIN%%"><span>♙</span> LOGIN</a>
<a class="idx-register" href="%%CTA_DAFTAR%%"><span>♛</span> DAFTAR</a>
</div>
</div>
</div>
<style id="%%SITE%%-genos-header">
.idx-palace-header{
  position:relative;
  z-index:50;
  background:#050505!important;
  border:0!important;
  box-shadow:
    0 15px 42px rgba(0,0,0,.78),
    0 2px 0 rgba(255,157,0,.06)!important;
}

.idx-skybar{
  position:relative;
  overflow:hidden;
  height:46px;
  background:
    radial-gradient(circle at 12% 0%,rgba(255,179,0,.12),transparent 30%),
    radial-gradient(circle at 85% 100%,rgba(255,72,0,.08),transparent 27%),
    linear-gradient(90deg,#030303 0%,#100d08 30%,#191208 50%,#100d08 70%,#030303 100%);
  border-bottom:1px solid rgba(255,177,25,.32);
  box-shadow:
    inset 0 1px rgba(255,255,255,.035),
    inset 0 -8px 20px rgba(0,0,0,.35);
}

.idx-skybar:before{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    repeating-linear-gradient(
      115deg,
      transparent 0,
      transparent 58px,
      rgba(255,177,0,.018) 59px,
      transparent 60px
    );
}

.idx-skybar:after{
  content:"";
  position:absolute;
  left:-35%;
  bottom:0;
  width:35%;
  height:2px;
  background:linear-gradient(
    90deg,
    transparent,
    #ff4d00,
    #ff9200,
    #ffd542,
    #fff2a2,
    #ffd542,
    #ff7200,
    transparent
  );
  box-shadow:
    0 0 6px #ff8c00,
    0 0 15px rgba(255,94,0,.75),
    0 0 28px rgba(255,177,0,.30);
  animation:genosSkyEnergy 4s linear infinite;
}

.idx-skybar-inner{
  position:relative;
  max-width:1380px;
  height:100%;
  margin:auto;
  padding:0 28px;
  display:flex;
  align-items:center;
  justify-content:space-between;
}

.idx-status,
.idx-actions{
  display:flex;
  align-items:center;
}

.idx-status{
  gap:10px;
  font:800 10px/1 Arial,sans-serif;
  letter-spacing:1.05px;
  color:#d4d4d8;
}

.idx-crest{
  position:relative;
  width:28px;
  height:28px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  color:#ffd74d;
  background:
    radial-gradient(circle,#31230b 0%,#110d06 70%);
  border:1px solid rgba(255,196,54,.62);
  border-radius:4px;
  transform:rotate(45deg);
  box-shadow:
    inset 0 0 9px rgba(255,181,0,.13),
    0 0 8px rgba(255,137,0,.26),
    0 0 15px rgba(255,76,0,.08);
}

.idx-crest:before{
  content:"";
  position:absolute;
  inset:4px;
  border:1px solid rgba(255,205,75,.18);
  border-radius:2px;
}

.idx-crest::first-letter{
  transform:rotate(-45deg);
}

.idx-palace-name{
  color:#f2f2f3;
  font-size:11px;
  font-weight:800;
  letter-spacing:1.35px;
  text-shadow:0 1px 2px #000;
}

.idx-palace-name b{
  color:#ffd653;
  font-weight:900;
  text-shadow:0 0 7px rgba(255,170,0,.25);
}

.idx-divider{
  width:1px;
  height:17px;
  margin:0 2px;
  background:linear-gradient(
    transparent,
    rgba(255,180,27,.55),
    transparent
  );
}

.idx-live{
  display:flex;
  align-items:center;
  gap:7px;
  color:#e0e0e3;
}

.idx-live em{
  position:relative;
  width:7px;
  height:7px;
  display:inline-block;
  border-radius:50%;
  background:#ffb514;
  box-shadow:
    0 0 5px #ffb514,
    0 0 12px rgba(255,108,0,.72);
  animation:genosStatusPulse 1.5s ease-in-out infinite;
}

.idx-tagline{
  color:#c99f42;
  font-size:9px;
  font-weight:700;
  letter-spacing:1.3px;
}

.idx-actions{
  gap:7px;
}

.idx-actions a{
  position:relative;
  overflow:hidden;
  height:30px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:6px;
  padding:0 14px;
  border-radius:4px 9px 4px 9px;
  text-decoration:none!important;
  font:900 10px Arial,sans-serif;
  letter-spacing:.8px;
  text-transform:uppercase;
  transition:
    transform .18s ease,
    border-color .18s ease,
    color .18s ease,
    filter .18s ease,
    box-shadow .18s ease;
}

.idx-actions a:before{
  content:"";
  position:absolute;
  top:-20%;
  left:-50%;
  width:28%;
  height:140%;
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.62),
    transparent
  );
  transform:skewX(-23deg);
  transition:left .48s ease;
}

.idx-actions a:hover:before{
  left:125%;
}

.idx-actions a:hover{
  transform:translateY(-2px);
}

.idx-actions a:active{
  transform:translateY(1px) scale(.97);
}

.idx-contact{
  color:#ffc64b!important;
  border:1px solid rgba(255,174,19,.30);
  background:
    linear-gradient(180deg,#21190b,#0d0b07);
  box-shadow:
    inset 0 1px rgba(255,255,255,.04),
    0 5px 12px rgba(0,0,0,.28);
}

.idx-contact:hover{
  color:#fff0a6!important;
  border-color:rgba(255,204,68,.55);
  box-shadow:
    0 7px 16px rgba(0,0,0,.38),
    0 0 12px rgba(255,135,0,.12);
}

.idx-login{
  color:#ededee!important;
  border:1px solid rgba(255,185,37,.26);
  background:
    linear-gradient(180deg,#1b1b1d,#0a0a0b);
  box-shadow:
    inset 0 1px rgba(255,255,255,.07),
    0 5px 12px rgba(0,0,0,.32);
}

.idx-login:hover{
  color:#ffe887!important;
  border-color:#d99c20;
  box-shadow:
    0 7px 16px rgba(0,0,0,.4),
    0 0 11px rgba(255,153,0,.12);
}

.idx-register{
  color:#150b00!important;
  border:1px solid #ffe373;
  background:
    linear-gradient(
      135deg,
      #b64b00 0%,
      #f18b00 18%,
      #ffc32b 43%,
      #fff08d 58%,
      #ffc329 76%,
      #d45b00 100%
    );
  text-shadow:0 1px rgba(255,255,255,.40);
  box-shadow:
    0 6px 16px rgba(0,0,0,.35),
    0 0 13px rgba(255,145,0,.25),
    inset 0 1px rgba(255,255,255,.72),
    inset 0 -2px rgba(122,39,0,.22);
}

.idx-register:hover{
  filter:brightness(1.12) saturate(1.1);
  box-shadow:
    0 8px 19px rgba(0,0,0,.42),
    0 0 12px rgba(255,201,51,.55),
    0 0 24px rgba(255,75,0,.18),
    inset 0 1px rgba(255,255,255,.78);
}

.idx-palace-header .header.content{
  position:relative;
  min-height:91px!important;
  background:
    radial-gradient(circle at 12% -30%,rgba(255,170,0,.10),transparent 31%),
    radial-gradient(circle at 88% 120%,rgba(255,66,0,.055),transparent 27%),
    linear-gradient(
      90deg,
      #050505 0%,
      #0d0d0f 28%,
      #17140d 50%,
      #0d0d0f 72%,
      #050505 100%
    )!important;
  border-bottom:1px solid rgba(255,169,16,.28)!important;
  box-shadow:
    inset 0 -1px rgba(255,178,0,.13),
    inset 0 1px rgba(255,255,255,.025);
}

.idx-palace-header .header.content:before{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    repeating-linear-gradient(
      120deg,
      transparent 0,
      transparent 65px,
      rgba(255,175,0,.012) 66px,
      transparent 67px
    );
}

.idx-palace-header .header.content:after{
  content:"";
  position:absolute;
  left:4%;
  right:4%;
  bottom:-1px;
  height:1px;
  background:linear-gradient(
    90deg,
    transparent,
    #ff5b00,
    #ffb40f,
    #ffe46b,
    #ffb40f,
    #ff5b00,
    transparent
  );
  box-shadow:0 0 8px rgba(255,128,0,.45);
  pointer-events:none;
}

.idx-palace-header .logo{
  position:relative;
  z-index:2;
  padding:5px 12px!important;
  margin-right:34px!important;
  border:0!important;
  background:transparent!important;
  min-width:215px!important;
}

.idx-palace-header .logo img{
  max-height:66px!important;
  max-width:220px!important;
  width:auto!important;
  height:auto!important;
  filter:
    drop-shadow(0 0 6px rgba(255,180,0,.38))
    drop-shadow(0 5px 9px rgba(0,0,0,.85));
  transition:
    filter .25s ease,
    transform .25s ease;
}

.idx-palace-header .logo:hover img{
  transform:scale(1.025);
  filter:
    drop-shadow(0 0 8px rgba(255,205,61,.55))
    drop-shadow(0 0 17px rgba(255,104,0,.14))
    drop-shadow(0 5px 9px rgba(0,0,0,.9));
}

.idx-palace-header .ves-megamenu.menu-hover{
  position:relative;
  z-index:2;
  background:transparent!important;
}

.idx-palace-header .ves-megamenu .nav-item.level0>.nav-anchor{
  position:relative;
  overflow:hidden;
  color:#d8d8dc!important;
  padding:12px 15px!important;
  margin:10px 3px!important;
  font-weight:900!important;
  letter-spacing:.3px;
  border:1px solid rgba(255,175,19,.19)!important;
  border-radius:5px 11px 5px 11px!important;
  background:
    linear-gradient(180deg,#19191b 0%,#0a0a0b 100%)!important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.055),
    inset 0 -1px 0 rgba(0,0,0,.6),
    0 6px 14px rgba(0,0,0,.34)!important;
  transition:
    transform .2s ease,
    box-shadow .2s ease,
    border-color .2s ease,
    color .2s ease,
    background .2s ease!important;
}

.idx-palace-header .ves-megamenu .nav-item.level0>.nav-anchor:before{
  content:"";
  position:absolute;
  top:0;
  left:-45%;
  width:26%;
  height:100%;
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,196,47,.09),
    transparent
  );
  transform:skewX(-24deg);
  transition:left .45s ease;
}

.idx-palace-header .ves-megamenu .nav-item.level0>.nav-anchor:after{
  content:"";
  position:absolute;
  left:50%;
  bottom:7px;
  width:0;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff5b00,
    #ffb914,
    #ffe36a
  );
  transform:translateX(-50%);
  transition:width .22s ease;
  box-shadow:
    0 0 5px rgba(255,158,0,.65),
    0 0 10px rgba(255,78,0,.18);
}

.idx-palace-header .ves-megamenu .nav-item.level0:hover>.nav-anchor{
  color:#fff0a4!important;
  background:
    linear-gradient(180deg,#251c0b,#100d07)!important;
  border-color:rgba(255,195,48,.48)!important;
  transform:translateY(-2px);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,.08),
    0 9px 20px rgba(0,0,0,.48),
    0 0 14px rgba(255,121,0,.09)!important;
}

.idx-palace-header .ves-megamenu .nav-item.level0:hover>.nav-anchor:before{
  left:125%;
}

.idx-palace-header .ves-megamenu .nav-item.level0:hover>.nav-anchor:after{
  width:55%;
}

.idx-palace-header .ves-megamenu .nav-item.level0:last-child>.nav-anchor{
  color:#160b00!important;
  background:
    linear-gradient(
      180deg,
      #fff198 0%,
      #ffd647 24%,
      #ffae0d 58%,
      #db6500 100%
    )!important;
  border:1px solid #ffe881!important;
  border-radius:6px 13px 6px 13px!important;
  padding:13px 18px!important;
  margin:9px 3px!important;
  transform:translateY(-2px);
  text-shadow:0 1px rgba(255,255,255,.42);
  box-shadow:
    inset 0 2px 0 rgba(255,255,255,.68),
    inset 0 -2px 0 rgba(122,42,0,.28),
    0 8px 19px rgba(0,0,0,.46),
    0 0 15px rgba(255,160,0,.27)!important;
}

.idx-palace-header .ves-megamenu .nav-item.level0:last-child>.nav-anchor:hover{
  filter:brightness(1.1) saturate(1.08);
  box-shadow:
    inset 0 2px 0 rgba(255,255,255,.78),
    inset 0 -2px 0 rgba(122,42,0,.25),
    0 10px 22px rgba(0,0,0,.5),
    0 0 14px rgba(255,205,51,.45),
    0 0 28px rgba(255,75,0,.16)!important;
}

.idx-palace-header .ves-megamenu .nav-item.level0:last-child>.nav-anchor:after{
  display:none;
}

.idx-palace-header .navigation .level0 .submenu{
  overflow:hidden;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,119,0,.07),transparent 35%),
    linear-gradient(145deg,#171719,#070708)!important;
  border:1px solid rgba(255,174,19,.28)!important;
  border-radius:5px 12px 5px 12px!important;
  box-shadow:
    0 18px 40px rgba(0,0,0,.75),
    0 0 17px rgba(255,110,0,.05),
    inset 0 1px rgba(255,255,255,.04)!important;
}

.idx-palace-header .navigation .level0 .submenu a{
  color:#d7d7da!important;
  transition:
    color .18s ease,
    background .18s ease,
    padding-left .18s ease!important;
}

.idx-palace-header .navigation .level0 .submenu a:hover{
  color:#ffe37a!important;
  background:linear-gradient(
    90deg,
    rgba(255,150,0,.10),
    transparent
  )!important;
  padding-left:4px!important;
}

.idx-palace-header .header.content .action:before{
  color:#ffc431!important;
  text-shadow:0 0 6px rgba(255,142,0,.32);
}

@keyframes genosSkyEnergy{
  0%{
    left:-35%;
    opacity:0;
  }
  8%{
    opacity:1;
  }
  88%{
    opacity:1;
  }
  100%{
    left:110%;
    opacity:0;
  }
}

@keyframes genosStatusPulse{
  0%,100%{
    transform:scale(.85);
    opacity:.65;
    box-shadow:
      0 0 4px #ffb514,
      0 0 8px rgba(255,108,0,.45);
  }
  50%{
    transform:scale(1.12);
    opacity:1;
    box-shadow:
      0 0 7px #ffd23d,
      0 0 15px rgba(255,98,0,.8);
  }
}

@media(max-width:769px){
  .idx-skybar{
    height:42px;
  }

  .idx-skybar-inner{
    padding:0 8px;
  }

  .idx-status{
    gap:7px;
  }

  .idx-palace-name,
  .idx-divider,
  .idx-hide-mobile,
  .idx-contact{
    display:none!important;
  }

  .idx-crest{
    width:24px;
    height:24px;
  }

  .idx-live{
    font-size:9px;
    letter-spacing:.5px;
  }

  .idx-actions{
    gap:4px;
  }

  .idx-actions a{
    height:28px;
    padding:0 9px;
    font-size:9px;
    border-radius:4px 8px 4px 8px;
  }

  .idx-palace-header .header.content{
    min-height:76px!important;
  }

  .idx-palace-header .logo{
    margin:auto!important;
    padding:4px 8px!important;
    min-width:0!important;
  }

  .idx-palace-header .logo img{
    max-width:185px!important;
    max-height:58px!important;
  }

  .idx-palace-header .ves-megamenu .nav-item.level0>.nav-anchor{
    padding:12px 14px!important;
    margin:4px 10px!important;
  }

  .idx-palace-header .ves-megamenu .nav-item.level0:last-child>.nav-anchor{
    margin:4px 10px!important;
  }
}

@media(prefers-reduced-motion:reduce){
  .idx-skybar:after,
  .idx-live em{
    animation:none!important;
  }

  .idx-actions a,
  .idx-palace-header .logo img,
  .idx-palace-header .ves-megamenu .nav-item.level0>.nav-anchor{
    transition:none!important;
  }
}
</style><div class="header content"><span class="action nav-toggle" data-action="toggle-nav" tabindex="0"><span>Toggle Nav</span></span>
<a aria-label="store logo" class="logo" href="%%CANONICAL%%" title="">
<img alt="%%SITE%%" height="66" src="%%LOGO%%" width="220"/>
</a>
<div class="kanmo-middle-header"><div class="navigation-wrapper"><span class="button-close" data-action="toggle-nav" tabindex="0"></span><div class="top-header-components-mc mobile-component">
<div class="header_panel_info">
<ul>
<li><a href="%%CANONICAL%%"><span><img alt="Kanmo Circle logo" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/KanmoCircle.svg" width="22"/></span>about Kanmo Circle</a></li>
<li><a href="%%CANONICAL%%"><img alt="gift registry icon" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gift_blue.svg" width="22"/>gift registry</a></li>
<li><a href="%%CANONICAL%%"><span><img alt="store finder icon" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/storefinder_blue.svg" width="22"/></span>store finder</a></li>
<li><a href="%%CANONICAL%%" target="_blank"><span><img alt="Whatsapp logo" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/Whatsapp_Logo.svg" width="22"/></span>contact us</a></li>
</ul>
</div>
</div>
<div class="block ves-menu ves-megamenu-mobile" id="menu-top-mothercare1742308061777474800-menu">
<nav class="navigation" data-action="navigation" role="navigation">
<ul class="ves-megamenu menu-hover ves-horizontal" data-breeze-processed="true" data-mage-init='{"menu":{"responsive":false, "expanded":false, "position":{"my":"left top","at":"left bottom"}}}' data-toggle-mobile-nav="false" id="menu-top-mothercare1742308061777474800">
<li class="nav-item level0 level-top" id="vesitem-884521742308061573800708"><a class="nav-anchor" href="%%CANONICAL%%" style="font-size:12px;"><span>%%SITE%%</span></a></li><li class="nav-item level0 level-top" id="vesitem-8845517423080611320572800"><a class="nav-anchor" href="%%CANONICAL%%" style="font-size:12px;white-space: nowrap;"><span>SLOT</span></a></li><li class="nav-item level0 level-top" id="vesitem-8845817423080611645503736"><a class="nav-anchor" href="%%CANONICAL%%" style="font-size:12px;white-space: nowrap;"><span>SLOT GACOR</span></a></li><li class="nav-item level0 level-top" id="vesitem-8846117423080611688273500"><a class="nav-anchor" href="%%CANONICAL%%" style="font-size:12px;white-space: nowrap;"><span>SLOT777</span></a></li><li class="nav-item level0 level-top" id="vesitem-884641742308061459618024"><a class="nav-anchor" href="%%CANONICAL%%" style="font-size:12px;white-space: nowrap;"><span>APK SLOT777</span></a></li><li class="nav-item level0 level-top" id="vesitem-8846717423080612122176647"><a class="nav-anchor" href="%%CANONICAL%%" style="max-lines: 2;
line-height: 20px;
width:100%;
font-size:12px;white-space: nowrap;"><span>SITUS SLOT</span></a></li><li class="nav-item level0 level-top" id="vesitem-88470174230806170878408"><a class="nav-anchor" href="%%CANONICAL%%" style="white-space: nowrap;
font-size:12px;
"><span>LINK GACOR</span></a></li><li class="nav-item level0 level-top" id="vesitem-8847317423080612096131144"><a class="nav-anchor" data-bgcolor="#ff0000 !important" data-color="#fff !important" data-hover-bgcolor="#ff0000 !important" data-hover-color="#fff !important" href="%%CANONICAL%%" style="color: #fff !important;background-color: #ff0000 !important;" target="_self"><span>SITUS SLOT GACOR HARI INI</span></a></li> </ul>
</nav>
</div>
<div class="kanmo-megamenu-header-mobile">
<div class="header-megamenu-mobile">
<span class="fa-user-icon-mc"></span>
<div class="account-label">
<a class="account-link" href="/customer/account">Akun Saya</a>
</div>
<button><a href="%%ORIGIN_SLASH%%">Masuk</a></button>
</div>
</div><div class="mobile-header-panel" data-breeze-temporary="true" style="background-color: #d4af37;"><a class="action skip contentarea" href="#contentarea">
<span>
        lewati ke Konten    </span>
</a>
<div class="left_top_header">
<div class="top-header-components-mc left-top">
<div class="header_panel_info">
<ul>
<li class="download-app-link">
<a href="%%ORIGIN_SLASH%%">
<img alt="download icon" height="14" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/app.png" width="14">
          download apps
        </img></a>
<div class="download-popup">
<div class="wrap">
<div class="right-cols">
<div class="app-content">
                download app  from:
                <div class="view code app_store">
<a class="sh-store-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="app store" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/apple-en.png" width="200">
</img></a>
</div>
<div class="view code play_store">
<a class="sh-store-link" data-test="google-play-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="google play" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/google-en.png" width="200">
</img></a>
</div>
</div>
</div>
</div>
</div>
</li>
<li>
<a href="%%ORIGIN_SLASH%%">
<img alt="gift registry icon" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gift_white.svg" width="22">
          gift registry        </img></a>
</li>
</ul>
</div>
</div>
</div><div class="right_top_header">
<div class="switcher language switcher-language" data-ui-id="language-switcher" id="switcher-language">
<strong class="label switcher-label"><span>Bahasa</span></strong>
<div class="actions dropdown options switcher-options">
<div class="action toggle switcher-trigger" id="switcher-language-trigger">
<strong class="view-mothercare_website_bahasa">
<span>ID</span>
</strong>
</div>
<div class="ui-dialog mage-dropdown-dialog" role="dialog"></div><div class="ui-dialog mage-dropdown-dialog" role="dialog"></div></div>
</div>
<style>.switch{position:relative;display:inline-block;width:60px;height:34px}.switch input{opacity:0;width:0;height:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}.slider:before{position:absolute;content:"ID";height:26px;width:26px;left:4px;bottom:4px;background-color:#0b2743;-webkit-transition:.4s;transition:.4s;color:#000}input:checked+.slider{background-color:#ccc}input:focus+.slider{box-shadow:0 0 1px #2196f3}input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px);content:"EN";color:#000}.slider.round{border-radius:34px}.slider.round:before{border-radius:50%}</style>
<div class="top-header-components-mc">
<div class="lang-switch-mc">
<li><label class="switch">
<input checked="" type="checkbox"/>
<span class="slider round"></span>
</label>
</li>
</div>
<div class="header_panel_info">
<ul>
<li><a href="%%ORIGIN_SLASH%%"><img alt="store finder icon" height="22" loading="lazy" src="%%FAVICON%%" width="22">LOGIN SLOT</img></a></li>
<li><a href="%%ORIGIN_SLASH%%"><img alt="Kanmo Circle logo" height="22" loading="lazy" src="%%FAVICON%%" width="22"/>DAFTAR VIP SLOT</a></li>
<li><a href="%%ORIGIN_SLASH%%" target="_blank"><img alt="Whatsapp logo" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/Whatsapp_Logo.svg" width="22"/> Contact Us</a></li>
</ul>
</div>
</div>
<style>.header_panel_info ul{display:flex;column-gap:20px;list-style:none}.header_panel_info img{margin-right:10px}.panel.header div{width:100%}.top-header-components-mc{display:flex;justify-content:space-between}.lang-switch-mc li{list-style:none}@media only screen and (max-width:769px){.header_panel_info ul{flex-direction:column}}</style>
</div></div><div class="mobile-header-panel" data-breeze-temporary="true" style="background-color: #d4af37;"><a class="action skip contentarea" href="#contentarea">
<span>
        lewati ke Konten    </span>
</a>
<div class="left_top_header">
<div class="top-header-components-mc left-top">
<div class="header_panel_info">
<ul>
<li class="download-app-link">
<a href="%%ORIGIN_SLASH%%">
<img alt="download icon" height="14" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/app.png" width="14"/>
          download apps
        </a>
<div class="download-popup">
<div class="wrap">
<div class="right-cols">
<div class="app-content">
                download app  from:
                <div class="view code app_store">
<a class="sh-store-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="app store" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/apple-en.png" width="200"/>
</a>
</div>
<div class="view code play_store">
<a class="sh-store-link" data-test="google-play-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="google play" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/google-en.png" width="200"/>
</a>
</div>
</div>
</div>
</div>
</div>
</li>
<li>
<a href="%%ORIGIN_SLASH%%">
<img alt="gift registry icon" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gift_white.svg" width="22"/>
          gift registry        </a>
</li>
</ul>
</div>
</div>
</div><div class="right_top_header">
<div class="switcher language switcher-language" data-ui-id="language-switcher" id="switcher-language">
<strong class="label switcher-label"><span>Bahasa</span></strong>
<div class="actions dropdown options switcher-options">
<div class="action toggle switcher-trigger" id="switcher-language-trigger">
<strong class="view-mothercare_website_bahasa">
<span>ID</span>
</strong>
</div>
<div class="ui-dialog mage-dropdown-dialog" role="dialog" style="display: none;"><ul class="dropdown switcher-dropdown" data-breeze-processed="true" data-mage-init='{"dropdownDialog":{
                "appendTo":"#switcher-language &gt; .options",
                "triggerTarget":"#switcher-language-trigger",
                "closeOnMouseLeave": false,
                "triggerClass":"active",
                "parentClass":"active",
                "buttons":null}}'>
</ul></div><div class="ui-dialog mage-dropdown-dialog" role="dialog" style="display: none;"><ul class="dropdown switcher-dropdown" data-breeze-processed="true" data-mage-init='{"dropdownDialog":{
                "appendTo":"#switcher-language &gt; .options",
                "triggerTarget":"#switcher-language-trigger",
                "closeOnMouseLeave": false,
                "triggerClass":"active",
                "parentClass":"active",
                "buttons":null}}'>
</ul></div></div>
</div>
<style>.switch{position:relative;display:inline-block;width:60px;height:34px}.switch input{opacity:0;width:0;height:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}.slider:before{position:absolute;content:"ID";height:26px;width:26px;left:4px;bottom:4px;background-color:#0b2743;-webkit-transition:.4s;transition:.4s;color:#000}input:checked+.slider{background-color:#ccc}input:focus+.slider{box-shadow:0 0 1px #2196f3}input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px);content:"EN";color:#000}.slider.round{border-radius:34px}.slider.round:before{border-radius:50%}</style>
<div class="top-header-components-mc">
<div class="lang-switch-mc">
<li><label class="switch">
<input checked="" type="checkbox"/>
<span class="slider round"></span>
</label>
</li>
</div>
<div class="header_panel_info">
<ul>
<li><a href="%%ORIGIN_SLASH%%"><img alt="store finder icon" height="22" loading="lazy" src="%%FAVICON%%" width="22"/>LOGIN</a></li>
<li><a href="%%ORIGIN_SLASH%%"><img alt="Kanmo Circle logo" height="22" loading="lazy" src="%%FAVICON%%" width="22"/>DAFTAR</a></li>
<li><a href="%%ORIGIN_SLASH%%" target="_blank"><img alt="Whatsapp logo" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/Whatsapp_Logo.svg" width="22"/> Contact Us</a></li>
</ul>
</div>
</div>
<style>.header_panel_info ul{display:flex;column-gap:20px;list-style:none}.header_panel_info img{margin-right:10px}.panel.header div{width:100%}.top-header-components-mc{display:flex;justify-content:space-between}.lang-switch-mc li{list-style:none}@media only screen and (max-width:769px){.header_panel_info ul{flex-direction:column}}</style>
</div></div><div class="mobile-header-panel" data-breeze-temporary="true" style="background-color: #d4af37;"><a class="action skip contentarea" href="#contentarea">
<span>
        lewati ke Konten    </span>
</a>
<div class="left_top_header">
<div class="top-header-components-mc left-top">
<div class="header_panel_info">
<ul>
<li class="download-app-link">
<a href="%%ORIGIN_SLASH%%">
<img alt="download icon" height="14" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/app.png" width="14"/>
          download apps
        </a>
<div class="download-popup">
<div class="wrap">
<div class="right-cols">
<div class="app-content">
                download app  from:
                <div class="view code app_store">
<a class="sh-store-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="app store" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/apple-en.png" width="200"/>
</a>
</div>
<div class="view code play_store">
<a class="sh-store-link" data-test="google-play-link" href="%%ORIGIN_SLASH%%" ng-click="callAction($event, 'appStore')" target="_blank">
<img alt="google play" class="sh-store-link__image" height="60" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/google-en.png" width="200"/>
</a>
</div>
</div>
</div>
</div>
</div>
</li>
<li>
<a href="%%ORIGIN_SLASH%%">
<img alt="gift registry icon" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gift_white.svg" width="22"/>
          gift registry        </a>
</li>
</ul>
</div>
</div>
</div><div class="right_top_header">
<div class="switcher language switcher-language" data-ui-id="language-switcher" id="switcher-language">
<strong class="label switcher-label"><span>Bahasa</span></strong>
<div class="actions dropdown options switcher-options">
<div class="action toggle switcher-trigger" id="switcher-language-trigger">
<strong class="view-mothercare_website_bahasa">
<span>ID</span>
</strong>
</div>
<div class="ui-dialog mage-dropdown-dialog" role="dialog" style="display: none;"><ul class="dropdown switcher-dropdown" data-breeze-processed="true" data-mage-init='{"dropdownDialog":{
                "appendTo":"#switcher-language &gt; .options",
                "triggerTarget":"#switcher-language-trigger",
                "closeOnMouseLeave": false,
                "triggerClass":"active",
                "parentClass":"active",
                "buttons":null}}'>
</ul></div><div class="ui-dialog mage-dropdown-dialog" role="dialog" style="display: none;"><ul class="dropdown switcher-dropdown" data-breeze-processed="true" data-mage-init='{"dropdownDialog":{
                "appendTo":"#switcher-language &gt; .options",
                "triggerTarget":"#switcher-language-trigger",
                "closeOnMouseLeave": false,
                "triggerClass":"active",
                "parentClass":"active",
                "buttons":null}}'>
</ul></div></div>
</div>
<style>.switch{position:relative;display:inline-block;width:60px;height:34px}.switch input{opacity:0;width:0;height:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}.slider:before{position:absolute;content:"ID";height:26px;width:26px;left:4px;bottom:4px;background-color:#0b2743;-webkit-transition:.4s;transition:.4s;color:#000}input:checked+.slider{background-color:#ccc}input:focus+.slider{box-shadow:0 0 1px #2196f3}input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px);content:"EN";color:#000}.slider.round{border-radius:34px}.slider.round:before{border-radius:50%}</style>
<div class="top-header-components-mc">
<div class="lang-switch-mc">
<li><label class="switch">
<input checked="" type="checkbox"/>
<span class="slider round"></span>
</label>
</li>
</div>
<div class="header_panel_info">
<ul>
<li><a href="%%ORIGIN_SLASH%%"><img alt="store finder icon" height="22" loading="lazy" src="%%FAVICON%%" width="22"/>LOGIN</a></li>
<li><a href="%%ORIGIN_SLASH%%"><img alt="Kanmo Circle logo" height="22" loading="lazy" src="%%FAVICON%%" width="22"/>DAFTAR</a></li>
<li><a href="%%ORIGIN_SLASH%%" target="_blank"><img alt="Whatsapp logo" height="22" loading="lazy" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/Whatsapp_Logo.svg" width="22"/> Contact Us</a></li>
</ul>
</div>
</div>
<style>.header_panel_info ul{display:flex;column-gap:20px;list-style:none}.header_panel_info img{margin-right:10px}.panel.header div{width:100%}.top-header-components-mc{display:flex;justify-content:space-between}.lang-switch-mc li{list-style:none}@media only screen and (max-width:769px){.header_panel_info ul{flex-direction:column}}</style>
</div></div></div><div class="ui-dialog mage-dropdown-dialog" role="dialog" style="display: none;"><div class="block block-minicart" data-breeze-processed="true" data-mage-init='{"dropdownDialog":{
                "appendTo":"[data-block=minicart]",
                "triggerTarget":".showcart",
                "timeout": "2000",
                "closeOnMouseLeave": false,
                "closeOnEscape": true,
                "triggerClass":"active",
                "parentClass":"active",
                "buttons":[]}}' data-role="dropdownDialog">
<div data-bind="
                scope: 'minicart_content'
            " id="minicart-content-wrapper"><!-- ko template: { if: shouldRender, name: getTemplate() } --><!-- /ko --></div>
</div></div></div>
</div></header>

<div class="idx-news-ticker" aria-label="%%SITE%% informasi berjalan">
  <div class="idx-news-track">
    <span>✦ %%SITE%% • SLOT GACOR HARI INI • LINK GAMPANG MENANG • RTP LIVE UPDATE • BONUS MEMBER • LAYANAN ONLINE 24 JAM ✦</span>
    <span aria-hidden="true">✦ %%SITE%% • SLOT GACOR HARI INI • LINK GAMPANG MENANG • RTP LIVE UPDATE • BONUS MEMBER • LAYANAN ONLINE 24 JAM ✦</span>
  </div>
</div>
<main class="page-main" id="maincontent"><div id="contentarea"></div>
<div class="page messages"><div data-placeholder="messages"></div>
<div data-bind="scope: 'messages'">
<!-- ko if: cookieMessages && cookieMessages.length > 0 --><!-- /ko -->
<!-- ko if: messages().messages && messages().messages.length > 0 --><!-- /ko -->
</div>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "*": {
            "Magento_Ui/js/core/app": {
                "components": {
                        "messages": {
                            "component": "Magento_Theme/js/view/messages"
                        }
                    }
                }
            }
    }
</script>
</div><div class="columns"><div class="column main"><div class="product-info-wrapper"><div class="product-info-main">
<style>.mpstorepickup-available-store-info{width:78%}.mpstorepickup-pcs{right:0;font-weight:600;position:absolute;width:20%;padding-right:12px}.mpstorepickup-available{display:inline-flex;width:100%;margin-bottom:20px}.mpstorepickup-available-content .pickup-checkbox{margin-top:-20px}.mpstorepickup-pcs .count:after{content:' pcs.'}#mpstorepickup-available-popup .store-pickup{margin-bottom:14px}</style>
<style>.mpstorepickup-available-content .pickup-checkbox{cursor:pointer}.mfp-wrap{position:fixed;overflow:auto}</style>
<div class="product-info"><div class="page-title-wrapper product">
<h1 class="page-title">
<span class="base" data-ui-id="page-title-wrapper" itemprop="name">%%H1%%</span> </h1>
</div>
<div class="product-info-price"><div class="price-box price-final_price" data-price-box="product-id-600193" data-product-id="600193" data-role="priceBox">
<div class="price-with-discount">
<span class="normal-price">
<span class="price-container price-final_price tax weee" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
<span class="price-label">Serendah</span>
<span class="price-wrapper" data-price-amount="10000" data-price-type="finalPrice" id="product-price-600193"><span class="price">Rp 10.000</span></span>
<meta content="" itemprop="availability"/>
<meta content="" itemprop="shippingDetails"/>
<meta content="" itemprop="hasMerchantReturnPolicy"/>
<meta content="10000" itemprop="price"/>
<meta content="IDR" itemprop="priceCurrency"/>
</span>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "SLOT",
  "url": "%%CANONICAL%%",
  "logo": "%%LOGO%%",
  "description": "%%SITE%% adalah link penyedia slot gacor hari ini dan situs gacor 777 online gampang maxwin malam ini dengan pola gacor rtp scatter x1000 yang update setiap hari dan sistem deposit 10k mudah via QRIS.",
  "foundingDate": "2024",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+62-000-0000-0000",
    "contactType": "customer service",
    "areaServed": "ID",
    "availableLanguage": [
      "Indonesian"
    ]
  },
  "sameAs": [
    "https://facebook.com/slot",
    "https://instagram.com/slot",
    "https://twitter.com/slot"
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Apa keunggulan %%SITE%% sebagai platform slot online 2026?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "%%SITE%% menghadirkan informasi berbagai kategori permainan slot online melalui tampilan modern yang dirancang agar mudah dijelajahi. Pengunjung dapat menemukan pilihan Slot777, mengenali fitur permainan, serta menelusuri katalog berdasarkan tema dan karakteristik game. Navigasi yang responsif juga membantu pengguna mengakses informasi melalui smartphone maupun desktop."
      }
    },
    {
      "@type": "Question",
      "name": "Bagaimana cara mengakses 777 APK SLOT melalui %%SITE%%?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Pengunjung yang mencari 777 APK SLOT dapat menelusuri informasi akses mobile melalui halaman %%SITE%%. Sebelum mengunduh aplikasi, pastikan sumber unduhan dapat dipercaya, perangkat mendukung versi aplikasi, dan izin akses yang diminta sesuai kebutuhan. Pengguna juga dapat memanfaatkan akses melalui browser apabila tersedia tanpa harus memasang aplikasi tambahan."
      }
    },
    {
      "@type": "Question",
      "name": "Di mana menemukan informasi link gacor 2026 dan Slot777 hari ini?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Informasi mengenai link gacor 2026 dan situs Slot777 hari ini dapat ditemukan melalui kategori pencarian permainan yang disusun pada halaman %%SITE%%. Pengunjung dapat menjelajahi berbagai tema, mempelajari mekanisme permainan, serta melihat informasi fitur yang tersedia. Istilah gacor merupakan istilah populer dalam pencarian dan tidak menunjukkan bahwa suatu permainan memiliki peluang kemenangan yang pasti lebih tinggi."
      }
    },
    {
      "@type": "Question",
      "name": "Benarkah situs slot gacor malam ini memiliki peluang menang lebih besar?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Istilah situs slot gacor malam ini sering digunakan untuk menggambarkan permainan yang dianggap menarik oleh sebagian pengguna pada waktu tertentu. Namun, tidak ada bukti bahwa bermain pada malam hari secara otomatis meningkatkan peluang kemenangan. Hasil permainan bergantung pada mekanisme dan aturan masing-masing game, sehingga tidak dapat dipastikan hanya berdasarkan waktu bermain atau istilah promosi."
      }
    },
    {
      "@type": "Question",
      "name": "Apa yang perlu diperhatikan sebelum memilih agen Slot777 resmi di %%SITE%%?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sebelum menggunakan layanan yang menawarkan akses Slot777, pengunjung sebaiknya memeriksa keaslian alamat situs, informasi pengelola, ketentuan penggunaan, keamanan akun, serta status perizinan yang dapat diverifikasi. %%SITE%% dapat dijadikan titik awal untuk menelusuri informasi kategori permainan, tetapi penyebutan agen Slot777 resmi tidak dengan sendirinya membuktikan bahwa suatu layanan telah memiliki izin atau sertifikasi tertentu."
      }
    }
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "%%ORIGIN_SLASH%%#organization",
      "name": "SLOT",
      "url": "%%CANONICAL%%",
      "description": "%%SITE%% adalah link penyedia slot gacor hari ini dan situs gacor 777 online gampang maxwin malam ini dengan pola gacor rtp scatter x1000 yang update setiap hari dan sistem deposit 10k mudah via QRIS."
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-1",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "BAGAS"
      },
      "datePublished": "2026-10-07",
      "reviewBody": "Saya suka halaman yang informasinya langsung kelihatan tanpa harus buka banyak menu. %%SITE%% punya susunan kategori yang cukup praktis, terutama saat diakses lewat smartphone. Kalau soal istilah slot gacor, saya tetap anggap itu sebatas istilah populer, bukan jaminan hasil.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "5",
        "bestRating": "5",
        "worstRating": "1"
      }
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-2",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "RANGGA"
      },
      "datePublished": "2026-10-02",
      "reviewBody": "Pertama lihat %%SITE%%, saya langsung tertarik sama tampilannya yang modern. Menu Slot777 gampang ditemukan dan pilihan gamenya tersusun cukup rapi. Buat buka dari HP juga nyaman, nggak perlu bolak-balik cari halaman.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.9",
        "bestRating": "5",
        "worstRating": "1"
      }
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-3",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "DIMAS"
      },
      "datePublished": "2026-10-03",
      "reviewBody": "Saya lebih suka situs yang menunya nggak bikin bingung. Di %%SITE%% informasi permainan cukup mudah dicari, terutama kategori slot online. Tampilan halaman di layar kecil juga tetap terbaca dengan jelas.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.8",
        "bestRating": "5",
        "worstRating": "1"
      }
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-4",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "FAJAR"
      },
      "datePublished": "2026-10-04",
      "reviewBody": "Yang menarik dari %%SITE%% menurut saya justru variasi tema permainannya. Ada yang tampilannya klasik, ada juga yang lebih futuristik. Informasi fiturnya lumayan membantu sebelum menentukan permainan yang mau dilihat.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.9",
        "bestRating": "5",
        "worstRating": "1"
      }
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-5",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "ALDI"
      },
      "datePublished": "2026-10-05",
      "reviewBody": "Awalnya saya cuma mau lihat informasi APK Slot777. Ternyata ada beberapa kategori lain yang bisa dijelajahi juga. Saya pribadi lebih nyaman menggunakan browser daripada memasang aplikasi tambahan.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.8",
        "bestRating": "5",
        "worstRating": "1"
      }
    },
    {
      "@type": "Review",
      "@id": "%%ORIGIN_SLASH%%#review-6",
      "itemReviewed": {
        "@id": "%%ORIGIN_SLASH%%#organization"
      },
      "author": {
        "@type": "Person",
        "name": "NANDO"
      },
      "datePublished": "2026-10-06",
      "reviewBody": "Menurut saya %%SITE%% punya konsep visual yang cukup menarik. Bagian navigasinya terasa sederhana dan informasi kategori game nggak terlalu menumpuk. Kalau cari referensi Slot777 terbaru, susunan halamannya cukup membantu.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.9",
        "bestRating": "5",
        "worstRating": "1"
      }
    }
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "SLOT",
      "item": "%%CANONICAL%%"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "SLOT GACOR",
      "item": "%%CANONICAL%%"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "SLOT GACOR GAMPANG MENANG",
      "item": "%%CANONICAL%%"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "SLOT GACOR HARI INI",
      "item": "%%CANONICAL%%"
    },
    {
      "@type": "ListItem",
      "position": 5,
      "name": "SITUS SLOT RESMI ONLINE",
      "item": "%%CANONICAL%%"
    }
  ]
}
</script>
</span>
</div>
</div></div><div class="kc-cashback check">
<a class="kc-points-lable" href="%%ORIGIN_SLASH%%">
<img class="kcpoints-img" src="%%FAVICON%%" width="30px"/>
<span>Earn <span class="price">50.000.000</span> - <span class="price">500.000.000</span> Bonus</span>
</a>
</div>
<div class="kredivo-label">
<div>Use Installment Options from<img alt="" class="kredivo-logo" src="https://www.mothercare.co.id/media/wysiwyg/kredivo/kredivo_logo.png" style="width: 55px; margin: 0 5px;"/>. <a href="https://www.mothercare.co.id/payment-method">Laarn more</a></div> </div>
<div class="available-block">
<div class="title">
<span class="avail-text">Available for :</span>
</div>
<div class="lables">
<div class="home-div">
<img class="delivery-img" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_Catalog/images/delivery.png"/><span class="home">home delivery</span>
</div>
<div class="store-div">
<img class="home-pick-img" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Magento_Catalog/images/store-pick.png"/><span class="store">store pickup</span>
</div>
</div>
</div>
<div class="product attribute overview">
<div class="value" style="text-align: justify;">
<style>
.idx-rtp-live{
  position:relative;
  overflow:hidden;
  margin:18px 0 8px;
  padding:20px;
  color:#f4f4f4;
  font-family:Arial,sans-serif;
  border:1px solid rgba(255,177,19,.30);
  border-radius:7px 20px 7px 20px;
  background:
    radial-gradient(circle at 0 0,rgba(255,171,0,.12),transparent 28%),
    radial-gradient(circle at 100% 100%,rgba(255,70,0,.07),transparent 30%),
    linear-gradient(145deg,#171719 0%,#0d0d0f 48%,#050505 100%);
  box-shadow:
    0 16px 36px rgba(0,0,0,.45),
    0 0 18px rgba(255,115,0,.04),
    inset 0 1px 0 rgba(255,255,255,.055);
}

.idx-rtp-live:before{
  content:"";
  position:absolute;
  z-index:0;
  top:0;
  left:-35%;
  width:35%;
  height:2px;
  background:linear-gradient(
    90deg,
    transparent,
    #ff5500,
    #ff9700,
    #ffd83d,
    #fff2a0,
    #ffd83d,
    #ff7300,
    transparent
  );
  box-shadow:
    0 0 6px #ff9700,
    0 0 15px rgba(255,83,0,.55);
  animation:idxRtpEnergy 4.5s linear infinite;
}

.idx-rtp-live:after{
  content:"";
  position:absolute;
  z-index:0;
  inset:0;
  pointer-events:none;
  background:
    linear-gradient(
      115deg,
      transparent 0 48%,
      rgba(255,182,24,.018) 49%,
      transparent 50%
    ),
    repeating-linear-gradient(
      120deg,
      transparent 0,
      transparent 55px,
      rgba(255,178,0,.012) 56px,
      transparent 57px
    );
}

.idx-rtp-live > *{
  position:relative;
  z-index:1;
}

.idx-rtp-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
  padding-bottom:13px;
  border-bottom:1px solid rgba(255,178,22,.14);
}

.idx-rtp-title{
  position:relative;
  padding-left:17px;
  color:#fff0b3;
  font-size:20px;
  line-height:1.2;
  font-weight:900;
  letter-spacing:.7px;
  text-transform:uppercase;
  text-shadow:
    0 1px 2px #000,
    0 0 10px rgba(255,169,0,.16);
}

.idx-rtp-title:before{
  content:"";
  position:absolute;
  left:0;
  top:50%;
  width:6px;
  height:22px;
  transform:translateY(-50%) skewY(-20deg);
  border-radius:1px;
  background:linear-gradient(
    180deg,
    #fff3a5,
    #ffc51e 42%,
    #ff6200
  );
  box-shadow:
    0 0 6px #ffb000,
    0 0 13px rgba(255,72,0,.35);
}

.idx-live-badge{
  position:relative;
  overflow:hidden;
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:7px 12px;
  border:1px solid rgba(255,191,37,.35);
  border-radius:4px 10px 4px 10px;
  color:#ffe26a;
  background:
    linear-gradient(
      135deg,
      rgba(255,151,0,.13),
      rgba(13,13,14,.85)
    );
  box-shadow:
    inset 0 1px rgba(255,255,255,.04),
    0 0 11px rgba(255,121,0,.07);
  font-size:11px;
  font-weight:900;
  letter-spacing:.8px;
  text-transform:uppercase;
}

.idx-live-badge:after{
  content:"";
  position:absolute;
  top:0;
  left:-45%;
  width:30%;
  height:100%;
  transform:skewX(-22deg);
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,237,151,.20),
    transparent
  );
  animation:idxBadgeScan 3.6s ease-in-out infinite;
}

.idx-live-dot{
  width:8px;
  height:8px;
  flex:0 0 8px;
  border-radius:50%;
  background:#ffc21d;
  box-shadow:
    0 0 4px #ffd849,
    0 0 10px #ff9700,
    0 0 17px rgba(255,71,0,.55);
  animation:idxPulse 1.35s ease-in-out infinite;
}

.idx-rtp-sub{
  margin:-3px 0 16px;
  color:#aaa9a7;
  font-size:12px;
  line-height:1.55;
  letter-spacing:.15px;
}

.idx-rtp-grid{
  display:grid;
  grid-template-columns:repeat(2,minmax(0,1fr));
  gap:11px;
}

.idx-rtp-card{
  position:relative;
  overflow:hidden;
  padding:14px;
  border:1px solid rgba(255,175,19,.17);
  border-radius:5px 14px 5px 14px;
  background:
    radial-gradient(circle at 100% 0,rgba(255,111,0,.06),transparent 35%),
    linear-gradient(
      135deg,
      rgba(30,30,32,.96),
      rgba(10,10,11,.98)
    );
  box-shadow:
    0 8px 18px rgba(0,0,0,.27),
    inset 0 1px 0 rgba(255,255,255,.04);
  transition:
    transform .22s ease,
    border-color .22s ease,
    box-shadow .22s ease,
    background .22s ease;
}

.idx-rtp-card:before{
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:45%;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff5700,
    #ffac0a,
    #ffe260,
    transparent
  );
  box-shadow:0 0 7px rgba(255,128,0,.45);
  opacity:.75;
  transition:width .25s ease;
}

.idx-rtp-card:after{
  content:"";
  position:absolute;
  right:-27px;
  bottom:-27px;
  width:55px;
  height:55px;
  border-radius:50%;
  border:1px solid rgba(255,181,22,.07);
  box-shadow:
    0 0 0 8px rgba(255,181,22,.015),
    0 0 0 16px rgba(255,181,22,.01);
}

.idx-rtp-card:hover{
  transform:translateY(-3px);
  border-color:rgba(255,191,39,.40);
  background:
    radial-gradient(circle at 100% 0,rgba(255,121,0,.10),transparent 37%),
    linear-gradient(
      135deg,
      rgba(36,30,19,.97),
      rgba(10,10,11,.98)
    );
  box-shadow:
    0 13px 25px rgba(0,0,0,.40),
    0 0 16px rgba(255,117,0,.06),
    inset 0 1px rgba(255,255,255,.055);
}

.idx-rtp-card:hover:before{
  width:78%;
}

.idx-rtp-row{
  position:relative;
  z-index:2;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:10px;
  margin-bottom:10px;
}

.idx-game{
  color:#e8e8e9;
  font-size:13px;
  line-height:1.35;
  font-weight:800;
  letter-spacing:.25px;
}

.idx-pct{
  flex-shrink:0;
  color:#ffd347;
  font-size:17px;
  font-weight:900;
  letter-spacing:.2px;
  text-shadow:
    0 0 6px rgba(255,177,0,.28),
    0 1px 2px #000;
}

.idx-bar{
  position:relative;
  z-index:2;
  overflow:hidden;
  height:8px;
  padding:1px;
  border:1px solid rgba(255,177,23,.12);
  border-radius:999px;
  background:#050505;
  box-shadow:
    inset 0 2px 5px rgba(0,0,0,.8),
    0 1px rgba(255,255,255,.025);
}

.idx-fill{
  position:relative;
  display:block;
  overflow:hidden;
  height:100%;
  border-radius:inherit;
  background:
    linear-gradient(
      90deg,
      #a83c00 0%,
      #ff6500 20%,
      #ff9f00 48%,
      #ffd43e 75%,
      #fff19b 100%
    );
  box-shadow:
    0 0 5px #ff9700,
    0 0 12px rgba(255,92,0,.36);
}

.idx-fill:after{
  content:"";
  position:absolute;
  top:0;
  left:-35%;
  width:25%;
  height:100%;
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.75),
    transparent
  );
  transform:skewX(-25deg);
  animation:idxFillScan 2.8s ease-in-out infinite;
}

.idx-rtp-card:nth-child(2n) .idx-fill:after{
  animation-delay:-1.2s;
}

.idx-rtp-card:nth-child(3n) .idx-fill:after{
  animation-delay:-2s;
}

.idx-rtp-note{
  position:relative;
  margin-top:15px;
  padding:12px 12px 2px 25px;
  border-top:1px solid rgba(255,185,33,.12);
  color:#858589;
  font-size:11px;
  line-height:1.6;
  text-align:left;
}

.idx-rtp-note:before{
  content:"!";
  position:absolute;
  left:3px;
  top:12px;
  width:14px;
  height:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  border:1px solid rgba(255,185,28,.38);
  border-radius:50%;
  color:#ffc12c;
  background:rgba(255,153,0,.06);
  font-size:9px;
  font-weight:900;
  box-shadow:0 0 7px rgba(255,126,0,.10);
}

@keyframes idxRtpEnergy{
  0%{
    left:-35%;
    opacity:0;
  }

  8%{
    opacity:1;
  }

  90%{
    opacity:1;
  }

  100%{
    left:110%;
    opacity:0;
  }
}

@keyframes idxPulse{
  0%,100%{
    opacity:.55;
    transform:scale(.75);
    box-shadow:
      0 0 3px #ffc21d,
      0 0 7px rgba(255,119,0,.5);
  }

  50%{
    opacity:1;
    transform:scale(1.15);
    box-shadow:
      0 0 6px #ffe06a,
      0 0 13px #ff9700,
      0 0 22px rgba(255,65,0,.48);
  }
}

@keyframes idxBadgeScan{
  0%,25%{
    left:-45%;
  }

  65%,100%{
    left:125%;
  }
}

@keyframes idxFillScan{
  0%{
    left:-35%;
  }

  55%,100%{
    left:125%;
  }
}

@media(max-width:600px){
  .idx-rtp-live{
    padding:15px;
    border-radius:6px 15px 6px 15px;
  }

  .idx-rtp-head{
    align-items:flex-start;
  }

  .idx-rtp-title{
    font-size:17px;
  }

  .idx-live-badge{
    padding:6px 9px;
    font-size:9px;
  }

  .idx-rtp-grid{
    grid-template-columns:1fr;
    gap:9px;
  }

  .idx-rtp-card{
    padding:12px 13px;
  }

  .idx-pct{
    font-size:16px;
  }
}

@media(prefers-reduced-motion:reduce){
  .idx-rtp-live:before,
  .idx-live-dot,
  .idx-live-badge:after,
  .idx-fill:after{
    animation:none!important;
  }

  .idx-rtp-card{
    transition:none!important;
  }
}
</style>
<section class="idx-rtp-live" aria-label="RTP Live">
  <div class="idx-rtp-head"><div class="idx-rtp-title">RTP LIVE %%SITE%%</div><div class="idx-live-badge"><span class="idx-live-dot"></span> LIVE</div></div>
  <div class="idx-rtp-sub">Statistik tampilan sesi saat ini</div>
  <div class="idx-rtp-grid">
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Mahjong Ways 2</span><span class="idx-pct">96.8%</span></div><div class="idx-bar"><span class="idx-fill" style="width:96.8%"></span></div></div>
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Gates of Olympus</span><span class="idx-pct">95.7%</span></div><div class="idx-bar"><span class="idx-fill" style="width:95.7%"></span></div></div>
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Starlight Princess</span><span class="idx-pct">94.9%</span></div><div class="idx-bar"><span class="idx-fill" style="width:94.9%"></span></div></div>
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Sweet Bonanza</span><span class="idx-pct">93.6%</span></div><div class="idx-bar"><span class="idx-fill" style="width:93.6%"></span></div></div>
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Wild Bandito</span><span class="idx-pct">92.8%</span></div><div class="idx-bar"><span class="idx-fill" style="width:92.8%"></span></div></div>
    <div class="idx-rtp-card"><div class="idx-rtp-row"><span class="idx-game">Fortune Tiger</span><span class="idx-pct">91.9%</span></div><div class="idx-bar"><span class="idx-fill" style="width:91.9%"></span></div></div>
  </div>
  <div class="idx-rtp-note">Catatan: angka di atas adalah tampilan/demo statis, bukan data RTP real-time dari provider. Hubungkan ke sumber data resmi jika ingin menampilkan nilai live sebenarnya.</div>
</section>
</div>
</div>
</div><div class="product-config">
<div class="product-add-form">
<form action="%%CANONICAL%%" data-catalog-addtocart-initialized="1" data-product-sku="011110180-JH5201" id="product_addtocart_form" method="post" novalidate="true">
<input name="product" type="hidden" value="600193"/>
<input name="selected_configurable_option" type="hidden" value=""/>
<input id="related-products-field" name="related_product" type="hidden" value=""/>
<input name="item" type="hidden" value="600193"/>
<input name="form_key" type="hidden" value="GS3lBfcBGTAzfQBS">
<div class="product-options-wrapper" data-hasrequired="* Wajib diisi" id="product-options-wrapper">
<div class="fieldset" tabindex="0">
<div class="swatch-opt" data-role="swatch-options"></div>
</div>
</div>
<div class="product-options-bottom">
<div class="box-tocart">
<div class="fieldset fieldset-cust">
<div class="field qty">
<label class="label" for="qty"><span>choose qty</span></label>
<div class="control">
<select class="input-text qty" id="qty" name="qty" title="Qty" value="1">
<option selected="" value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select>
<div><span id="show_message" style="color: red"></span></div>
<input id="val_hidden" name="val_hidden" type="hidden" value="1"/>
</div>
</div>
<div class="actions">
<a href="%%CTA_LOGIN%%"><button class="action primary tocart" id="product-addtocart-button" style="background: #d218eb;
background: linear-gradient(90deg,rgb(193, 10, 248) 0%, rgb(193, 10, 248) 35%, rgb(193, 10, 248) 100%);" title="add to cart" type="button">
<span>LOGIN SLOT 777</span></button></a>
<a class="action towishlist" data-action="add-to-wishlist" href="%%CTA_DAFTAR%%"><img class="idx-register-icon" src="%%FAVICON%%" alt="%%SITE%%" width="24" height="24"><span>DAFTAR AKUN SLOT</span></a><div id="giftreg-message-pdp" style="">
<a class="notlogingift" href="%%ORIGIN_SLASH%%">
<img alt="" src="https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/Kanmo_CustomerDetail/images/gift.png">
	    	CLAIM BONUS SLOT
	    </img></a>
</div>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "body": {
            "addToWishlist": {"productType":"configurable","giftcardInfo":"[id^=giftcard]"}        }
    }
</script>
</div>
</div>
</div>
<!--Product video logic-->
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "#product_addtocart_form": {
            "Magento_Catalog/product/view/validation": {
                "radioCheckboxClosest": ".nested"
            }
        }
    }
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "#product_addtocart_form": {
            "catalogAddToCart": {
                "bindSubmit": true
            }
        }
    }
    </script>
</div>
</input></form>
</div>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "[data-role=priceBox][data-price-box=product-id-600193]": {
            "priceBox": {
                "priceConfig":  {"productId":600193,"priceFormat":{"pattern":"Rp %s","precision":2,"requiredPrecision":0,"decimalSymbol":",","groupSymbol":".","groupLength":3,"integerRequired":false},"prices":{"baseOldPrice":{"amount":10000,"adjustments":[]},"oldPrice":{"amount":10000,"adjustments":[]},"basePrice":{"amount":10000,"adjustments":[]},"finalPrice":{"amount":10000,"adjustments":[]}},"idSuffix":"_clone","tierPrices":[],"calculationAlgorithm":"UNIT_BASE_CALCULATION"}            }
        }
    }
</script>
<!-- <script>
// When the user clicks on div, open the popup
function myFunction() {
  var popup = document.getElementById("size-chart");
  popup.classList.toggle("show");
}
</script> -->
<script data-breeze="">require(['jquery'],($)=>{$(document).ready(function(){var modal=document.getElementById("myModals");var span=document.getElementsByClassName("close-icon")[0];$('#myBtns').on("click",function(e){$('#myModals').show();});$('#myBtns').on("click",function(e){$('#myModals').show();});$('.close-icon').on("click",function(e){$('#myModals').hide();});if(!typeof span==='undefined'){span.onclick=function(){modal.style.display="none";}}window.onclick=function(event){if(event.target==modal){modal.style.display="none";}}});});</script>
<style>@media screen and (max-width:767px){#myModals{animation:mymovess 2s;animation-fill-mode:forwards}}@keyframes mymovess {
  from {bottom: 0px;}
  to {bottom: 200px;}
}h5#content-baby{font-family:'Mothercare 2020';font-style:normal;font-weight:600;font-size:32px;line-height:150%;text-align:center;letter-spacing:-.011em;color:#165c7d}h5#content-baby{font-family:'Mothercare 2020';font-style:normal;font-weight:600;font-size:32px;line-height:150%;text-align:center;letter-spacing:-.011em;color:#165c7d;margin-bottom:1em}a.sizechart-link{position:relative;top:-20.5em;font-style:normal;font-weight:600;font-size:14px;line-height:19px;text-align:left;text-decoration-line:underline;text-transform:lowercase;color:#165c7d;font-family:'mothercare_2020-regular-webfont'}@media screen and (max-width:767px){a.sizechart-link{top:-17.5em}}</style>
<div class="sharing-buttons">
<label>Share product:</label>
<div class="sharethis-inline-share-buttons"></div>

</div></div></div><div class="product media">
<div class="breeze-gallery horizontal">
<div class="stage" data-gallery-role="gallery-placeholder">
<div class="main-image-wrapper">
<img alt="" class="main-image" height="608" src="%%IMAGE%%" width="608"/>
</div>
<a class="prev" href="%%CANONICAL%%" tabindex="-1">
<svg fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</a>
<a class="next" href="%%CANONICAL%%" tabindex="-1">
<svg fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</a>
<div class="controls">
<a class="zoom zoom-in" href="%%CANONICAL%%">
<svg fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</a>
<a class="zoom zoom-out" href="%%CANONICAL%%">
<svg fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M20 12H4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</a>
</div>
<a class="close" href="%%CANONICAL%%">
<svg fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</a>
</div>
<div class="thumbnails">
<a class="item active" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
<a class="item" href="%%CANONICAL%%" title="View Image">
<span class="slide-image-container">
<img alt="" loading="lazy" src="%%IMAGE%%"/>
</span>
</a>
</div>
</div>
<script id="gallery-thumbnail" type="text/x-magento-template">
    <a class="<%- classes %>" href="<%- full %>">
        <span class="slide-image-container">
            <img loading="lazy" alt="<%- caption %>"
                src="<%- thumb %>"
                srcset="<%- srcset ? srcset.small : '' %>"
                sizes="110px"
                width="110"
                height="110"
            />
        </span>
    </a>
</script>
<!-- <script data-breeze>
	require(['jquery'], ($) => {
        $('.thumbnails').pagebuilderSlider({
            dots: true,
            arrows: true
        });
    });
</script> -->
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "[data-gallery-role=gallery-placeholder]": {
            "Magento_ProductVideo/js/fotorama-add-video-events": {
                "videoData": [{"mediaType":"image","videoUrl":null,"isBase":true},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false}],
                "videoSettings": [{"playIfBase":"0","showRelated":"0","videoAutoRestart":"0"}],
                "optionsVideoData": {"598663":[{"mediaType":"image","videoUrl":null,"isBase":true},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false},{"mediaType":"image","videoUrl":null,"isBase":false}]}            }
        }
    }
</script>
</div></div>
<div class="product info detailed">
</div>
<input name="form_key" type="hidden" value="GS3lBfcBGTAzfQBS">
<script data-breeze="">var sectionsConfig={sections:{"stores\/store\/switch":["*"],"stores\/store\/switchrequest":["*"],"directory\/currency\/switch":["*"],"*":["messages","company","personal-data"],"customer\/account\/logout":["*","recently_viewed_product","recently_compared_product","persistent"],"customer\/account\/loginpost":["*"],"customer\/account\/createpost":["*"],"customer\/account\/editpost":["*"],"customer\/ajax\/login":["checkout-data","cart","captcha"],"catalog\/product_compare\/add":["compare-products","ajaxpro-reinit"],"catalog\/product_compare\/remove":["compare-products","ajaxpro-reinit"],"catalog\/product_compare\/clear":["compare-products","ajaxpro-reinit"],"sales\/guest\/reorder":["cart","ammessages"],"sales\/order\/reorder":["cart","ammessages"],"rest\/v1\/requisition_lists":["requisition"],"requisition_list\/requisition\/delete":["requisition"],"requisition_list\/item\/addtocart":["cart"],"checkout\/cart\/add":["cart","directory-data","ammessages","ajaxpro-cart"],"checkout\/cart\/delete":["cart","ammessages","ajaxpro-cart"],"checkout\/cart\/updatepost":["cart","ammessages","ajaxpro-cart"],"checkout\/cart\/updateitemoptions":["cart","ammessages","ajaxpro-cart"],"checkout\/cart\/couponpost":["cart","ammessages","ajaxpro-cart"],"checkout\/cart\/estimatepost":["cart","ammessages","ajaxpro-cart"],"checkout\/cart\/estimateupdatepost":["cart","ammessages","ajaxpro-cart"],"checkout\/onepage\/saveorder":["cart","checkout-data","last-ordered-items","ammessages"],"checkout\/sidebar\/removeitem":["cart","ammessages"],"checkout\/sidebar\/updateitemqty":["cart","ammessages"],"rest\/*\/v1\/carts\/*\/payment-information":["cart","last-ordered-items","captcha","instant-purchase","ammessages","osc-data"],"rest\/*\/v1\/guest-carts\/*\/payment-information":["cart","captcha","ammessages","osc-data"],"rest\/*\/v1\/guest-carts\/*\/selected-payment-method":["cart","checkout-data","ammessages","osc-data"],"rest\/*\/v1\/carts\/*\/selected-payment-method":["cart","checkout-data","instant-purchase","ammessages","osc-data"],"wishlist\/index\/add":["wishlist","ajaxpro-reinit"],"wishlist\/index\/remove":["wishlist","ajaxpro-reinit"],"wishlist\/index\/updateitemoptions":["wishlist"],"wishlist\/index\/update":["wishlist"],"wishlist\/index\/cart":["wishlist","cart","ajaxpro-cart"],"wishlist\/index\/fromcart":["wishlist","cart","ajaxpro-cart"],"wishlist\/index\/allcart":["wishlist","cart"],"wishlist\/shared\/allcart":["wishlist","cart"],"wishlist\/shared\/cart":["cart"],"customer_order\/cart\/updatefaileditemoptions":["cart"],"checkout\/cart\/updatefaileditemoptions":["cart"],"customer_order\/cart\/advancedadd":["cart"],"checkout\/cart\/advancedadd":["cart"],"checkout\/cart\/removeallfailed":["cart"],"checkout\/cart\/removefailed":["cart"],"customer_order\/cart\/addfaileditems":["cart"],"checkout\/cart\/addfaileditems":["cart"],"customer_order\/sku\/uploadfile":["cart"],"giftregistry\/index\/cart":["cart"],"giftregistry\/view\/addtocart":["cart"],"customer\/address\/*":["instant-purchase"],"customer\/account\/*":["instant-purchase"],"vault\/cards\/deleteaction":["instant-purchase"],"multishipping\/checkout\/overviewpost":["cart","ammessages"],"wishlist\/index\/copyitem":["wishlist"],"wishlist\/index\/copyitems":["wishlist"],"wishlist\/index\/deletewishlist":["wishlist","multiplewishlist"],"wishlist\/index\/createwishlist":["multiplewishlist"],"wishlist\/index\/editwishlist":["multiplewishlist"],"wishlist\/index\/moveitem":["wishlist"],"wishlist\/index\/moveitems":["wishlist"],"wishlist\/search\/addtocart":["cart","wishlist"],"paypal\/express\/placeorder":["cart","checkout-data","ammessages"],"paypal\/payflowexpress\/placeorder":["cart","checkout-data","ammessages"],"paypal\/express\/onauthorization":["cart","checkout-data","ammessages"],"rest\/*\/v1\/carts\/*\/po-payment-information":["cart","checkout-data","last-ordered-items"],"purchaseorder\/purchaseorder\/success":["cart","checkout-data","last-ordered-items"],"persistent\/index\/unsetcookie":["persistent"],"quickorder\/sku\/uploadfile\/":["cart"],"review\/product\/post":["review"],"amasty_promo\/cart\/add":["cart","ammessages"],"braintree\/paypal\/placeorder":["ammessages","cart","checkout-data"],"authorizenet\/directpost_payment\/place":["ammessages"],"rest\/*\/v1\/carts\/*\/update-item":["cart","checkout-data"],"rest\/*\/v1\/guest-carts\/*\/update-item":["cart","checkout-data"],"rest\/*\/v1\/guest-carts\/*\/remove-item":["cart","checkout-data"],"rest\/*\/v1\/carts\/*\/remove-item":["cart","checkout-data"],"kanmopopup\/catalog\/popupaddtocart":["cart"],"stylaconnect2page\/cart\/add":["cart"],"braintree\/googlepay\/placeorder":["cart","checkout-data"]},clientSideSections:["checkout-data","cart-data","osc-data"],baseUrls:["https:\/\/www.mothercare.co.id\/"],sectionNames:["messages","customer","compare-products","last-ordered-items","requisition","cart","directory-data","captcha","wishlist","company","company_authorization","negotiable_quote","instant-purchase","loggedAsCustomer","multiplewishlist","purchase_order","persistent","review","ammessages","personal-data","ajaxpro-reinit","ajaxpro-cart","ajaxpro-product","recently_viewed_product","recently_compared_product","product_data_storage","paypal-billing-agreement"]}</script>
<script data-breeze="">var customerDataConfig={"sectionLoadUrl":"https\u003A\u002F\u002Fwww.mothercare.co.id\u002Fcustomer\u002Fsection\u002Fload\u002F","expirableSectionLifetime":129600,"expirableSectionNames":["cart","persistent","ajaxpro-reinit","ajaxpro-cart","ajaxpro-product"],"cookieLifeTime":"3600","updateSessionUrl":"https\u003A\u002F\u002Fwww.mothercare.co.id\u002Fcustomer\u002Faccount\u002FupdateSession\u002F"}</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "*": {
            "Magento_Customer/js/invalidation-processor": {
                "invalidationRules": {
                    "website-rule": {
                        "Magento_Customer/js/invalidation-rules/website-rule": {
                            "scopeConfig": {
                                "websiteId": "3"
                            }
                        }
                    }
                }
            }
        }
    }
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "body": {
            "pageCache": {"url":"https:\/\/www.mothercare.co.id\/page_cache\/block\/render\/id\/600193\/","handles":["default","catalog_product_view","catalog_product_view_type_configurable","catalog_product_view_id_600193","catalog_product_view_sku_011110180-JH5201","customer_logged_out","breeze_default","breeze_catalog_product_view","breeze_catalog_product_view_type_configurable","breeze_catalog_product_view_id_600193","breeze_catalog_product_view_sku_011110180-JH5201","breeze_customer_logged_out","breeze_theme","breeze"],"originalRequest":{"route":"catalog","controller":"product","action":"view","uri":"\/nordic-naturals-baby-s-dha-011110180-JH5201.html"},"versionCookieName":"private_content_version"}        }
    }
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
{
    "*": {
        "Magento_Banner/js/model/banner":
            {"sectionLoadUrl":"https:\/\/www.mothercare.co.id\/banner\/ajax\/load\/","cacheTtl":30000}        }
}
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
{"*":{"Styla_Connect2\/js\/cart-update":[]}}</script>
<script async="" src="https://maps.google.com/maps/api/js?key=%0D%0A&amp;libraries=places,geometry"></script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "body": {
            "requireCookie": {"noCookieUrl":"https:\/\/www.mothercare.co.id\/cookie\/index\/noCookies\/","triggers":[".review .action.submit"],"isRedirectCmsPage":true}        }
    }
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "*": {
                "Magento_Catalog/js/product/view/provider": {
                    "data": {"items":{"600193":{"add_to_cart_button":{"post_data":"{\"action\":\"https:\\\/\\\/www.mothercare.co.id\\\/checkout\\\/cart\\\/add\\\/uenc\\\/%25uenc%25\\\/product\\\/600193\\\/\",\"data\":{\"product\":\"600193\",\"uenc\":\"%uenc%\"}}","url":"https:\/\/www.mothercare.co.id\/checkout\/cart\/add\/uenc\/%25uenc%25\/product\/600193\/","required_options":true},"add_to_compare_button":{"post_data":null,"url":"{\"action\":\"https:\\\/\\\/www.mothercare.co.id\\\/catalog\\\/product_compare\\\/add\\\/\",\"data\":{\"product\":\"600193\",\"uenc\":\"aHR0cHM6Ly93d3cubW90aGVyY2FyZS5jby5pZC9ub3JkaWMtbmF0dXJhbHMtYmFieS1zLWRoYS01MjEzMDEyNS1jbm8wMS5odG1s\"}}","required_options":null},"price_info":{"final_price":10000,"max_price":10000,"max_regular_price":10000,"minimal_regular_price":10000,"special_price":null,"minimal_price":10000,"regular_price":10000,"formatted_prices":{"final_price":"<span class=\"price\">Rp 10.000<\/span>","max_price":"<span class=\"price\">Rp 10.000<\/span>","minimal_price":"<span class=\"price\">Rp 10.000<\/span>","max_regular_price":"<span class=\"price\">Rp 10.000<\/span>","minimal_regular_price":null,"special_price":null,"regular_price":"<span class=\"price\">Rp 10.000<\/span>"},"extension_attributes":{"msrp":{"msrp_price":"<span class=\"price\">Rp 0<\/span>","is_applicable":"","is_shown_price_on_gesture":"1","msrp_message":"","explanation_message":"Our price is lower than the manufacturer&#039;s &quot;minimum advertised price.&quot; As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart."},"tax_adjustments":{"final_price":10000,"max_price":10000,"max_regular_price":10000,"minimal_regular_price":10000,"special_price":10000,"minimal_price":10000,"regular_price":10000,"formatted_prices":{"final_price":"<span class=\"price\">Rp 10.000<\/span>","max_price":"<span class=\"price\">Rp 10.000<\/span>","minimal_price":"<span class=\"price\">Rp 10.000<\/span>","max_regular_price":"<span class=\"price\">Rp 10.000<\/span>","minimal_regular_price":null,"special_price":"<span class=\"price\">Rp 10.000<\/span>","regular_price":"<span class=\"price\">Rp 10.000<\/span>"}},"weee_attributes":[],"weee_adjustment":"<span class=\"price\">Rp 10.000<\/span>"}},"images":[{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=325&height=325&canvas=325,325&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_viewed_products_grid_content_widget","height":325,"width":325,"label":"Nordic Natural Baby's DHA","resized_width":325,"resized_height":325},{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=270&height=270&canvas=270,270&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_viewed_products_list_content_widget","height":270,"width":270,"label":"Nordic Natural Baby's DHA","resized_width":270,"resized_height":270},{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=156&height=156&canvas=156,156&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_viewed_products_images_names_widget","height":156,"width":156,"label":"Nordic Natural Baby's DHA","resized_width":156,"resized_height":156},{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=325&height=325&canvas=325,325&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_compared_products_grid_content_widget","height":325,"width":325,"label":"Nordic Natural Baby's DHA","resized_width":325,"resized_height":325},{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=270&height=207&canvas=270,207&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_compared_products_list_content_widget","height":207,"width":270,"label":"Nordic Natural Baby's DHA","resized_width":270,"resized_height":207},{"url":"https:\/\/www.mothercare.co.id\/media\/catalog\/product\/1\/1\/119669web1_1.jpg?width=156&height=156&canvas=156,156&optimize=low&bg-color=255,255,255&fit=bounds","code":"recently_compared_products_images_names_widget","height":156,"width":156,"label":"Nordic Natural Baby's DHA","resized_width":156,"resized_height":156}],"url":"https:\/\/www.mothercare.co.id\/nordic-naturals-baby-s-dha-011110180-JH5201.html","id":600193,"name":"%%SITE%%: Link Slot Gacor Hari Ini Main Slot777 Gacor Online Resmi Gampang Maxwin","type":"configurable","is_salable":"1","store_id":15,"currency_code":"IDR","extension_attributes":{"wishlist_button":{"post_data":null,"url":"{\"action\":\"https:\\\/\\\/www.mothercare.co.id\\\/wishlist\\\/index\\\/add\\\/\",\"data\":{\"product\":600193,\"uenc\":\"aHR0cHM6Ly93d3cubW90aGVyY2FyZS5jby5pZC9ub3JkaWMtbmF0dXJhbHMtYmFieS1zLWRoYS01MjEzMDEyNS1jbm8wMS5odG1s\"}}","required_options":null},"review_html":"    <div class=\"product-reviews-summary short empty\">\n        <div class=\"reviews-actions\">\n            \n        <\/div>\n    <\/div>\n"},"is_available":true}},"store":"15","currency":"IDR","productCurrentScope":"website"}            }
        }
    }
</script>
<script data-role="msrp-popup-template" type="text/x-magento-template">
    <div id="map-popup-click-for-price" class="map-popup">
        <div class="popup-header">
            <strong class="title" id="map-popup-heading-price"></strong>
        </div>
        <div class="popup-content">
            <div class="map-info-price" id="map-popup-content">
                <div class="price-box">
                    <div class="map-msrp" id="map-popup-msrp-box">
                        <span class="label">Harga</span>
                        <span class="old-price map-old-price" id="map-popup-msrp">
                            <span class="price"></span>
                        </span>
                    </div>
                    <div class="map-price" id="map-popup-price-box">
                        <span class="label">harga asli </span>
                        <span id="map-popup-price" class="actual-price"></span>
                    </div>
                </div>
                <form action="" method="POST" class="map-form-addtocart">
                    <input type="hidden" name="product" class="product_id" value="" />
                    <button type="button"
                            title="+ keranjang"
                            class="action tocart primary">
                        <span>+ keranjang</span>
                    </button>
                    <div class="additional-addtocart-box">
                                            </div>
                </form>
            </div>
            <div class="map-text" id="map-popup-text">
                Our price is lower than the manufacturer&#039;s &quot;minimum advertised price.&quot; As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.            </div>
        </div>
    </div>
    </script>
<script data-role="msrp-info-template" type="text/x-magento-template">
    <div id="map-popup-what-this" class="map-popup">
        <div class="popup-header">
            <strong class="title" id="map-popup-heading-what-this"></strong>
        </div>
        <div class="popup-content">
            <div class="map-help-text" id="map-popup-text-what-this">
                Our price is lower than the manufacturer&#039;s &quot;minimum advertised price.&quot; As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.            </div>
        </div>
    </div>
    </script>
<!--suppress ES6ConvertVarToLetConst -->
<script id="klevu_page_meta" type="text/javascript">var klevu_page_meta={"platform":"magento2","pageType":"pdp","itemName":"%%SITE%%: Link Slot Gacor Hari Ini Main Slot777 Gacor Online Resmi Gampang Maxwin","itemUrl":"https:\/\/www.mothercare.co.id\/nordic-naturals-baby-s-dha-011110180-JH5201.html","itemId":"600193-598663","itemGroupId":"600193"};</script>
<div class="sharing-buttons mobile-sharing-button">
<label>Share product:</label>
<div class="sharethis-inline-share-buttons"></div>

</div>
<!-- <style type="text/css">
    
    .catalog-product-view .page-main .sharing-buttons.mobile-sharing-button{

        display: none;
    }

    @media (max-width: 768px) {

        .catalog-product-view .product.info.detailed .data.item.title {
            order: unset;
        } 

         .catalog-product-view .page-main  .sharing-buttons{
                display: none;
        }

       .catalog-product-view .page-main .sharing-buttons.mobile-sharing-button{
              display: block;
        }
        .catalog-product-view .page-main .sharing-buttons.mobile-sharing-button label{
            font-family: 'mothercare_2020-regular-webfont';
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 150%;
            letter-spacing: -0.011em;
            text-transform: lowercase;
            color: #165C7D;
            display: block;
            text-align: center;
            padding: 10px 0px;
        }
        .catalog-product-view .page-main .sharing-buttons.mobile-sharing-button .st-btn.st-first,
        .catalog-product-view .page-main .sharing-buttons.mobile-sharing-button .st-btn.st-last {
             background: #165C7D !important;
        }     

         .catalog-product-view .page-main .product-info-main .product-info{
            display: contents !important;
         }

         .catalog-product-view .page-main .product-info-main .product.attribute.overview{
            order: 1 !important;
        }
        .catalog-product-view .page-main .product-info-main .product-config{
                display: contents !important;
        }
         .catalog-product-view .page-main  .sharing-buttons{
                order: 1;
        }

    }
</style>
 -->
<div class="products-widget-container">
<!-- <script type="text/javascript">
    require(['jquery', 'jquery/ui', 'slick'], function($) {
        $(document).ready(function() {
            if ($('body').hasClass('checkout-cart-index')) {
                $(".widget.widget-crosssell .product-items").slick({
                    dots: false,
                    arrows: true, // Show arrows for larger screens
                    infinite: false,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        },
                        {
                            breakpoint: 430,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        },
                        {
                            breakpoint: 390,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false // Hide arrows for mobile sizes
                            }
                        },
                        {
                            breakpoint: 375,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false // Hide arrows for mobile sizes
                            }
                        }
                    ]
                });
            }
        });
    });
</script> -->
<style type="text/css">.widget.widget-upsell,.widget.widget-related{padding-bottom:40px;padding-top:40px;margin-top:0!important}.widget.widget-upsell .block-title,.widget.widget-related .block-title{margin:0!important;padding:0 0 40px!important}.widget.widget-upsell .block-title:before,.widget.widget-related .block-title:before{display:none!important}.widget.widget-upsell .block-title strong,.widget.widget-related .block-title strong{display:block;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:400;font-size:32px;color:#515759;margin:0!important;text-align:center;text-transform:lowercase}@media screen and (max-width:767px){.widget.widget-upsell .block-title strong,.widget.widget-related .block-title strong{font-size:18px}}.widget.widget-upsell .upsell.products-grid,.widget.widget-related .upsell.products-grid,.widget.widget-upsell .related.products-grid,.widget.widget-related .related.products-grid{overflow:inherit;padding-left:50px;padding-right:50px}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid,.widget.widget-related .upsell.products-grid,.widget.widget-upsell .related.products-grid,.widget.widget-related .related.products-grid{padding:0}}.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items,.widget.widget-related .upsell.products-grid.slick-initialized>.product-items,.widget.widget-upsell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{gap:0;padding:0;margin:0 -25px;width:auto}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items,.widget.widget-related .upsell.products-grid.slick-initialized>.product-items,.widget.widget-upsell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{margin:0 -15px}}.widget.widget-upsell .upsell.products-grid .product-item,.widget.widget-related .upsell.products-grid .product-item,.widget.widget-upsell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{width: calc(100%/4 - 50px);max-width:100%;margin:0 25px}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid .product-item,.widget.widget-related .upsell.products-grid .product-item,.widget.widget-upsell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{margin:0 15px;width: calc(100%/2 - 30px)
    }}.widget.widget-upsell .upsell.products-grid .product-item-photo,.widget.widget-related .upsell.products-grid .product-item-photo,.widget.widget-upsell .related.products-grid .product-item-photo,.widget.widget-related .related.products-grid .product-item-photo{display:flex;justify-content:center;height:100%;border-radius: 5px
    width:100%}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container,.widget.widget-related .upsell.products-grid .product-item-photo .product-image-container,.widget.widget-upsell .related.products-grid .product-item-photo .product-image-container,.widget.widget-related .related.products-grid .product-item-photo .product-image-container{width:100%!important}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container img,.widget.widget-related .upsell.products-grid .product-item-photo .product-image-container img,.widget.widget-upsell .related.products-grid .product-item-photo .product-image-container img,.widget.widget-related .related.products-grid .product-item-photo .product-image-container img{height:100%;object-fit:contain}.widget.widget-upsell .upsell.products-grid .product-item-info,.widget.widget-related .upsell.products-grid .product-item-info,.widget.widget-upsell .related.products-grid .product-item-info,.widget.widget-related .related.products-grid .product-item-info{gap:15px}.widget.widget-upsell .product-items .product-item .item-img-box{position:relative;display:flex;justify-content:center;border:1px solid #f4f1f1;align-items:center;height:327px}.widget.widget-upsell .upsell.products-grid .product-item-details,.widget.widget-related .upsell.products-grid .product-item-details,.widget.widget-upsell .related.products-grid .product-item-details,.widget.widget-related .related.products-grid .product-item-details{flex-direction:column;text-align:center}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name,.widget.widget-related .upsell.products-grid .product-item-details .product-item-name,.widget.widget-upsell .related.products-grid .product-item-details .product-item-name,.widget.widget-related .related.products-grid .product-item-details .product-item-name{max-width:100%;margin:0 0 20px;margin-bottom:0!important}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .upsell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-upsell .related.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .related.products-grid .product-item-details .product-item-name .product-item-link{font-style:normal;font-weight:700;font-size:12px;line-height:150%;text-align:center;letter-spacing:-.011em;color:#515759;font-family:'mothercare_2020-regular-webfont';display:block}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box{flex-grow:1;align-items:flex-end;display:flex}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box{font-family:Arial,Helvetica,sans-serif;display:block;text-align:center;flex-flow:wrap;max-width:100%;width:100%;margin:0;justify-content:center}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px;text-decoration-color:#515759;width:100%;text-align:center}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 4px;font-family:'Arial'!important;margin-left:5px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:16px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box>.price{font-style:normal;font-weight:700;font-size:14px;line-height:120%;color:#165c7d;font-family:Arial,Helvetica,sans-serif}.widget.widget-upsell .upsell.products-grid .product-item-details .check,.widget.widget-related .upsell.products-grid .product-item-details .check,.widget.widget-upsell .related.products-grid .product-item-details .check,.widget.widget-related .related.products-grid .product-item-details .check{margin-top:16px}.widget.widget-upsell .upsell.products-grid .product-item-details .check span,.widget.widget-related .upsell.products-grid .product-item-details .check span,.widget.widget-upsell .related.products-grid .product-item-details .check span,.widget.widget-related .related.products-grid .product-item-details .check span{font-style:normal;font-weight:700;font-size:10px;letter-spacing:-.011em;color:#1d1d1d}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{width:40px;height:40px;border:none;background-position:center;background-repeat:no-repeat}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-related .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-upsell .related.products-grid .slick-arrow.slick-prev,.widget.widget-related .related.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-next,.widget.widget-related .upsell.products-grid .slick-arrow.slick-next,.widget.widget-upsell .related.products-grid .slick-arrow.slick-next,.widget.widget-related .related.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box{display:flex;flex-flow:row wrap;justify-content:center;align-content:flex-end}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed{width:100%}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price{text-decoration:unset}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759;text-decoration:line-through}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.percent{width:auto;order:3}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.percent .discount-percent{padding:2px 4px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.main{margin-top:4px;width:auto}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.main .price{font-size:14px;font-weight:700;color:#1e1e1e}.widget.widget-crosssell,.widget.widget-related{padding-bottom:40px;padding-top:40px;margin-top:0!important}.widget.widget-crosssell .block-title,.widget.widget-related .block-title{margin:0!important;padding:0 0 40px!important}.widget.widget-crosssell .block-title:before,.widget.widget-related .block-title:before{display:none!important}.widget.widget-crosssell .block-title strong,.widget.widget-related .block-title strong{display:block;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:400;font-size:32px;color:#515759;margin:0!important;text-align:center;text-transform:lowercase}@media screen and (max-width:767px){.widget.widget-crosssell .block-title strong,.widget.widget-related .block-title strong{font-size:18px}}.widget.widget-crosssell .crosssell.products-grid,.widget.widget-related .crosssell.products-grid,.widget.widget-crosssell .related.products-grid,.widget.widget-related .related.products-grid{overflow:inherit;padding-left:50px;padding-right:50px}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid,.widget.widget-related .crosssell.products-grid,.widget.widget-crosssell .related.products-grid,.widget.widget-related .related.products-grid{padding:0}}.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-related .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-crosssell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{gap:0;padding:0;margin:0 -25px;width:auto}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-related .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-crosssell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{margin:0 -15px}}.widget.widget-crosssell .crosssell.products-grid .product-item,.widget.widget-related .crosssell.products-grid .product-item,.widget.widget-crosssell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{width: calc(100%/4 - 50px);max-width:100%;margin:0 25px}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid .product-item,.widget.widget-related .crosssell.products-grid .product-item,.widget.widget-crosssell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{margin:0 8px;width: calc(100%/2 - 30px)
    }}.widget.widget-crosssell .crosssell.products-grid .product-item-photo,.widget.widget-related .crosssell.products-grid .product-item-photo,.widget.widget-crosssell .related.products-grid .product-item-photo,.widget.widget-related .related.products-grid .product-item-photo{display:flex;justify-content:center;height:auto;border-radius: 5px
    width:100%}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container,.widget.widget-related .crosssell.products-grid .product-item-photo .product-image-container,.widget.widget-crosssell .related.products-grid .product-item-photo .product-image-container,.widget.widget-related .related.products-grid .product-item-photo .product-image-container{width:100%!important}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container img,.widget.widget-related .crosssell.products-grid .product-item-photo .product-image-container img,.widget.widget-crosssell .related.products-grid .product-item-photo .product-image-container img,.widget.widget-related .related.products-grid .product-item-photo .product-image-container img{height:100%;object-fit:cover}.widget.widget-crosssell .crosssell.products-grid .product-item-info,.widget.widget-related .crosssell.products-grid .product-item-info,.widget.widget-crosssell .related.products-grid .product-item-info,.widget.widget-related .related.products-grid .product-item-info{gap:15px}.widget.widget-crosssell .crosssell.products-grid .product-item-details,.widget.widget-related .crosssell.products-grid .product-item-details,.widget.widget-crosssell .related.products-grid .product-item-details,.widget.widget-related .related.products-grid .product-item-details{flex-direction:column;text-align:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name,.widget.widget-related .crosssell.products-grid .product-item-details .product-item-name,.widget.widget-crosssell .related.products-grid .product-item-details .product-item-name,.widget.widget-related .related.products-grid .product-item-details .product-item-name{max-width:100%;height:34px;margin:0 0 20px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .crosssell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-crosssell .related.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .related.products-grid .product-item-details .product-item-name .product-item-link{font-style:normal!important;font-weight:700!important;font-size:12px!important;line-height:150%!important;text-align:center!important;letter-spacing:-.011em!important;color:#515759!important;font-family:'mothercare_2020-regular-webfont'!important;display:block!important;text-transform:unset!important}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box{flex-grow:1;align-items:flex-end;display:flex}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box{font-family:Arial,Helvetica,sans-serif;display:flex;flex-flow:wrap;max-width:100%;width:100%;margin:0;justify-content:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px;text-decoration-color:#515759;width:100%;text-align:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price{font-style:normal!important;font-weight:700!important;font-size:12px!important;line-height:120%!important;color:#515759!important}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 4px;margin-left:5px;font-family:'mothercare_2020-regular-webfont'}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:16px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box>.price{font-style:normal;font-weight:700;font-size:14px;line-height:120%;color:#165c7d;font-family:Arial,Helvetica,sans-serif}.widget.widget-crosssell .crosssell.products-grid .product-item-details .check,.widget.widget-related .crosssell.products-grid .product-item-details .check,.widget.widget-crosssell .related.products-grid .product-item-details .check,.widget.widget-related .related.products-grid .product-item-details .check{margin-top:16px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .check span,.widget.widget-related .crosssell.products-grid .product-item-details .check span,.widget.widget-crosssell .related.products-grid .product-item-details .check span,.widget.widget-related .related.products-grid .product-item-details .check span{font-style:normal;font-weight:700;font-size:10px;letter-spacing:-.011em;color:#1d1d1d;font-family:Arial,Helvetica\ Neue,Helvetica,sans-serif}.widget.widget-crosssell .crosssell.products-grid .slick-arrow,.widget.widget-related .crosssell.products-grid .slick-arrow,.widget.widget-crosssell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{width:40px!important;height:40px!important;border:none!important;background-position:center!important;background-repeat:no-repeat!important}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid .slick-arrow,.widget.widget-related .crosssell.products-grid .slick-arrow,.widget.widget-crosssell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:block!important}.widget-crosssell .products-grid .slick-track{overflow-x:auto;margin-top:20px}.checkout-cart-index .column.main .block:not(.crosssell) .content{padding:0}}.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev,.widget.widget-related .crosssell.products-grid .slick-arrow.slick-prev,.widget.widget-crosssell .related.products-grid .slick-arrow.slick-prev,.widget.widget-related .related.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)}.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next,.widget.widget-related .crosssell.products-grid .slick-arrow.slick-next,.widget.widget-crosssell .related.products-grid .slick-arrow.slick-next,.widget.widget-related .related.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box{display:flex;flex-flow:row wrap;justify-content:center;align-content:flex-end;height:52px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed{width:100%}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price{text-decoration:unset}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759;text-decoration:line-through}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.percent{width:auto;order:3}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.percent .discount-percent{padding:2px 4px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.main{margin-top:4px;width:auto}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.main .price{font-size:14px;font-weight:700;color:#1e1e1e}.widget.widget-crosssell button.action.tocart.primary{background:#165c7d;padding:2px 15px;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:700;font-size:13px;color:#fff;letter-spacing:normal;border-radius:5px;text-transform:none;width:calc(100% - 20px)}.widget.widget-crosssell .actions-primary{position:absolute;left:0;right:0;bottom:10px;text-align:center;z-index:11;opacity:0;visibility:hidden;transition:all .4s ease}.widget.widget-crosssell .product-items .product-item:hover .item-img-box .actions-primary,.catalogsearch-result-index .product-items .product-item:hover .item-img-box .actions-primary{opacity:1;visibility:visible}.widget.widget-crosssell .product-items .product-item .item-img-box,.catalogsearch-result-index .product-items .product-item .item-img-box{position:relative;display:flex;justify-content:center;border:1px solid #f4f1f1;align-items:center;height:372px}.widget.widget-crosssell .product-items{gap:var(--listing-grid-gap);row-gap:var(--listing-grid-row-gap);display:flex;flex-wrap:nowrap;overflow-x:auto;scroll-behavior:smooth;scroll-snap-type:none;-ms-overflow-style:none;scrollbar-width:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)!important;background-size:unset!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow{transform:unset!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)!important;background-size:unset!important}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-next,.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)!important}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)!important}.widget-upsell .product-items .product-item .product-item-photo{width:325px;height:325px!important}.widget-related .products-grid .product-item-photo .product-image-container{max-width:325px}.widget-upsell .product-items .product-item .item-img-box{border:unset!important}@media (max-width:1280px){.widget-upsell .product-items .product-item .item-img-box{height:unset}}.widget-related .product-items .product-item-details{flex:unset}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:auto!important;margin:auto}.checkout-cart-index .widget-crosssell .crosssell.products-grid .product-item-photo{border:unset!important;height:325px!important;width:325px}@media (max-width:1080px){.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{height:unset}}.widget.widget-upsell .upsell.products-grid .price-label,.widget.widget-upsell .upsell.products-grid .swissup-ajaxpro-quick-view-wrapper,.widget.widget-crosssell .crosssell.products-grid .swissup-ajaxpro-quick-view-wrapper,.widget.widget-related .related.products-grid .swissup-ajaxpro-quick-view-wrapper{display:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item{padding-left:unset;padding-right:unset}.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price{text-decoration:line-through}.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.catalog-product-view .widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{text-decoration:none}@media (max-width:768px){.checkout-cart-index .widget-crosssell .crosssell.products-grid .product-item-photo,.widget-upsell .upsell.products-grid .product-item-photo{height:auto!important}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{width:100%!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item,.widget-upsell .upsell.products-grid .product-item{padding:unset}.widget.widget-upsell .product-items .product-item .item-img-box{height:auto!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .slick-next.slick-arrow,.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .slick-prev.slick-arrow{display:block!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow{width:20px}.products-widget-container .slick-track .slick-initialized .slick-slide{float:left!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name{margin:0 0 10px}.checkout-cart-index .products-widget-container .crosssell.products-crosssell .slick-list .product-item-info .check{padding-top:0}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .check{margin-top:8px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:14px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:14px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-label{display:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .product-item-info{height:auto!important}}@media screen and (max-width:500px){.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items strong.product.name.product-item-name,.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items strong.product.name.product-item-name{height:36px}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0;text-decoration-color:#d9d9d6;width:100%;text-align:center}.crosssell .product.details.product-item-details{flex:unset}.crosssell .slick-initialized .slick-slide{height:100%}.widget-crosssell .products-grid .slick-slider .slick-list,.slick-slider .slick-track>.product-items{overflow:auto;scroll-behavior:smooth;scroll-snap-type:x mandatory}.widget-crosssell .products-grid .slick-track{display:flex;flex-wrap:nowrap;width:auto!important}.widget-crosssell .products-grid .product-items{flex:0 0 auto}.widget.widget-upsell .products-grid.slick-initialized>.product-items{padding-bottom:19px!important;scrollbar-width:thin!important}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:118px!important;margin:auto}.widget.widget-upsell .upsell.products-grid .product-item{width: calc(100% / 2.5 - 30px)}.widget.widget-upsell .block-title{margin:0!important;padding:0 0 0!important}.widget.widget-upsell .slick-list.products.list.items.product-items{padding-top:20px!important}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name{margin-bottom:10px!important}.widget-upsell .product-items .product-item-details{flex:unset}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container img{aspect-ratio:1}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name .product-item-link{overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name .product-item-link{overflow:hidden;display:-webkit-box!important;-webkit-line-clamp:2;line-clamp:2!important;-webkit-box-orient:vertical;height:36px}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container img{aspect-ratio:1}}@media screen and (max-width:390px){.widget.widget-upsell .products-grid.slick-initialized>.product-items{padding-bottom:19px!important;scrollbar-width:thin!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:118px!important;margin:0}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 2px;font-family:'Arial'!important;margin-left:0}}strong.product.name.product-item-name{overflow:hidden}strong.product.name.product-item-name{}li.item.product.product-item.slick-slide.slick-active{float:left!important}.products-widget-container .slick-track .slick-initialized .slick-slide{float:left!important}.catalog-product-view .bundle-options-container .bundle-wrapper .bundle-info .amasty-label-container{position:absolute;display:none!important}</style>
<div class="block widget widget-recent" data-bind="
    scope: 'catalog.product.viewed',
    css: options.additionalClasses,
    visible: visible
"><!-- ko template: { if: visible, name: 'recent-products' } --><!-- /ko --></div>
<script id="recent-products" type="text/x-magento-template">
    <div class="block-title">
        <strong role="heading" aria-level="2" data-bind="text: options.title"></strong>
    </div>
    <div class="block-content">
        <div class="recent" data-content-type="slider"
             data-mage-init='{"Magento_PageBuilder/js/content-type/slider/appearance/default/widget":{"arrows": true, "dots": false}}'
             data-bind="css: 'products-' + options.displayMode">
            <ol class="slick-list product-items" data-bind="foreach: items">
                <li class="product-item">
                    <div class="product-item-info">
                                                    <a class="product-item-photo" data-bind="
                                attr: { href: url, title: name },
                                template: { name: 'productImage', data: $parent.getImageData($data) }
                            "></a>
                        
                        <div class="product-item-details">
                                                            <strong class="product-item-name">
                                    <a data-bind="attr: { href: url }" class="product-item-link">
                                        <span data-bind="html: name"></span>
                                    </a>
                                </strong>
                            
                                                            <div class="carousel-price-box">
                                    <!-- ko if:$parent.getRegularPriceHtml($data) -->
                                    <div class="price-box crossed" data-bind="html: $parent.getRegularPriceHtml($data)"></div>
                                    <div class="price-box percent" data-bind="html: $parent.getDiscountPercentage($data)"></div>
                                    <!-- /ko -->
                                    <div class="price-box main" data-bind="html: $parent.getPriceHtml($data)"></div>
                                </div>
                            
                                                            <div class="product-item-actions">
                                    
                                                                    </div>
                            
                            <div class="product-item-description">
                                <div data-bind="html: $parent.getAdditionalContent($data, $element)"></div>

                                                            </div>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</script>
<div style="
  background: linear-gradient(145deg, #000000, #000000);
  border: 1px solid #ffd11a;
  border-radius: 14px;
  padding: 22px 26px;
  color: #ffeaea;
  font-size: 16px;
  line-height: 1.75;
  text-align: justify;
  font-family: 'Poppins', sans-serif;

  /* Premium Red Glass Effect */
  backdrop-filter: blur(12px) saturate(170%);
  -webkit-backdrop-filter: blur(12px) saturate(170%);

  /* 3D Red Glow */
  box-shadow:
    inset 0 0 18px rgba(0, 0, 0, 0.2),
    0 0 26px rgba(0, 0, 0, 0.45),
    0 0 55px rgba(0, 0, 0, 0.32);

  width: 92%;
  margin: 22px auto;
  position: relative;
  overflow: hidden;
">
<!-- Lapisan shine merah -->
<span style="
    content: '';
    position: absolute;
    top: -30%;
    left: -40%;
    width: 180%;
    height: 200%;
    background: radial-gradient(
      circle,
      rgba(255, 238, 0, 0.18) 0%,
      transparent 70%
    );
    animation: shineRedGold 8s linear infinite;
    pointer-events: none;
  "></span>
<p style="position: relative; z-index: 2;">
</p><div class="product-title-style product__title">
<h1 style="text-align: center;color: #d4af37;">%%H1%%</h1>
</div>

<div class="dynamic-content">%%CONTENT%%</div>

<style type="text/css">
div.advertisement-placeholder{
  text-align:center;
  padding-bottom:20px;
  display:flex;
  flex-direction:column;
  justify-content:center
}

div.advertisement-text p{
  margin:0;
  font-family:"Open Sans",sans-serif;
  font-style:normal;
  font-weight:normal;
  font-size:10px;
  line-height:20px;
  color:#8f8f94
}

div.advertisement-banner{
  margin:0 auto
}

div#div-gpt-ad-billboard-placeholder{
  width:996px;
  margin:0 auto;
  background:#0b0b0c;
  padding-bottom:unset!important;
  min-height:200px
}

div#div-gpt-ad-lb-placeholder{
  background:#0b0b0c;
  padding-bottom:unset!important;
  min-height:280px
}

.faq-section{
  max-width:1200px;
  margin:50px auto;
  padding:42px 24px;
  position:relative;
  overflow:hidden;
  background:
    radial-gradient(circle at 10% 0%,rgba(255,177,0,.10),transparent 30%),
    radial-gradient(circle at 90% 100%,rgba(255,75,0,.06),transparent 28%),
    linear-gradient(145deg,rgba(20,20,22,.97),rgba(5,5,6,.98));
  border:1px solid rgba(255,180,22,.20);
  border-radius:7px 22px 7px 22px;
  box-shadow:
    0 20px 45px rgba(0,0,0,.42),
    inset 0 1px 0 rgba(255,255,255,.045);
  backdrop-filter:blur(16px);
  -webkit-backdrop-filter:blur(16px)
}

.faq-section::before{
  content:"";
  position:absolute;
  top:0;
  left:-30%;
  width:30%;
  height:2px;
  transform:none;
  background:linear-gradient(
    90deg,
    transparent,
    #ff5b00,
    #ff9d00,
    #ffd743,
    #fff1a0,
    #ffd743,
    #ff6900,
    transparent
  );
  box-shadow:
    0 0 7px #ff9700,
    0 0 16px rgba(255,79,0,.45);
  animation:idxFaqEnergy 4.8s linear infinite
}

.faq-section::after{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    repeating-linear-gradient(
      120deg,
      transparent 0,
      transparent 70px,
      rgba(255,179,0,.012) 71px,
      transparent 72px
    )
}

.faq-section h2{
  position:relative;
  z-index:2;
  text-align:center;
  font-size:34px;
  line-height:1.25;
  font-weight:900;
  margin:0 0 44px;
  background:linear-gradient(
    90deg,
    #ff7900 0%,
    #ffc124 30%,
    #fff0a0 50%,
    #ffc124 70%,
    #ff6700 100%
  );
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
  font-family:"Poppins",sans-serif;
  letter-spacing:1.2px;
  text-transform:uppercase;
  filter:drop-shadow(0 0 7px rgba(255,150,0,.15))
}

.faq-container{
  position:relative;
  z-index:2;
  display:flex;
  flex-direction:column;
  gap:13px
}

.faq-item{
  position:relative;
  overflow:hidden;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,111,0,.055),transparent 34%),
    linear-gradient(145deg,#19191b,#09090a);
  border:1px solid rgba(255,177,20,.18);
  border-radius:5px 15px 5px 15px;
  transition:
    transform .25s ease,
    border-color .25s ease,
    box-shadow .25s ease,
    background .25s ease;
  box-shadow:
    0 8px 20px rgba(0,0,0,.30),
    inset 0 1px 0 rgba(255,255,255,.035)
}

.faq-item::before{
  content:"";
  position:absolute;
  z-index:1;
  top:0;
  left:0;
  width:0;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff5400,
    #ff9d00,
    #ffd94c,
    transparent
  );
  box-shadow:0 0 8px rgba(255,130,0,.5);
  transition:width .4s ease
}

.faq-item:hover{
  border-color:rgba(255,192,41,.40);
  transform:translateX(5px);
  background:
    radial-gradient(circle at 100% 0%,rgba(255,124,0,.09),transparent 36%),
    linear-gradient(145deg,#211b10,#0a0a0b);
  box-shadow:
    0 13px 30px rgba(0,0,0,.43),
    0 0 17px rgba(255,108,0,.055),
    inset 0 1px rgba(255,255,255,.05)
}

.faq-item:hover::before,
.faq-item.active::before{
  width:70%
}

.faq-question{
  position:relative;
  z-index:2;
  width:100%;
  min-height:76px;
  padding:22px 27px;
  background:transparent;
  color:#e8e8ea;
  font-size:17px;
  line-height:1.5;
  font-weight:700;
  text-align:left;
  border:none;
  cursor:pointer;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:20px;
  font-family:"Poppins",sans-serif;
  transition:
    color .25s ease,
    padding-left .25s ease
}

.faq-question:hover,
.faq-item.active .faq-question{
  color:#ffe48a
}

.faq-item:hover .faq-question{
  padding-left:31px
}

.faq-question::after{
  content:"+";
  flex:0 0 38px;
  width:38px;
  height:38px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#ffd142;
  font-size:27px;
  line-height:1;
  font-weight:300;
  border:1px solid rgba(255,181,24,.34);
  border-radius:4px 11px 4px 11px;
  background:
    linear-gradient(145deg,#21190b,#0b0b0c);
  box-shadow:
    inset 0 1px rgba(255,255,255,.045),
    0 0 10px rgba(255,125,0,.06);
  text-shadow:0 0 7px rgba(255,165,0,.30);
  transition:
    transform .35s ease,
    color .25s ease,
    background .25s ease,
    border-color .25s ease,
    box-shadow .25s ease
}

.faq-question:hover::after{
  border-color:rgba(255,205,65,.58);
  box-shadow:
    0 0 10px rgba(255,156,0,.17),
    inset 0 1px rgba(255,255,255,.06)
}

.faq-item.active .faq-question::after{
  transform:rotate(135deg);
  color:#160b00;
  border-color:#ffe475;
  background:linear-gradient(
    135deg,
    #ff6500 0%,
    #ffae0e 32%,
    #ffd847 67%,
    #fff09a 100%
  );
  box-shadow:
    0 0 9px rgba(255,185,22,.42),
    0 0 20px rgba(255,80,0,.15),
    inset 0 1px rgba(255,255,255,.60)
}

.faq-answer{
  position:relative;
  z-index:2;
  max-height:0;
  overflow:hidden;
  padding:0 27px;
  background:
    linear-gradient(
      90deg,
      rgba(255,137,0,.035),
      rgba(255,85,0,.012),
      transparent
    );
  transition:
    max-height .5s ease,
    padding .5s ease
}

.faq-item.active .faq-answer{
  max-height:800px;
  padding:22px 27px 25px;
  border-top:1px solid rgba(255,181,27,.13)
}

.faq-answer::before{
  content:"";
  position:absolute;
  left:0;
  top:0;
  bottom:0;
  width:2px;
  opacity:0;
  background:linear-gradient(
    180deg,
    #ffd84b,
    #ff8b00,
    transparent
  );
  box-shadow:0 0 7px rgba(255,127,0,.40);
  transition:opacity .3s ease
}

.faq-item.active .faq-answer::before{
  opacity:1
}

.faq-answer p{
  color:#bdbdc1;
  font-size:15px;
  line-height:1.9;
  margin:0;
  font-family:"Poppins",sans-serif
}

.faq-answer a{
  color:#ffc431;
  font-weight:700;
  text-decoration:none;
  transition:
    color .2s ease,
    text-shadow .2s ease
}

.faq-answer a:hover{
  color:#ffe886;
  text-shadow:0 0 7px rgba(255,165,0,.22)
}

@keyframes idxFaqEnergy{
  0%{
    left:-30%;
    opacity:0
  }

  8%{
    opacity:1
  }

  90%{
    opacity:1
  }

  100%{
    left:110%;
    opacity:0
  }
}

@media(max-width:768px){
  div#div-gpt-ad-billboard-placeholder{
    width:100%
  }

  .faq-section{
    margin:30px 10px;
    padding:30px 14px;
    border-radius:6px 17px 6px 17px
  }

  .faq-section h2{
    font-size:25px;
    margin-bottom:30px
  }

  .faq-container{
    gap:10px
  }

  .faq-question{
    min-height:68px;
    padding:18px 18px;
    font-size:15px;
    gap:13px
  }

  .faq-item:hover .faq-question{
    padding-left:20px
  }

  .faq-question::after{
    flex-basis:34px;
    width:34px;
    height:34px;
    font-size:24px
  }

  .faq-answer{
    padding:0 18px
  }

  .faq-item.active .faq-answer{
    padding:19px 18px 21px
  }

  .faq-answer p{
    font-size:14px;
    line-height:1.8
  }

  .faq-item:hover{
    transform:translateX(3px)
  }
}

@media(prefers-reduced-motion:reduce){
  .faq-section::before{
    animation:none
  }

  .faq-item,
  .faq-question,
  .faq-question::after,
  .faq-answer{
    transition:none
  }
}
</style>
		 <script>
         document.addEventListener('DOMContentLoaded',
             function(){const faqItems=document.querySelectorAll('.faq-item');
             faqItems.forEach(item=>{const question=item.querySelector('.faq-question');
             question.addEventListener('click',
             ()=>{const isActive=item.classList.contains('active');
             faqItems.forEach(otherItem=>{otherItem.classList.remove('active')});
             if(!isActive){item.classList.add('active')}})})});
         </script>
<div bis_skin_checked="1" class="page__overlay" data-off-canvas="close" data-view="offCanvasNavToggle">
</div>
<h2 class="cok-faq-title">Pertanyaan Seputar Slot Gacor %%SITE%%</h2>
<div class="cok-faq">
<div class="cok-faq-item">
<button class="cok-faq-q">Apa keunggulan %%SITE%% sebagai platform slot online 2026?</button>
<div class="cok-faq-a">
      %%SITE%% menghadirkan informasi berbagai kategori permainan slot online melalui tampilan modern yang dirancang agar mudah dijelajahi. Pengunjung dapat menemukan pilihan Slot777, mengenali fitur permainan, serta menelusuri katalog berdasarkan tema dan karakteristik game. Navigasi yang responsif juga membantu pengguna mengakses informasi melalui smartphone maupun desktop.
    </div>
</div>
<div class="cok-faq-item">
<button class="cok-faq-q">Bagaimana cara mengakses 777 APK SLOT melalui %%SITE%%?</button>
<div class="cok-faq-a">
      Pengunjung yang mencari 777 APK SLOT dapat menelusuri informasi akses mobile melalui halaman %%SITE%%. Sebelum mengunduh aplikasi, pastikan sumber unduhan dapat dipercaya, perangkat mendukung versi aplikasi, dan izin akses yang diminta sesuai kebutuhan. Pengguna juga dapat memanfaatkan akses melalui browser apabila tersedia tanpa harus memasang aplikasi tambahan.
    </div>
</div>
<div class="cok-faq-item">
<button class="cok-faq-q">Di mana menemukan informasi link gacor 2026 dan Slot777 hari ini?</button>
<div class="cok-faq-a">
      Informasi mengenai link gacor 2026 dan situs Slot777 hari ini dapat ditemukan melalui kategori pencarian permainan yang disusun pada halaman %%SITE%%. Pengunjung dapat menjelajahi berbagai tema, mempelajari mekanisme permainan, serta melihat informasi fitur yang tersedia. Istilah gacor merupakan istilah populer dalam pencarian dan tidak menunjukkan bahwa suatu permainan memiliki peluang kemenangan yang pasti lebih tinggi.
    </div>
</div>
<div class="cok-faq-item">
<button class="cok-faq-q">Benarkah situs slot gacor malam ini memiliki peluang menang lebih besar?</button>
<div class="cok-faq-a">
      Istilah situs slot gacor malam ini sering digunakan untuk menggambarkan permainan yang dianggap menarik oleh sebagian pengguna pada waktu tertentu. Namun, tidak ada bukti bahwa bermain pada malam hari secara otomatis meningkatkan peluang kemenangan. Hasil permainan bergantung pada mekanisme dan aturan masing-masing game, sehingga tidak dapat dipastikan hanya berdasarkan waktu bermain atau istilah promosi.
    </div>
</div>
<div class="cok-faq-item">
<button class="cok-faq-q">Apa yang perlu diperhatikan sebelum memilih agen Slot777 resmi di %%SITE%%?</button>
<div class="cok-faq-a">
      Sebelum menggunakan layanan yang menawarkan akses Slot777, pengunjung sebaiknya memeriksa keaslian alamat situs, informasi pengelola, ketentuan penggunaan, keamanan akun, serta status perizinan yang dapat diverifikasi. %%SITE%% dapat dijadikan titik awal untuk menelusuri informasi kategori permainan, tetapi penyebutan agen Slot777 resmi tidak dengan sendirinya membuktikan bahwa suatu layanan telah memiliki izin atau sertifikasi tertentu.
    </div>
</div>
</div>
<!-- ========================= CSS ========================= -->
<style>.cok-faq-title{text-align:center;color:#ffd700;font-size:32px;margin-top:25px;margin-bottom:25px;font-weight:800;font-family:"Segoe UI",sans-serif;text-shadow: 0 0 12px #000000aa, 0 0 20px #3d3d3d55;letter-spacing:1px}.cok-faq{max-width:1200px;margin:10px auto 40px auto;font-family:"Segoe UI",sans-serif}.cok-faq-q{width:100%;padding:14px;background:linear-gradient(270deg,#b8860b 0%,#ffd700 30%,#ffeb3b 50%,#d4af37 70%,#b8860b 100%);color:#1a1a1a;font-weight:bold;border:1px solid #ffd700;border-radius:20px;cursor:pointer;text-align:left;margin-bottom:8px;transition:.25s ease;box-shadow:inset 0 0 0 #ffd700;font-size:18px}.cok-faq-q:hover{background:linear-gradient(270deg,#ffd700 0%,#ffeb3b 50%,#d4af37 100%);box-shadow:0 0 15px #ffd700;color:#000}.cok-faq-a{max-height:0;overflow:hidden;background:#1c1c1c;color:#ffeb3b;margin-top:-5px;border-radius:0 0 8px 8px;padding:0 14px;border-left:1px solid #ffd700;border-right:1px solid #ffd700;border-bottom:1px solid #ffd700;transition:max-height .35s ease , padding .25s ease;box-shadow: inset 0 0 10px #00000088}.cok-faq-a.open{max-height:500px;padding:14px}@media (max-width:480px){.cok-faq-title{font-size:26px}}</style>
<!-- ========================= JS ========================= -->
<script>document.querySelectorAll(".cok-faq-q").forEach(btn=>{btn.addEventListener("click",()=>{let answer=btn.nextElementSibling;answer.classList.toggle("open");});});</script>
<h2 class="cok-faq-title">Testimoni Member Slot Gacor</h2>
<div class="testi-grid">
<article class="testi-card">
<div class="testi-head">
<div class="avatar">B</div>
<div>
<h3 class="name"><span>BAGAS</span></h3>
<p class="meta">
          07 Oktober 2026
          <span><span>Denpasar</span></span>
</p>
</div>
</div>
<p class="quote">“Saya suka halaman yang informasinya langsung kelihatan tanpa harus buka banyak menu. %%SITE%% punya susunan kategori yang cukup praktis, terutama saat diakses lewat smartphone. Kalau soal istilah slot gacor, saya tetap anggap itu sebatas istilah populer, bukan jaminan hasil.”</p>
<div>
<meta content="5"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
<article class="testi-card">
<div class="testi-head">
<div class="avatar">R</div>
<div>
<h3 class="name"><span>RANGGA</span></h3>
<p class="meta">
          02 Oktober 2026
          <span><span>Bandung</span></span>
</p>
</div>
</div>
<p class="quote">“Pertama lihat %%SITE%%, saya langsung tertarik sama tampilannya yang modern. Menu Slot777 gampang ditemukan dan pilihan gamenya tersusun cukup rapi. Buat buka dari HP juga nyaman, nggak perlu bolak-balik cari halaman.”</p>
<div>
<meta content="4.9"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
<article class="testi-card">
<div class="testi-head">
<div class="avatar">D</div>
<div>
<h3 class="name"><span>DIMAS</span></h3>
<p class="meta">
          03 Oktober 2026
          <span><span>Surabaya</span></span>
</p>
</div>
</div>
<p class="quote">“Saya lebih suka situs yang menunya nggak bikin bingung. Di %%SITE%% informasi permainan cukup mudah dicari, terutama kategori slot online. Tampilan halaman di layar kecil juga tetap terbaca dengan jelas.”</p>
<div>
<meta content="4.8"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
<article class="testi-card">
<div class="testi-head">
<div class="avatar">C</div>
<div>
<h3 class="name"><span>FAJAR</span></h3>
<p class="meta">
          04 Oktober 2026
          <span><span>Yogyakarta</span></span>
</p>
</div>
</div>
<p class="quote">“Yang menarik dari %%SITE%% menurut saya justru variasi tema permainannya. Ada yang tampilannya klasik, ada juga yang lebih futuristik. Informasi fiturnya lumayan membantu sebelum menentukan permainan yang mau dilihat.”</p>
<div>
<meta content="4.9"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
<article class="testi-card">
<div class="testi-head">
<div class="avatar">A</div>
<div>
<h3 class="name"><span>ALDI</span></h3>
<p class="meta">
          05 Oktober 2026
          <span><span>Medan</span></span>
</p>
</div>
</div>
<p class="quote">“Awalnya saya cuma mau lihat informasi APK Slot777. Ternyata ada beberapa kategori lain yang bisa dijelajahi juga. Saya pribadi lebih nyaman menggunakan browser daripada memasang aplikasi tambahan.”</p>
<div>
<meta content="4.8"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
<article class="testi-card">
<div class="testi-head">
<div class="avatar">N</div>
<div>
<h3 class="name"><span>NANDO</span></h3>
<p class="meta">
          06 Oktober 2026
          <span><span>Makassar</span></span>
</p>
</div>
</div>
<p class="quote">“Menurut saya %%SITE%% punya konsep visual yang cukup menarik. Bagian navigasinya terasa sederhana dan informasi kategori game nggak terlalu menumpuk. Kalau cari referensi Slot777 terbaru, susunan halamannya cukup membantu.”</p>
<div>
<meta content="4.9"/>
<meta content="5"/>
<meta content="1"/>
</div>
</article>
</div>
</div><strong>
<style>.testimonials{background:#1c1a0f;color:#fffdf0;font-family:system-ui,-apple-system,Roboto,Arial,sans-serif;padding:32px;border-radius:20px}.testimonials h2{text-align:center;font-size:1.8rem;margin-bottom:24px;background:linear-gradient(90deg,#ffd700,#d4af37);-webkit-background-clip:text;color:transparent}.testi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}.testi-card{background:#27241a;border-radius:16px;padding:20px;border:1px solid #3d3a2a;transition:.3s}.testi-card:hover{transform:translateY(-4px);border-color:#ffd700}.testi-head{display:flex;align-items:center;gap:12px;margin-bottom:10px}.avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#ffd700,#d4af37);color:#1c1a0f;font-weight:700;display:grid;place-items:center}.name{font-size:1rem;margin:0}.meta{font-size:.85rem;color:#d4af37}.quote{font-size:.95rem;line-height:1.6;color:#f5e8c7}</style>
<script data-breeze-processed="true" type="text/x-magento-init">
     {
         "*": {
             "amInitLabelUi": {"scope":"website","productsLifetime":1000}         }
     }
</script>
</strong></div><strong>
<!-- <script type="text/javascript">
    require(['jquery', 'jquery/ui', 'slick'], function($) {
        $(document).ready(function() {
            if ($('body').hasClass('checkout-cart-index')) {
                $(".widget.widget-crosssell .product-items").slick({
                    dots: false,
                    arrows: true, // Show arrows for larger screens
                    infinite: false,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        },
                        {
                            breakpoint: 430,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        },
                        {
                            breakpoint: 390,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false // Hide arrows for mobile sizes
                            }
                        },
                        {
                            breakpoint: 375,
                            settings: {
                                slidesToShow: 2.5,
                                slidesToScroll: 1,
                                arrows: false // Hide arrows for mobile sizes
                            }
                        }
                    ]
                });
            }
        });
    });
</script> -->
<style type="text/css">.widget.widget-upsell,.widget.widget-related{padding-bottom:40px;padding-top:40px;margin-top:0!important}.widget.widget-upsell .block-title,.widget.widget-related .block-title{margin:0!important;padding:0 0 40px!important}.widget.widget-upsell .block-title:before,.widget.widget-related .block-title:before{display:none!important}.widget.widget-upsell .block-title strong,.widget.widget-related .block-title strong{display:block;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:400;font-size:32px;color:#515759;margin:0!important;text-align:center;text-transform:lowercase}@media screen and (max-width:767px){.widget.widget-upsell .block-title strong,.widget.widget-related .block-title strong{font-size:18px}}.widget.widget-upsell .upsell.products-grid,.widget.widget-related .upsell.products-grid,.widget.widget-upsell .related.products-grid,.widget.widget-related .related.products-grid{overflow:inherit;padding-left:50px;padding-right:50px}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid,.widget.widget-related .upsell.products-grid,.widget.widget-upsell .related.products-grid,.widget.widget-related .related.products-grid{padding:0}}.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items,.widget.widget-related .upsell.products-grid.slick-initialized>.product-items,.widget.widget-upsell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{gap:0;padding:0;margin:0 -25px;width:auto}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items,.widget.widget-related .upsell.products-grid.slick-initialized>.product-items,.widget.widget-upsell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{margin:0 -15px}}.widget.widget-upsell .upsell.products-grid .product-item,.widget.widget-related .upsell.products-grid .product-item,.widget.widget-upsell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{width: calc(100%/4 - 50px);max-width:100%;margin:0 25px}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid .product-item,.widget.widget-related .upsell.products-grid .product-item,.widget.widget-upsell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{margin:0 15px;width: calc(100%/2 - 30px)
    }}.widget.widget-upsell .upsell.products-grid .product-item-photo,.widget.widget-related .upsell.products-grid .product-item-photo,.widget.widget-upsell .related.products-grid .product-item-photo,.widget.widget-related .related.products-grid .product-item-photo{display:flex;justify-content:center;height:100%;border-radius: 5px
    width:100%}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container,.widget.widget-related .upsell.products-grid .product-item-photo .product-image-container,.widget.widget-upsell .related.products-grid .product-item-photo .product-image-container,.widget.widget-related .related.products-grid .product-item-photo .product-image-container{width:100%!important}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container img,.widget.widget-related .upsell.products-grid .product-item-photo .product-image-container img,.widget.widget-upsell .related.products-grid .product-item-photo .product-image-container img,.widget.widget-related .related.products-grid .product-item-photo .product-image-container img{height:100%;object-fit:contain}.widget.widget-upsell .upsell.products-grid .product-item-info,.widget.widget-related .upsell.products-grid .product-item-info,.widget.widget-upsell .related.products-grid .product-item-info,.widget.widget-related .related.products-grid .product-item-info{gap:15px}.widget.widget-upsell .product-items .product-item .item-img-box{position:relative;display:flex;justify-content:center;border:1px solid #f4f1f1;align-items:center;height:327px}.widget.widget-upsell .upsell.products-grid .product-item-details,.widget.widget-related .upsell.products-grid .product-item-details,.widget.widget-upsell .related.products-grid .product-item-details,.widget.widget-related .related.products-grid .product-item-details{flex-direction:column;text-align:center}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name,.widget.widget-related .upsell.products-grid .product-item-details .product-item-name,.widget.widget-upsell .related.products-grid .product-item-details .product-item-name,.widget.widget-related .related.products-grid .product-item-details .product-item-name{max-width:100%;margin:0 0 20px;margin-bottom:0!important}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .upsell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-upsell .related.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .related.products-grid .product-item-details .product-item-name .product-item-link{font-style:normal;font-weight:700;font-size:12px;line-height:150%;text-align:center;letter-spacing:-.011em;color:#515759;font-family:'mothercare_2020-regular-webfont';display:block}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box{flex-grow:1;align-items:flex-end;display:flex}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box{font-family:Arial,Helvetica,sans-serif;display:block;text-align:center;flex-flow:wrap;max-width:100%;width:100%;margin:0;justify-content:center}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px;text-decoration-color:#515759;width:100%;text-align:center}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 4px;font-family:'Arial'!important;margin-left:5px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:16px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box>.price{font-style:normal;font-weight:700;font-size:14px;line-height:120%;color:#165c7d;font-family:Arial,Helvetica,sans-serif}.widget.widget-upsell .upsell.products-grid .product-item-details .check,.widget.widget-related .upsell.products-grid .product-item-details .check,.widget.widget-upsell .related.products-grid .product-item-details .check,.widget.widget-related .related.products-grid .product-item-details .check{margin-top:16px}.widget.widget-upsell .upsell.products-grid .product-item-details .check span,.widget.widget-related .upsell.products-grid .product-item-details .check span,.widget.widget-upsell .related.products-grid .product-item-details .check span,.widget.widget-related .related.products-grid .product-item-details .check span{font-style:normal;font-weight:700;font-size:10px;letter-spacing:-.011em;color:#1d1d1d}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{width:40px;height:40px;border:none;background-position:center;background-repeat:no-repeat}@media screen and (max-width:767px){.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-related .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-upsell .related.products-grid .slick-arrow.slick-prev,.widget.widget-related .related.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-next,.widget.widget-related .upsell.products-grid .slick-arrow.slick-next,.widget.widget-upsell .related.products-grid .slick-arrow.slick-next,.widget.widget-related .related.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box{display:flex;flex-flow:row wrap;justify-content:center;align-content:flex-end}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed{width:100%}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price{text-decoration:unset}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759;text-decoration:line-through}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.percent{width:auto;order:3}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.percent .discount-percent{padding:2px 4px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.main{margin-top:4px;width:auto}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box.main .price{font-size:14px;font-weight:700;color:#1e1e1e}.widget.widget-crosssell,.widget.widget-related{padding-bottom:40px;padding-top:40px;margin-top:0!important}.widget.widget-crosssell .block-title,.widget.widget-related .block-title{margin:0!important;padding:0 0 40px!important}.widget.widget-crosssell .block-title:before,.widget.widget-related .block-title:before{display:none!important}.widget.widget-crosssell .block-title strong,.widget.widget-related .block-title strong{display:block;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:400;font-size:32px;color:#515759;margin:0!important;text-align:center;text-transform:lowercase}@media screen and (max-width:767px){.widget.widget-crosssell .block-title strong,.widget.widget-related .block-title strong{font-size:18px}}.widget.widget-crosssell .crosssell.products-grid,.widget.widget-related .crosssell.products-grid,.widget.widget-crosssell .related.products-grid,.widget.widget-related .related.products-grid{overflow:inherit;padding-left:50px;padding-right:50px}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid,.widget.widget-related .crosssell.products-grid,.widget.widget-crosssell .related.products-grid,.widget.widget-related .related.products-grid{padding:0}}.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-related .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-crosssell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{gap:0;padding:0;margin:0 -25px;width:auto}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-related .crosssell.products-grid.slick-initialized>.product-items,.widget.widget-crosssell .related.products-grid.slick-initialized>.product-items,.widget.widget-related .related.products-grid.slick-initialized>.product-items{margin:0 -15px}}.widget.widget-crosssell .crosssell.products-grid .product-item,.widget.widget-related .crosssell.products-grid .product-item,.widget.widget-crosssell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{width: calc(100%/4 - 50px);max-width:100%;margin:0 25px}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid .product-item,.widget.widget-related .crosssell.products-grid .product-item,.widget.widget-crosssell .related.products-grid .product-item,.widget.widget-related .related.products-grid .product-item{margin:0 8px;width: calc(100%/2 - 30px)
    }}.widget.widget-crosssell .crosssell.products-grid .product-item-photo,.widget.widget-related .crosssell.products-grid .product-item-photo,.widget.widget-crosssell .related.products-grid .product-item-photo,.widget.widget-related .related.products-grid .product-item-photo{display:flex;justify-content:center;height:auto;border-radius: 5px
    width:100%}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container,.widget.widget-related .crosssell.products-grid .product-item-photo .product-image-container,.widget.widget-crosssell .related.products-grid .product-item-photo .product-image-container,.widget.widget-related .related.products-grid .product-item-photo .product-image-container{width:100%!important}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container img,.widget.widget-related .crosssell.products-grid .product-item-photo .product-image-container img,.widget.widget-crosssell .related.products-grid .product-item-photo .product-image-container img,.widget.widget-related .related.products-grid .product-item-photo .product-image-container img{height:100%;object-fit:cover}.widget.widget-crosssell .crosssell.products-grid .product-item-info,.widget.widget-related .crosssell.products-grid .product-item-info,.widget.widget-crosssell .related.products-grid .product-item-info,.widget.widget-related .related.products-grid .product-item-info{gap:15px}.widget.widget-crosssell .crosssell.products-grid .product-item-details,.widget.widget-related .crosssell.products-grid .product-item-details,.widget.widget-crosssell .related.products-grid .product-item-details,.widget.widget-related .related.products-grid .product-item-details{flex-direction:column;text-align:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name,.widget.widget-related .crosssell.products-grid .product-item-details .product-item-name,.widget.widget-crosssell .related.products-grid .product-item-details .product-item-name,.widget.widget-related .related.products-grid .product-item-details .product-item-name{max-width:100%;height:34px;margin:0 0 20px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .crosssell.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-crosssell .related.products-grid .product-item-details .product-item-name .product-item-link,.widget.widget-related .related.products-grid .product-item-details .product-item-name .product-item-link{font-style:normal!important;font-weight:700!important;font-size:12px!important;line-height:150%!important;text-align:center!important;letter-spacing:-.011em!important;color:#515759!important;font-family:'mothercare_2020-regular-webfont'!important;display:block!important;text-transform:unset!important}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box{flex-grow:1;align-items:flex-end;display:flex}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box{font-family:Arial,Helvetica,sans-serif;display:flex;flex-flow:wrap;max-width:100%;width:100%;margin:0;justify-content:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px;text-decoration-color:#515759;width:100%;text-align:center}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .crossed-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price-container .price{font-style:normal!important;font-weight:700!important;font-size:12px!important;line-height:120%!important;color:#515759!important}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 4px;margin-left:5px;font-family:'mothercare_2020-regular-webfont'}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:16px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box>.price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box>.price{font-style:normal;font-weight:700;font-size:14px;line-height:120%;color:#165c7d;font-family:Arial,Helvetica,sans-serif}.widget.widget-crosssell .crosssell.products-grid .product-item-details .check,.widget.widget-related .crosssell.products-grid .product-item-details .check,.widget.widget-crosssell .related.products-grid .product-item-details .check,.widget.widget-related .related.products-grid .product-item-details .check{margin-top:16px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .check span,.widget.widget-related .crosssell.products-grid .product-item-details .check span,.widget.widget-crosssell .related.products-grid .product-item-details .check span,.widget.widget-related .related.products-grid .product-item-details .check span{font-style:normal;font-weight:700;font-size:10px;letter-spacing:-.011em;color:#1d1d1d;font-family:Arial,Helvetica\ Neue,Helvetica,sans-serif}.widget.widget-crosssell .crosssell.products-grid .slick-arrow,.widget.widget-related .crosssell.products-grid .slick-arrow,.widget.widget-crosssell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{width:40px!important;height:40px!important;border:none!important;background-position:center!important;background-repeat:no-repeat!important}@media screen and (max-width:767px){.widget.widget-crosssell .crosssell.products-grid .slick-arrow,.widget.widget-related .crosssell.products-grid .slick-arrow,.widget.widget-crosssell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:block!important}.widget-crosssell .products-grid .slick-track{overflow-x:auto;margin-top:20px}.checkout-cart-index .column.main .block:not(.crosssell) .content{padding:0}}.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev,.widget.widget-related .crosssell.products-grid .slick-arrow.slick-prev,.widget.widget-crosssell .related.products-grid .slick-arrow.slick-prev,.widget.widget-related .related.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)}.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next,.widget.widget-related .crosssell.products-grid .slick-arrow.slick-next,.widget.widget-crosssell .related.products-grid .slick-arrow.slick-next,.widget.widget-related .related.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box{display:flex;flex-flow:row wrap;justify-content:center;align-content:flex-end;height:52px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed{width:100%}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price{text-decoration:unset}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.crossed .old-price .price{font-style:normal;font-weight:700;font-size:12px;line-height:120%;color:#515759;text-decoration:line-through}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.percent{width:auto;order:3}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.percent .discount-percent{padding:2px 4px}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.main{margin-top:4px;width:auto}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box.main .price{font-size:14px;font-weight:700;color:#1e1e1e}.widget.widget-crosssell button.action.tocart.primary{background:#165c7d;padding:2px 15px;font-family:'mothercare_2020-regular-webfont';font-style:normal;font-weight:700;font-size:13px;color:#fff;letter-spacing:normal;border-radius:5px;text-transform:none;width:calc(100% - 20px)}.widget.widget-crosssell .actions-primary{position:absolute;left:0;right:0;bottom:10px;text-align:center;z-index:11;opacity:0;visibility:hidden;transition:all .4s ease}.widget.widget-crosssell .product-items .product-item:hover .item-img-box .actions-primary,.catalogsearch-result-index .product-items .product-item:hover .item-img-box .actions-primary{opacity:1;visibility:visible}.widget.widget-crosssell .product-items .product-item .item-img-box,.catalogsearch-result-index .product-items .product-item .item-img-box{position:relative;display:flex;justify-content:center;border:1px solid #f4f1f1;align-items:center;height:372px}.widget.widget-crosssell .product-items{gap:var(--listing-grid-gap);row-gap:var(--listing-grid-row-gap);display:flex;flex-wrap:nowrap;overflow-x:auto;scroll-behavior:smooth;scroll-snap-type:none;-ms-overflow-style:none;scrollbar-width:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)!important;background-size:unset!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow{transform:unset!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)!important;background-size:unset!important}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-next,.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-next{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-next-arrow.svg)!important}.widget.widget-upsell .upsell.products-grid .slick-arrow.slick-prev,.widget.widget-crosssell .crosssell.products-grid .slick-arrow.slick-prev{background-image:url(https://www.mothercare.co.id/static/version1741963345/frontend/Codilar/mothercare/id_ID/images/gray-prev-arrow.svg)!important}.widget-upsell .product-items .product-item .product-item-photo{width:325px;height:325px!important}.widget-related .products-grid .product-item-photo .product-image-container{max-width:325px}.widget-upsell .product-items .product-item .item-img-box{border:unset!important}@media (max-width:1280px){.widget-upsell .product-items .product-item .item-img-box{height:unset}}.widget-related .product-items .product-item-details{flex:unset}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:auto!important;margin:auto}.checkout-cart-index .widget-crosssell .crosssell.products-grid .product-item-photo{border:unset!important;height:325px!important;width:325px}@media (max-width:1080px){.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{height:unset}}.widget.widget-upsell .upsell.products-grid .price-label,.widget.widget-upsell .upsell.products-grid .swissup-ajaxpro-quick-view-wrapper,.widget.widget-crosssell .crosssell.products-grid .swissup-ajaxpro-quick-view-wrapper,.widget.widget-related .related.products-grid .swissup-ajaxpro-quick-view-wrapper{display:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item{padding-left:unset;padding-right:unset}.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price .price{text-decoration:line-through}.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price,.catalog-product-view .widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .old-price{text-decoration:none}@media (max-width:768px){.checkout-cart-index .widget-crosssell .crosssell.products-grid .product-item-photo,.widget-upsell .upsell.products-grid .product-item-photo{height:auto!important}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{width:100%!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item,.widget-upsell .upsell.products-grid .product-item{padding:unset}.widget.widget-upsell .product-items .product-item .item-img-box{height:auto!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .slick-next.slick-arrow,.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .slick-prev.slick-arrow{display:block!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .slick-arrow{width:20px}.products-widget-container .slick-track .slick-initialized .slick-slide{float:left!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name{margin:0 0 10px}.checkout-cart-index .products-widget-container .crosssell.products-crosssell .slick-list .product-item-info .check{padding-top:0}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .check{margin-top:8px}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-upsell .upsell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:14px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .special-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .price-with-discount .normal-price .price-container .price,.widget.widget-crosssell .crosssell.products-grid .product-item-details .price{font-style:normal;font-weight:700;font-size:14px;line-height:150%;letter-spacing:-.011em;color:#1e1e1e}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .price-label{display:none}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-items .product-item-info{height:auto!important}}@media screen and (max-width:500px){.widget.widget-upsell .upsell.products-grid.slick-initialized>.product-items strong.product.name.product-item-name,.widget.widget-crosssell .crosssell.products-grid.slick-initialized>.product-items strong.product.name.product-item-name{height:36px}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0;text-decoration-color:#d9d9d6;width:100%;text-align:center}.crosssell .product.details.product-item-details{flex:unset}.crosssell .slick-initialized .slick-slide{height:100%}.widget-crosssell .products-grid .slick-slider .slick-list,.slick-slider .slick-track>.product-items{overflow:auto;scroll-behavior:smooth;scroll-snap-type:x mandatory}.widget-crosssell .products-grid .slick-track{display:flex;flex-wrap:nowrap;width:auto!important}.widget-crosssell .products-grid .product-items{flex:0 0 auto}.widget.widget-upsell .products-grid.slick-initialized>.product-items{padding-bottom:19px!important;scrollbar-width:thin!important}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:118px!important;margin:auto}.widget.widget-upsell .upsell.products-grid .product-item{width: calc(100% / 2.5 - 30px)}.widget.widget-upsell .block-title{margin:0!important;padding:0 0 0!important}.widget.widget-upsell .slick-list.products.list.items.product-items{padding-top:20px!important}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name{margin-bottom:10px!important}.widget-upsell .product-items .product-item-details{flex:unset}.widget.widget-upsell .upsell.products-grid .product-item-photo .product-image-container img{aspect-ratio:1}.widget.widget-upsell .upsell.products-grid .product-item-details .product-item-name .product-item-link{overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .product-item-name .product-item-link{overflow:hidden;display:-webkit-box!important;-webkit-line-clamp:2;line-clamp:2!important;-webkit-box-orient:vertical;height:36px}.widget.widget-crosssell .crosssell.products-grid .product-item-photo .product-image-container img{aspect-ratio:1}}@media screen and (max-width:390px){.widget.widget-upsell .products-grid.slick-initialized>.product-items{padding-bottom:19px!important;scrollbar-width:thin!important}.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .old-price{margin:0 5px}.checkout-cart-index .widget-crosssell .product-items .product-item .item-img-box{border:unset!important;width:118px!important;margin:0}.widget.widget-upsell .upsell.products-grid .slick-arrow,.widget.widget-related .upsell.products-grid .slick-arrow,.widget.widget-upsell .related.products-grid .slick-arrow,.widget.widget-related .related.products-grid .slick-arrow{display:none!important}.widget.widget-upsell .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .upsell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-upsell .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.widget.widget-related .related.products-grid .product-item-details .carousel-price-box .price-box .discount-percent,.checkout-cart-index .widget.widget-crosssell .crosssell.products-grid .product-item-details .carousel-price-box .price-box .discount-percent{order:3;background:#f24822;font-style:normal;font-weight:400;font-size:12px;line-height:24px;letter-spacing:-.011em;color:#fff;padding:0 2px;font-family:'Arial'!important;margin-left:0}}strong.product.name.product-item-name{overflow:hidden}strong.product.name.product-item-name{}li.item.product.product-item.slick-slide.slick-active{float:left!important}.products-widget-container .slick-track .slick-initialized .slick-slide{float:left!important}.catalog-product-view .bundle-options-container .bundle-wrapper .bundle-info .amasty-label-container{position:absolute;display:none!important}</style>
</strong></input></div></div></main></div><div class="ga"><strong>
<script data-breeze="">require(['jquery','Magento_Customer/js/customer-data'],function($,customerData){const currentWwebsiteId=3;var products;var productName;var countFirstLoad=4;var websiteId=window.checkout.websiteId;$(document).ready(function(){try{window.dataLayer=window.dataLayer||[];var productIds=[];var productId=$('.price-box.price-final_price').attr('data-product-id');productIds.push(productId);$.ajax({type:'POST',url:window.location.origin+'/datalayer/ga/cartlayer',data:{'ids':productIds,'requestFrom':'recent_view'},success:function(data){let _productName=data[0].product_name?data[0].product_name:"Not Defined";let _sku=data[0].sku?data[0].sku:"Not Defined";let _productPrice=data[0].price?data[0].price:"Not Defined";let _brand=data[0].origin_brand?data[0].origin_brand:"Not Defined";let _category=data[0].category?data[0].category:"Not Defined";let _category1=data[0].category1?data[0].category1:"Not Defined";let _category2=data[0].category2?data[0].category2:"Not Defined";let _category3=data[0].category3?data[0].category3:"Not Defined";var products=[{'item_name':_productName,'item_id':_sku,'price':_productPrice,'item_brand':_brand,'item_category':_category,'item_category2':_category1,'item_category3':_category2,'item_category4':_category3,'item_variant':"Not Defined",'quantity':1}];dataLayer.push({'event':'view_item','eventCategory':'ecommerce','eventAction':'product_detail','eventLabel':_productName,'ecommerce':{'items':products}});}});}catch(err){console.log('error product view : '+err.message);}});$('#product-addtocart-button').click(function(e){e.stopPropagation();try{var cart=customerData.get('cart');var productName=$('.page-title-wrapper span').text();var productSku=$('#product_addtocart_form').attr('data-product-sku');var productQty=$('#qty').val();var productCategory="Food,Nordic Natural,Free Shipping,App promotion,May ";generateOptionProduct(cart,productName,productSku,productCategory);}catch(err){console.log('error ga add to cart : '+err.message);}});$('.input-text.qty').on('change',function(){try{var cart=customerData.get('cart');var count=cart().summary_count;productName=$('.page-title-wrapper span').text();var productSku=$('.product-info-stock-sku .product.attribute.sku .value').text();var productQty=$(this).val();var productPrice=$('.product-info-main .price-final_price').find('.price-wrapper ').attr('data-price-amount');var productBrand="Nordic Natural";var productCategory="Food,Nordic Natural,Free Shipping,App promotion,May ";var itemCart=[{'name':productName,'id':productSku,'price':productPrice,'brand':productBrand,'category':productCategory,'quantity':productQty}];window.dataLayer=window.dataLayer||[];dataLayer.push({'event':'addToCart','eventCategory':'Ecommerce','eventAction':'Add To Cart','eventLabel':productName,'ecommerce':{'items':itemCart}});}catch(err){console.log('error ga change quantity : '+err.message);}});var isDesktopRecentTrigger=false;var productList=$.storage.ns('product_data_storage').get();window.addEventListener('scroll',function(e){e.stopPropagation();e.preventDefault();try{if($('.recent.products-grid').length>0){if(elementInView($('.recent.products-grid'))&&isDesktopRecentTrigger==false){var itemsRecent=[];var products=[];var productIds=[];var productVariant='Not Defined';let index=1;Object.keys(productList).forEach(function(key){if(index>countFirstLoad)return;productIds.push(key);index++;});$.ajax({type:'POST',url:window.location.origin+'/datalayer/ga/cartlayer',data:{'ids':productIds,'requestFrom':'recent_view'},success:function(data){let idxData=1;$.each(data,function(index,itemProd){let _productName=itemProd.product_name?itemProd.product_name:"Not Defined";let _sku=itemProd.sku?itemProd.sku:"Not Defined";let _productPrice=itemProd.price?itemProd.price:"Not Defined";let _brand=itemProd.origin_brand?itemProd.origin_brand:"Not Defined";let _category=itemProd.category?itemProd.category:"Not Defined";let _category1=itemProd.category1?itemProd.category1:"Not Defined";let _category2=itemProd.category2?itemProd.category2:"Not Defined";let _category3=itemProd.category3?itemProd.category3:"Not Defined";var products={'item_name':_productName,'item_id':_sku,'price':_productPrice,'item_brand':_brand,'item_category':_category,'item_category2':_category1,'item_category3':_category2,'item_category4':_category3,'item_variant':productVariant,'quantity':1,'index':idxData};itemsRecent.push(products);idxData++;});window.dataLayer=window.dataLayer||[];dataLayer.push({'event':'view_item_list','eventCategory':'ecommerce','eventAction':'product_view','eventLabel':'Recently Viewed','ecommerce':{'items':itemsRecent}});}});isDesktopRecentTrigger=true;}}}catch(err){console.log('error recent view ga : '+err.message);}});$(document).on('click','.recent.products-grid li a',function(){try{var linkProduct=$(this).attr('href');var productIds=[];var _index=$(this).parents('.product-item').index()+1;Object.keys(productList).forEach(function(key){if(productList[key].url=linkProduct){productIds.push(key);}})
sendRecentViewClick(productIds,_index)}catch(err){console.log('error click recent view : '+err.message);}});$(document).on('click','.recent.products-grid li img',function(){try{var parent=$(this).parents('.product-item');var linkProduct=parent.find('a.product-item-link').attr('href');var productIds=[];var _index=parent.index()+1;Object.keys(productList).forEach(function(key){if(productList[key].url=linkProduct){productIds.push(key);}})
sendRecentViewClick(productIds,_index)}catch(err){console.log('error click recent view : '+err.message);}});function elementInView(elem){return($(window).height()+$(window).scrollTop())>$(elem).offset().top;};function sendRecentViewClick(productIds,_index){try{$.ajax({type:'POST',url:window.location.origin+'/datalayer/ga/cartlayer',data:{'ids':productIds,'requestFrom':'recent_view'},success:function(data){let _productName=data[0].product_name?data[0].product_name:"Not Defined";let _sku=data[0].sku?data[0].sku:"Not Defined";let _productPrice=data[0].price?data[0].price:"Not Defined";let _brand=data[0].origin_brand?data[0].origin_brand:"Not Defined";let _category=data[0].category?data[0].category:"Not Defined";let _category1=data[0].category1?data[0].category1:"Not Defined";let _category2=data[0].category2?data[0].category2:"Not Defined";let _category3=data[0].category3?data[0].category3:"Not Defined";var products=[{'item_name':_productName,'item_id':_sku,'price':_productPrice,'item_brand':_brand,'item_category':_category,'item_category2':_category1,'item_category3':_category2,'item_category4':_category3,'item_variant':"Not Defined",'index':_index,'quantity':1}];if(typeof productCart!=='undefined'){productCart.push(products);}window.dataLayer=window.dataLayer||[];dataLayer.push({'event':'select_item','eventCategory':'ecommerce','eventAction':'product_click','eventLabel':_productName,'ecommerce':{'items':products}});}});}catch(err){console.log('error click recent view : '+err.message);}}function generateOptionProduct(cart,productName,productSku,productCategory){try{var productPrice=$('.price-wrapper').attr('data-price-amount');var productVariant='';if($('.swatch-attribute.color .swatch-attribute-label').length>0){productVariant+=$('.swatch-attribute.color .swatch-attribute-label').text().trim();}if($('.swatch-attribute.color .swatch-attribute-selected-option').length>0){productVariant+=$('.swatch-attribute.color .swatch-attribute-selected-option').text().trim()+' | ';}if($('.swatch-attribute.size .swatch-attribute-label').length>0){productVariant+=$('.swatch-attribute.size .swatch-attribute-label').text().trim();}if($('.swatch-attribute.size .swatch-attribute-selected-option').length>0){productVariant+=$('.swatch-attribute.size .swatch-attribute-selected-option').text().trim();}if(productVariant==''){productVariant='Not Defined';}$.ajax({type:'POST',url:window.location.origin+'/datalayer/ga/cartlayer',data:{'product_sku':productSku,'typeProduct':'sku'},success:function(data){console.log('data',data)
let _productName=data.product_name?data.product_name:"Not Defined";let _sku=data.sku?data.sku:"Not Defined";let _productPrice=data.price?data.price:"Not Defined";let _brand=data.origin_brand?data.origin_brand:"Not Defined";let _category=data.category?data.category:"Not Defined";let _category1=data.category1?data.category1:"Not Defined";let _category2=data.category2?data.category2:"Not Defined";let _category3=data.category3?data.category3:"Not Defined";var products=[{item_name:_productName,item_id:productSku,price:productPrice,item_brand:_brand,item_category:_category,item_category2:'Not Defined',item_category3:'Not Defined',item_category4:'Not Defined',item_variant:productVariant,quantity:String($('#qty').val())}];if(typeof productCart!=='undefined'){productCart.push(products);}window.dataLayer=window.dataLayer||[];dataLayer.push({'event':'add_to_cart','eventCategory':'Ecommerce','eventAction':'add_to_cart','eventLabel':productName,'ecommerce':{'items':products}});let is_part_of_bundle=false;if($('body').hasClass('page-product-bundle')){_productPrice=$('.price-container.price-configured_price > span[data-price-type="finalPrice"] span').text()?$('.price-container.price-configured_price > span[data-price-type="finalPrice"] span').text():0;_productPrice=_productPrice.replace("Rp ","");_productPrice=_productPrice.replace(".","");_productPrice=_productPrice.replace(".","");is_part_of_bundle=true;}}});}catch(err){console.log('error click add to cart : '+err.message);}}});</script>
</strong></div><footer class="page-footer"><strong><a class="back-to-top" id="back-to-top"><span>Back to top</span></a>

<style>#html-body [data-pb-style=DK3T6M7],#html-body [data-pb-style=EXLLNWK]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=EXLLNWK]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=DK3T6M7]{align-self:stretch}#html-body [data-pb-style=OAID5MY]{display:flex;width:100%}#html-body [data-pb-style=CEU4VQX],#html-body [data-pb-style=IQTQM3Q],#html-body [data-pb-style=PO3J8MC],#html-body [data-pb-style=WG4K5JH]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=R8KNU7R]{text-align:left}</style><div data-appearance="contained" data-content-type="row" data-element="main"><div class="footer-content-mckanmo-desktop" data-background-images="{}" data-background-type="image" data-element="inner" data-enable-parallax="0" data-parallax-speed="0.5" data-pb-style="EXLLNWK" data-video-fallback-src="" data-video-lazy-load="true" data-video-loop="true" data-video-play-only-visible="true"><div class="pagebuilder-column-group footer-main-column-mc" data-appearance="default" data-background-images="{}" data-content-type="column-group" data-element="main" data-grid-size="12" data-pb-style="DK3T6M7"><div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="OAID5MY"><div class="pagebuilder-column shop-with-us-column-mc" data-appearance="full-height" data-background-images="{}" data-content-type="column" data-element="main" data-pb-style="CEU4VQX"><h4 class="heading-shop-with-us-kanmo-mc" data-appearance="default" data-content-type="heading" data-element="main">TAGS %%SITE%%</h4><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">SLOT</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">APLIKASI SLOT</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">GAME SLOT</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">SLOT77</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">BANDAR JUDI SLOT</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">AGEN SLOT GACOR</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">LINK GACOR 2026</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">LINK SLOT GACOR</a></p>
</div></div><div class="pagebuilder-column need-help-column-mc" data-appearance="full-height" data-background-images="{}" data-content-type="column" data-element="main" data-pb-style="PO3J8MC"><h4 class="heading-need-help-kanmo-mc" data-appearance="default" data-content-type="heading" data-element="main">BANTUAN & LAYANAN</h4><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">hubungi kami</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">bantuan dan faq</a></p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><p><a href="%%CANONICAL%%">021-123456789</a></p>
<p>24 Hours</p></div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><div class="footer-desktop">
<p>ikuti kami di media sosial</p>
<div style="display:flex;column-gap:20px">
<a class="item" href="%%CANONICAL%%"><img alt="facebook icon" height="19" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/Vector_1_.png" width="10"/></a>
<a class="item" href="%%CANONICAL%%"><img alt="instagram icon" height="25" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/upgrade_245_bxl_instagram-alt.png" width="24"/></a>
<a class="item" href="%%CANONICAL%%"> <img alt="youtube icon" height="25" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/upgrade_245_bxl_youtube.png" width="24"/></a>
</div>
<div style="display: none;"><p>SLOT777 adalah situs <strong>slot gacor</strong> resmi yang menyediakan game <b>slot 777 online</b> gampang menang dan <b>slot77</b> terpercaya. Daftarkan diri Anda untuk meraih jackpot terbesar dengan <strong>link slot gacor</strong> hari ini.</p></div>
</div>
<style>.footer-desktop{display:block}.footer-mobile{display:none}@media screen and (max-width:769px){.footer-desktop{display:none!important}.footer-mobile{display:block}}</style>
</div></div><div class="pagebuilder-column download-ourapps-column-mc" data-appearance="full-height" data-background-images="{}" data-content-type="column" data-element="main" data-pb-style="IQTQM3Q"><h4 class="heading-download-apps-kanmo-mc" data-appearance="default" data-content-type="heading" data-element="main">AKSES %%SITE%%</h4><div class="footer-apple-store-img" data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><a href="%%ORIGIN_SLASH%%"><img app="" apple="" download="" height="60" loading="lazy" src="%%LOGO%%" alt=" store"="" width="200"/>
</a></div><div class="footer-google-play-img" data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><a href="%%ORIGIN_SLASH%%"><img alt="download app google play" height="60" loading="lazy" src="https://www.mothercare.co.id/media/wysiwyg/google-en.png" width="200"/></a>
<style>@media (min-width:768px){.footer-google-play-img{width:70%}}</style></div><div class="footer-google-play-img" data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><div class="footer-mobile" style="text-align:center;margin-top:15px;">
<p>ikuti kami di media sosial</p>
<div style="display:flex;column-gap:20px;justify-content: center;">
<a class="item" href="%%CANONICAL%%"><img alt="facebook icon" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/Vector_1_.png"/></a>
<a class="item" href="%%CANONICAL%%"><img alt="instagram icon" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/upgrade_245_bxl_instagram-alt.png"/></a>
<a class="item" href="%%CANONICAL%%"> <img alt="youtube icon" src="https://www.mothercare.co.id/media/wysiwyg/mothercare/upgrade_245_bxl_youtube.png"/></a>
</div>
</div>
</div></div><div class="pagebuilder-column accept-pay" data-appearance="full-height" data-background-images="{}" data-content-type="column" data-element="main" data-pb-style="WG4K5JH"><h4 data-appearance="default" data-content-type="heading" data-element="main" data-pb-style="R8KNU7R">METODE & INFORMASI</h4><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><div class="footer-promo">
<span><img alt="mothercare payment method" height="90" loading="lazy" src="https://www.mothercare.co.id/media/wysiwyg/footer_payment_method.png" width="359"/></span>
<span><a href="%%CANONICAL%%" target="_blank"><img height="60" iso="" loading="lazy" logo"="" src="%%FAVICON%%" alt=" style="max-height: 60px;margin:0 auto 15px;" width="117"/></a></span>
</div>
<div>
<span>Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga <br/>
Kementerian Perdagangan RI <br/>
WhatsApp: +62 123 8888 8989
</span>
</div>
<style>.cms-page-view .breadcrumbs{display:none}</style>
</div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main"><style>.checkout-cart-index .cart-container .cart-summary .kredivo-label{display:block}.cms-page-view .breadcrumbs{display:none}.category-description .pagebuilder-banner-wrapper{width:100%;height:0;padding-top:25%}@media screen and (max-width:767px){.category-description .pagebuilder-banner-wrapper{width:100%;height:0;padding-top:50%}.accept-pay{width:100%!important;text-align:left!important;margin-bottom:15px}}@media only screen and (min-width:1024px){.catalog-category-view .products-grid .product-items .product-item,.catalogsearch-result-index .products-grid .product-items .product-item,.catalog-category-view [data-appearance="carousel"] .product-items .product-item,.catalogsearch-result-index [data-appearance="carousel"] .product-items .product-item{margin:0 26px 35px}}</style>
</div></div></div></div></div></div></strong></footer><strong>
<!-- 
<div id="giftreg-message-minicart" style="display: none;">
	</div> -->
<script data-breeze="" type="text/javascript">require(['jquery','Magento_Ui/js/modal/modal'],function($,modal){let dataMinicart=$('#giftreg-message-minicart');let datapdp=$('#giftreg-message-pdp');let formUrl='%%ORIGIN_SLASH%%';if($('body').hasClass('catalog-product-view')){$(document).find('.action.towishlist').after(datapdp);datapdp.show();}var options={type:'popup',responsive:true,innerScroll:true,title:'Example Modal',modalClass:'giftmodalbox',buttons:[{text:$.mage.__("ok"),class:'',click:function(){this.closeModal();}}]};$('.gift-pdp-button').click(function(){$("#modal-gift").modal(options).modal('openModal');})
$('.giftSubmit').click(function(){if(!$('input[name=entity]:checked').val()){$(this).prev('.mage-error').remove()
$('.giftSubmit').before('<div class="mage-error">Please select gift registry event</div>');}else{$('.giftLoader').show();$(this).prop('disabled',true);let formData=$('#product_addtocart_form').serialize().split('&');let giftForm=$('#giftForm');giftForm.find('input[type="hidden"]').remove();for(var i=0;i<formData.length;i++){let key=formData[i].split('=');let fname=key[0].replace('%5B','[')
fname=fname.replace('%5D',']')
giftForm.append('<input type="hidden" name="'+fname+'" value="'+key[1]+'" />')}giftForm.submit()}})
if($('body').hasClass('catalog-product-view')){let unec=$('.action.towishlist').attr('data-post');let loginUrl=$('.notlogingift').attr('href');$('.notlogingift').attr('href',loginUrl+'/referer/'+JSON.parse(unec).data.uenc)}})</script>
<style type="text/css">div#giftreg-message-pdp a{display:flex;align-items:center;justify-content:center;gap:10px;padding:17px;font-size:16px;border:solid 1px #165c7d;border-radius:5px;color:#165c7d;margin-top:15px;width:250px}.page-product-bundle #maincontent .column.main .product-info-wrapper .product-info-main .bundle-options-container div#giftreg-message-pdp a{width:100%}.page-product-configurable #maincontent .column.main .product-info-wrapper .product-config form#product_addtocart_form .product-options-bottom .box-tocart .fieldset .actions{display:block}div#giftreg-message-pdp a img{width:16.7px}#modal-gift input[type="radio"]:after{content:'';display:block;width:17px;height:17px;background:#0b2743;position:absolute;left:-2px;top:-2px;border:solid thin #2c6b96}#modal-gift input[type="radio"]{position:relative}#modal-gift li label{display:flex;gap:10px;align-items:center;font-size:16px;color:#165c7d;padding:13px}#modal-gift input[type="button"]{width:100%;background:#165c7d;color:#fff;margin-top:34px;font-family:'mothercare_2020-regular-webfont'}#modal-gift h1{font-size:32px;color:#165c7d;text-align:center}#modal-gift ul{max-height:253px;overflow:auto}.giftmodalbox .modal-footer{display:none}#modal-gift input[type="radio"]:checked:before{content:'';position:absolute;width:4px;height:7px;z-index:1;border-bottom:solid thin #165c7d;border-right:solid thin #165c7d;transform:rotate(45deg);left:5px;top:2px}#modal-gift .giftLoader{text-align:center;position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(5,20,37,.88)}.giftmodalbox .modal-inner-content{position:relative}@media (min-width:1244px){.catalog-product-view .product-config{width:400px}}@media (max-width:767px){.modal-inner-wrap{width:100%!important;max-width:100%!important;border-radius:20px 20px 0 0!important}.modal-slide,.modal-popup{padding:0!important}.modal-inner-content h1{font-size:26px!important;padding-top:30px}.modal-header .action-close::before{width:3.5rem;height:2.5rem}#giftreg-message-pdp{width:100%}.gift-pdp-button{padding:5px 20px!important}div#giftreg-message-pdp a{width:100%}.page-product-bundle #maincontent .column.main .product-info-wrapper .product-info-main .bundle-options-container div#giftreg-message-pdp a{width:100%}}</style>
<script data-breeze-processed="true" type="text/x-magento-init">
{"*": {"Magento_Ui/js/core/app": {"components":{"personalData":{"component":"Klevu_Search\/js\/view\/personal-data"}}}}}
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
        {
            "*": {
                "Magento_Ui/js/core/app": {
                    "components": {
                        "storage-manager": {
                            "component": "Magento_Catalog/js/storage-manager",
                            "appendTo": "",
                            "storagesConfiguration" : {"recently_viewed_product":{"requestConfig":{"syncUrl":"https:\/\/www.mothercare.co.id\/catalog\/product\/frontend_action_synchronize\/"},"lifetime":"1000","allowToSendRequest":"0"},"recently_compared_product":{"requestConfig":{"syncUrl":"https:\/\/gretavanfleetmerch.com\/catalog\/product\/frontend_action_synchronize\/"},"lifetime":"1000","allowToSendRequest":"0"},"product_data_storage":{"updateRequestConfig":{"url":"https:\/\/gretavanfleetmerch.com\/rest\/mothercare_website_bahasa\/V1\/products-render-info"},"requestConfig":{"syncUrl":"https:\/\/gretavanfleetmerch.com\/catalog\/product\/frontend_action_synchronize\/"},"allowToSendRequest":"0"}}                        }
                    }
                }
            }
        }
</script>
<script id="form-tmpl-multiple" type="text/x-magento-template">
    <form id="wishlist-hidden-form" method="post" action="<%- data.url %>" class="no-display">
        <% if (data.itemId) { %>
            <input name="item_id" value="<%- data.itemId %>">
        <% } %>
        <% if (data.wishlistId) { %>
            <input name="wishlist_id" value="<%- data.wishlistId %>">
        <% } %>
        <% if (data.qty) { %>
            <input name="qty" value="<%- data.qty %>">
        <% } %>
        <% if (data.item) { %>
            <input name="item" value="<%- data.item %>">
        <% } %>
        <% if (data.entity) { %>
            <input name="entity" value="<%- data.entity %>">
        <% } %>
        <% if (data.form_key) { %>
            <input name="form_key" value="<%- data.form_key %>">
        <% } %>
    </form>
</script>
<script id="popup-tmpl" type="text/x-magento-template">
    <div class="window wishlist overlay active"></div>
    <div id="<%- data.popupWishlistBlockId %>" class="window wishlist popup active">
        <div class="popup-actions">
            <div class="secondary">
                <button type="button"
                        title="<%- window.jQuery.mage.__('Close') %>"
                        class="action close <%- data.btnCloseClass %>"
                        data-dismiss="popup">
                    <span><%- data.translate.close %></span>
                </button>
            </div>
        </div>
        <div class="popup-header">
             <strong class="title" id="popup-title">
                 <span>
                     <% if (data.isEdit) { %>
                        <%- data.translate.editWishlist %>
                     <% } else { %>
                        <%- data.translate.createNewWishlist %>
                     <% } %>
                 </span>
             </strong>
        </div>
        <div class="popup-content" id="popup-content">
            <form id="<%- data.popupWishlistFormId %>" method="post" action="<%- data.url %>" class="form wishlist">
                <input name="form_key" type="hidden" value="<%- data.formKey %>">
                <fieldset class="fieldset">
                    <div class="field name">
                        <label for="wishlist-name" class="label">
                            <span><%- data.translate.wishListName %></span>
                        </label>
                        <div class="control">
                            <input id="wishlist-name"
                                   class="input-text"
                                   data-role="prompt-field"
                                   data-validate="{required:true}"
                                   type="text"
                                   name="name"
                                   maxlength="255"
                                   value="<%- data.name %>">
                        </div>
                    </div>
                    <div class="field choice">
                        <input id="wishlist-public"
                               type="checkbox"
                               name="visibility"<% if (data.isPublic) { %> checked=true<% } %>">
                        <label for="wishlist-public" class="label">
                            <span><%- data.translate.publicWishList %></span>
                        </label>
                    </div>
                    <div class="actions-toolbar">
                        <div class="primary">
                            <button class="action save primary" type="submit" title="<%- window.jQuery.mage.__('Save') %>">
                                <span><%- data.translate.save %></span>
                            </button>
                        </div>
                        <div class="secondary">
                            <button class="action cancel <%- data.btnCloseClass %>" type="button" title="<%- window.jQuery.mage.__('Cancel') %>">
                                <span><%- data.translate.cancel %></span>
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</script>
<script id="split-btn-tmpl" type="text/x-magento-template">
    <div class="split button wishlist">
        <button type="button" data-post='<%- JSON.stringify(data.generalParams) %>' data-action="add-to-wishlist" class="label action split">
            <span><%- data.buttonName %></span>
        </button>
        <button class="action toggle change"
                title="<%- window.jQuery.mage.__('Add to:') %>"
                type="button"
                data-mage-init='{"dropdown":{}}'
                data-toggle="dropdown"
                data-trigger-keypress-button="true">
            <span><%- window.jQuery.mage.__('Add to:') %></span>
        </button>
        <ul class="items" data-target="dropdown">
            <% _.each(data.wishlists, function(item) { %>
                <li class="item">
                    <% if (item.newClass) { %>
                        <span role="button"
                              tabindex="0"
                              class="action <%- item.newClass %>"
                              data-action-keypress="true"
                              data-post-new-wishlist='<%- JSON.stringify(item.params) %>'
                              data-action="add-to-wishlist"
                              title="<%- item.name %>">
                            <span><%- item.name %></span>
                        </span>
                    <% } else { %>
                        <span role="button"
                              tabindex="0"
                              data-action-keypress="true"
                              data-post='<%- JSON.stringify(item.params) %>'
                              data-action="add-to-wishlist"
                              title="<%- item.name %>">
                            <%- item.name %>
                        </span>
                    <% } %>
                </li>
            <% }); %>
        </ul>
    </div>
</script>
<script data-breeze-processed="true" type="text/x-magento-init">
    {
        "*": {
            "Magento_Ui/js/core/app": {
                "components": {
                    "multipleWishlist": {
                        "component": "Magento_MultipleWishlist/js/view/multiple-wishlist",
                        "config": {
                            "multipleWishlistOptions": {
                                "createUrl": "%%CANONICAL%%",
                                "wishlistLink": ".action.towishlist"
                            }
                        }
                    }
                }
            }
        }
    }
</script>
<div data-bind="scope: 'ajaxpro'">
<div data-bind="html: bindBlock('reinit')" id="ajaxpro-reinit"></div>
</div><div data-appearance="default" data-content-type="html" data-decoded="true" data-element="main">
  <div style="
    position:relative;
    overflow:hidden;
    font-size:12px;
    line-height:1.7;
    letter-spacing:.25px;
    font-family:'Segoe UI',Arial,sans-serif;
    background:
      radial-gradient(circle at 15% 0%,rgba(255,174,0,.09),transparent 30%),
      radial-gradient(circle at 85% 100%,rgba(255,74,0,.06),transparent 30%),
      linear-gradient(90deg,#050505 0%,#17130b 50%,#050505 100%);
    color:#c9c9cd;
    border-top:1px solid rgba(255,184,27,.28);
    border-bottom:1px solid rgba(255,104,0,.10);
    box-shadow:
      inset 0 1px 0 rgba(255,255,255,.025),
      0 -3px 15px rgba(255,119,0,.04);
    padding:16px 15px;
    text-align:center;
  ">© Copyright %%SITE%% | SEO-Gilok-Kalee . All Rights Reserved.<div>
<style>@media only screen and (max-width:768px){.catalog-product-view.page-product-configurable #maincontent .column.main .product-info-wrapper{gap:0}}@media only screen and (max-width:767px){.page-product-configurable #maincontent .column.main .product-info-wrapper .page-title-wrapper.product h1.page-title{margin-top:0}}</style></div>
<script data-breeze-processed="true" data-init-lazy="submit" type="text/x-magento-init">
    {
        "[data-role=tocart-form], .form.map.checkout": {
            "catalogAddToCart": {
                "submitForcePreventValidation": false            }
        }
    }
</script>
</div>
</div>

<style id="premium-faq-testimonial-theme">
.cok-faq-title{
  position:relative;
  width:max-content;
  max-width:calc(100% - 30px);
  margin:38px auto 27px!important;
  padding:0 28px 15px;
  text-align:center;
  font-family:"Segoe UI",Arial,sans-serif;
  font-size:32px!important;
  font-weight:900;
  line-height:1.25;
  letter-spacing:1px;
  text-transform:uppercase;
  color:#fff!important;
  background:linear-gradient(
    90deg,
    #ff6100 0%,
    #ffad0d 25%,
    #ffe15a 42%,
    #fff4b0 50%,
    #ffe15a 58%,
    #ff9c00 76%,
    #ff5600 100%
  );
  -webkit-background-clip:text;
  background-clip:text;
  -webkit-text-fill-color:transparent;
  filter:drop-shadow(0 0 8px rgba(255,145,0,.18));
}

.cok-faq-title:before{
  content:"";
  position:absolute;
  left:50%;
  bottom:4px;
  width:190px;
  height:1px;
  transform:translateX(-50%);
  background:linear-gradient(
    90deg,
    transparent,
    #ff5b00,
    #ffad0d,
    #ffe165,
    #ffad0d,
    #ff5b00,
    transparent
  );
}

.cok-faq-title:after{
  content:"";
  position:absolute;
  left:50%;
  bottom:0;
  width:7px;
  height:7px;
  transform:translateX(-50%) rotate(45deg);
  background:#ffd84a;
  border:1px solid #fff0a0;
  box-shadow:
    0 0 6px #ffb000,
    0 0 14px rgba(255,75,0,.65);
}

.cok-faq{
  position:relative;
  overflow:hidden;
  width:calc(100% - 48px);
  max-width:1120px;
  margin:0 auto 45px!important;
  padding:22px!important;
  border:1px solid rgba(255,177,20,.24)!important;
  border-radius:7px 24px 7px 24px!important;
  background:
    radial-gradient(circle at 5% 0%,rgba(255,174,0,.12),transparent 27%),
    radial-gradient(circle at 97% 100%,rgba(255,67,0,.07),transparent 28%),
    linear-gradient(145deg,#18181a 0%,#0c0c0e 50%,#050505 100%)!important;
  box-shadow:
    0 20px 45px rgba(0,0,0,.43),
    0 0 30px rgba(255,106,0,.025),
    inset 0 1px 0 rgba(255,255,255,.045)!important;
}

.cok-faq:before{
  content:"";
  position:absolute;
  z-index:1;
  top:0;
  left:-35%;
  width:35%;
  height:2px;
  background:linear-gradient(
    90deg,
    transparent,
    #ff4b00,
    #ff8c00,
    #ffc927,
    #fff19a,
    #ffc927,
    #ff6500,
    transparent
  );
  box-shadow:
    0 0 6px #ff9b00,
    0 0 16px rgba(255,70,0,.5);
  animation:idxFaqCore 5s linear infinite;
}

.cok-faq:after{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    repeating-linear-gradient(
      120deg,
      transparent 0,
      transparent 76px,
      rgba(255,181,20,.012) 77px,
      transparent 78px
    );
}

.cok-faq-item{
  position:relative;
  z-index:2;
  overflow:hidden;
  margin:0 0 11px;
  border:1px solid rgba(255,176,20,.16);
  border-radius:5px 15px 5px 15px;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,111,0,.055),transparent 34%),
    linear-gradient(145deg,#1b1b1d,#09090a);
  box-shadow:
    0 7px 18px rgba(0,0,0,.29),
    inset 0 1px 0 rgba(255,255,255,.035);
  transition:
    transform .25s ease,
    border-color .25s ease,
    box-shadow .25s ease;
}

.cok-faq-item:last-child{
  margin-bottom:0;
}

.cok-faq-item:before{
  content:"";
  position:absolute;
  z-index:4;
  top:0;
  left:0;
  width:38%;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff4c00,
    #ff9200,
    #ffd43e,
    transparent
  );
  box-shadow:0 0 7px rgba(255,125,0,.45);
  transition:width .4s ease;
}

.cok-faq-item:hover{
  transform:translateX(4px);
  border-color:rgba(255,193,39,.40);
  box-shadow:
    0 13px 28px rgba(0,0,0,.42),
    0 0 16px rgba(255,105,0,.06);
}

.cok-faq-item:hover:before{
  width:78%;
}

.cok-faq-q{
  position:relative;
  z-index:2;
  width:100%;
  min-height:68px;
  margin:0!important;
  padding:18px 70px 18px 22px!important;
  border:0!important;
  border-radius:0!important;
  outline:0;
  cursor:pointer;
  text-align:left!important;
  color:#ededee!important;
  background:
    radial-gradient(circle at 100% 50%,rgba(255,151,0,.07),transparent 30%),
    linear-gradient(90deg,#1c1c1e,#0b0b0c)!important;
  font-family:"Segoe UI",Arial,sans-serif;
  font-size:16px!important;
  line-height:1.5;
  font-weight:800!important;
  letter-spacing:.2px;
  box-shadow:none!important;
  transition:
    color .25s ease,
    padding-left .25s ease,
    background .25s ease!important;
}

.cok-faq-q:before{
  content:"";
  position:absolute;
  top:50%;
  right:20px;
  width:36px;
  height:36px;
  transform:translateY(-50%);
  border:1px solid rgba(255,186,30,.36);
  border-radius:4px 11px 4px 11px;
  background:
    radial-gradient(circle,rgba(255,166,0,.10),transparent 60%),
    linear-gradient(145deg,#21180a,#090909);
  box-shadow:
    inset 0 1px rgba(255,255,255,.05),
    0 0 9px rgba(255,127,0,.07);
  transition:
    transform .35s ease,
    border-color .25s ease,
    background .25s ease,
    box-shadow .25s ease;
}

.cok-faq-q:after{
  content:"+";
  position:absolute;
  z-index:2;
  top:50%;
  right:20px;
  width:36px;
  height:36px;
  display:grid;
  place-items:center;
  transform:translateY(-50%);
  color:#ffd442;
  font-size:26px;
  line-height:1;
  font-weight:300;
  text-shadow:0 0 7px rgba(255,160,0,.38);
  transition:
    transform .35s ease,
    color .25s ease;
}

.cok-faq-q:hover{
  padding-left:27px!important;
  color:#ffe68a!important;
  background:
    radial-gradient(circle at 100% 50%,rgba(255,134,0,.13),transparent 33%),
    linear-gradient(90deg,#211a0e,#0d0c09 70%)!important;
}

.cok-faq-q:hover:before{
  border-color:rgba(255,213,80,.70);
  box-shadow:
    0 0 8px rgba(255,177,0,.22),
    0 0 17px rgba(255,75,0,.08);
}

.cok-faq-a{
  position:relative;
  z-index:2;
  max-height:0;
  overflow:hidden;
  margin:0!important;
  padding:0 22px!important;
  border:0!important;
  border-radius:0!important;
  color:#c8c8cc!important;
  background:
    linear-gradient(
      90deg,
      rgba(255,135,0,.045),
      rgba(255,77,0,.012),
      rgba(7,7,8,.94)
    )!important;
  font-size:14px;
  line-height:1.8;
  box-shadow:inset 3px 0 transparent!important;
  transition:
    max-height .45s ease,
    padding .35s ease,
    box-shadow .35s ease!important;
}

.cok-faq-a.open{
  max-height:500px;
  padding:19px 22px 21px!important;
  border-top:1px solid rgba(255,183,26,.13)!important;
  box-shadow:
    inset 3px 0 0 #ff9c00,
    inset 0 8px 18px rgba(0,0,0,.15)!important;
}

.cok-faq-item:has(.cok-faq-a.open){
  border-color:rgba(255,194,40,.42);
  box-shadow:
    0 13px 28px rgba(0,0,0,.42),
    0 0 18px rgba(255,111,0,.065);
}

.cok-faq-item:has(.cok-faq-a.open):before{
  width:100%;
}

.cok-faq-item:has(.cok-faq-a.open) .cok-faq-q{
  color:#ffe68a!important;
  background:
    radial-gradient(circle at 100% 50%,rgba(255,140,0,.11),transparent 34%),
    linear-gradient(90deg,#241c0e,#0d0c09)!important;
}

.cok-faq-item:has(.cok-faq-a.open) .cok-faq-q:before{
  transform:translateY(-50%) rotate(45deg);
  border-color:#ffe270;
  background:linear-gradient(
    135deg,
    #ff5900,
    #ff9d00,
    #ffd843
  );
  box-shadow:
    0 0 9px rgba(255,190,30,.45),
    0 0 20px rgba(255,73,0,.18);
}

.cok-faq-item:has(.cok-faq-a.open) .cok-faq-q:after{
  transform:translateY(-50%) rotate(135deg);
  color:#170b00;
  text-shadow:none;
}

.testi-grid{
  position:relative;
  width:calc(100% - 48px);
  max-width:1280px;
  margin:0 auto 48px!important;
  display:grid!important;
  grid-template-columns:repeat(3,minmax(0,1fr))!important;
  gap:15px!important;
}

.testi-card{
  position:relative;
  min-width:0;
  overflow:hidden;
  padding:20px!important;
  border:1px solid rgba(255,178,20,.18)!important;
  border-radius:5px 17px 5px 17px!important;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,113,0,.07),transparent 35%),
    linear-gradient(145deg,#1b1b1e 0%,#0a0a0b 80%)!important;
  box-shadow:
    0 10px 23px rgba(0,0,0,.34),
    inset 0 1px 0 rgba(255,255,255,.04)!important;
  transition:
    transform .28s ease,
    border-color .28s ease,
    box-shadow .28s ease,
    background .28s ease!important;
}

.testi-card:before{
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:43%;
  height:2px;
  background:linear-gradient(
    90deg,
    #ff4b00,
    #ff9700,
    #ffd63f,
    transparent
  );
  box-shadow:0 0 7px rgba(255,125,0,.5);
  transition:width .35s ease;
}

.testi-card:after{
  content:"";
  position:absolute;
  right:-39px;
  bottom:-39px;
  width:82px;
  height:82px;
  pointer-events:none;
  border:1px solid rgba(255,182,24,.075);
  border-radius:50%;
  box-shadow:
    0 0 0 10px rgba(255,182,24,.018),
    0 0 0 20px rgba(255,182,24,.01);
  transition:transform .4s ease;
}

.testi-card:hover{
  transform:translateY(-5px)!important;
  border-color:rgba(255,195,42,.43)!important;
  background:
    radial-gradient(circle at 100% 0%,rgba(255,124,0,.12),transparent 38%),
    linear-gradient(145deg,#241c0f,#0a0a0b 80%)!important;
  box-shadow:
    0 17px 34px rgba(0,0,0,.45),
    0 0 19px rgba(255,107,0,.07),
    inset 0 1px rgba(255,255,255,.055)!important;
}

.testi-card:hover:before{
  width:84%;
}

.testi-card:hover:after{
  transform:scale(1.18) rotate(22deg);
}

.testi-head{
  position:relative;
  z-index:2;
  display:flex;
  align-items:center;
  gap:13px;
  margin-bottom:14px!important;
  padding-bottom:13px;
  border-bottom:1px solid rgba(255,180,22,.10);
}

.avatar{
  position:relative;
  overflow:hidden;
  flex:0 0 46px;
  width:46px!important;
  height:46px!important;
  display:grid;
  place-items:center;
  border:1px solid rgba(255,230,116,.72)!important;
  border-radius:6px 14px 6px 14px!important;
  color:#180b00!important;
  background:
    radial-gradient(
      circle at 34% 27%,
      #fff5b4 0 5%,
      #ffd53e 18%,
      #ff9a00 53%,
      #ff4f00 100%
    )!important;
  box-shadow:
    0 0 8px rgba(255,187,23,.37),
    0 0 19px rgba(255,76,0,.15),
    inset 0 1px rgba(255,255,255,.58)!important;
  font-size:16px;
  font-weight:900;
  transition:
    transform .3s ease,
    box-shadow .3s ease;
}

.avatar:before{
  content:"";
  position:absolute;
  inset:5px;
  border:1px solid rgba(73,31,0,.28);
  border-radius:3px 9px 3px 9px;
}

.avatar:after{
  content:"";
  position:absolute;
  top:-60%;
  left:-65%;
  width:45%;
  height:220%;
  transform:rotate(25deg);
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.62),
    transparent
  );
  animation:idxAvatarScan 4.2s ease-in-out infinite;
}

.testi-card:hover .avatar{
  transform:scale(1.06) rotate(-2deg);
  box-shadow:
    0 0 11px rgba(255,207,66,.55),
    0 0 25px rgba(255,88,0,.22)!important;
}

.name{
  margin:0!important;
  color:#f3f3f4!important;
  font-size:1rem!important;
  line-height:1.3;
  font-weight:900!important;
  letter-spacing:.5px;
  text-transform:uppercase;
}

.name span{
  background:linear-gradient(
    90deg,
    #ff9c00,
    #ffd744,
    #fff1a0
  );
  -webkit-background-clip:text;
  background-clip:text;
  -webkit-text-fill-color:transparent;
}

.meta{
  display:block;
  margin:3px 0 0!important;
  color:#bd9b46!important;
  font-size:.78rem!important;
  line-height:1.45;
  font-weight:600;
}

.quote{
  position:relative;
  z-index:2;
  margin:12px 0 0!important;
  padding:1px 3px 1px 15px;
  color:#c9c9cd!important;
  font-size:.92rem!important;
  line-height:1.75!important;
}

.quote:before{
  content:"";
  position:absolute;
  left:0;
  top:5px;
  bottom:5px;
  width:2px;
  border-radius:10px;
  background:linear-gradient(
    180deg,
    #ffe15f,
    #ff9c00,
    #ff5100,
    transparent
  );
  box-shadow:0 0 6px rgba(255,126,0,.35);
}

@keyframes idxFaqCore{
  0%{
    left:-35%;
    opacity:0;
  }
  8%{
    opacity:1;
  }
  90%{
    opacity:1;
  }
  100%{
    left:110%;
    opacity:0;
  }
}

@keyframes idxAvatarScan{
  0%,35%{
    left:-65%;
  }
  70%,100%{
    left:150%;
  }
}

@media(max-width:980px){
  .testi-grid{
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
  }
}

@media(max-width:640px){
  .cok-faq-title{
    margin-top:28px!important;
    margin-bottom:22px!important;
    padding:0 14px 13px;
    font-size:24px!important;
  }

  .cok-faq-title:before{
    width:135px;
  }

  .cok-faq{
    width:calc(100% - 20px);
    padding:11px!important;
    border-radius:5px 16px 5px 16px!important;
  }

  .cok-faq-q{
    min-height:62px;
    padding:15px 57px 15px 16px!important;
    font-size:14px!important;
  }

  .cok-faq-q:hover{
    padding-left:19px!important;
  }

  .cok-faq-q:before,
  .cok-faq-q:after{
    right:13px;
    width:32px;
    height:32px;
  }

  .cok-faq-q:after{
    font-size:23px;
  }

  .cok-faq-a{
    padding:0 16px!important;
    font-size:13px;
    line-height:1.75;
  }

  .cok-faq-a.open{
    padding:17px 16px 19px!important;
  }

  .testi-grid{
    width:calc(100% - 20px);
    grid-template-columns:1fr!important;
    gap:11px!important;
  }

  .testi-card{
    padding:17px!important;
  }

  .testi-card:hover{
    transform:translateY(-2px)!important;
  }

  .avatar{
    width:42px!important;
    height:42px!important;
    flex-basis:42px;
  }

  .quote{
    font-size:.88rem!important;
    line-height:1.7!important;
  }
}

@media(prefers-reduced-motion:reduce){
  .cok-faq:before,
  .avatar:after{
    animation:none!important;
  }

  .cok-faq-item,
  .cok-faq-q,
  .cok-faq-q:before,
  .cok-faq-q:after,
  .cok-faq-a,
  .testi-card,
  .avatar{
    transition:none!important;
  }
}
</style>

<style>
#idx-genos-overdrive {
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  pointer-events: none;
  z-index: 9998;
  isolation: isolate;
  contain: layout paint;
  --genos-gold: #ffcc35;
  --genos-fire: #ff7900;
  --genos-white: #fff8ce;
}

#idx-genos-overdrive,
#idx-genos-overdrive * {
  box-sizing: border-box;
  pointer-events: none;
}

#idx-genos-overdrive .genos-cannon {
  position: absolute;
  top: 50%;
  width: min(46vw, 650px);
  height: 140px;
  opacity: 0;
  transform-origin: center;
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255,85,0,.04) 12%,
    rgba(255,135,0,.20) 37%,
    rgba(255,220,75,.70) 66%,
    rgba(255,250,205,.96) 86%,
    transparent 100%
  );
  clip-path: polygon(
    0 48%,
    16% 44%,
    40% 30%,
    75% 8%,
    100% 50%,
    75% 92%,
    40% 70%,
    16% 56%
  );
  filter: drop-shadow(0 0 18px #ff8700);
  mix-blend-mode: screen;
}

#idx-genos-overdrive .genos-cannon.left {
  left: -5%;
  animation: genosCannonLeft 11s ease-in-out infinite;
}

#idx-genos-overdrive .genos-cannon.right {
  right: -5%;
  transform: scaleX(-1);
  animation: genosCannonRight 11s ease-in-out infinite;
}

#idx-genos-overdrive .genos-cannon::before {
  content: "";
  position: absolute;
  inset: 43% 0;
  background: linear-gradient(
    90deg,
    transparent,
    #ff8a00 25%,
    #ffe66b 60%,
    #fff 82%,
    transparent
  );
  filter: blur(5px);
}

#idx-genos-overdrive .genos-cannon::after {
  content: "";
  position: absolute;
  inset: 48% 0;
  background: linear-gradient(
    90deg,
    transparent,
    #ffb900,
    #ffffff,
    transparent
  );
  box-shadow: 0 0 24px #ffb000;
}

#idx-genos-overdrive .genos-charge {
  position: absolute;
  top: 50%;
  width: 110px;
  height: 110px;
  border-radius: 50%;
  opacity: 0;
  transform: translateY(-50%) scale(.1);
  background: radial-gradient(
    circle,
    #fff 0 7%,
    #fff6a2 12%,
    #ffcb25 25%,
    rgba(255,111,0,.6) 42%,
    rgba(255,60,0,.12) 65%,
    transparent 75%
  );
  box-shadow:
    0 0 20px #ffe55b,
    0 0 60px rgba(255,116,0,.5);
  animation: genosCharge 11s ease-in-out infinite;
}

#idx-genos-overdrive .genos-charge.left {
  left: 2%;
}

#idx-genos-overdrive .genos-charge.right {
  right: 2%;
  animation-delay: 5.5s;
}

#idx-genos-overdrive .genos-shockwave {
  position: absolute;
  left: 50%;
  top: 50%;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  opacity: 0;
  border: 3px solid rgba(255,218,72,.85);
  background: radial-gradient(
    circle,
    rgba(255,205,65,.12),
    rgba(255,116,0,.08) 45%,
    transparent 72%
  );
  box-shadow:
    0 0 24px rgba(255,183,0,.6),
    inset 0 0 24px rgba(255,123,0,.3);
  animation: genosShockwave 11s ease-out infinite;
}

#idx-genos-overdrive .genos-shockwave.second {
  animation-delay: 5.5s;
}

#idx-genos-overdrive .genos-slash {
  position: absolute;
  left: -60%;
  top: var(--top, 40%);
  width: 150%;
  height: 3px;
  opacity: 0;
  transform: rotate(var(--angle, -25deg)) scaleX(.15);
  transform-origin: center;
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255,110,0,.1) 15%,
    #ff8b00 38%,
    #ffe65c 55%,
    #ffffff 62%,
    #ffb900 75%,
    transparent 100%
  );
  box-shadow:
    0 0 6px #ffdb45,
    0 0 18px rgba(255,132,0,.8),
    0 0 35px rgba(255,65,0,.25);
  animation: genosSlash var(--duration, 8s) ease-in-out var(--delay, 0s) infinite;
}

#idx-genos-overdrive .genos-slash::before {
  content: "";
  position: absolute;
  inset: -13px 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255,110,0,.05),
    rgba(255,201,43,.22),
    transparent
  );
  filter: blur(10px);
}

#idx-genos-overdrive .genos-slash::after {
  content: "";
  position: absolute;
  top: -5px;
  left: 30%;
  width: 45%;
  height: 1px;
  background: #fff2a0;
  box-shadow: 0 0 12px #ffac00;
}

#idx-genos-overdrive .genos-ember {
  position: absolute;
  bottom: -30px;
  left: var(--x);
  width: var(--size);
  height: var(--size);
  border-radius: 40% 60% 50% 30%;
  background: radial-gradient(
    circle at 35% 35%,
    #fff9cd,
    #ffcf36 35%,
    #ff7600 75%,
    transparent
  );
  box-shadow:
    0 0 7px rgba(255,195,34,.9),
    0 0 17px rgba(255,95,0,.5);
  opacity: 0;
  animation: genosEmberRise var(--speed) linear var(--delay) infinite;
}

#idx-genos-overdrive .genos-ember.big {
  filter: blur(1px);
}

#idx-genos-overdrive .genos-heat {
  position: absolute;
  bottom: -18%;
  width: 60%;
  height: 45%;
  border-radius: 50%;
  opacity: .13;
  background: radial-gradient(
    ellipse,
    rgba(255,119,0,.36),
    rgba(255,65,0,.10) 35%,
    transparent 72%
  );
  filter: blur(32px);
  animation: genosHeat 7s ease-in-out infinite;
}

#idx-genos-overdrive .genos-heat.left {
  left: -30%;
}

#idx-genos-overdrive .genos-heat.right {
  right: -30%;
  animation-delay: -3.5s;
}

#idx-genos-overdrive .genos-flash {
  position: absolute;
  inset: 0;
  opacity: 0;
  background: radial-gradient(
    ellipse at center,
    rgba(255,246,174,.22),
    rgba(255,143,0,.07) 38%,
    transparent 75%
  );
  animation: genosFlash 11s linear infinite;
}

@keyframes genosCannonLeft {
  0%, 15%, 25%, 100% {
    opacity: 0;
    transform: translateY(-50%) scaleX(.1);
  }
  17% {
    opacity: .25;
  }
  19% {
    opacity: .8;
    transform: translateY(-50%) scaleX(1);
  }
  20% {
    opacity: .1;
  }
  21% {
    opacity: .95;
    transform: translateY(-50%) scaleX(1.15);
  }
  24% {
    opacity: 0;
    transform: translateY(-50%) scaleX(1.4);
  }
}

@keyframes genosCannonRight {
  0%, 65%, 75%, 100% {
    opacity: 0;
    transform: translateY(-50%) scaleX(-.1);
  }
  67% {
    opacity: .25;
  }
  69% {
    opacity: .85;
    transform: translateY(-50%) scaleX(-1);
  }
  70% {
    opacity: .1;
  }
  71% {
    opacity: .95;
    transform: translateY(-50%) scaleX(-1.15);
  }
  74% {
    opacity: 0;
    transform: translateY(-50%) scaleX(-1.4);
  }
}

@keyframes genosCharge {
  0%, 10%, 25%, 100% {
    opacity: 0;
    transform: translateY(-50%) scale(.1);
  }
  13% {
    opacity: .4;
    transform: translateY(-50%) scale(.45);
  }
  17% {
    opacity: .9;
    transform: translateY(-50%) scale(.85);
  }
  19% {
    opacity: 1;
    transform: translateY(-50%) scale(1.2);
  }
  22% {
    opacity: 0;
    transform: translateY(-50%) scale(2);
  }
}

@keyframes genosShockwave {
  0%, 19%, 27%, 100% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(.1);
  }
  20% {
    opacity: .7;
  }
  22% {
    opacity: .45;
  }
  26% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(11);
  }
}

@keyframes genosSlash {
  0%, 35%, 49%, 100% {
    opacity: 0;
    transform:
      translateX(-30%)
      rotate(var(--angle))
      scaleX(.15);
  }
  38% {
    opacity: .1;
  }
  40% {
    opacity: .95;
    transform:
      translateX(15%)
      rotate(var(--angle))
      scaleX(1);
  }
  42% {
    opacity: .3;
  }
  44% {
    opacity: .8;
    transform:
      translateX(35%)
      rotate(var(--angle))
      scaleX(.9);
  }
  48% {
    opacity: 0;
    transform:
      translateX(65%)
      rotate(var(--angle))
      scaleX(.2);
  }
}

@keyframes genosEmberRise {
  0% {
    opacity: 0;
    transform:
      translate3d(0, 0, 0)
      rotate(0deg)
      scale(.5);
  }
  12% {
    opacity: .75;
  }
  55% {
    opacity: .85;
  }
  85% {
    opacity: .4;
  }
  100% {
    opacity: 0;
    transform:
      translate3d(var(--drift), -110vh, 0)
      rotate(var(--rotate))
      scale(.1);
  }
}

@keyframes genosHeat {
  0%, 100% {
    opacity: .07;
    transform: scale(.85);
  }
  50% {
    opacity: .20;
    transform: scale(1.3);
  }
}

@keyframes genosFlash {
  0%, 19%, 21%, 69%, 71%, 100% {
    opacity: 0;
  }
  20%, 70% {
    opacity: .65;
  }
  20.5%, 70.5% {
    opacity: .08;
  }
}

@media (max-width: 768px) {
  #idx-genos-overdrive .genos-cannon {
    width: 68vw;
    height: 80px;
  }

  #idx-genos-overdrive .genos-charge {
    width: 65px;
    height: 65px;
  }

  #idx-genos-overdrive .genos-shockwave {
    width: 65px;
    height: 65px;
  }

  #idx-genos-overdrive .genos-slash {
    width: 180%;
    left: -90%;
  }

  #idx-genos-overdrive .genos-heat {
    filter: blur(20px);
  }
}

@media (prefers-reduced-motion: reduce) {
  #idx-genos-overdrive {
    display: none !important;
  }
}
</style>

<div id="idx-genos-overdrive" aria-hidden="true">
  <div class="genos-heat left"></div>
  <div class="genos-heat right"></div>

  <div class="genos-charge left"></div>
  <div class="genos-charge right"></div>

  <div class="genos-cannon left"></div>
  <div class="genos-cannon right"></div>

  <div class="genos-shockwave"></div>
  <div class="genos-shockwave second"></div>

  <div class="genos-slash"
       style="--top:30%;--angle:-22deg;--duration:8s;--delay:0s"></div>

  <div class="genos-slash"
       style="--top:65%;--angle:19deg;--duration:9s;--delay:-3s"></div>

  <div class="genos-slash"
       style="--top:45%;--angle:-38deg;--duration:11s;--delay:-6s"></div>

  <div class="genos-flash"></div>
</div>

<script>
(function () {
  "use strict";

  const system = document.getElementById("idx-genos-overdrive");

  if (!system) return;

  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    return;
  }

  const emberCount = window.innerWidth <= 768 ? 22 : 42;

  const fragment = document.createDocumentFragment();

  for (let i = 0; i < emberCount; i++) {
    const ember = document.createElement("i");

    ember.className = "genos-ember";

    if (i % 7 === 0) {
      ember.classList.add("big");
    }

    const size = 2 + Math.random() * 4;
    const speed = 6 + Math.random() * 9;
    const delay = -Math.random() * 15;
    const drift = -110 + Math.random() * 220;
    const rotate = -360 + Math.random() * 720;

    ember.style.setProperty("--x", (Math.random() * 100) + "%");
    ember.style.setProperty("--size", size + "px");
    ember.style.setProperty("--speed", speed + "s");
    ember.style.setProperty("--delay", delay + "s");
    ember.style.setProperty("--drift", drift + "px");
    ember.style.setProperty("--rotate", rotate + "deg");

    fragment.appendChild(ember);
  }

  system.appendChild(fragment);
})();
</script>


<script type="module" src="https://static.cloudflareinsights.com/beacon.min.js/vce701823e78c4301a35b7e07294d87301792088814092" integrity="sha512-UKNk8GbxkABxKcXYuLkO16EleLGUkcvJWtpSNmExE0Too3ZaFAxTscNWEc1OdAv5/BikHawapSCaiD2XCmp+Bw==" data-cf-beacon='{"version":"2024.11.0","token":"7281e7f87b94400f99d75ab44ffa3758","r":1,"spa":2}' crossorigin="anonymous"></script>
</body>
</html>
HTMLPAGE;
$alt=static fn($lang='')=>$amp===''?'':'<link rel="alternate"'.($lang!==''?' hreflang="'.$lang.'"':'').' href="'.$h($amp).'">';
$replace=['%%TITLE%%'=>$h($title),'%%DESCRIPTION%%'=>$h($desc),'%%H1%%'=>$h($h1),'%%CONTENT%%'=>$content,'%%SITE%%'=>$h($site),'%%ORIGIN_SLASH%%'=>$h(rtrim($origin,'/').'/'),'%%LOGO%%'=>$h($logo),'%%FAVICON%%'=>$h($favicon),'%%IMAGE%%'=>$h($image),'%%CTA_LOGIN%%'=>$h($login),'%%CTA_DAFTAR%%'=>$h($daftar),'%%LIVECHAT%%'=>$h($livechat),'%%CANONICAL%%'=>$h($canonical),'%%ROBOTS%%'=>$robots,'%%GSC_META%%'=>$gsc!==''?'<meta name="google-site-verification" content="'.$h($gsc).'">':'','%%AMPHTML%%'=>$amp!==''?'<link rel="amphtml" href="'.$h($amp).'">':'','%%ALT_PLAIN%%'=>$alt(),'%%ALT_ID_ID%%'=>$alt('id-ID'),'%%ALT_ID%%'=>$alt('id'),'%%ALT_EN%%'=>$alt('en'),'%%ALT_X_DEFAULT%%'=>$alt('x-default')];
echo strtr($template,$replace);
