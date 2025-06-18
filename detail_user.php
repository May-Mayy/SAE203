<?php include 'config.php'; ?>
<?php include 'include.php'; ?>

<?php
$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user): ?>
    <p>Utilisateur introuvable.</p>
<?php else: ?>
    <h2>Profil de <?= htmlspecialchars($user['pseudo']) ?></h2>
    <p>Date d'inscription : <?= $user['date_inscription'] ?></p>

    <h3>Wish list</h3>
    <ul>
    <?php
    $wish = $pdo->prepare("SELECT s.set_number, s.set_name FROM user_sets us JOIN sets s ON us.set_number = s.set_number WHERE us.user_id = ? AND us.status = 'wishlist'");
    $wish->execute([$id]);
    foreach ($wish as $set) {
        echo '<li><a href="detail_set.php?id=' . $set['set_number'] . '">' . htmlspecialchars($set['set_name']) . '</a></li>';
    }
    ?>
    </ul>

    <h3>Possédé</h3>
    <ul>
    <?php
    $owned = $pdo->prepare("SELECT s.set_number, s.set_name FROM user_sets us JOIN sets s ON us.set_number = s.set_number WHERE us.user_id = ? AND us.status = 'owned'");
    $owned->execute([$id]);
    foreach ($owned as $set) {
        echo '<li><a href="detail_set.php?id=' . $set['set_number'] . '">' . htmlspecialchars($set['set_name']) . '</a></li>';
    }
    ?>
    </ul>
<?php endif; ?>

<?php include 'includes.php'; ?>
