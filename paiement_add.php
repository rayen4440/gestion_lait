<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;
$cnss_taux = 0.094; // 9.4%

$error = '';
$success = '';
$mois_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employe_id = intval($_POST['employe_id']);
    $mois_input = trim($_POST['mois']) ?? '';
    $mois = $mois_input ? $mois_input . '-01' : null;

    $stmtEmp = $conn->prepare("SELECT salaire_base FROM employes WHERE id=?");
    $stmtEmp->bind_param("i", $employe_id);
    $stmtEmp->execute();
    $resEmp = $stmtEmp->get_result()->fetch_assoc();
    $stmtEmp->close();

    if ($resEmp) {
        $jours_absence = 0;
        $jours_presence = 0;

        $stmtPres = $conn->prepare("SELECT statut FROM presence WHERE employe_id=? AND DATE_FORMAT(date,'%Y-%m')=?");
        $stmtPres->bind_param("is", $employe_id, $mois_input);
        $stmtPres->execute();
        $resPres = $stmtPres->get_result();

        while ($rowP = $resPres->fetch_assoc()) {
            if ($rowP['statut'] === 'absent') $jours_absence++;
            if ($rowP['statut'] === 'present') $jours_presence++;
        }
        $stmtPres->close();

        $salaire_brut = $resEmp['salaire_base'];
        $salaire_brut -= ($salaire_brut / 30) * $jours_absence;
        $cnss = round($salaire_brut * $cnss_taux, 2);
        $salaire_net = $salaire_brut - $cnss;

        $stmtInsert = $conn->prepare("INSERT INTO paiement (employe_id, mois, salaire_net, cnss, jours_absence, jours_presence) VALUES (?,?,?,?,?,?)");
        $stmtInsert->bind_param("isddii", $employe_id, $mois, $salaire_net, $cnss, $jours_absence, $jours_presence);

        if ($stmtInsert->execute()) {
            $success = $lang=='ar' ? "✅ تم تسجيل الدفع بنجاح" : "✅ Paiement ajouté avec succès";
        } else {
            $error = "Erreur SQL: " . $conn->error;
        }
        $stmtInsert->close();
    } else {
        $error = $lang=='ar' ? "⚠️ الموظف غير موجود" : "⚠️ Employé introuvable";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $lang==='ar' ? 'إضافة راتب' : 'Ajouter Paiement' ?></title>
    <style>
        body {
            background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            direction: <?= $lang==='ar' ? 'rtl' : 'ltr' ?>;
            text-align: <?= $lang==='ar' ? 'right' : 'left' ?>;
        }
        h2 {
            color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            margin-bottom: 15px;
        }
        form {
            max-width: 500px;
            padding: 15px;
            border-radius: 6px;
            background-color: <?= $dark_mode ? '#1e1e1e' : '#f9f9f9' ?>;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label { display: block; margin: 10px 0 5px; }
        select, input[type="month"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid <?= $dark_mode ? '#333' : '#ccc' ?>;
            background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
        }
        button {
            cursor: pointer;
            font-weight: 600;
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }
        button:hover { background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>; }
        .btn-back {
            background-color: <?= $dark_mode ? '#6c757d' : '#6c757d' ?>;
        }
        .btn-back:hover { background-color: <?= $dark_mode ? '#5a6268' : '#5a6268' ?>; }
        p { font-weight: 600; }
    </style>
</head>
<body>

<h2><?= $lang==='ar' ? 'إضافة راتب' : 'Ajouter Paiement' ?></h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST">
    <label><?= $lang==='ar' ? 'الموظف' : 'Employé' ?> :</label>
    <select name="employe_id" required>
        <?php
        $emps = $conn->query("SELECT id, nom, prenom FROM employes");
        while ($row = $emps->fetch_assoc()) {
            $selected = (isset($_POST['employe_id']) && $_POST['employe_id']==$row['id']) ? 'selected' : '';
            echo "<option value='{$row['id']}' $selected>{$row['nom']} {$row['prenom']}</option>";
        }
        ?>
    </select>

    <label for="mois"><?= $lang==='ar' ? 'الشهر' : 'Mois' ?> :</label>
    <input type="month" name="mois" value="<?= htmlspecialchars($mois_input ?? '') ?>" required />

    <button type="submit"><?= $lang==='ar' ? '💾 حفظ' : '💾 Enregistrer' ?></button>
    <button type="button" class="btn-back" onclick="window.location.href='paiement.php'">
        <?= $lang==='ar' ? '⬅️ رجوع للقائمة' : '⬅️ Retour à la liste' ?>
    </button>
</form>

</body>
</html>
