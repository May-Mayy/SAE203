<?php include 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
if (empty($_GET['id'])) {
    die("Aucun identifiant de set fourni.");
}

$id = $_GET['id'];

// Préparer la requête
$stmt = $conn->prepare("SELECT * FROM lego_sets WHERE id_set_number = ?");
$stmt->execute([$id]);
$set = $stmt->fetch();

if (!$set): ?>
    <p>Ce set n'existe pas ou est introuvable.</p>
<?php else: ?>
    <h2><?= htmlspecialchars($set['set_name']) ?></h2>

    <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
        <img src="<?= htmlspecialchars($set['image_url']) ?: 'placeholder.jpg' ?>" 
             alt="<?= htmlspecialchars($set['set_name']) ?>" 
             style="max-width: 300px; border: 1px solid #ccc; background: #f9f9f9;">

        <ul style="list-style: none; padding: 0; font-size: 16px;">
            <li><strong>Année :</strong> <?= htmlspecialchars($set['year_released']) ?></li>
            <li><strong>Pièces :</strong> <?= htmlspecialchars($set['number_of_parts']) ?></li>
            <li><strong>Thème :</strong> <?= htmlspecialchars($set['theme_name']) ?></li>
            <li><strong>Numéro de set :</strong> <?= htmlspecialchars($set['id_set_number']) ?></li>
        </ul>
    </div>

    <a href="sets.php" style="text-decoration: none; color: #007BFF;">← Retour à la liste des sets</a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
