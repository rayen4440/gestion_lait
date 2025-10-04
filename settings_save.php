<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['lang'] = $_POST['lang'] ?? 'fr';
    $_SESSION['dark_mode'] = isset($_POST['dark_mode']) && $_POST['dark_mode'] == '1';
}

header("Location: settings.php");
exit();
