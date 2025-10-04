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
$error = '';

$employes = $conn->query("SELECT id, nom, prenom FROM employes ORDER BY nom ASC");

$id = intval($_GET['modifier'] ?? 0);
if ($id <= 0) {
    header("Location: presence.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM presence WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$presence = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employe_id = intval($_POST['employe_id'] ?? 0);
    $date = $_POST['date'] ?? '';
    $statut = $_POST['statut'] ?? '';

    if ($employe_id > 0 && in_array($statut, ['present','absent']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $stmt = $conn->prepare("UPDATE presence SET employe_id=?, date=?, statut=? WHERE id=?");
        $stmt->bind_param("issi", $employe_id, $date, $statut, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: presence.php");
        exit();
    } else {
        $error = $lang_code=='fr' ? "Données invalides" : "بيانات غير صالحة";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang_code=='fr' ? 'Modifier Présence' : 'تعديل الحضور' ?></title>
<style>
body { 
    background-color: <?= $dark_mode?'#121212':'#fff' ?>; 
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',Tahoma,sans-serif;
    margin:20px;
    direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang_code==='ar'?'right':'left' ?>;
}
h2 { color: <?= $dark_mode?'#28a745':'#007BFF' ?>; margin-bottom:15px; }
form { max-width:450px; }
input, select, button {
    width:100%; padding:10px; margin-bottom:15px;
    border-radius:6px; border:1px solid <?= $dark_mode?'#444':'#ccc' ?>;
    background-color: <?= $dark_mode?'#222':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
}
button {
    background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
    color:white; border:none; font-weight:600; cursor:pointer;
}
button:hover { background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
.error { color:#d9534f; font-weight:600; margin-bottom:10px; }
  .btn-back {
            display: inline-block;
            padding: 8px 16px;
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF'  ?>;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-back:hover {
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            transform: translateY(-2px);
        }
        .btn-back:active {
            transform: translateY(0);
        }
</style>
</head>
<body>

<h2><?= $lang_code=='fr' ? 'Modifier Présence' : 'تعديل الحضور' ?></h2>

<?php if(!empty($error)) echo "<div class='error'>".htmlspecialchars($error)."</div>"; ?>

<form method="post">
    <select name="employe_id" required>
        <option value=""><?= $lang_code=='fr'?'Sélectionnez un employé':'اختر الموظف' ?></option>
        <?php while($emp=$employes->fetch_assoc()): ?>
            <option value="<?= $emp['id'] ?>" <?= $presence['employe_id']==$emp['id']?'selected':'' ?>>
                <?= htmlspecialchars($emp['nom'].' '.$emp['prenom']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="date" name="date" value="<?= htmlspecialchars($presence['date']) ?>" required>

    <select name="statut" required>
        <option value="present" <?= $presence['statut']=='present'?'selected':'' ?>><?= $lang_code=='fr'?'Présent':'حاضر' ?></option>
        <option value="absent" <?= $presence['statut']=='absent'?'selected':'' ?>><?= $lang_code=='fr'?'Absent':'غائب' ?></option>
    </select>

    <button type="submit"><?= $lang_code=='fr'?'Mettre à jour':'تحديث' ?></button>
</form>

  <a href="presence.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

<?php include('footer.php'); ?>
</body>
</html>
