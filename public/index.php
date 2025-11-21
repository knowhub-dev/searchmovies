<?php
require_once __DIR__ . '/../app/db.php';
$q = trim($_GET['q'] ?? '');
$sql = "SELECT id,title,slug,poster_url,year,genres,rating,votes FROM movies WHERE is_published=1";
$p=[]; if($q!==''){ $sql.=" AND (title LIKE :q OR genres LIKE :q)"; $p[':q']="%$q%"; }
$sql.=" ORDER BY created_at DESC LIMIT 60";
$st = db()->prepare($sql); $st->execute($p); $movies=$st->fetchAll();
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h(APP_NAME)?> — Fresh movies</title>
<meta name="description" content="Find movies and jump to Telegram downloads by quality.">
<link rel="stylesheet" href="/assets/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head><body class="bg-slate-950 text-slate-100">
<header class="border-b border-slate-800 sticky top-0 bg-slate-950/80 backdrop-blur">
  <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
    <a href="/" class="text-xl font-extrabold brand-grad">🎬 <?=h(APP_NAME)?></a>
    <form class="flex gap-2 w-full max-w-lg" method="get">
      <input type="search" name="q" value="<?=h($q)?>" placeholder="Search movies..." class="flex-1 rounded-xl bg-slate-900 border border-slate-700 px-3 py-2 outline-none focus:ring focus:ring-sky-600">
      <button class="rounded-xl border border-slate-700 px-4 py-2">Search</button>
    </form>
  </div>
</header>
<main class="max-w-6xl mx-auto px-4 py-6">
  <div class="flex items-end justify-between">
    <h1 class="text-2xl font-bold">Fresh Movies</h1>
    <div class="text-slate-400 text-sm"><?=count($movies)?> results</div>
  </div>
  <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php foreach($movies as $m): ?>
      <a href="/movie/<?=h($m['slug'])?>" class="block rounded-2xl overflow-hidden border border-slate-800 hover:border-sky-600 transition">
        <img src="<?=h($m['poster_url'] ?: 'https://placehold.co/600x900/png')?>" alt="<?=h($m['title'])?> poster" class="w-full h-80 object-cover">
        <div class="p-3">
          <div class="text-sm text-slate-400"><?=h($m['year'])?> • <?=h($m['genres'])?></div>
          <div class="mt-1 font-semibold"><?=h($m['title'])?></div>
          <?php if($m['rating']): ?><div class="mt-1 text-amber-300 text-sm">★ <?=h($m['rating'])?> <?php if($m['votes']):?>(<?=h($m['votes'])?>)<?php endif; ?></div><?php endif; ?>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</main>
<footer class="border-t border-slate-800">
  <div class="max-w-6xl mx-auto px-4 py-6 text-slate-400 text-sm flex flex-wrap items-center gap-3">
    <span>© 2025 <?=h(APP_NAME)?></span>
    <span class="footer-tag">Designed by <span class="font-semibold brand-grad">SamDevX</span></span>
    <span class="footer-tag">Powered by <a href="https://knowhub.uz" target="_blank" rel="noopener" class="underline">KnowHub.uz</a></span>
  </div>
</footer>
</body></html>
