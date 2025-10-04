<?php

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Textes des menus selon la langue
$menu_items = [
    'fr' => [
        'dashboard' => 'Accueil 🏠',
        'employes' => 'Employés 👥',
        'fournisseur' => 'Fournisseur 📋',
        'presence' => 'Présence 👤',
        'paiement' => 'Paiement 💵',
        'lait' => 'Lait 🥛',
        'alimentation' => 'Alimentation 🌾',
        'settings' => 'Paramètres ⚙️',
    ],
    'ar' => [
        'dashboard' => '🏠 الرئيسية',
        'employes' => '👥 الموظفين',
        'fournisseur' => '📋 المورد',
        'presence' => '👤 الحضور',
        'paiement' => '💵 الأجور',
        'lait' => '🥛 الحليب',
        'alimentation' => '🌾 العلف',
        'settings' => '⚙️ الإعدادات',
    ]
];

$items = $menu_items[$lang];
?>
<style>
/* ----------- Styles globaux ----------- */
body {
    padding-top: 60px; /* espace pour le header */
    font-family: Arial, sans-serif;
    direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
    text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
}

.header {
    background-color: #1565C0;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    flex-direction: <?= $lang === 'ar' ? 'row-reverse' : 'row' ?>;
}

.site-title {
    font-weight: bold;
    font-size: 1.3rem;
}

.nav-menu {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.nav-menu a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.nav-menu a:hover {
    background-color: <?= $dark_mode ? '#a71d2a' : '#c82333' ?>;
}

.logout-link {
    padding: 6px 12px;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.logout-link:hover {
    background-color: #000;
}

/* Responsive */
@media (max-width: 600px) {
    .nav-menu {
        flex-direction: column;
        width: 100%;
        margin-top: 10px;
    }
    .logout-link {
        margin-top: 10px;
    }
}
</style>

<div class="header">
    <div class="site-title">
        <?= $lang === 'ar' ? 'مجمع الحليب' : 'Complexe laitier' ?>
    </div>
    <nav class="nav-menu">
        <a href="dashboard.php"><?= $items['dashboard'] ?></a>
        <a href="employes.php"><?= $items['employes'] ?></a>
        <a href="fournisseur.php"><?= $items['fournisseur'] ?></a>
        <a href="presence.php"><?= $items['presence'] ?></a>
        <a href="paiement.php"><?= $items['paiement'] ?></a>
        <a href="lait.php"><?= $items['lait'] ?></a>
        <a href="alimentation.php"><?= $items['alimentation'] ?></a>
        <a href="settings.php"><?= $items['settings'] ?></a>
        <a href="logout.php" class="logout-link">
            <?= $lang === 'ar' ? '🚪 تسجيل الخروج' : 'Déconnexion 🚪' ?>
        </a>
    </nav>
</div>
