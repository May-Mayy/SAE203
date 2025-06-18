<?php
session_start();
require 'config/config.php';

$id_user = $_SESSION['id_user'] ?? null;
$id_set_number = $_POST['id_set_number'] ?? null;
$action = $_POST['action'] ?? null;

if (!$id_user || !$id_set_number || !in_array($action, ['wishlist', 'possede'])) {
    die("Requête invalide.");
}

$table = $action === 'wishlist' ? 'SAE203_wishlisted_set' : 'SAE203_owned_set';

$sql = "DELETE FROM $table WHERE id_user = :id_user AND id_set_number = :id_set_number";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id_user' => $id_user,
    'id_set_number' => $id_set_number
]);

header("Location: {$action}s.php?message=suppression");
exit;
