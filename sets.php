<?php
require 'config/config.php';
include 'includes/header.php';

$search = trim($_GET['search'] ?? '');

if ($search) {
    $sql = "SELECT * FROM lego_sets 
            WHERE set_name LIKE :search OR theme_name LIKE :search 
            ORDER BY year_released DESC LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $sql = "SELECT * FROM lego_sets ORDER BY year_released DESC LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}
$sets = $stmt->fetchAll();
?>

<h2>Liste des Sets LEGO</h2>

<form method="GET" style="margin-bottom: 2em;">
    <input 
        type="text" 
        name="search" 
        placeholder="Rechercher par nom ou thème..."
        value="<?= htmlspecialchars($search) ?>"
        style="padding: 0.5em; width: 250px;"
    >
    <button type="submit" style="padding: 0.5em;">🔍 Rechercher</button>
</form>

<div class="sets-list">
<?php foreach ($sets as $set): ?>
    <div class="set-card">
        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
        <h3><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h3>
        <form action="detail_set.php" method="GET">
            <input type="hidden" name="id" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <button type="submit">🔍 Visualiser</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
