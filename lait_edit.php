<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');
if (!isset($_GET['id'])) {
    header("Location: lait.php");
    exit();
}

$lang_code = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

$id = intval($_GET['id']);

// Récupérer la donnée
$stmt = $conn->prepare("SELECT * FROM lait WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$lait = $stmt->get_result()->fetch_assoc();

if (!$lait) {
    header("Location: lait.php");
    exit();
}

// Modifier
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $date_lait = $_POST['date_lait'];
    $quantite = floatval($_POST['quantite']);
    $stmt = $conn->prepare("UPDATE lait SET date_lait=?, quantite=? WHERE id=?");
    $stmt->bind_param("sdi",$date_lait,$quantite,$id);
    $stmt->execute();
    header("Location: lait.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang_code=='ar'?'تعديل كمية الحليب':'Modifier quantité de lait' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    margin: 20px;
    direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang_code==='ar'?'right':'left' ?>;
}
h2 { color: <?= $dark_mode?'#28a745':'#007BFF' ?>; margin-bottom:20px; }
form { max-width:400px; }
input,button { width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid <?= $dark_mode?'#444':'#ccc' ?>; background-color:<?= $dark_mode?'#222':'#fff' ?>; color:<?= $dark_mode?'#eee':'#222' ?>; }
button { background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>; color:white; border:none; font-weight:600; cursor:pointer; }
button:hover { background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
a.btn-back { display:inline-block; margin-top:10px; padding:10px 15px; background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>; color:#fff; text-decoration:none; border-radius:6px; font-weight:600; }
a.btn-back:hover { background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
</style>
</head>
<body>

<h2><?= $lang_code=='ar'?'تعديل كمية الحليب':'Modifier quantité de lait' ?></h2>

<form method="post">
<label><?= $lang_code=='ar'?'التاريخ':'Date' ?></label>
<input type="date" name="date_lait" value="<?= $lait['date_lait'] ?>" required>
<label><?= $lang_code=='ar'?'الكمية (لتر)':'Quantité (litres)' ?></label>
<input type="number" step="0.01" name="quantite" value="<?= $lait['quantite'] ?>" required>
<button type="submit"><?= $lang_code=='ar'?'تحديث':'Mettre à jour' ?></button>
</form>

<a href="lait.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

</body>
</html>
