<?php
include 'config/config.php';
$pseudo = $_POST['pseudo'];
$email = $_POST['email'];
$pass = $_POST['password'];
$confirm = $_POST['confirm'];

if ($pass !== $confirm) {
    die("Les mots de passe ne correspondent pas.");
}

$stmt = $conn->prepare("SELECT * FROM SAE203_user WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    die("Email déjà utilisé.");
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(16));
$stmt = $conn->prepare("INSERT INTO SAE203_user (pseudo, email, mot_de_passe, date_inscription, confirmation_token, is_confirmed) VALUES (?, ?, ?, NOW(), ?, 0)");
$stmt->execute([$pseudo, $email, $hash, $token]);

$confirmLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/confirm.php?token=" . $token;
$subject = 'Confirmation de votre inscription';
$message = "Bonjour $pseudo,\n\nCliquez sur le lien suivant pour confirmer votre inscription : $confirmLink";
$headers = 'From: no-reply@example.com';
mail($email, $subject, $message, $headers);

echo "Un email de confirmation vous a été envoyé.";
