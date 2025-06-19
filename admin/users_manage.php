<?php
include '../includes/admin_auth.php';
include '../includes/header.php';

$stmt = $conn->query("SELECT * FROM SAE203_user ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<h2>Gestion des utilisateurs</h2>
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
<?php include '../includes/footer.php'; ?>
