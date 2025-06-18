<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$userId = $_SESSION['user']['id'];
$setNumber = $_POST['set_number'] ?? '';
$status = $_POST['status'] ?? '';

if (!$setNumber || !in_array($status, ['wishlist', 'owned'], true)) {
    header('Location: index.php');
    exit;
}

// Check if entry exists
$check = $pdo->prepare('SELECT COUNT(*) FROM user_sets WHERE user_id = ? AND set_number = ?');
$check->execute([$userId, $setNumber]);
if ($check->fetchColumn() > 0) {
    $stmt = $pdo->prepare('UPDATE user_sets SET status = ? WHERE user_id = ? AND set_number = ?');
    $stmt->execute([$status, $userId, $setNumber]);
} else {
    $stmt = $pdo->prepare('INSERT INTO user_sets (user_id, set_number, status) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $setNumber, $status]);
}

header('Location: detail_set.php?id=' . urlencode($setNumber));
exit;
?>
