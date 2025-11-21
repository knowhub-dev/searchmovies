<?php
require_once __DIR__ . '/../app/db.php';
$slug = $_GET['slug'] ?? '';
$st = db()->prepare("SELECT * FROM movies WHERE slug=:s AND is_published=1"); $st->execute([':s'=>$slug]); $movie=$st->fetch();
if(!$movie){ http_response_code(404); echo "Not found"; exit; }
$ls = db()->prepare("SELECT quality,tg_url FROM movie_links WHERE movie_id=:id ORDER BY FIELD(quality,'2160p','1080p','720p','480p','360p')");
$ls->execute([':id'=>$movie['id']]); $links=$ls->fetchAll(PDO::FETCH_KEY_PAIR);
$title = $movie['title']." (".$movie['year'].") • ".APP_NAME;
$desc  = substr($movie['description'] ?? '',0,160);
$poster= $movie['poster_url'] ?: 'https://placehold.co/1200x630/png';
$jsonld = [
  "@context"=>"https://schema.org",
  "@type"=>"Movie",
  "name"=>$movie['title'],
  "datePublished"=> $movie['year'] ? (string)$movie['year'] : None,
  "aggregateRating"=> ($movie['rating']?["@type"=>"AggregateRating","ratingValue"=>$movie['rating'],"ratingCount"=>$movie['votes']?:0]:None),
  "image"=>$poster,
  "description"=>$movie['description'] ?: ""
];
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($title)?></title>
<meta name="description" content="<?=h($desc)?>">
<meta property="og:title" content="<?=h($title)?>">
<meta property="og:description" content="<?=h($desc)?>">
<meta property="og:image" content="<?=h($poster)?>">
<link rel="stylesheet" href="/assets/style.css">
<script src="https://cdn.tailwindcss.com"></script>
<script type="application/ld+json"><?=json_encode($jsonld, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)?></script>
</head><body class="bg-slate-950 text-slate-100">
<header class="border-b border-slate-800 sticky top-0 bg-slate-950/80 backdrop-blur">
  <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
    <a href="/" class="text-xl font-extrabold brand-grad">🎬 <?=h(APP_NAME)?></a>
    <a href="/" class="text-sm underline text-slate-300">Back</a>
  </div>
</header>
<main class="max-w-4xl mx-auto px-4 py-6">
  <div class="grid grid-cols-1 sm:grid-cols-[220px,1fr] gap-6">
    <img src="<?=h($poster)?>" alt="<?=h($movie['title'])?> poster" class="w-full sm:w-[220px] rounded-2xl border border-slate-800 object-cover">
    <div>
      <h1 class="text-3xl font-bold"><?=h($movie['title'])?></h1>
      <div class="mt-1 flex items-center gap-3 text-slate-300">
        <span class="footer-tag">📅 <?=h($movie['year'])?></span>
        <span class="footer-tag">🎞️ Movie</span>
        <?php if($movie['rating']): ?><span class="footer-tag">⭐ <?=h($movie['rating'])?> <?php if($movie['votes']):?>(<?=h($movie['votes'])?> votes)<?php endif; ?></span><?php endif; ?>
      </div>
      <div class="mt-6">
        <h2 class="text-xl font-semibold">Description</h2>
        <p class="mt-2 text-slate-300 leading-relaxed"><?=nl2br(h($movie['description']))?></p>
      </div>
      <?php if($movie['genres']): ?>
      <div class="mt-6">
        <h3 class="text-lg font-semibold">Genres</h3>
        <div class="mt-2 flex flex-wrap gap-2">
          <?php foreach (array_map('trim', explode(',', $movie['genres'])) as $g): if(!$g) continue; ?>
            <span class="footer-tag">🎬 <?=h($g)?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      <div class="mt-8">
        <h3 class="text-lg font-semibold">Available Downloads</h3>
        <div class="mt-3 flex flex-wrap gap-2">
          <?php foreach($links as $q=>$u): $go="/go.php?movie_id=".$movie['id']."&quality=".urlencode($q); ?>
            <a class="inline-flex items-center gap-2 rounded-xl border border-slate-700 px-3 py-2 hover:border-sky-600" href="<?=$go?>" target="_blank" rel="nofollow noopener">
              <span class="font-medium"><?=h($q)?></span> ↗
            </a>
          <?php endforeach; ?>
        </div>
        <div class="text-slate-400 text-sm mt-3">Channel: <?=h($movie['telegram_channel'] ?: '—')?></div>
      </div>
    </div>
  </div>
</main>
<footer class="border-t border-slate-800">
  <div class="max-w-4xl mx-auto px-4 py-6 text-slate-400 text-sm flex flex-wrap items-center gap-3">
    <span>© 2025 <?=h(APP_NAME)?></span>
    <span class="footer-tag">Designed by <span class="font-semibold brand-grad">SamDevX</span></span>
    <span class="footer-tag">Powered by <a href="https://knowhub.uz" target="_blank" class="underline">KnowHub.uz</a></span>
  </div>
</footer>
</body></html>
