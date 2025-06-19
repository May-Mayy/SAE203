<?php
require 'config/config.php';
include 'includes/header.php';

// Traitement du formulaire
$search = trim($_GET['search'] ?? '');
$users = [];

if ($search) {
    $stmt = $conn->prepare("SELECT id, username FROM SAE203_user WHERE LOWER(username) LIKE :search ORDER BY username ASC");
    $stmt->execute(['search' => '%' . strtolower($search) . '%']);
    $users = $stmt->fetchAll();
} else {
    // Optionnel : afficher tous les utilisateurs, ou rien
    // $users = $conn->query("SELECT id, username FROM SAE203_user ORDER BY username ASC")->fetchAll();
}
?>

<h2>Rechercher un utilisateur</h2>
<form method="GET" style="margin-bottom:2em;">
    <input type="text" name="search" placeholder="Pseudo, nom, etc." value="<?= htmlspecialchars($search) ?>" style="padding:0.5em; width:250px;">
    <button type="submit" style="padding:0.5em;">🔍 Rechercher</button>
    <?php if ($search): ?>
        <a href="users.php" style="margin-left:1em; color:#555;">Réinitialiser</a>
    <?php endif; ?>
</form>

<?php if ($search && empty($users)): ?>
    <p style="color:#c00;">Aucun utilisateur trouvé.</p>
<?php elseif ($users): ?>
    <ul style="list-style:none; padding:0;">
        <?php foreach ($users as $u): ?>
            <li style="margin-bottom:0.7em;">
                <a href="detail_user.php?id=<?= $u['id'] ?>" style="font-weight:bold; color:#007BFF;">
                    <?= htmlspecialchars($u['username']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php elseif ($search): ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
