<?php
include 'config/config.php';
$pseudo = $_POST['pseudo'];
$email = $_POST['email'];
$pass = $_POST['password'];
$confirm = $_POST['confirm'];

if ($pass !== $confirm) {
    die("Les mots de passe ne correspondent pas.");
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    die("Email déjà utilisé.");
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (pseudo, email, mot_de_passe, date_inscription) VALUES (?, ?, ?, NOW())");
$stmt->execute([$pseudo, $email, $hash]);

header("Location: connexion.php");
exit;
