<?php include 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$id = $_GET['id'] ?? '';
$stmt =  $conn->prepare("SELECT * FROM lego_sets WHERE id_set_number = ?");
$stmt->execute([$id]);
$set = $stmt->fetch();

if (!$set): ?>
    <p>Ce set n'existe pas.</p>
<?php else: ?>
    <h2><?= htmlspecialchars($set['set_name']) ?></h2>
    <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" style="max-width:300px;">
    <ul>
        <li>Année : <?= htmlspecialchars($set['year_released']) ?></li>
        <li>Pièces : <?= htmlspecialchars($set['number_of_parts']) ?></li>
        <li>Thème : <?= htmlspecialchars($set['theme_name']) ?></li>
    </ul>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

