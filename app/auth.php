<?php
require_once __DIR__ . '/db.php';
function session_start_custom(){
  if(session_status()===PHP_SESSION_NONE){
    session_set_cookie_params(['lifetime'=>SESSION_LIFETIME,'path'=>'/','secure'=>false,'httponly'=>true,'samesite'=>'Lax']);
    session_name(SESSION_COOKIE); session_start();
  }
}
function is_logged_in(){ session_start_custom(); return !empty($_SESSION['admin_id']); }
function require_login(){ if(!is_logged_in()){ header('Location: /admin/login.php'); exit; } }
function csrf_token(){ session_start_custom(); if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_check($t){ session_start_custom(); return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'],$t); }
