<?php
require 'config/config.php';
include 'includes/header.php';

$sql = "SELECT * FROM lego_sets ORDER BY year_released DESC LIMIT 50";
$stmt = $conn->query($sql);
$sets = $stmt->fetchAll();
?>

<h2>Liste des Sets LEGO</h2>

<div class="sets-list">
<?php foreach ($sets as $set): ?>
    <div class="set-card">
        <img src="<?= $set['image_url'] ?>" alt="<?= $set['set_name'] ?>">
        <h3><?= $set['set_name'] ?> (<?= $set['id_set_number'] ?>)</h3>
    </div>
<?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
