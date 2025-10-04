<?php 
session_start();
include('config.php');
if (isset($_GET['toggle_dark'])) {
    $_SESSION['dark_mode'] = !($_SESSION['dark_mode'] ?? false);
    $redirect = strtok($_SERVER["REQUEST_URI"], '?'); // chemin sans query
    header("Location: $redirect");
    exit();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'ar' ? 'ar' : 'fr';
}
$lang = $_SESSION['lang'] ?? 'fr';

$dark_mode = $_SESSION['dark_mode'] ?? false;

$user_id = $_SESSION['user_id'] ?? 0;
$username = "Utilisateur";
if ($user_id) {
    $result = $conn->query("SELECT login FROM user WHERE id=" . intval($user_id));
    if ($result && $row = $result->fetch_assoc()) $username = $row['login'];
}

$texts = [
    'fr' => [
        'title' => 'Tableau de bord',
        'welcome' => "Bienvenue, $username",
        'home' => 'Accueil',
        'employees' => 'Employés',
        'presence' => 'Présence',
        'payroll' => 'Paiement',
        'feed' => 'Alimentation',
        'milk' => 'Lait',
        'settings' => 'Paramètres',
        'logout' => 'Déconnexion 🚪',
        'welcome_msg' => 'Sélectionnez un indicateur dans le menu pour commencer.',
        'milk_production' => 'Production quotidienne de lait',
        'feed_stock' => 'Stock d’alimentation',
        'presence_today' => 'Présence aujourd’hui',
        'absence_today' => 'Absences aujourd’hui',
        'dark_mode' => 'Mode Sombre',
        'light_mode' => 'Mode Clair',
    ],
    'ar' => [
        'title' => 'لوحة التحكم',
        'welcome' => "مرحبًا، $username",
        'home' => 'الرئيسية',
        'employees' => 'الموظفون',
        'presence' => 'الحضور',
        'payroll' => 'الرواتب',
        'feed' => 'العلف',
        'milk' => 'الحليب',
        'settings' => 'الإعدادات',
        'logout' => 'تسجيل الخروج',
        'welcome_msg' => 'اختر قسمًا من القائمة للبدء.',
        'milk_production' => 'إنتاج الحليب اليومي',
        'feed_stock' => 'مخزون العلف',
        'presence_today' => 'الحضور اليوم',
        'absence_today' => 'الغياب اليوم',
        'dark_mode' => 'الوضع الداكن',
        'light_mode' => 'الوضع الفاتح',
    ],
];
$text = $texts[$lang];

$today = date('Y-m-d');
$presence_today = $conn->query("SELECT COUNT(*) as total FROM presence WHERE date='$today' AND statut='Présent'")->fetch_assoc()['total'] ?? 0;
$absence_today  = $conn->query("SELECT COUNT(*) as total FROM presence WHERE date='$today' AND statut='Absent'")->fetch_assoc()['total'] ?? 0;
$milk_today = $conn->query("SELECT SUM(quantite) as total FROM lait WHERE date_lait='$today'")->fetch_assoc()['total'] ?? 0;
$feed_stock = $conn->query("SELECT SUM(quantite) as total FROM alimentation")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang==='ar'?'rtl':'ltr' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($text['title']) ?></title>
</head>
<body class="<?= $dark_mode ? 'dark' : '' ?>" >
<style>
  :root {
    --bg-color: #f3f4f6;
    --text-color: #000;
    --sidebar-bg: #1e40af;
    --card-bg: #ffffff;
    --link-hover: #2563eb;
  }
  body.dark {
    --bg-color: #121212;
    --text-color: #e6e6e6;
    --sidebar-bg: #0f172a;
    --card-bg: #1f2937;
    --link-hover: #1e40af;
  }
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    background: var(--bg-color);
    color: var(--text-color);
    transition: background .25s, color .25s;
  }

  .sidebar {
    width: 220px;
    background: var(--sidebar-bg);
    color: white;
    min-height: 100vh;
    padding: 20px;
    position: fixed;
    left: 0;
    top: 0;
    transition: background .25s;
  }
  [dir="rtl"] .sidebar { left: auto; right: 0; text-align: right; }
  .sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
  }
  .sidebar nav a {
    display: block;
    padding: 10px;
    color: white;
    text-decoration: none;
    margin: 5px 0;
    border-radius: 4px;
  }
  .sidebar nav a:hover {
    background: var(--link-hover);
  }
  .logout {
    background: #dc2626;
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 10px;
    border-radius: 4px;
  }

  .main-content {
    margin-left: 240px;
    flex: 1;
    padding: 20px;
    min-height: 100vh;
    transition: margin .2s;
  }
  [dir="rtl"] .main-content { margin-left: 0; margin-right: 240px; }

  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  .card {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    transition: background .25s, color .25s;
  }
  .card.red { border-left: 5px solid #dc2626; }
  .card.green { border-left: 5px solid #16a34a; }
  .card.blue { border-left: 5px solid #2563eb; }
  .card.yellow { border-left: 5px solid #f97316; }
  h1 { text-align: center; margin-top: 0; } 
  p { text-align: center; color: rgba(0,0,0,0.6); }
  body.dark p { color: rgba(255,255,255,0.75); }

  .theme-toggle {
    margin-top: 10px;
    display: block;
    padding: 8px;
    background: #2563eb;
    color: white;
    text-align: center;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
  }
</style>

<aside class="sidebar">
  <h2><?= htmlspecialchars($text['title']) ?></h2>
  <nav>
    <a href="dashboard.php"><?= htmlspecialchars($text['home']) ?></a>
    <a href="employes.php"><?= htmlspecialchars($text['employees']) ?></a>
    <a href="fournisseur.php"><?= $lang === 'ar' ? 'الموردون' : 'Fournisseurs' ?></a>
    <a href="presence.php"><?= htmlspecialchars($text['presence']) ?></a>
    <a href="paiement.php"><?= htmlspecialchars($text['payroll']) ?></a>
    <a href="alimentation.php"><?= htmlspecialchars($text['feed']) ?></a>
    <a href="lait.php"><?= htmlspecialchars($text['milk']) ?></a>
    <a href="settings.php"><?= htmlspecialchars($text['settings']) ?></a>
    <a href="logout.php" class="logout"><?= htmlspecialchars($text['logout']) ?></a>
  </nav>
</aside>

<main class="main-content">
  <h1><?= htmlspecialchars($text['welcome']) ?></h1>
  <p><?= htmlspecialchars($text['welcome_msg']) ?></p>

  <div class="stats">
    <div class="card red">
      <p><?= htmlspecialchars($text['absence_today']) ?></p>
      <h3><?= intval($absence_today) ?></h3>
    </div>
    <div class="card green">
      <p><?= htmlspecialchars($text['presence_today']) ?></p>
      <h3><?= intval($presence_today) ?></h3>
    </div>
    <div class="card blue">
      <p><?= htmlspecialchars($text['milk_production']) ?></p>
      <h3><?= intval($milk_today) ?> L</h3>
    </div>
    <div class="card yellow">
      <p><?= htmlspecialchars($text['feed_stock']) ?></p>
      <h3><?= intval($feed_stock) ?> kg</h3>
    </div>
  </div>
</main>
</body>
</html>
