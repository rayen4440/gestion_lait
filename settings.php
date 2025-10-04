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
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 40px;
  direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
  text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
  background-color: <?= $dark_mode ? '#121212' : '#90D5FF' ?>;

  color: var(--text-color);
}

.container {
  max-width: 400px;
  margin: auto;
  padding: 20px 30px;
  border-radius: 12px;
  background-color: <?= $dark_mode ? '#121212' : '#3990eeff' ?>;
  box-shadow: 0 0 12px rgba(0,0,0,0.15);
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
  margin: 15px 0 8px;
  font-weight: 600;
  cursor: pointer;
}

select {
  margin-top: 5px;
  padding: 8px;
  font-size: 1rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  cursor: pointer;
  width: 100%;
}

.switch {
  position: relative;
  display: inline-block;
  width: 52px;
  height: 28px;
  vertical-align: middle;
  margin-left: 10px;
}

.switch input { display: none; }

.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

.switch input:checked + .slider { background-color: #1565C0; }
.switch input:checked + .slider:before { transform: translateX(24px); }

:root {
  --primary-color: #000000;
  --primary-color-hover: #0d47a1;
  --background-color: #ffffff;
  --text-color: #222222;
  --container-bg: #f9f9f9;
}

body.dark-mode {
  --primary-color: #ffffffff;
  --primary-color-hover: #64b5f6;
  --background-color: #121212;
  --text-color: #ffffffff;
  --container-bg: #1e1e1e;
}
    </style>
</head>
<body class="<?= $dark_mode ? 'dark-mode' : '' ?>">
    <div class="container">
        <h2><?= $lang === 'ar' ? '⚙️ الإعدادات' : 'Paramètres ⚙️' ?></h2>
        <form id="settingsForm">
            <label for="lang"><?= $lang === 'ar' ? 'اختر اللغة' : 'Choisissez la langue' ?>:</label>
            <select name="lang" id="lang">
                <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
                <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>العربية</option>
            </select>

            <label style="margin-top:20px;">
                <?= $lang === 'ar' ? 'تفعيل الوضع الداكن' : 'Activer le mode sombre' ?>
                <label class="switch">
                    <input type="checkbox" name="dark_mode" id="dark_mode" value="1" <?= $dark_mode ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </label>
        </form>
    </div>

    <script>
    function saveSettings() {
        const formData = new FormData(document.getElementById("settingsForm"));
        fetch("settings_save.php", {
            method: "POST",
            body: formData
        }).then(res => res.text()).then(data => {
            console.log("✅ Paramètres enregistrés:", data);
            location.reload(); 
        }).catch(err => console.error(err));
    }

    document.getElementById("lang").addEventListener("change", saveSettings);
    document.getElementById("dark_mode").addEventListener("change", saveSettings);
    </script>
</body>
</html>
