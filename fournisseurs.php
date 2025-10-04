<?php
include 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$lang_code = $_SESSION['lang'] ?? 'fr';
include "lang_$lang_code.php";

if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $produits = $_POST['produits'];

    $stmt = $conn->prepare("INSERT INTO fournisseurs (nom, telephone, produits) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $telephone, $produits);
    $stmt->execute();
}

if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $conn->query("DELETE FROM fournisseurs WHERE id = $id");
}

$fournisseurs = $conn->query("SELECT * FROM fournisseurs ORDER BY nom ASC");
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head><meta charset="UTF-8"><title><?= $lang['suppliers'] ?></title></head>
<body>
<div style="text-align:right; padding:10px;">
    <a href="set_lang.php?lang=fr">Français</a> | 
    <a href="set_lang.php?lang=ar">عربي</a>
</div>

<h2><?= $lang['suppliers'] ?></h2>

<form method="post" action="">
    <label><?= $lang_code=='fr' ? 'Nom' : 'الاسم' ?>:</label><br>
    <input type="text" name="nom" required><br>

    <label><?= $lang_code=='fr' ? 'Téléphone' : 'الهاتف' ?>:</label><br>
    <input type="text" name="telephone" required><br>

    <label><?= $lang_code=='fr' ? 'Produits' : 'المنتجات' ?>:</label><br>
    <textarea name="produits"></textarea><br><br>

    <button type="submit" name="ajouter"><?= $lang_code=='fr' ? 'Ajouter' : 'أضف' ?></button>
</form>

<hr>

<table border="1" cellpadding="5">
<tr><th>ID</th><th><?= $lang['suppliers'] ?></th><th><?= $lang_code=='fr' ? 'Téléphone' : 'الهاتف' ?></th><th><?= $lang_code=='fr' ? 'Produits' : 'المنتجات' ?></th><th>Action</th></tr>
<?php while($row = $fournisseurs->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['nom']) ?></td>
    <td><?= htmlspecialchars($row['telephone']) ?></td>
    <td><?= htmlspecialchars($row['produits']) ?></td>
    <td><a href="?supprimer=<?= $row['id'] ?>" onclick="return confirm('Confirm?')"><?= $lang_code=='fr' ? 'Supprimer' : 'حذف' ?></a></td>
</tr>
<?php } ?>
</table>

<a href="dashboard.php"><?= $lang_code=='fr' ? 'Retour' : 'عودة' ?></a>
</body>
</html>
