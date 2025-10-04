<?php
session_start();
include('config.php');

if (!isset($_GET['id'])) {
    header("Location: employes.php");
    exit();
}

$lang_code = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

$id = intval($_GET['id']);

// Récupérer données employé
$stmt = $conn->prepare("SELECT * FROM employes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employe = $result->fetch_assoc();

if (!$employe) {
    header("Location: employes.php");
    exit();
}

// Mettre à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $poste = $_POST['poste'];
    $salaire_base = floatval($_POST['salaire_base']);

    $stmt = $conn->prepare("UPDATE employes SET nom = ?, prenom = ?, poste = ?, salaire_base = ? WHERE id = ?");
    $stmt->bind_param("sssdi", $nom, $prenom, $poste, $salaire_base, $id);
    $stmt->execute();

    header("Location: employes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $lang_code=='fr'?'Modifier Employé':'تعديل الموظف' ?></title>
    <style>
        body {
            background-color: <?= $dark_mode?'#121212':'#fff' ?>;
            color: <?= $dark_mode?'#eee':'#222' ?>;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 20px;
            direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
            text-align: <?= $lang_code==='ar'?'right':'left' ?>;
        }
        h2 {
            color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
            margin-bottom: 20px;
        }
        form {
            max-width: 450px;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }
        input {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid <?= $dark_mode?'#444':'#ccc' ?>;
            background-color: <?= $dark_mode?'#222':'#fff' ?>;
            color: <?= $dark_mode?'#eee':'#222' ?>;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
            color: #fff;
        }
        button:hover {
            background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>;
        }
        a.btn-back {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: <?= $dark_mode?'#28a745':'#007BFF' ?>;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        a.btn-back:hover {
            background-color: <?= $dark_mode?'#1e7e34':'#0056b3' ?>;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<h2><?= $lang_code=='fr'?'Modifier Employé':'تعديل الموظف' ?></h2>

<form method="post" action="">
    <label><?= $lang_code=='fr'?'Nom':'الاسم' ?> :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($employe['nom']) ?>" required>

    <label><?= $lang_code=='fr'?'Prénom':'اللقب' ?> :</label>
    <input type="text" name="prenom" value="<?= htmlspecialchars($employe['prenom']) ?>" required>

    <label><?= $lang_code=='fr'?'Poste':'المنصب' ?> :</label>
    <input type="text" name="poste" value="<?= htmlspecialchars($employe['poste']) ?>">

    <label><?= $lang_code=='fr'?'Salaire de base':'الراتب الأساسي' ?> :</label>
    <input type="number" step="0.01" name="salaire_base" value="<?= htmlspecialchars($employe['salaire_base']) ?>" required>

    <button type="submit"><?= $lang_code=='fr'?'Modifier':'تحديث' ?></button>
</form>

<a href="employes.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

<?php include('footer.php'); ?>
</body>
</html>
