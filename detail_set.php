<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

if (empty($_GET['id'])) {
    die("Aucun identifiant de set fourni.");
}
$id_set_number = $_GET['id'];

// Récupérer le set
$stmt = $conn->prepare("SELECT * FROM lego_sets WHERE id_set_number = :id_set_number");
$stmt->execute(['id_set_number' => $id_set_number]);
$set = $stmt->fetch();

if (!$set): ?>
    <p>Ce set n'existe pas ou est introuvable.</p>
    <a href="sets.php">← Retour à la liste</a>
<?php else:
    $id_user = $_SESSION['id_user'] ?? null;
    $isWishlisted = false;
    $isOwned = false;
    if ($id_user) {
        // Déjà en wishlist ?
        $stmt = $conn->prepare("SELECT 1 FROM SAE203_wishlisted_set WHERE id_user = :id_user AND id_set_number = :id_set_number");
        $stmt->execute(['id_user' => $id_user, 'id_set_number' => $id_set_number]);
        $isWishlisted = (bool)$stmt->fetch();

        // Déjà possédé ?
        $stmt = $conn->prepare("SELECT 1 FROM SAE203_owned_set WHERE id_user = :id_user AND id_set_number = :id_set_number");
        $stmt->execute(['id_user' => $id_user, 'id_set_number' => $id_set_number]);
        $isOwned = (bool)$stmt->fetch();
    }
?>
    <h2><?= htmlspecialchars($set['set_name']) ?></h2>
    <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" style="max-width: 300px; border: 1px solid #ccc; background: #f9f9f9;">
        <ul style="list-style: none; padding: 0; font-size: 16px;">
            <li><strong>Année :</strong> <?= htmlspecialchars($set['year_released']) ?></li>
            <li><strong>Pièces :</strong> <?= htmlspecialchars($set['number_of_parts']) ?></li>
            <li><strong>Thème :</strong> <?= htmlspecialchars($set['theme_name']) ?></li>
            <li><strong>Numéro de set :</strong> <?= htmlspecialchars($set['id_set_number']) ?></li>
        </ul>
    </div>

    <?php if ($id_user): ?>
        <form method="POST" action="ajouter_set.php" style="display:inline;">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" name="action" value="wishlist" <?= $isWishlisted ? 'disabled' : '' ?>>
                <?= $isWishlisted ? "Déjà dans la wishlist" : "💖 Ajouter à la wishlist" ?>
            </button>
        </form>
        <form method="POST" action="ajouter_set.php" style="display:inline;">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" name="action" value="possede" <?= $isOwned ? 'disabled' : '' ?>>
                <?= $isOwned ? "Déjà possédé" : "📦 Ajouter à mes sets" ?>
            </button>
        </form>
    <?php else: ?>
        <p><em>Connecte-toi pour ajouter ce set à ta collection !</em></p>
    <?php endif; ?>

    <a href="sets.php" style="text-decoration: none; color: #007BFF;">← Retour à la liste des sets</a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
