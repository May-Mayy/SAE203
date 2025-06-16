<?php include 'includes/header.php'; ?>
<h2>Connexion</h2>
<form method="POST" action="connexion.php">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Mot de passe">
    <button type="submit">Se connecter</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config/config.php';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'], $user['mot_de_passe'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
    } else {
        echo "<p>Identifiants incorrects</p>";
    }
}
include 'includes/footer.php';
?>
