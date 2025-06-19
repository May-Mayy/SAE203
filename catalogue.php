<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

// Vérification connexion
if (!isset($_SESSION['id_user'])) {
    die("Vous devez être connecté pour accéder à votre catalogue.");
}

// Affichage des sets (on peut filtrer plus tard)
$sql = "SELECT * FROM lego_sets ORDER BY year_released DESC LIMIT 50";
$stmt = $conn->query($sql);
$sets = $stmt->fetchAll();
?>

<h2>Catalogue LEGO</h2>

<div class="sets-list">
<?php foreach ($sets as $set): ?>
    <div class="set-card">
        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
        <h3><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h3>
        <form method="POST" action="ajouter_set.php">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="action" value="wishlist">💖 Wishlist</button>
            <button type="submit" name="action" value="possede">📦 Possédé</button>
        </form>
    </div>
<?php endforeach; ?>
</div>


