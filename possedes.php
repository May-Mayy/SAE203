<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) die("Non connecté.");

$sql = "SELECT s.*, o.quantity FROM SAE203_owned_set o
        JOIN lego_sets s ON s.id_set_number = o.id_set_number
        WHERE o.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_user]);
$sets = $stmt->fetchAll();
?>

<h2>Mes Sets Possédés</h2>

<?php foreach ($sets as $set): ?>
    <div class="set-card">
        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
        <h3><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h3>
        <p>Quantité : <?= $set['quantity'] ?></p>
        <form method="POST" action="supprime_set.php">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="hidden" name="action" value="possede">
            <button type="submit">❌ Supprimer</button>
        </form>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
