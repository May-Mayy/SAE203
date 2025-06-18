<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
$isLogged = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Brick List</title>
    <link rel="stylesheet" href="/style/style.css"> 
    <link rel="icon" type="image/png" href="/images/lego-logo-1.png"> 
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <img src="../images/lego-logo-1.png" alt="Logo LEGO" class="logo">
        <h1>Bienvenue sur Brick List</h1>
    </header>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../connexion.php">Connexion</a>
        <a href="../inscription.php">Inscription</a>
        <a href="../catalogue.php">Catalogue</a>
    </nav>




