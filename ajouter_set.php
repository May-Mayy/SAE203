<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['id_user'])) {
    die("Vous devez être connecté.");
}

$id_user = $_SESSION['id_user'];
$id_set_number = $_POST['id_set_number'] ?? null;
$quantity = intval($_POST['quantity'] ?? 1);
$action = $_POST['action'] ?? null;

if (!$id_set_number || !in_array($action, ['wishlist', 'possede'])) {
    die("Requête invalide.");
}

$table = $action === 'wishlist' ? 'SAE203_wishlisted_set' : 'SAE203_owned_set';

// Vérifier si le set existe déjà pour cet utilisateur
$query = "SELECT quantity FROM $table WHERE id_user = :id_user AND id_set_number = :id_set_number";
$stmt = $conn->prepare($query);
$stmt->execute([
    'id_user' => $id_user,
    'id_set_number' => $id_set_number
]);

if ($row = $stmt->fetch()) {
    $new_quantity = $row['quantity'] + $quantity;
    $update = "UPDATE $table SET quantity = :quantity WHERE id_user = :id_user AND id_set_number = :id_set_number";
    $stmt = $conn->prepare($update);
    $stmt->execute([
        'quantity' => $new_quantity,
        'id_user' => $id_user,
        'id_set_number' => $id_set_number
    ]);
} else {
    $insert = "INSERT INTO $table (id_user, id_set_number, quantity) VALUES (:id_user, :id_set_number, :quantity)";
    $stmt = $conn->prepare($insert);
    $stmt->execute([
        'id_user' => $id_user,
        'id_set_number' => $id_set_number,
        'quantity' => $quantity
    ]);
}

header("Location: detail_set.php?id=" . urlencode($id_set_number));
exit;
