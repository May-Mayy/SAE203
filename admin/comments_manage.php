<?php
include '../includes/admin_auth.php';
include '../includes/header.php';

// Flèche retour dashboard
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

// Suppression d'un commentaire si demandé
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del = $conn->prepare("DELETE FROM SAE203_comment WHERE id = ?");
    $del->execute([$id]);
    header('Location: comments_manage.php');
    exit;
}

// Barre de recherche commentaires (texte ou pseudo user)
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];

if ($search) {
    $where[] = '(LOWER(c.text) LIKE :search OR LOWER(u.username) LIKE :search)';
    $params['search'] = '%' . strtolower($search) . '%';
}
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Récupérer les commentaires filtrés
$sql = "SELECT c.*, u.username, s.set_name
        FROM SAE203_comment c
        JOIN SAE203_user u ON u.id = c.id_user
        JOIN lego_sets s ON s.id_set_number = c.id_lego_set
        $whereSQL
        ORDER BY c.post_date DESC";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
}
$stmt->execute();
$comments = $stmt->fetchAll();
?>

<h2>Gestion des commentaires</h2>

<!-- Barre de recherche -->
<form method="GET" style="margin-bottom: 1em; display: flex; gap: 1em; align-items: center;">
    <input type="text" name="search" placeholder="Recherche texte ou utilisateur..." value="<?= htmlspecialchars($search) ?>" style="padding:0.5em; width:300px;">
    <button type="submit" style="padding:0.5em;">🔍 Rechercher</button>
    <?php if ($search): ?>
        <a href="comments_manage.php" style="padding:0.5em;color:#555;">Réinitialiser</a>
    <?php endif; ?>
</form>

<?php if (count($comments) === 0): ?>
    <p style="color:#c00;">Aucun commentaire ne correspond à votre recherche.</p>
<?php else: ?>
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
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
