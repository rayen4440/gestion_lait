<?php
session_start();
include('config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM employes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: employes.php");
exit();
?>
