<?php
include '../includes/admin_auth.php';
include '../includes/header.php';

// Suppression d'un commentaire si demandé
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del = $conn->prepare("DELETE FROM SAE203_comment WHERE id = ?");
    $del->execute([$id]);
    header('Location: comments_manage.php');
    exit;
}

// Affichage des commentaires
$stmt = $conn->query(
    "SELECT c.*, u.username, s.set_name 
     FROM SAE203_comment c
     JOIN SAE203_user u ON c.id_user = u.id
     JOIN lego_sets s ON c.id_lego_set = s.id_set_number
     ORDER BY c.post_date DESC"
);
$comments = $stmt->fetchAll();
?>
<h2>Gestion des commentaires</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Utilisateur</th>
        <th>Set</th>
        <th>Note</th>
        <th>Commentaire</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($comments as $com): ?>
    <tr>
        <td><?= htmlspecialchars($com['username']) ?></td>
        <td><?= htmlspecialchars($com['set_name']) ?></td>
        <td><?= str_repeat('⭐', intval($com['rate'])) ?></td>
        <td><?= htmlspecialchars($com['text']) ?></td>
        <td><?= htmlspecialchars($com['post_date']) ?></td>
        <td>
            <a href="?delete=<?= $com['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>
