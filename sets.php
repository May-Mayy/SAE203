<?php include 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

<h2>Liste des Sets LEGO</h2>

<form method="GET" class="filter-form">
    <input type="text" name="theme" placeholder="Filtrer par thème">
    <select name="sort">
        <option value="year_released">Par année</option>
        <option value="set_name">Par nom</option>
    </select>
    <button type="submit">Filtrer</button>
</form>

<div class="sets-list">
<?php
$theme = $_GET['theme'] ?? '';
$sort = $_GET['sort'] ?? 'year_released';
$query = "SELECT * FROM lego_sets WHERE theme_name LIKE :theme ORDER BY $sort LIMIT 50";
$stmt =  $conn->prepare($query);
$stmt->execute(['theme' => "%$theme%"]);
$sets = $stmt->fetchAll();
foreach ($sets as $set):
?>
    <div class="set-card">
        <a href="detail_set.php?id=<?= $set['id_set_number'] ?>">
            <img src="<?= $set['image_url'] ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
            <h3><?= htmlspecialchars($set['set_name']) ?></h3>
        </a>
    </div>
<?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
