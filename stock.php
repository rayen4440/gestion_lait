<?php
session_start();
include('config.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

$sqlLait = "SELECT SUM(quantite) AS total_lait FROM lait";
$resultLait = $conn->query($sqlLait);
$rowLait = $resultLait->fetch_assoc();
$total_lait = $rowLait['total_lait'] ?? 0;

$sqlAlim = "SELECT SUM(quantite) AS total_alimentation FROM alimentation";
$resultAlim = $conn->query($sqlAlim);
$rowAlim = $resultAlim->fetch_assoc();
$total_alimentation = $rowAlim['total_alimentation'] ?? 0;
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8" />
<title><?= $lang === 'ar' ? 'مخزون الحليب والعلف' : 'Stock de lait et alimentation' ?></title>
<style>
  body {
    background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 40px;
    direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
    text-align: center;
  }
  .stock-box {
    background-color: <?= $dark_mode ? '#1e1e1e' : '#f4f4f4' ?>;
    padding: 30px;
    margin: 20px auto;
    border-radius: 10px;
    width: 300px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
  }
  h1 {
    margin-bottom: 40px;
    color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
  }
  .quantity {
    font-size: 2.8rem;
    font-weight: bold;
    margin: 15px 0;
    color: <?= $dark_mode ? '#80d080' : '#0056b3' ?>;
  }
</style>
</head>
<body>

<h1><?= $lang === 'ar' ? 'مخزون الحليب والعلف' : 'Stock de lait et alimentation' ?></h1>

<div class="stock-box">
  <div><?= $lang === 'ar' ? 'كمية الحليب المتوفرة' : 'Quantité de lait disponible' ?></div>
  <div class="quantity"><?= number_format($total_lait, 2) ?> <?= $lang === 'ar' ? 'لتر' : 'litres' ?></div>
</div>

<div class="stock-box">
  <div><?= $lang === 'ar' ? 'كمية العلف المتوفرة' : 'Quantité d\'alimentation disponible' ?></div>
  <div class="quantity"><?= number_format($total_alimentation, 2) ?> <?= $lang === 'ar' ? 'كيلوغرام' : 'kg' ?></div>
</div>

</body>
</html>
