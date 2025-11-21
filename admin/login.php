<?php
require_once __DIR__ . '/../app/db.php'; require_once __DIR__ . '/../app/auth.php';
session_start_custom(); $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  $st=db()->prepare("SELECT * FROM admins WHERE email=:e"); $st->execute([':e'=>$email]); $adm=$st->fetch();
  if($adm && password_verify($pass,$adm['password_hash'])){ $_SESSION['admin_id']=$adm['id']; header('Location: /admin/index.php'); exit; }
  $error='Invalid credentials';
}
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login • <?=h(APP_NAME)?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head><body class="bg-slate-950 text-slate-100">
<div class="min-h-screen grid place-items-center p-4">
  <form method="post" class="w-full max-w-sm rounded-2xl border border-slate-800 p-5">
    <h1 class="text-xl font-bold mb-4">Admin Login</h1>
    <?php if($error): ?><div class="mb-3 text-red-300"><?=h($error)?></div><?php endif; ?>
    <input name="email" type="email" placeholder="Email" class="w-full mb-2 rounded-xl bg-slate-900 border border-slate-700 px-3 py-2" required>
    <input name="password" type="password" placeholder="Password" class="w-full mb-4 rounded-xl bg-slate-900 border border-slate-700 px-3 py-2" required>
    <button class="w-full rounded-xl border border-slate-700 px-4 py-2">Sign in</button>
  </form>
</div>
</body></html>
