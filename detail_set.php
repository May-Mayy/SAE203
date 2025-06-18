<?php include 'config.php'; ?>
<?php include 'include.php'; ?>

<?php
$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM sets WHERE set_number = ?");
$stmt->execute([$id]);
$set = $stmt->fetch();
if (!$set): ?>
    <p>Ce set n'existe pas.</p>
<?php else: ?>
    <h2><?= htmlspecialchars($set['set_name']) ?></h2>
    <img src="<?= $set['image_url'] ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
    <ul>
        <li>Année : <?= $set['year_released'] ?></li>
        <li>Pièces : <?= $set['number_of_parts'] ?></li>
        <li>Thème : <?= $set['theme_name'] ?></li>
    </ul>
    <?php if (isset($_SESSION['user'])): ?>
        <form method="POST" action="update_inventory.php">
            <input type="hidden" name="set_number" value="<?= $set['set_number'] ?>">
            <button type="submit" name="status" value="wishlist">Ajouter à la wish list</button>
        </form>
        <form method="POST" action="update_inventory.php">
            <input type="hidden" name="set_number" value="<?= $set['set_number'] ?>">
            <button type="submit" name="status" value="owned">Marquer comme possédé</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes.php'; ?>
