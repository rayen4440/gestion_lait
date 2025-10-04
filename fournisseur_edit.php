<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

$error = '';
$success = '';

// Vérifier l'ID passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: fournisseur.php");
    exit();
}

$id = intval($_GET['id']);

// Récupérer les données du fournisseur
$stmt = $conn->prepare("SELECT * FROM fournisseur WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$fournisseur = $result->fetch_assoc();

if (!$fournisseur) {
    header("Location: fournisseur.php");
    exit();
}

// Mise à jour du fournisseur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);

    if (empty($nom)) {
        $error = $lang=='ar'?'الاسم مطلوب':'Le nom est obligatoire';
    } else {
        $stmt = $conn->prepare("UPDATE fournisseur SET nom=?, telephone=?, email=?, adresse=? WHERE id=?");
        $stmt->bind_param("ssssi", $nom, $telephone, $email, $adresse, $id);
        if ($stmt->execute()) {
            $success = $lang=='ar'?'تم تحديث المورد بنجاح':'Fournisseur mis à jour avec succès';
            header("Location: fournisseur.php");
            exit();
        } else {
            $error = $lang=='ar'?'حدث خطأ أثناء التحديث':'Erreur lors de la mise à jour';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang=='ar'?'✏️ تعديل مورد':'Modifier Fournisseur ✏️' ?></title>
<style>
body {
    background-color: <?= $dark_mode?'#121212':'#fff' ?>;
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',Tahoma,sans-serif;
    margin:20px;
    direction: <?= $lang=='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang=='ar'?'right':'left' ?>;
}
h2 { text-align:center; margin-bottom:20px; }
form { max-width:500px; margin:0 auto; }
label { display:block; margin-top:15px; font-weight:600; }
input, textarea { width:100%; padding:8px; margin-top:5px; border-radius:4px; border:1px solid #ccc; background-color: <?= $dark_mode?'#1e1e1e':'#fff' ?>; color: <?= $dark_mode?'#eee':'#222' ?>; }
input[type="submit"] { margin-top:20px; background-color:#007BFF; color:#fff; border:none; cursor:pointer; }
input[type="submit"]:hover { background-color:#0056b3; }
.error { color:#dc3545; margin-top:10px; text-align:center; }
.success { color:#28a745; margin-top:10px; text-align:center; }
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

<h2><?= $lang=='ar'?'✏️ تعديل مورد':'Modifier Fournisseur ✏️' ?></h2>

<?php if($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>
<?php if($success): ?>
<p class="success"><?= $success ?></p>
<?php endif; ?>

<form method="POST">
    <label><?= $lang=='ar'?'الاسم':'Nom' ?></label>
    <input type="text" name="nom" value="<?= htmlspecialchars($fournisseur['nom']) ?>" required>

    <label><?= $lang=='ar'?'الهاتف':'Téléphone' ?></label>
    <input type="text" name="telephone" value="<?= htmlspecialchars($fournisseur['telephone']) ?>">

    <label><?= $lang=='ar'?'البريد الإلكتروني':'Email' ?></label>
    <input type="email" name="email" value="<?= htmlspecialchars($fournisseur['email']) ?>">

    <label><?= $lang=='ar'?'العنوان':'Adresse' ?></label>
    <textarea name="adresse" rows="3"><?= htmlspecialchars($fournisseur['adresse']) ?></textarea>

    <input type="submit" value="<?= $lang=='ar'?'تحديث':'Mettre à jour' ?>">
      <a href="fournisseur.php" class="btn-back"><?= $lang=='ar'?'⬅️ العودة إلى القائمة':'Retour à la liste ⬅️' ?></a>

</form>

</body>
</html>
