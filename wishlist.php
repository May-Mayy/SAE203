<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die("Non connecté");

$sql = "SELECT s.*, w.quantity FROM SAE203_wishlisted_set w
        JOIN lego_sets s ON s.id_set_number = w.id_set_number
        WHERE w.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$sets = $stmt->fetchAll();
?>

<h2>Ma Wishlist</h2>

<?php foreach ($sets as $set): ?>
    <div class="set-card">
        <img src="<?= $set['image_url'] ?>" alt="<?= $set['set_name'] ?>">
        <h3><?= $set['set_name'] ?> (<?= $set['id_set_number'] ?>)</h3>
        <p>Quantité souhaitée : <?= $set['quantity'] ?></p>

        <form method="POST" action="supprime_set.php">
            <input type="hidden" name="set_number" value="<?= $set['id_set_number'] ?>">
            <input type="hidden" name="action" value="wishlist">
            <button type="submit">❌ Supprimer</button>
        </form>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
