<?php
session_start();
require 'config/config.php';

$user_id = $_SESSION['user_id'] ?? null;
$set_number = $_POST['set_number'] ?? null;
$action = $_POST['action'] ?? null;

if (!$user_id || !$set_number || !in_array($action, ['wishlist', 'possede'])) {
    die("Requête invalide.");
}

$table = $action === 'wishlist' ? 'SAE203_wishlisted_set' : 'SAE203_owned_set';

$sql = "DELETE FROM $table WHERE id_user = :user_id AND id_set_number = :set_number";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id, 'set_number' => $set_number]);

header("Location: {$action}.php?message=suppression");
exit;
