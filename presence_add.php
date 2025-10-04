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

$employes = $conn->query("SELECT * FROM employes ORDER BY nom ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employe_id = intval($_POST['employe_id'] ?? 0);
    $date = $_POST['date_presence'] ?? '';
    $statut = $_POST['statut'] ?? '';

    if ($employe_id > 0 && in_array($statut, ['present', 'absent']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $stmtCheck = $conn->prepare("SELECT id FROM presence WHERE employe_id=? AND date=?");
        $stmtCheck->bind_param("is", $employe_id, $date);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();

        if ($resCheck->num_rows === 0) {
            $stmtCheck->close();
            $stmt = $conn->prepare("INSERT INTO presence (employe_id,date,statut) VALUES (?,?,?)");
            $stmt->bind_param("iss", $employe_id, $date, $statut);
            $stmt->execute();
            $stmt->close();
            header("Location: presence.php"); 
            exit();
        } else {
            $error = $lang_code == 'fr' ? "Cette présence existe déjà." : "هذا الحضور موجود مسبقًا.";
        }
    } else {
        $error = $lang_code == 'fr' ? "Données invalides." : "بيانات غير صالحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang_code=='fr'?'Ajouter Présence':'إضافة حضور' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family: 'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    margin:20px;
    direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang_code==='ar'?'right':'left' ?>;
}
h2 { color: <?= $dark_mode?'#28a745':'#007BFF' ?>; margin-bottom:15px; }
form label, form select, form input { display:block; margin-bottom:10px; width:100%; max-width:350px; }
form select, form input[type=date] { padding:6px 10px; border:1px solid <?= $dark_mode?'#444':'#ccc' ?>; border-radius:4px; background-color:<?= $dark_mode?'#222':'#fff' ?>; color:<?= $dark_mode?'#eee':'#222' ?>; }
button { padding:10px 18px; background-color:<?= $dark_mode?'#28a745':'#007BFF' ?>; color:white; border:none; border-radius:5px; font-weight:600; cursor:pointer; margin-top:10px; }
button:hover { background-color:<?= $dark_mode?'#1e7e34':'#0056b3' ?>; }
.error { color:#d9534f; margin-bottom:10px; font-weight:600; }

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
    background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>;
    transform: translateY(-2px);
}
.btn-back:active {
    transform: translateY(0);
}
</style>
</head>
<body>

<h2><?= $lang_code=='fr'?'Ajouter Présence':'إضافة حضور' ?></h2>

<?php if(!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <label><?= $lang_code=='fr'?'Employé':'الموظف' ?>:</label>
    <select name="employe_id" required>
        <?php while($emp=$employes->fetch_assoc()): ?>
            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nom'].' '.$emp['prenom']) ?></option>
        <?php endwhile; ?>
    </select>

    <label><?= $lang_code=='fr'?'Date':'التاريخ' ?>:</label>
    <input type="date" name="date_presence" required value="<?= date('Y-m-d') ?>">

    <label><?= $lang_code=='fr'?'Statut':'الحالة' ?>:</label>
    <select name="statut" required>
        <option value="present"><?= $lang_code=='fr'?'Présent':'حاضر' ?></option>
        <option value="absent"><?= $lang_code=='fr'?'Absent':'غائب' ?></option>
    </select>

    <button type="submit"><?= $lang_code=='fr'?'Ajouter ➕':'➕ أضف' ?></button>
</form>

<a href="presence.php" class="btn-back"><?= $lang_code=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

<?php include('footer.php'); ?>
</body>
</html>
