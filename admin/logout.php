<?php
require_once __DIR__ . '/../app/auth.php'; session_start_custom(); session_destroy(); header('Location: /admin/login.php'); exit;
