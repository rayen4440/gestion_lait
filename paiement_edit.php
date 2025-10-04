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

$result_employes = $conn->query("SELECT id, nom, prenom FROM employes ORDER BY nom ASC");

$id = intval($_GET['modifier'] ?? 0);
if ($id <= 0) {
    header("Location: paiement.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM paiement WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$modif = $stmt->get_result()->fetch_assoc();
$stmt->close();

$mois_input = '';
if (!empty($modif['mois'])) {
    $mois_input = date('Y-m', strtotime($modif['mois']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employe_id = intval($_POST['employe_id'] ?? 0);
    $mois_post = $_POST['mois'] ?? '';
    $salaire_net = floatval($_POST['salaire_net'] ?? 0);
    $cnss = floatval($_POST['cnss'] ?? 0);
    $jours_absence = intval($_POST['jours_absence'] ?? 0);

    if ($employe_id > 0 && !empty($mois_post)) {
        $mois = $mois_post . '-01';

        $stmt = $conn->prepare("UPDATE paiement SET employe_id=?, mois=?, salaire_net=?, cnss=?, jours_absence=? WHERE id=?");
        $stmt->bind_param("issdii", $employe_id, $mois, $salaire_net, $cnss, $jours_absence, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: paiement.php");
        exit();
    } else {
        $error = $lang == 'ar' ? "بيانات غير صالحة" : "Données invalides";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang == 'ar' ? 'تعديل الراتب' : 'Modifier Paiement' ?></title>
<style>
body {
    background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    margin: 20px;
    direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
    text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
}
h2 { color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>; margin-bottom:15px; }
form { max-width:450px; }
input, select, button {
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid <?= $dark_mode ? '#444' : '#ccc' ?>;
    background-color: <?= $dark_mode ? '#222' : '#fff' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
}
button {
    background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    color:white;
    border:none;
    font-weight:600;
    cursor:pointer;
}
button:hover {
    background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>;
}
.error { color:#d9534f; font-weight:600; margin-bottom:10px; }
.btn-back {
    display: inline-block;
    padding: 8px 16px;
    background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    margin-top: 15px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.btn-back:hover { transform: translateY(-2px); }
.btn-back:active { transform: translateY(0); }
</style>
</head>
<body>

<h2><?= $lang == 'ar' ? 'تعديل الراتب' : 'Modifier Paiement' ?></h2>

<?php if(!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <select name="employe_id" required>
        <option value=""><?= $lang == 'ar' ? 'اختر الموظف' : 'Sélectionnez un employé' ?></option>
        <?php while($emp = $result_employes->fetch_assoc()): ?>
            <option value="<?= $emp['id'] ?>" <?= $modif['employe_id'] == $emp['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($emp['nom'].' '.$emp['prenom']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="month" name="mois" value="<?= htmlspecialchars($mois_input) ?>" required>
    <input type="number" step="0.01" name="salaire_net" placeholder="<?= $lang == 'ar' ? 'الراتب الصافي' : 'Salaire Net' ?>" value="<?= htmlspecialchars($modif['salaire_net']) ?>" required>
    <input type="number" step="0.01" name="cnss" placeholder="CNSS" value="<?= htmlspecialchars($modif['cnss']) ?>" required>
    <input type="number" name="jours_absence" placeholder="<?= $lang == 'ar' ? 'أيام الغياب' : 'Jours d\'absence' ?>" value="<?= htmlspecialchars($modif['jours_absence']) ?>" required>

    <button type="submit"><?= $lang == 'ar' ? 'تحديث' : 'Mettre à jour' ?></button>
</form>

<a href="paiement.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

<?php include('footer.php'); ?>
</body>
</html>
