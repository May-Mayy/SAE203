<?php include 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$id = $_GET['id'] ?? '';
$stmt =  $conn->prepare("SELECT * FROM SAE203_user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user): ?>
    <p>Utilisateur introuvable.</p>
<?php else: ?>
    <h2>Profil de <?= htmlspecialchars($user['username']) ?></h2>
    <p>Email : <?= htmlspecialchars($user['email']) ?></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
