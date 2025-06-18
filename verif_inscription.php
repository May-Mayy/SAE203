<?php
include 'config/config.php';

$username = $_POST['username'] ?? '';
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

// S'il y a une colonne date_inscription dans ta BDD, garde-la. Sinon, enlève-la.
$stmt = $conn->prepare("INSERT INTO SAE203_user (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hash]);

header("Location: connexion.php");
exit;
