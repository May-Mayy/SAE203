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
    <title>Gestion LEGO</title>
    <link rel="stylesheet" href="style/header.css">
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

<body>
<header>
    <h1>LEGO Collection</h1>
   <nav style="background: #ffc600; padding: 15px;">
    <strong>LEGO Collection</strong>
    <a href="users.php">🔎 Rechercher un profil</a>
    <a href="index.php">Accueil</a>
    <a href="sets.php">Voir tous les sets</a>

   <?php if (isset($_SESSION['id_user'])): ?>
    <a href="profil.php">Mon profil</a>
    <a href="logout.php">Déconnexion</a>
<?php else: ?>
    <a href="connexion.php">Connexion</a>
    <a href="inscription.php">Inscription</a>
<?php endif; ?>

</nav>
</header>
<main>

