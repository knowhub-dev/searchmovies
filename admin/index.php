<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php'; require_login();
$stats = db()->query("SELECT COUNT(*) movies FROM movies")->fetch();
$totalClicks = db()->query("SELECT COUNT(*) clicks FROM clicks")->fetch();
$top = db()->query("SELECT m.title, m.slug, COUNT(c.id) c FROM clicks c JOIN movies m ON m.id=c.movie_id GROUP BY c.movie_id ORDER BY c DESC LIMIT 10")->fetchAll();
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard • <?=h(APP_NAME)?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head><body class="bg-slate-950 text-slate-100">
<header class="border-b border-slate-800">
  <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
    <strong>Admin • <?=h(APP_NAME)?></strong>
    <nav class="flex gap-2">
      <a class="underline" href="/admin/movie_list.php">Movies</a>
      <a class="underline" href="/admin/movie_new.php">+ Add</a>
      <a class="underline" href="/admin/logout.php">Logout</a>
    </nav>
  </div>
</header>
<main class="max-w-6xl mx-auto px-4 py-6">
  <div class="grid md:grid-cols-3 gap-4">
    <div class="rounded-2xl border border-slate-800 p-4"><div class="text-slate-400">Total movies</div><div class="text-3xl font-bold"><?=$stats['movies']?></div></div>
    <div class="rounded-2xl border border-slate-800 p-4"><div class="text-slate-400">Total clicks</div><div class="text-3xl font-bold"><?=$totalClicks['clicks']?></div></div>
    <div class="rounded-2xl border border-slate-800 p-4"><div class="text-slate-400">Today</div><div class="text-3xl font-bold" id="todayClicks">—</div></div>
  </div>
  <div class="mt-6 grid lg:grid-cols-3 gap-4">
    <div class="rounded-2xl border border-slate-800 p-4 lg:col-span-2">
      <div class="mb-2 font-semibold">Daily Clicks (last 30 days)</div>
      <canvas id="dailyChart" height="120"></canvas>
    </div>
    <div class="rounded-2xl border border-slate-800 p-4">
      <div class="mb-2 font-semibold">Top Movies</div>
      <ol class="list-decimal pl-4 space-y-1">
      <?php foreach($top as $t): ?>
        <li><?=$t['title']?> — <a class="underline" href="/movie/<?=$t['slug']?>" target="_blank">view</a> (<?=$t['c']?>)</li>
      <?php endforeach; ?>
      </ol>
    </div>
  </div>
</main>
<script>
fetch('/admin/stats_data.php').then(r=>r.json()).then(d=>{
  document.getElementById('todayClicks').textContent = d.today;
  new Chart(document.getElementById('dailyChart').getContext('2d'), {
    type: 'line',
    data: { labels: d.days, datasets: [{ label: 'Clicks', data: d.counts }] },
    options: { responsive: true, tension:.3, scales:{ y:{ beginAtZero:true } } }
  });
});
</script>
</body></html>
