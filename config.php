<?php
$host = "localhost";
$user = "root";
$pass = ""; // اتركه فارغًا إذا لم يكن هناك كلمة مرور
$dbname = "gestion_lait"; // اسم قاعدة البيانات

$conn = new mysqli($host, $user, $pass, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}
?>
