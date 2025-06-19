<?php
include '../includes/admin_auth.php';
include '../includes/header.php';

// ← Flèche retour vers le dashboard
if (basename($_SERVER['PHP_SELF']) !== 'dashboard.php') : ?>
    <a href="dashboard.php" style="
        display: inline-block;
        margin-bottom: 1em;
        color: #007bff;
        text-decoration: none;
        font-size: 1.15em;
        font-weight: bold;
        transition: color 0.2s;
    " onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#007bff'">
        &#8592; Retour au Dashboard
    </a>
<?php endif;

// Recherche utilisateur (pseudo ou email)
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];
if ($search) {
    $where[] = '(LOWER(username) LIKE :search OR LOWER(email) LIKE :search)';
    $params['search'] = '%' . strtolower($search) . '%';
}
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT * FROM SAE203_user $whereSQL ORDER BY username ASC";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
}
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!-- Barre de recherche -->
<form method="GET" style="margin-bottom: 1em; display: flex; gap: 1em; align-items: center;">
    <input type="text" name="search" placeholder="Recherche par pseudo ou email..." value="<?= htmlspecialchars($search) ?>" style="padding:0.5em; width:300px;">
    <button type="submit" style="padding:0.5em;">🔍 Rechercher</button>
    <?php if ($search): ?>
        <a href="users_manage.php" style="padding:0.5em;color:#555;">Réinitialiser</a>
    <?php endif; ?>
</form>

<h2>Gestion des utilisateurs</h2>

<?php if (count($users) === 0): ?>
    <p style="color:#c00;">Aucun utilisateur ne correspond à votre recherche.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Admin ?</th>
            <th>Date d'inscription</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['is_admin'] ? 'Oui' : 'Non' ?></td>
            <td><?= htmlspecialchars($u['date_inscription']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
