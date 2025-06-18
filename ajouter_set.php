<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté.");
}

$user_id = $_SESSION['user_id'];
$set_number = $_POST['set_number'] ?? null;
$quantity = intval($_POST['quantity'] ?? 1);
$action = $_POST['action'] ?? null;

if (!$set_number || !in_array($action, ['wishlist', 'possede'])) {
    die("Requête invalide.");
}

$table = $action === 'wishlist' ? 'SAE203_wishlisted_set' : 'SAE203_owned_set';

$query = "SELECT quantity FROM $table WHERE id_user = :user_id AND id_set_number = :set_number";
$stmt = $conn->prepare($query);
$stmt->execute(['user_id' => $user_id, 'set_number' => $set_number]);

if ($row = $stmt->fetch()) {
    $new_quantity = $row['quantity'] + $quantity;
    $update = "UPDATE $table SET quantity = :quantity WHERE id_user = :user_id AND id_set_number = :set_number";
    $stmt = $conn->prepare($update);
    $stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'set_number' => $set_number]);
} else {
    $insert = "INSERT INTO $table (id_user, id_set_number, quantity) VALUES (:user_id, :set_number, :quantity)";
    $stmt = $conn->prepare($insert);
    $stmt->execute(['user_id' => $user_id, 'set_number' => $set_number, 'quantity' => $quantity]);
}

header("Location: catalogue.php?message=success&action=$action");
exit;
