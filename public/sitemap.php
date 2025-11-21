<?php
require_once __DIR__ . '/../app/db.php';
header('Content-Type: application/xml; charset=utf-8');
$base = APP_URL ?: (($_SERVER['REQUEST_SCHEME']??'https').'://'.$_SERVER['HTTP_HOST']);
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
echo "<url><loc>{$base}/</loc><changefreq>daily</changefreq></url>\n";
$st = db()->query("SELECT slug,updated_at FROM movies WHERE is_published=1 ORDER BY updated_at DESC LIMIT 5000");
while($r=$st->fetch()){
  $loc = htmlspecialchars($base.'/movie/'.$r['slug'], ENT_XML1);
  $lastmod = gmdate('Y-m-d\TH:i:s\Z', strtotime($r['updated_at']));
  echo "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq></url>\n";
}
echo "</urlset>";
