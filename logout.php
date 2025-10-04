<?php
session_start();
session_unset();    // حذف كل متغيرات الجلسة
session_destroy();  // إنهاء الجلسة

header("Location: index.php");  // إعادة التوجيه لصفحة الدخول
exit();
?>
