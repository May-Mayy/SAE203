<?php
session_start();
include 'includes/header.php';
?>

<h2>Connexion</h2>
<form method="POST" action="./connexion.php">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Mot de passe">
    <button type="submit">Se connecter</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config/config.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt =  $conn->prepare("SELECT * FROM SAE203_user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // L'utilisateur est authentifié → on stocke dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Identifiants incorrects</p>";
    }
}
?>

<?php 
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?>

<head>
    <link rel="stylesheet" href="./style/header.css">
    <link rel="stylesheet" href="./style/connexion.css">
    <link rel="icon" type="image/png" href="./images/lego-logo-1.png">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>