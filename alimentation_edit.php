<?php
session_start();
include('config.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang_code = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Vérifier l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: alimentation.php");
    exit();
}
$id = intval($_GET['id']);

// Récupérer alimentation
$stmt = $conn->prepare("SELECT * FROM alimentation WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$alimentation = $result->fetch_assoc();
$stmt->close();

if (!$alimentation) {
    header("Location: alimentation.php");
    exit();
}

$error = '';
$success = '';

// Récupérer la liste des fournisseurs
$fournisseurs = $conn->query("SELECT * FROM fournisseur ORDER BY nom ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_aliment = trim($_POST['type_aliment'] ?? '');
    $quantite = floatval($_POST['quantite'] ?? 0);
    $date_achat = $_POST['date_achat'] ?? '';
    $fournisseur_id = intval($_POST['fournisseur_id'] ?? 0);

    if ($type_aliment === '' || $quantite <= 0 || $date_achat === '' || $fournisseur_id <= 0) {
        $error = $lang_code=='fr' ? "Veuillez remplir tous les champs correctement." : "يرجى ملء جميع الحقول بشكل صحيح.";
    } else {
        $stmt = $conn->prepare("UPDATE alimentation SET type_aliment=?, quantite=?, date_achat=?, fournisseur_id=? WHERE id=?");
        $stmt->bind_param("sdssi", $type_aliment, $quantite, $date_achat, $fournisseur_id, $id);
        if ($stmt->execute()) {
            $success = $lang_code=='fr' ? "Mise à jour effectuée avec succès." : "تم التحديث بنجاح.";
            $alimentation['type_aliment'] = $type_aliment;
            $alimentation['quantite'] = $quantite;
            $alimentation['date_achat'] = $date_achat;
            $alimentation['fournisseur_id'] = $fournisseur_id;
        } else {
            $error = $lang_code=='fr' ? "Erreur lors de la mise à jour." : "حدث خطأ أثناء التحديث.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang_code=='fr'?'Modifier Alimentation':'✏️ تعديل علف' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    margin:20px;
    direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang_code==='ar'?'right':'left' ?>;
}
h2 { color: <?= $dark_mode?'#28a745':'#007BFF' ?>; margin-bottom:15px; }
form label, form select, form input { display:block; margin-bottom:10px; width:100%; max-width:400px; }
form select, form input[type=date], form input[type=text], form input[type=number] { padding:6px 10px; border:1px solid <?= $dark_mode?'#444':'#ccc' ?>; border-radius:4px; background-color:<?= $dark_mode?'#222':'#fff' ?>; color:<?= $dark_mode?'#eee':'#222' ?>; }
button { padding:10px 18px; background-color:<?= $dark_mode?'#28a745':'#007BFF' ?>; color:white; border:none; border-radius:5px; font-weight:600; cursor:pointer; margin-top:10px; }
button:hover { background-color:<?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
.error { color:#d9534f; margin-bottom:10px; font-weight:600; }
.success { color:#28a745; margin-bottom:10px; font-weight:600; }
/* Bouton retour */
.btn-back { display:inline-block; padding:10px 20px; margin-top:15px; background-color:<?= $dark_mode?'#28a745':'#007BFF' ?>; color:#fff; text-decoration:none; font-weight:600; border-radius:6px; transition:0.3s; }
.btn-back:hover { background-color:<?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
</style>
</head>
<body>

<h2><?= $lang_code=='fr'?'Modifier Alimentation':'✏️ تعديل علف' ?></h2>

<?php if(!empty($error)): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if(!empty($success)): ?>
<div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST">
    <label><?= $lang_code=='fr'?'Type d\'aliment':'نوع العلف' ?></label>
    <input type="text" name="type_aliment" value="<?= htmlspecialchars($alimentation['type_aliment']) ?>" required>

    <label><?= $lang_code=='fr'?'Quantité':'الكمية' ?></label>
    <input type="number" step="0.01" name="quantite" value="<?= htmlspecialchars($alimentation['quantite']) ?>" required>

    <label><?= $lang_code=='fr'?'Date d\'achat':'تاريخ الشراء' ?></label>
    <input type="date" name="date_achat" value="<?= htmlspecialchars($alimentation['date_achat']) ?>" required>

    <label><?= $lang_code=='fr'?'Fournisseur':'المورد' ?></label>
    <select name="fournisseur_id" required>
        <option value=""><?= $lang_code=='fr'?'Sélectionnez un fournisseur':'اختر المورد' ?></option>
        <?php while($f = $fournisseurs->fetch_assoc()): ?>
        <option value="<?= $f['id'] ?>" <?= $alimentation['fournisseur_id']==$f['id']?'selected':'' ?>>
            <?= htmlspecialchars($f['nom']) ?>
        </option>
        <?php endwhile; ?>
    </select>

    <button type="submit"><?= $lang_code=='fr'?'Mettre à jour':'تحديث' ?></button>
</form>

<a href="alimentation.php" class="btn-back"><?= $lang_code=='fr'?'Retour à la liste ⬅️':'⬅️ العودة إلى القائمة' ?></a>

<?php include('footer.php'); ?>
</body>
</html>
