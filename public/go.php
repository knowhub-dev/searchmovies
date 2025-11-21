<?php
require_once __DIR__ . '/../app/db.php';
$movie_id = (int)($_GET['movie_id'] ?? 0);
$quality = $_GET['quality'] ?? '';
if(!$movie_id || !$quality){ http_response_code(400); echo "Bad request"; exit; }
$st = db()->prepare("SELECT tg_url FROM movie_links WHERE movie_id=:m AND quality=:q");
$st->execute([':m'=>$movie_id,':q'=>$quality]); $row=$st->fetch();
if(!$row){ http_response_code(404); echo "Link not found"; exit; }
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '',0,255);
$ref = substr($_SERVER['HTTP_REFERER'] ?? '',0,255);
$ip  = $_SERVER['REMOTE_ADDR'] ?? null; $ipbin = $ip ? @inet_pton($ip) : null;
$log = db()->prepare("INSERT INTO clicks (movie_id,quality,ip,user_agent,referrer) VALUES (:m,:q,:ip,:ua,:rf)");
$log->execute([':m'=>$movie_id,':q'=>$quality,':ip'=>$ipbin,':ua'=>$ua,':rf'=>$ref]);
header('Location: '.$row['tg_url'], true, 302); exit;
