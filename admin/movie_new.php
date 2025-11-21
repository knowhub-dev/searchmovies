<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php'; require_login(); $csrf=csrf_token();
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add movie • <?=h(APP_NAME)?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head><body class="bg-slate-950 text-slate-100">
<header class="border-b border-slate-800">
  <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
    <strong>Add movie</strong>
    <nav><a class="underline" href="/admin/movie_list.php">Back</a></nav>
  </div>
</header>
<main class="max-w-4xl mx-auto px-4 py-6">
  <form method="post" action="/admin/movie_save.php" class="grid gap-3">
    <input type="hidden" name="csrf" value="<?=$csrf?>">
    <input name="title" placeholder="Title" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2" required>
    <div class="grid grid-cols-2 gap-3">
      <input type="number" name="year" placeholder="Year" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
      <input name="poster_url" placeholder="Poster URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
    </div>
    <div class="grid grid-cols-2 gap-3">
      <input name="genres" placeholder="Action, Drama" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
      <input name="telegram_channel" placeholder="@yourchannel" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
    </div>
    <div class="grid grid-cols-2 gap-3">
      <input name="rating" placeholder="Rating (e.g. 6.8)" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
      <input name="votes" placeholder="Votes (e.g. 44)" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
    </div>
    <textarea name="description" rows="5" placeholder="Description" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2"></textarea>
    <fieldset class="rounded-2xl border border-slate-800 p-3">
      <legend class="px-2 text-slate-300">Telegram links by quality</legend>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <input name="q_360p" placeholder="360p URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
        <input name="q_480p" placeholder="480p URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
        <input name="q_720p" placeholder="720p URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
        <input name="q_1080p" placeholder="1080p URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
        <input name="q_2160p" placeholder="2160p URL" class="rounded-xl bg-slate-900 border border-slate-700 px-3 py-2">
      </div>
    </fieldset>
    <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" checked> Published</label>
    <button class="rounded-xl border border-slate-700 px-4 py-2">Save</button>
  </form>
</main>
</body></html>
