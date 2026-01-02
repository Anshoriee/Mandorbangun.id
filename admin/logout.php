<?php
require_once '../includes/config.php';

// Hancurkan semua data session
$_SESSION = array();
session_destroy();

// Redirect ke halaman login
header("Location: " . BASE_URL . "admin/login.php");
exit;
?>
