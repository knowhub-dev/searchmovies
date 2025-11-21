<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php'; require_login();
$rows = db()->query("SELECT id,title,slug,year,is_published,created_at FROM movies ORDER BY created_at DESC")->fetchAll();
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Movies • <?=h(APP_NAME)?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head><body class="bg-slate-950 text-slate-100">
<header class="border-b border-slate-800">
  <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
    <strong>Movies</strong>
    <nav class="flex gap-2">
      <a class="underline" href="/admin/index.php">Dashboard</a>
      <a class="underline" href="/admin/movie_new.php">+ Add</a>
      <a class="underline" href="/admin/logout.php">Logout</a>
    </nav>
  </div>
</header>
<main class="max-w-6xl mx-auto px-4 py-6">
  <div class="overflow-x-auto rounded-2xl border border-slate-800">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-900 text-slate-300">
        <tr><th class="text-left p-2">Title</th><th class="p-2">Year</th><th class="p-2">Status</th><th class="p-2">Created</th><th class="p-2">Public</th></tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr class="border-t border-slate-800">
          <td class="p-2"><?=h($r['title'])?></td>
          <td class="p-2 text-center"><?=h($r['year'])?></td>
          <td class="p-2 text-center"><?=$r['is_published']?'Published':'Draft'?></td>
          <td class="p-2 text-center"><?=h($r['created_at'])?></td>
          <td class="p-2 text-center"><a class="underline" href="/movie/<?=h($r['slug'])?>" target="_blank">Open</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body></html>
