<?php
session_start();
include('header.php');
include('footer.php');
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Ajouter lait
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $date_lait = $_POST['date_lait'];
    $quantite = floatval($_POST['quantite']);
    $stmt = $conn->prepare("INSERT INTO lait (date_lait, quantite) VALUES (?, ?)");
    $stmt->bind_param("sd", $date_lait, $quantite);
    $stmt->execute();
    header("Location: lait.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar'?'إضافة كمية الحليب':'Ajouter quantité de lait' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',sans-serif;
    margin:20px;
    direction: <?= $lang==='ar'?'rtl':'ltr' ?>;
}
form { max-width:400px; }
input,button {
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid <?= $dark_mode?'#444':'#ccc' ?>;
    background-color:<?= $dark_mode?'#222':'#fff' ?>;
    color:<?= $dark_mode?'#eee':'#222' ?>;
}
button.btn {
    background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
    color:white;
    border:none;
    font-weight:600;
    cursor:pointer;
}
button.btn:hover {
    background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>;
}
a.btn-back {
    display:inline-block;
    padding:10px 20px;
    margin-top:10px;
    background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
    color:white;
    text-decoration:none;
    font-weight:600;
    border-radius:6px;
    transition:0.3s;
}
a.btn-back:hover {
    background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
}
</style>
</head>
<body>

<h2><?= $lang=='ar'?'إضافة كمية الحليب':'Ajouter quantité de lait' ?></h2>

<form method="post">
    <input type="date" name="date_lait" required>
    <input type="number" step="0.01" name="quantite" placeholder="<?= $lang=='ar'?'الكمية':'Quantité' ?>" required>
    <button type="submit" class="btn"><?= $lang=='ar'?'➕ إضافة':'Ajouter ➕' ?></button>
</form>

<a href="lait.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

</body>
</html>
