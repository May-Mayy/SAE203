<?php
session_start();
include 'config/config.php';
include 'includes/header.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé. Vous devez être connecté pour voir votre profil.");
}

// Récupérer l'utilisateur connecté
$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM SAE203_user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user): ?>
    <p>Utilisateur introuvable.</p>
<?php else: ?>
    <h2>Bienvenue <?= htmlspecialchars($user['username']) ?> !</h2>

    <ul>
        <li>Email : <?= htmlspecialchars($user['email']) ?></li>
        <li>Compte confirmé : <?= $user['is_confirmed'] ? 'Oui' : 'Non' ?></li>
    </ul>
<?php endif; ?>

<?php 
// Évite l'erreur si le footer n'existe pas encore
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php'; 
}
?>
