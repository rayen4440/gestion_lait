<?php
session_start();

// Liste des langues autorisées
$allowed_langs = ['fr', 'ar'];

// Vérifie que le paramètre 'lang' est passé et valide
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Redirection vers la page précédente ou vers une page par défaut
$redirect = $_SERVER['HTTP_REFERER'] ?? 'presence.php';
header("Location: $redirect");
exit();
