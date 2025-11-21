<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php'; require_login();
if(!csrf_check($_POST['csrf']??'')){ http_response_code(400); echo "Bad CSRF"; exit; }
$title=trim($_POST['title']??''); if($title===''){ echo "Title required"; exit; }
$year=(int)($_POST['year']??0); $poster=trim($_POST['poster_url']??'');
$genres=trim($_POST['genres']??''); $desc=trim($_POST['description']??'');
$tg=trim($_POST['telegram_channel']??''); $pub=isset($_POST['is_published'])?1:0;
$rating=$_POST['rating']!==''? (float)$_POST['rating'] : null;
$votes=$_POST['votes']!==''? (int)$_POST['votes'] : null;
$slug=slugify($title);
db()->beginTransaction();
try{
  $st=db()->prepare("INSERT INTO movies (title,slug,year,poster_url,description,genres,telegram_channel,is_published,rating,votes) VALUES (?,?,?,?,?,?,?,?,?,?)");
  $st->execute([$title,$slug,$year?:null,$poster?:null,$desc?:null,$genres?:null,$tg?:null,$pub,$rating,$votes]);
  $id=db()->lastInsertId();
  foreach(['360p','480p','720p','1080p','2160p'] as $q){
    $key='q_'.str_replace('p','p',$q);
    $val=trim($_POST[$key]??'');
    if($val!==''){ $ins=db()->prepare("INSERT INTO movie_links (movie_id,quality,tg_url) VALUES (?,?,?)"); $ins->execute([$id,$q,$val]); }
  }
  db()->commit();
} catch(Exception $e){ db()->rollBack(); throw $e; }
header('Location: /admin/movie_list.php'); exit;
