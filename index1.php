<?php
session_start();
include('config.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $mp = $_POST['mp'];
    $_SESSION['lang'] = $_POST['lang'] ?? 'fr';
    $_SESSION['dark_mode'] = isset($_POST['dark_mode']);

    $result = mysql_query("SELECT * FROM user WHERE login = '$login' AND mdp = '$mp'");
    
    if ($user = mysql_fetch_array($result)) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = ($_SESSION['lang'] ?? 'fr') === 'ar' ? 'بيانات الدخول خاطئة' : 'Identifiants incorrects';
    }
}

$lang = $_SESSION['lang'] ?? 'fr';
$dark = $_SESSION['dark_mode'] ?? false;
?>
