<?php include 'includes/header.php'; ?>
<h2>Créer un compte</h2>
<form method="POST" action="verif_inscription.php">
    <input type="text" name="username" required placeholder="Nom d'utilisateur">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Mot de passe">
    <input type="password" name="confirm" required placeholder="Confirmer le mot de passe">
    <button type="submit">S'inscrire</button>
</form>
<?php include 'includes/footer.php'; ?>
