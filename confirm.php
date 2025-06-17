<?php
include 'config/config.php';
$token = $_GET['token'] ?? '';
if (!$token) {
    echo "Token manquant.";
    exit;
}
$stmt = $conn->prepare("SELECT * FROM SAE203_user WHERE confirmation_token = ? AND is_confirmed = 0");
$stmt->execute([$token]);
$user = $stmt->fetch();
if (!$user) {
    echo "Lien de confirmation invalide ou déjà utilisé.";
    exit;
}
$update = $conn->prepare("UPDATE SAE203_user SET is_confirmed = 1, confirmation_token = NULL WHERE id = ?");
$update->execute([$user['id']]);
echo "Votre compte est maintenant confirmé. Vous pouvez <a href='connexion.php'>vous connecter</a>.";
?>
