<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Définir la langue et le mode sombre
$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Textes selon la langue
$texts = [
    'fr' => [
        'title' => 'Ajouter Employé',
        'nom' => 'Nom',
        'prenom' => 'Prénom',
        'poste' => 'Poste',
        'salaire_base' => 'Salaire de base',
        'ajouter' => 'Ajouter ➕',
        'retour' => 'Retour à la liste ⬅️'
    ],
    'ar' => [
        'title' => 'إضافة موظف',
        'nom' => 'الاسم',
        'prenom' => 'اللقب',
        'poste' => 'الوظيفة',
        'salaire_base' => 'الراتب الأساسي',
        'ajouter' => '➕ إضافة',
        'retour' => '⬅️ عودة إلى القائمة'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $poste = $_POST['poste'];
    $salaire_base = floatval($_POST['salaire_base']);

    $stmt = $conn->prepare("INSERT INTO employes (nom, prenom, poste, salaire_base) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $nom, $prenom, $poste, $salaire_base);
    $stmt->execute();

    header("Location: employes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $texts[$lang]['title'] ?></title>
    <style>
        body {
            background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
            text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
            padding: 20px;
        }

        h2 { color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>; margin-bottom: 20px; }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input {
            display: block;
            margin: 8px 0 15px 0;
            padding: 8px;
            width: 300px;
            border-radius: 6px;
            border: 1px solid <?= $dark_mode ? '#444' : '#ccc' ?>;
            background-color: <?= $dark_mode ? '#222' : '#fff' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
        }

        button.btn {
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button.btn:hover {
            background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>;
            transform: translateY(-2px);
            
        }
        button.btn:active { transform: translateY(0); }

        /* Bouton retour */
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

<h2><?= $texts[$lang]['title'] ?></h2>

<form method="post" action="">
    <label><?= $texts[$lang]['nom'] ?> :</label>
    <input type="text" name="nom" required>

    <label><?= $texts[$lang]['prenom'] ?> :</label>
    <input type="text" name="prenom" required>

    <label><?= $texts[$lang]['poste'] ?> :</label>
    <input type="text" name="poste">

    <label><?= $texts[$lang]['salaire_base'] ?> :</label>
    <input type="number" step="0.01" name="salaire_base" required>

    <button type="submit" class="btn"><?= $texts[$lang]['ajouter'] ?></button>
</form>

  <button class="btn-back" onclick="window.location.href='fournisseur.php'">
            <?= $lang=='ar' ? "⬅️ رجوع للقائمة" : "⬅️ Retour à la liste" ?>
        </button>
</body>
</html>
