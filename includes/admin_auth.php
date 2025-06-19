<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header('Location: ../connexion.php');
    exit;
}

require_once '../config/config.php';
$stmt = $conn->prepare("SELECT is_admin FROM SAE203_user WHERE id = ?");
$stmt->execute([$_SESSION['id_user']]);
$isAdmin = $stmt->fetchColumn();

if (!$isAdmin) {
    die("Accès interdit. Réservé à l'administrateur.");
}
?>
