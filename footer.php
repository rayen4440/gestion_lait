<?php
$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;
?>

<style>
.footer {
    background-color: <?= $dark_mode ? '#121212' : '#90D5FF' ?>;
    color: <?= $dark_mode ? '#ccc' : '#555' ?>;
    text-align: center;
    padding: 12px 20px;
    font-size: 0.9rem;
    position: fixed;
    bottom: 0;
    width: 100%;
    user-select: none;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>

<div class="footer">
     <p>
        <?= $lang === 'ar' 
            ? "تم التطوير بواسطة <strong>راين قزماير</strong> و <strong>ندى بلحاج</strong>" 
            : "Développé par <strong>Rayen Guezmir</strong> et <strong>Nada Belhadj</strong>" ?>
            <?= $lang === 'ar' 
            ? "© " . date('Y') . " جميع الحقوق محفوظة - complexe laitier" 
            : "© " . date('Y') . " Tous droits réservés - complexe laitier" ?>
    </p>
</div>
