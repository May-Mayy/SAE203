<?php
$isLogged = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion LEGO</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
<header>
    <h1>LEGO Collection</h1>
    <nav>
        <a href="./index.php">Accueil</a>
        <a href="./set.php">Voir tous les sets</a>
        <?php if ($isLogged): ?>
            <a href="./detail_user.php?= $_SESSION['user']['id'] ?>">Mon profil</a>
            <a href="./logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="./connexion.php">Connexion</a>
            <a href="./inscription.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<main>
