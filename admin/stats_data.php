<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php'; require_login();
header('Content-Type: application/json; charset=utf-8');
$rows = db()->query("SELECT DATE(created_at) d, COUNT(*) c FROM clicks WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY d ASC")->fetchAll();
$map = []; foreach($rows as $r){ $map[$r['d']] = (int)$r['c']; }
$days=[]; $counts=[]; for($i=29;$i>=0;$i--){ $day=date('Y-m-d',strtotime("-$i days")); $days[]=$day; $counts[]=$map[$day]??0; }
$today = $map[date('Y-m-d')] ?? 0;
echo json_encode(['days'=>$days,'counts'=>$counts,'today'=>$today]);
