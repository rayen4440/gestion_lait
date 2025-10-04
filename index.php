<?php
session_start();
include('config.php');
// Vérification de connexion
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $mp = $_POST['mp'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE login = ? AND mdp = ?");
    $stmt->bind_param("ss", $login, $mp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['lang'] = $_POST['lang'] ?? 'fr';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = ($_SESSION['lang'] ?? 'fr') === 'ar' ? 'بيانات الدخول خاطئة' : 'Identifiants incorrects';
    }
}

// Langue
$lang = $_SESSION['lang'] ?? 'fr';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $lang === 'ar' ? 'تسجيل الدخول' : 'Connexion - Système de gestion' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .blue-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            border-top-left-radius: 2.5rem;
            clip-path: ellipse(140% 60% at 15% 100%);
            z-index: -1;
        }
        main.container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5rem;
            flex-wrap: wrap;
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
        }
        section.info {
            flex: 1 1 300px;
            min-width: 300px;
            text-align: left;
        }
        section.info h1 { font-size: 60px; font-weight: 900; color: #154580; margin-bottom: 0.5rem; }
        section.info h2 { font-size: 2rem; font-weight: 600; color: #154580; margin-bottom: 1rem; }
        section.info p { font-size: 1.25rem; font-weight: 500; }

        .login-device {
            flex: 0 0 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .phone-frame {
            width: 280px;
            height: 560px;
            border-radius: 40px;
            background: black;
            padding: 30px 0 40px 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-box {
            width: 240px;
            background: white;
            border-radius: 12px;
            padding: 2rem 1.6rem;
            box-shadow: 0 7px 18px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            text-align: center;
            border: 1px solid #e0e0e0;
        }
        .login-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: #154580;
            margin-bottom: 1rem;
            text-align: center;
        }
        .form-field { margin-bottom: 1rem; display: flex; flex-direction: column; }
        .form-field label { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; color: #444; text-align: center; }
        .form-field input, .form-field select {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline-offset: 2px;
            transition: border-color 0.3s;
        }
        .form-field input:focus, .form-field select:focus { border-color: #07a37f; box-shadow: 0 0 0 3px #07a37f80; }
        .btn-submit {
            background-color: #154580;
            color: white;
            font-weight: 700;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 0.25rem;
            box-shadow: 0 3px 7px #154580cc;
            width: 100%;
            text-align: center;
            text-transform: uppercase;
        }
        .btn-submit:hover { background-color: #113d73; box-shadow: 0 6px 14px #113d73cc; }
        .login-footer { margin-top: 1rem; text-align: center; font-size: 0.75rem; color: #999; }

        /* --- iPhone Switch --- */
        .switch {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 28px;
        }
        .switch input { display: none; }
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0; left: 0;
          right: 0; bottom: 0;
          background-color: #ccc;
          transition: .4s;
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
          transition: .4s;
          border-radius: 50%;
        }
        input:checked + .slider {
          background-color: #154580;
        }
        input:checked + .slider:before {
          transform: translateX(22px);
        }

        /* --- Mode sombre --- */
        body.dark { background: #121212; color: #f0f0f0; }
        body.dark .login-box { background: #1e1e1e; border: 1px solid #333; }
        body.dark .btn-submit { background-color: #07a37f; box-shadow: 0 3px 7px #07a37fcc; }
        body.dark .btn-submit:hover { background-color: #059268; box-shadow: 0 6px 14px #059268cc; }
        body.dark .form-field input, body.dark .form-field select { background: #ffffff; border: 1px solid #555; color: #000; }
        body.dark .form-field label { color: #ddd; }
        body.dark .form-field input:focus, body.dark .form-field select:focus { border-color: #07a37f; box-shadow: 0 0 0 3px #07a37f80; }
        body.dark .login-footer { color: #bbb; }

        @media (max-width: 768px) { main.container { justify-content: center; gap: 3rem; } }
    </style>
</head>
<body>
<div class="blue-wave" aria-hidden="true"></div>

<main class="container" role="main">
    <!-- Section info -->
    <section class="info">
        <h1><?= $lang === 'ar' ? 'نظام إدارة' : 'Système de gestion' ?></h1>
        <h2><?= $lang === 'ar' ? 'المجمع الألباني' : 'du complexe laitier' ?></h2>
        <p><?= $lang === 'ar' ? ' و نــدى بلحــاج رايان غوزمير' : 'Rayen Guezmir et Nada Belhadj' ?></p>
    </section>

    <!-- Section login -->
    <section class="login-device">
        <div class="phone-frame" role="form" aria-labelledby="login-title">
            <!-- 🔹 Notch fixe -->
            <div class="phone-top-notch"></div>

            <div class="login-box">
                <h3 id="login-title" class="login-title"><?= $lang === 'ar' ? 'تسجيل الدخول' : 'Connexion' ?></h3>
                <?php if ($error): ?>
                    <div class="text-red-600 mb-2 text-center"><?= $error ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-field">
                        <label for="login"><?= $lang === 'ar' ? 'اسم المستخدم' : 'Nom d\'utilisateur' ?></label>
                        <input type="text" id="login" name="login" required>
                    </div>
                    <div class="form-field">
                        <label for="mp"><?= $lang === 'ar' ? 'كلمة السر' : 'Mot de passe' ?></label>
                        <input type="password" id="mp" name="mp" required>
                    </div>
                    <div class="form-field">
                        <label for="lang"><?= $lang === 'ar' ? 'اللغة' : 'Langue' ?></label>
                        <select id="lang" name="lang">
                            <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
                            <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>العربية</option>
                        </select>
                    </div>
                    <!-- 🔹 Bouton iPhone Mode sombre -->
                    <div class="form-field">
                        
                    </div>
                    <button class="btn-submit" type="submit"><?= $lang === 'ar' ? 'دخول' : 'Se connecter' ?></button>
                </form>
                <p class="login-footer"><?= $lang === 'ar' ? 'محمي بواسطة نظام إدارة المجمع الألباني' : 'Protected by Système de gestion Laitier' ?></p>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("darkmode");
    const body = document.body;

    // Charger depuis localStorage
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark");
        toggle.checked = true;
    }

    // Changement d'état
    toggle.addEventListener("change", () => {
        if (toggle.checked) {
            body.classList.add("dark");
            localStorage.setItem("darkMode", "enabled");
        } else {
            body.classList.remove("dark");
            localStorage.setItem("darkMode", "disabled");
        }
    });
});
</script>
</body>
</html>
