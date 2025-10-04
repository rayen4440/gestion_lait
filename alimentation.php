<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Supprimer alimentation
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $conn->prepare("DELETE FROM alimentation WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: alimentation.php");
    exit();
}

// Liste alimentation avec nom du fournisseur
$sql = "SELECT a.*, f.nom AS fournisseur_nom 
        FROM alimentation a 
        LEFT JOIN fournisseur f ON a.fournisseur_id = f.id
        ORDER BY a.date_achat DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar'?'🌾 قائمة العلف':'Liste de l\'alimentation 🌾' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#90D5FF' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',Tahoma,sans-serif;
    margin:20px;
    direction: <?= $lang=='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang=='ar'?'right':'left' ?>;
}
h2 { text-align:center; margin-bottom:20px;}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th, td {
    border:1px solid <?= $dark_mode?'#333':'#000000ff' ?>;
    padding:12px;
    text-align:center;
}
th {
    background-color: #1565C0;
    color:white;
}
tr:{background-color:<?= $dark_mode?'#1e1e1e':'#1565C0' ?>;}
tr:hover{background-color:<?= $dark_mode?'#333':'#d6eaff' ?>;}
a.btn { padding:6px 12px; border-radius:6px; color:#fff; text-decoration:none; font-weight:600; margin:0 3px; display:inline-block; }
a.btn-add { background-color: #007BFF; }
a.btn-add:hover { background-color:#0056b3; }
a.btn-modifier { background-color:#007BFF; }
a.btn-modifier:hover { background-color:#0056b3; }
a.btn-supprimer { background-color:#dc3545; }
a.btn-supprimer:hover { background-color:#a71d2a; }
a.btn-print { background-color:#6c757d; }
a.btn-print:hover { background-color:#6c757d; }
@media print {
    body * { visibility:hidden; }
    table, table * { visibility:visible; }
    table { position:absolute; left:0; top:0; width:100%; }
    table th:last-child, table td:last-child { display:none; }
}
</style>
</head>
<body>

<h2><?= $lang=='ar'?'🌾 قائمة العلف':'Liste de l\'alimentation 🌾' ?></h2>

<a href="alimentation_add.php" class="btn btn-add"><?= $lang=='ar'?'➕ إضافة علف':'Ajouter Alimentation ➕' ?></a>
<a href="#" onclick="window.print();" class="btn btn-print"><?= $lang=='ar'?'🖨️ طباعة':'Imprimer 🖨️' ?></a>

<table>
<thead>
<tr>
<th><?= $lang=='ar'?'نوع العلف':'Type d\'aliment' ?></th>
<th><?= $lang=='ar'?'الكمية':'Quantité' ?></th>
<th><?= $lang=='ar'?'تاريخ الشراء':'Date d\'achat' ?></th>
<th><?= $lang=='ar'?'المورد':'Fournisseur' ?></th>
<th><?= $lang=='ar'?'الإجراءات':'Actions' ?></th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['type_aliment']) ?></td>
<td><?= number_format($row['quantite']) ?> Kg</td>
<td><?= htmlspecialchars($row['date_achat']) ?></td>
<td><?= htmlspecialchars($row['fournisseur_nom'] ?? '') ?></td>
<td>
<a href="alimentation_edit.php?id=<?= $row['id'] ?>" class="btn btn-modifier"><?= $lang=='ar'?'✏️ تعديل':'Modifier ✏️' ?></a>
<a href="?supprimer=<?= $row['id'] ?>" class="btn btn-supprimer" onclick="return confirm('<?= $lang=='ar'?'هل أنت متأكد من الحذف؟':'Confirmer la suppression ?' ?>');"><?= $lang=='ar'?'🗑️ حذف':'Supprimer 🗑️' ?></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
