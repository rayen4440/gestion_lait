<?php
session_start();
include('config.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

if (!isset($_GET['id'])) die($lang=='ar' ? "معرّف غير صالح" : "ID invalide");

$id = intval($_GET['id']);
$stmt = $conn->prepare("
    SELECT p.id, e.nom, e.prenom,e.salaire_base, p.mois, p.salaire_net, p.cnss, p.jours_absence, 
           p.salaire_net + p.cnss AS salaire_brutee
    FROM paiement p
    JOIN employes e ON p.employe_id = e.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) die($lang=='ar' ? "الدفع غير موجود" : "Paiement introuvable");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar' ? 'إستخراج ' : 'Extrait' ?></title>
<style>
body {
    font-family:'Segoe UI', Tahoma, sans-serif;
    margin:40px;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    background-color: <?= $dark_mode ? '#121212' : '#afdefbff' ?>;
    direction: <?= $lang=='ar' ? 'rtl' : 'ltr' ?>;
    text-align: <?= $lang=='ar' ? 'right' : 'left' ?>;
}
h2 {
    text-align:center;
    margin-bottom:30px;
    color: <?= $dark_mode ? '#ffffff' : '#000' ?>;
    font-size:28px;
}
table {
    width:70%;
    margin:auto;
    border-collapse:collapse;
    box-shadow:0 0 12px rgba(0,0,0,0.2);
}
th, td {
    border:1px solid <?= $dark_mode ? '#333' : '#000000ff' ?>;
    padding:12px;
    font-size:16px;
}
th {
    background-color:<?= $dark_mode ? '#ffffffff' : '#ffffffff' ?>;
    color:black;
    text-align:center;
}
td {
    background-color: <?= $dark_mode ? '#1e1e1e' : '#fdfdfd' ?>;
}
td:last-child { font-weight:bold; }
@media print {
    body { margin:0; }
    table { width:100%; box-shadow:none; }
}
</style>
</head>
<body>

<h2><?= $lang=='ar' ? '💳 إستخراج بنكي للراتب' : 'Extrait du Salaire 💳' ?></h2>

<table>
<tr><th><?= $lang=='ar' ? 'الاسم' : 'Nom' ?></th><td><?= htmlspecialchars($row['nom']) ?></td></tr>
<tr><th><?= $lang=='ar' ? 'اللقب' : 'Prénom' ?></th><td><?= htmlspecialchars($row['prenom']) ?></td></tr>
<tr><th><?= $lang=='ar' ? 'الشهر' : 'Mois' ?></th><td><?= $row['mois'] ?></td></tr>
<tr><th><?= $lang=='ar' ? 'الراتب الأساسي' : 'Salaire de Base' ?></th><td><?= number_format($row['salaire_base'], 0) ?> TND</td></tr>
<tr><th><?= $lang=='ar' ? 'الغياب' : 'Absence' ?></th><td><?= $row['jours_absence'] ?></td></tr>
<tr><th><?= $lang=='ar' ? 'الراتب الإجمالي' : 'Salaire Brut' ?></th><td><?= number_format($row['salaire_brutee'], 0) ?> TND</td></tr>
<tr><th>CNSS (9.18%)</th><td><?= number_format($row['cnss'], 0) ?> TND</td></tr>
<tr><th><?= $lang=='ar' ? 'الراتب الصافي' : 'Salaire Net' ?></th><td><?= number_format($row['salaire_net'], 0) ?> TND</td></tr>
<tr><th><?= $lang=='ar' ? 'تاريخ الدفع' : 'Date de Paiement' ?></th><td><?= $row['mois'] ?></td></tr>
</table>

<script>
window.onload = function() { window.print(); }
</script>

</body>
</html>
