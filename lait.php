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

// --- Supprimer lait ---
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $conn->prepare("DELETE FROM lait WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: lait.php");
    exit();
}

// --- Redirection modifier ---
if (isset($_GET['modifier'])) {
    $id = intval($_GET['modifier']);
    header("Location: lait_edit.php?id=$id");
    exit();
}

// --- Récupération des données ---
$result = $conn->query("SELECT * FROM lait ORDER BY date_lait DESC");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar'?'الحليب':'Lait' ?></title>
<style>
body {
    background-color: <?= $dark_mode ? '#121212':'#90D5FF' ?>;
    color: <?= $dark_mode ? '#eee':'#222' ?>;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    margin: 20px;
    direction: <?= $lang=='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang=='ar'?'right':'left' ?>;
}
h2 {
    color: <?= $dark_mode ? '#ffffffff':'#000000ff' ?>;
    margin-bottom: 15px;
    text-align: center;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid <?= $dark_mode ? '#333':'#000000ff' ?>;
    padding: 10px;
    text-align: center;
}
th {
    background-color: <?= $dark_mode ? '#1565C0':'#1565C0' ?>;
    color: #fff;
}
tr:{background-color:<?= $dark_mode?'#1e1e1e':'#1565C0' ?>;}
tr:hover { background-color: <?= $dark_mode ? '#333':'#e8f2ff' ?>; }

a.btn {
        display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    margin: 2px;
}
a.btn-ajouter {background-color:<?= $dark_mode ? '#007BFF' : '#007BFF' ?>;}
a.btn-ajouter:hover { background-color:<?= $dark_mode ? '#0056b3' : '#0056b3' ?>; }

a.btn-modifier { background-color: #007BFF; }
a.btn-modifier:hover { background-color: #0056b3; }

a.btn-supprimer { background-color: #dc3545; }
a.btn-supprimer:hover { background-color: #a71d2a; }

a.btn-print { background-color: #6c757d; }
a.btn-print:hover { background-color: #6c757d; }

@media print {
    body * { visibility: hidden; }
    table, table * { visibility: visible; }
    table { position: absolute; left: 0; top: 0; width: 100%; }
    table th:last-child, table td:last-child { display: none; }
}
</style>
</head>
<body>

<h2><?= $lang=='ar'?'🥛 كمية الحليب اليومية':'Quantité quotidienne de lait 🥛' ?></h2>

<a href="lait_add.php" class="btn btn-ajouter"><?= $lang=='ar'?'➕  أضف الحليب':'➕ Ajouter lait' ?></a>
<a href="#" onclick="window.print();" class="btn btn-print"><?= $lang=='ar'?'🖨️ طباعة':'🖨️ Imprimer' ?></a>

<table>
<thead>
<tr>
    <th><?= $lang=='ar'?'التاريخ':'Date' ?></th>
    <th><?= $lang=='ar'?'الكمية (لتر)':'Quantité (litres)' ?></th>
    <th><?= $lang=='ar'?'الإجراءات':'Actions' ?></th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['date_lait']) ?></td>
    <td><?= htmlspecialchars($row['quantite']) ?> litres</td>
    <td>
        <a href="lait_edit.php?id=<?= $row['id'] ?>" class="btn btn-modifier"><?= $lang=='ar'?'✏️ تعديل':'✏️ Modifier' ?></a>
        <a href="?supprimer=<?= $row['id'] ?>" class="btn btn-supprimer" onclick="return confirm('<?= $lang=='ar'?'هل أنت متأكد من الحذف؟':'Confirmer la suppression ?' ?>');"><?= $lang=='ar'?'🗑️ حذف':'🗑️ Supprimer' ?></a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
