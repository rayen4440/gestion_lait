<?php
session_start();
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
