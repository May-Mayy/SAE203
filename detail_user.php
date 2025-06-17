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
    <h2>Profil de <?= htmlspecialchars($user['pseudo']) ?></h2>
    <p>Date d'inscription : <?= $user['date_inscription'] ?></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
