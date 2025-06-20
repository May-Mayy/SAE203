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
<div class="page-container">
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
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    .page-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* Titre */
    .page-container h2 {
        font-family: 'Press Start 2P';
        font-size: 1.5rem;
        color: #d82323;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    /* Formulaire de recherche */
    .page-container form {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2em;
    }

    .page-container input[type="text"] {
        padding: 0.6em;
        border: 1px solid #ccc;
        border-radius: 10px;
        width: 250px;
        font-size: 1rem;
    }

    .page-container button {
        padding: 0.6em 1em;
        background-color: #ffd500;
        color: #000;
        border: none;
        border-radius: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .page-container button:hover {
        background-color: #ffbb00;
    }

    .page-container a {
        color: #007BFF;
        text-decoration: none;
        font-weight: bold;
    }

    .page-container a:hover {
        text-decoration: underline;
    }

    .page-container .reset-link {
        margin-left: 1em;
        color: #666;
        font-size: 0.95rem;
    }

    /* Résultats */
    .page-container ul {
        list-style: none;
        padding-left: 0;
    }

    .page-container li {
        margin-bottom: 0.7em;
        font-size: 1.1rem;
    }

    .page-container p {
        text-align: center;
        font-size: 1rem;
        margin-top: 1rem;
    }

    .page-container p[style*="color:#c00"] {
        color: #c00;
        font-weight: bold;
    }
</style>

<!-- <?php include 'includes/footer.php'; ?> -->