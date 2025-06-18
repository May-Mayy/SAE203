<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['id_user'])) {
    die("Vous devez être connecté.");
}

$id_user = $_SESSION['id_user'];
$id_set_number = $_GET['id_set_number'] ?? null;
$type = $_GET['type'] ?? null;

if (!$id_set_number || !in_array($type, ['wishlist', 'possede'])) {
    die("Requête invalide.");
}

$table = $type === 'wishlist' ? 'SAE203_wishlisted_set' : 'SAE203_owned_set';

$stmt = $conn->prepare("DELETE FROM $table WHERE id_user = :id_user AND id_set_number = :id_set_number");
$stmt->execute([
    'id_user' => $id_user,
    'id_set_number' => $id_set_number
]);

header("Location: profil.php");
exit;
