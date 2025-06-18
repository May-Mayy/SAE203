<?php include 'includes/header.php'; ?>
<h2>Créer un compte</h2>
<form method="POST" action="verif_inscription.php">
    <input type="text" name="pseudo" required placeholder="Pseudo">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Mot de passe">
    <input type="password" name="confirm" required placeholder="Confirmer le mot de passe">
    <button type="submit">S'inscrire</button>
</form>
<?php include 'includes/footer.php'; ?>

<head>
    <link rel="stylesheet" href="/style/header.css">
    <link rel="stylesheet" href="/style/inscription.css">
    <link rel="icon" type="image/png" href="/images/lego-logo-1.png">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>