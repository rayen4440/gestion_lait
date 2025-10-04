<?php
session_start();
include('header.php');
include('footer.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $lang === 'ar' ? 'الإعدادات' : 'Paramètres' ?></title>
    <style>
body {
   background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 40px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.container {
  max-width: 400px;
  margin: auto;
  padding: 20px 30px;
  border-radius: 8px;
  background-color: var(--container-bg);
  box-shadow: 0 0 12px rgba(0,0,0,0.1);
}

h2 {
  margin-bottom: 25px;
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--primary-color);
  user-select: none;
  text-align: center;
}

label {
  display: block;
  margin: 15px 0 5px;
  font-weight: 600;
  cursor: pointer;
}

select, input[type="checkbox"] {
  margin-right: 8px;
  transform: scale(1.2);
  vertical-align: middle;
  cursor: pointer;
}

button {
  margin-top: 20px;
  width: 100%;
  padding: 12px 0;
  background-color: var(--primary-color);
  color: white;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: var(--primary-color-hover);
}

:root {
  --primary-color: #007BFF;
  --primary-color-hover: #0056b3;
  --background-color: #ffffff;
  --text-color: #222222;
  --container-bg: #f9f9f9;
}

body.dark-mode {
  --primary-color: #28a745;
  --primary-color-hover: #1e7e34;
  --background-color: #121212;
  --text-color: #eee;
  --container-bg: #1e1e1e;
}

body {
  background-color: var(--background-color);
  color: var(--text-color);
}

    </style>
</head>
<body>
    <div class="container">
        <h2><?= $lang === 'ar' ? 'الإعدادات' : 'Paramètres' ?></h2>
        <form method="post" action="settings_save.php">
            <label for="lang"><?= $lang === 'ar' ? 'اختر اللغة' : 'Choisissez la langue' ?>:</label>
            <select name="lang" id="lang">
                <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
                <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>العربية</option>
            </select>

            <label>
                <input type="checkbox" name="dark_mode" value="1" <?= $dark_mode ? 'checked' : '' ?>>
                <?= $lang === 'ar' ? ' تفعيل الوضع الداكن 🌞 🌙' : 'Activer le mode sombre 🌙 🌞' ?>
            </label>

            <button type="submit"><?= $lang === 'ar' ? 'حفظ' : 'Enregistrer' ?></button>
        </form>
    </div>
</body>
</html>
