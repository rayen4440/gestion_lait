<?php
session_start();
include('config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM alimentation WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: alimentation.php");
exit();
?>
