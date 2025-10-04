<?php
session_start();
include('config.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;
$error = '';

if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM paiement WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: paiement.php");
        exit();
    }
}

$sql = "SELECT p.id, e.nom, e.prenom, p.employe_id, p.mois, e.salaire_base, p.salaire_net, p.jours_absence 
        FROM paiement p 
        JOIN employes e ON p.employe_id = e.id 
        ORDER BY mois DESC, p.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang === 'ar' ? 'الأجور' : 'Paiements' ?></title>
<style>
body {
    background-color: <?= $dark_mode ? '#121212' : '#90D5FF' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    margin: 20px;
    direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
    text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
}
h2 { color: <?= $dark_mode ? '#fff' : '#000' ?>; text-align:center; margin-bottom:15px; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { border:1px solid <?= $dark_mode ? '#333' : '#000000ff' ?>; padding:10px; text-align:center; }
th { background-color:#1565C0; color:#fff; }
tr:{background-color:<?= $dark_mode?'#1e1e1e':'#1565C0' ?>;}
tr:hover { background-color: <?= $dark_mode ? '#333' : '#d6eaff' ?>; }
a.btn { display:inline-block; padding:6px 12px; border-radius:6px; font-weight:600; text-decoration:none; margin:2px; color:#fff; }
a.btn-add { background-color:#007BFF; }
a.btn-add:hover { background-color:#0056b3; }
a.btn-print { background-color:#6c757d; }
a.btn-print:hover { background-color:#5a6268; }
a.btn-modifier { background-color:#007BFF; }
a.btn-modifier:hover { background-color:#0056b3; }
a.btn-supprimer { background-color:#dc3545; }
a.btn-supprimer:hover { background-color:#a71d2a; }
@media print {
    body * { visibility:hidden; }
    table, table * { visibility:visible; }
    table { position:absolute; left:0; top:0; width:100%; }
    table th:last-child, table td:last-child { display:none; }
}
</style>
</head>
<body>

<h2><?= $lang === 'ar' ? '💵 قائمة المدفوعات' : 'Liste des paiements 💵' ?></h2>

<a href="paiement_add.php" class="btn btn-add"><?= $lang === 'ar' ? '➕ إضافة راتب جديد' : 'Ajouter Paiement ➕' ?></a>
<a href="#" onclick="window.print();" class="btn btn-print"><?= $lang === 'ar' ? '🖨️ طباعة' : 'Imprimer liste salaires 🖨️' ?></a>

<?php if ($result && $result->num_rows > 0): ?>
<table>
<thead>
<tr>
    <th><?= $lang === 'ar' ? 'الاسم' : 'Nom' ?></th>
    <th><?= $lang === 'ar' ? 'اللقب' : 'Prénom' ?></th>
    <th><?= $lang === 'ar' ? 'الشهر' : 'Mois' ?></th>
    <th><?= $lang === 'ar' ? 'الراتب الأساسي' : 'Salaire brut' ?></th>
    <th><?= $lang === 'ar' ? 'الراتب الصافي' : 'Salaire net' ?></th>
    <th><?= $lang === 'ar' ? 'الغياب' : 'Absence' ?></th>
    <th><?= $lang === 'ar' ? 'الإجراءات' : 'Actions' ?></th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['nom']) ?></td>
    <td><?= htmlspecialchars($row['prenom']) ?></td>
    <td><?= htmlspecialchars($row['mois']) ?></td>
    <td><?= number_format($row['salaire_base'], 0) ?> TND</td>
    <td><?= number_format($row['salaire_net'], 0) ?> TND</td>
    <td><?= intval($row['jours_absence']) ?></td>
    <td>
        <a href="paiement_edit.php?modifier=<?= $row['id'] ?>" class="btn btn-modifier"><?= $lang=='ar' ? 'تعديل ✏️' : 'Modifier ✏️' ?></a>
        <a href="paiement.php?supprimer=<?= $row['id'] ?>" class="btn btn-supprimer" onclick="return confirm('<?= $lang=='ar' ? '🗑️ هل أنت متأكد من الحذف؟' : 'Êtes-vous sûr de supprimer ce paiement ?' ?>');"><?= $lang=='ar' ? '🗑️ حذف' : 'Supprimer 🗑️' ?></a>
        <a href="paiement_print.php?id=<?= $row['id'] ?>" class="btn btn-print" target="_blank"><?= $lang=='ar' ? '🖨️ طباعة الراتب' : 'Imprimer Extrait 🖨️' ?></a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p><?= $lang==='ar' ? 'لا توجد مدفوعات مسجلة.' : 'Aucun paiement enregistré.' ?></p>
<?php endif; ?>

<?php include('footer.php'); ?>
</body>
</html>
