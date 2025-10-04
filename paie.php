<?php
session_start();
include('header.php');
include('footer.php');

include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_paie'])) {
    $employe_id = $_POST['employe_id'];
    $mois = $_POST['mois'];
    $salaire_net = $_POST['salaire_net'];
    $cnss = $_POST['cnss'];
    $jours_absence = $_POST['jours_absence'];

    $stmt = $conn->prepare("INSERT INTO paie (employe_id, mois, salaire_net, cnss, jours_absence) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issdi", $employe_id, $mois, $salaire_net, $cnss, $jours_absence);
    $stmt->execute();
    header("Location: paie.php");
    exit();
}

$sql = "SELECT p.id, e.nom, e.prenom, p.mois, p.salaire_net, p.cnss, p.jours_absence FROM paie p JOIN employes e ON p.employe_id = e.id ORDER BY p.mois DESC";
$result = $conn->query($sql);

$sql_employes = "SELECT id, nom, prenom FROM employes ORDER BY nom";
$result_employes = $conn->query($sql_employes);

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo ($lang == 'ar') ? 'الأجور' : 'Paie'; ?></title>
    <style>
        body {
    background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 20px;
    direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
    text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
}

h2, h3 {
    color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

th, td {
    border: 1px solid <?= $dark_mode ? '#333' : '#ccc' ?>;
    padding: 12px 15px;
    text-align: center;
}

th {
    background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    color: white;
    user-select: none;
}

tr:nth-child(even) {
    background-color: <?= $dark_mode ? '#1e1e1e' : '#f9f9f9' ?>;
}

tr:hover {
    background-color: <?= $dark_mode ? '#333' : '#d6eaff' ?>;
}

form {
    max-width: 450px;
    margin-bottom: 30px;
}

form select,
form input[type="month"],
form input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    margin-bottom: 15px;
    border: 1px solid <?= $dark_mode ? '#444' : '#ccc' ?>;
    border-radius: 6px;
    background-color: <?= $dark_mode ? '#222' : '#fff' ?>;
    color: <?= $dark_mode ? '#eee' : '#222' ?>;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.3s, box-shadow 0.3s;
}

form select:focus,
form input[type="month"]:focus,
form input[type="number"]:focus {
    outline: none;
    border-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    box-shadow: 0 0 6px <?= $dark_mode ? '#28a745' : '#007BFF' ?>88;
}

button.btn {
    background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button.btn:hover {
    background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>;
}

/* Responsive */
@media (max-width: 600px) {
    table, th, td {
        font-size: 0.9rem;
    }
    form select,
    form input[type="month"],
    form input[type="number"],
    button.btn {
        font-size: 0.9rem;
        padding: 8px 12px;
    }
}

    </style>
</head>
<body>
    <h2><?php echo ($lang == 'ar') ? 'الأجور' : 'Paie'; ?></h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo ($lang == 'ar') ? 'الاسم' : 'Nom'; ?></th>
                <th><?php echo ($lang == 'ar') ? 'اللقب' : 'Prénom'; ?></th>
                <th><?php echo ($lang == 'ar') ? 'الشهر' : 'Mois'; ?></th>
                <th><?php echo ($lang == 'ar') ? 'الراتب الصافي' : 'Salaire Net'; ?></th>
                <th><?php echo ($lang == 'ar') ? 'CNSS' : 'CNSS'; ?></th>
                <th><?php echo ($lang == 'ar') ? 'أيام الغياب' : 'Jours d\'absence'; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                    <td><?php echo $row['mois']; ?></td>
                    <td><?php echo $row['salaire_net']; ?></td>
                    <td><?php echo $row['cnss']; ?></td>
                    <td><?php echo $row['jours_absence']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h3><?php echo ($lang == 'ar') ? 'إضافة راتب جديد' : 'Ajouter une nouvelle paie'; ?></h3>
    <form method="post" action="">
        <select name="employe_id" required>
            <option value=""><?php echo ($lang == 'ar') ? 'اختر الموظف' : 'Sélectionnez un employé'; ?></option>
            <?php while ($emp = $result_employes->fetch_assoc()) { ?>
                <option value="<?php echo $emp['id']; ?>"><?php echo htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']); ?></option>
            <?php } ?>
        </select>
        <input type="month" name="mois" required>
        <input type="number" step="0.01" name="salaire_net" placeholder="<?php echo ($lang == 'ar') ? 'الراتب الصافي' : 'Salaire Net'; ?>" required>
        <input type="number" step="0.01" name="cnss" placeholder="CNSS" required>
        <input type="number" name="jours_absence" placeholder="<?php echo ($lang == 'ar') ? 'أيام الغياب' : 'Jours d\'absence'; ?>" required>
        <button type="submit" name="ajouter_paie" class="btn"><?php echo ($lang == 'ar') ? 'إضافة' : 'Ajouter'; ?></button>
    </form>
</body>
</html>
