<?php
include 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$lang_code = $_SESSION['lang'] ?? 'fr';
include "lang_$lang_code.php";

$result = $conn->query("SELECT type, SUM(quantite_kg) AS total_kg FROM alimentation GROUP BY type");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ['type' => $row['type'], 'total' => $row['total_kg']];
}

$types = array_column($data, 'type');
$totals = array_column($data, 'total');
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['statistics'] ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="text-align:right; padding:10px;">
    <a href="set_lang.php?lang=fr">Français</a> | 
    <a href="set_lang.php?lang=ar">عربي</a>
</div>

<h2><?= $lang['statistics'] ?></h2>

<canvas id="myChart" width="400" height="200"></canvas>

<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($types) ?>,
        datasets: [{
            label: '<?= $lang_code=='fr' ? 'Quantité totale (Kg)' : 'الكمية الكلية (كغ)' ?>',
            data: <?= json_encode($totals) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {beginAtZero: true}
        }
    }
});
</script>

<a href="dashboard.php"><?= $lang_code=='fr' ? 'Retour' : 'عودة' ?></a>
</body>
</html>
