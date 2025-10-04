<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) header("Location: login.php");

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

if (!isset($_GET['employe_id']) || !isset($_GET['mois'])) die($lang=='ar'?"⚠️ يجب اختيار موظف وشهر":"⚠️ Employé et mois requis !");

$employe_id = intval($_GET['employe_id']);
$mois = $_GET['mois'];

$stmt = $conn->prepare("SELECT * FROM employes WHERE id=?");
$stmt->bind_param("i",$employe_id);
$stmt->execute();
$employe = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM paiement WHERE employe_id=? AND mois=?");
$stmt->bind_param("is",$employe_id,$mois);
$stmt->execute();
$paiement = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar'?'استخراج الراتب':'Extrait Salaire' ?></title>
<style>
body { background:<?= $dark_mode?'#121212':'#fff' ?>; color:<?= $dark_mode?'#eee':'#222' ?>; font-family:'Segoe UI',Tahoma,sans-serif; margin:20px;
       direction:<?= $lang=='ar'?'rtl':'ltr' ?>; text-align:<?= $lang=='ar'?'right':'left' ?>; }
h2,h3 { text-align:center; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th,td { border:1px solid <?= $dark_mode?'#333':'#ccc' ?>; padding:8px; text-align:center; }
th { background:#1565C0; color:#fff; }
tr:nth-child(even){background:<?= $dark_mode?'#1e1e1e':'#f9f9f9' ?>;}
.no-print{ margin-top:20px; text-align:center;}
@media print{.no-print{display:none;}}
</style>
</head>
<body>

<h2><?= $lang=='ar'?'📄 كشف راتب':'📄 Extrait Salaire' ?></h2>
<h3><?= htmlspecialchars($employe['nom'].' '.$employe['prenom']).' - '.$mois ?></h3>

<?php if($paiement): ?>
<table>
<tr>
<th><?= $lang=='ar'?'الشهر':'Mois' ?></th>
<th><?= $lang=='ar'?'الراتب الخام':'Salaire Brut' ?></th>
<th><?= $lang=='ar'?'الراتب الصافي':'Salaire Net' ?></th>
<th><?= $lang=='ar'?'الغياب':'Absence' ?></th>
</tr>
<tr>
<td><?= $paiement['mois'] ?></td>
<td><?= $paiement['salaire_base'] ?></td>
<td><?= $paiement['salaire_net'] ?></td>
<td><?= $paiement['jours_absence'] ?></td>
</tr>
</table>
<?php else: ?>
<p style="text-align:center; color:red;"><?= $lang=='ar'?'❌ لا يوجد راتب لهذا الشهر':'❌ Aucun paiement trouvé pour ce mois' ?></p>
<?php endif; ?>

<div class="no-print">
<button onclick="window.print();">🖨️ <?= $lang=='ar'?'طباعة':'Imprimer' ?></button>
</div>

</body>
</html>
