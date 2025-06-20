<?php
session_start();
include 'config/config.php';
include 'includes/header.php';

if (!isset($_SESSION['id_user'])) {
    die("Accès refusé. Vous devez être connecté pour voir votre profil.");
}

$id = $_SESSION['id_user'];
$stmt = $conn->prepare("SELECT * FROM SAE203_user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user): ?>
    <p>Utilisateur introuvable.</p>
<?php else: ?>
    <div class="page-container">
        <h2>
            Bienvenue <?= htmlspecialchars($user['username']) ?>
            <?php if (!empty($user['is_admin'])): ?>
                <span style="
                background: #ffcb05;
                color: #222;
                border-radius: 6px;
                padding: 0.15em 0.7em;
                font-size: 0.8em;
                margin-left: 0.5em;
                font-weight: bold;
                vertical-align: middle;
                box-shadow: 0 1px 5px #ffe082;
                letter-spacing: 1px;
            ">👑 Admin</span>
            <?php endif; ?>
            !
        </h2>

        <?php if (!empty($user['is_admin'])): ?>
            <a href="admin/dashboard.php" style="
            display: inline-block;
            padding: 0.5em 1.2em;
            margin-bottom: 1.5em;
            background: #333;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
            margin-top:0.5em;
            transition: background 0.2s;
        " onmouseover="this.style.background='#4c8bf5'" onmouseout="this.style.background='#333'">
                🚦 Dashboard Admin
            </a>
        <?php endif; ?>

        <ul>
            <li>Email : <?= htmlspecialchars($user['email']) ?></li>
            <li>Compte confirmé : <?= $user['is_confirmed'] ? 'Oui' : 'Non' ?></li>
        </ul>

        <?php
        // Wishlist
        $sqlW = "SELECT s.*, w.quantity FROM SAE203_wishlisted_set w
              JOIN lego_sets s ON s.id_set_number = w.id_set_number
              WHERE w.id_user = ?";
        $stmtW = $conn->prepare($sqlW);
        $stmtW->execute([$id]);
        $wishlist = $stmtW->fetchAll();

        // Owned
        $sqlO = "SELECT s.*, o.quantity FROM SAE203_owned_set o
              JOIN lego_sets s ON s.id_set_number = o.id_set_number
              WHERE o.id_user = ?";
        $stmtO = $conn->prepare($sqlO);
        $stmtO->execute([$id]);
        $owned = $stmtO->fetchAll();

        // Mes commentaires/notes
        $sqlC = "SELECT c.*, s.set_name 
             FROM SAE203_comment c 
             JOIN lego_sets s ON c.id_lego_set = s.id_set_number
             WHERE c.id_user = ?
             ORDER BY c.post_date DESC";
        $stmtC = $conn->prepare($sqlC);
        $stmtC->execute([$id]);
        $comments = $stmtC->fetchAll();
        ?>

        <h3>💖 Ma Wishlist</h3>
        <?php if (!$wishlist): ?>
            <p>Aucun set en wishlist.</p>
        <?php else: ?>
            <div class="sets-list">
                <?php foreach ($wishlist as $set): ?>
                    <div class="set-card">
                        <a href="detail_set.php?id=<?= urlencode($set['id_set_number']) ?>">
                            <img class="img_profil" src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
                        </a>
                        <h4><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h4>
                        <p>Quantité : <?= $set['quantity'] ?></p>
                        <!-- Formulaire de suppression -->
                        <form action="retirer_set.php" method="get" style="margin-top: 8px;">
                            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
                            <input type="hidden" name="type" value="wishlist">
                            <button type="submit" onclick="return confirm('Retirer ce set de la wishlist ?');">❌ Retirer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>📦 Mes Sets Possédés</h3>
        <?php if (!$owned): ?>
            <p>Aucun set possédé.</p>
        <?php else: ?>
            <div class="sets-list">
                <?php foreach ($owned as $set): ?>
                    <div class="set-card">
                        <a href="detail_set.php?id=<?= urlencode($set['id_set_number']) ?>">
                            <img class="img_profil" src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
                        </a>
                        <h4><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h4>
                        <p>Quantité : <?= $set['quantity'] ?></p>
                        <!-- Formulaire de suppression -->
                        <form action="retirer_set.php" method="get" style="margin-top: 8px;">
                            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
                            <input type="hidden" name="type" value="possede">
                            <button type="submit" onclick="return confirm('Retirer ce set des possédés ?');">❌ Retirer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- SECTION COMMENTAIRES & NOTES -->
        <h3 style="margin-top:2em;">📝 Mes avis et notes</h3>
        <?php if (!$comments): ?>
            <p>Aucun avis laissé pour le moment.</p>
        <?php else: ?>
            <div class="comments-list">
                <?php foreach ($comments as $com): ?>
                    <div class="comment-card" style="border-bottom:1px solid #ddd; padding:0.7em 0;">
                        <strong>
                            <a href="detail_set.php?id=<?= urlencode($com['id_lego_set']) ?>" style="color:#007BFF;">
                                <?= htmlspecialchars($com['set_name']) ?>
                            </a>
                        </strong>
                        <span><?= str_repeat('⭐', intval($com['rate'])) ?></span>
                        <span style="color:#888; font-size:0.9em;">(<?= date('d/m/Y', strtotime($com['post_date'])) ?>)</span>
                        <div style="margin-top:0.3em;"><?= nl2br(htmlspecialchars($com['text'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
<?php endif; ?>

<?php endif; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    /* Conteneur global sous le header */
    .page-container {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* Titres */
    .page-container h2 {
        font-family: 'Press Start 2P';

        font-size: 1.5rem;
        color: #d82323;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .page-container h3 {
        font-size: 1.5rem;
        margin: 2rem 0 1rem;
        color: #222;
    }

    /* Listes de sets */
    .sets-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        padding: 1rem 0;
    }

    .set-card {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease;
    }

    .set-card:hover {
        transform: scale(1.02);
    }

    .set-card img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    .set-card h4 {
        font-size: 1rem;
        color: #333;
        margin: 0.5rem 0;
    }

    .set-card p {
        margin: 0.2rem 0 0.8rem;
        font-size: 0.95rem;
        color: #666;
    }

    .set-card button {
        padding: 0.5rem;
        background-color: #ffd500;
        color: #000;
        border: none;
        border-radius: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .set-card button:hover {
        background-color: #ffbb00;
    }

    /* Admin badge et dashboard */
    .page-container .admin-badge {
        background: #ffcb05;
        color: #222;
        border-radius: 6px;
        padding: 0.15em 0.7em;
        font-size: 0.8em;
        margin-left: 0.5em;
        font-weight: bold;
        vertical-align: middle;
        box-shadow: 0 1px 5px #ffe082;
        letter-spacing: 1px;
    }

    .page-container .admin-link {
        display: inline-block;
        padding: 0.5em 1.2em;
        background: #333;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        margin: 0.8em 0 1.5em;
        transition: background 0.2s;
    }

    .page-container .admin-link:hover {
        background: #4c8bf5;
    }

    /* Commentaires */
    .comments-list {
        padding: 1rem;
        background: #fafafa;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .comment-card {
        margin-bottom: 1rem;
    }

    .comment-card strong a {
        color: #007BFF;
        text-decoration: none;
    }

    .comment-card span {
        margin-left: 0.5rem;
    }

    /* Ajustement texte */
    .page-container ul {
        list-style: none;
        padding-left: 0;
    }

    .page-container li {
        margin: 0.5rem 0;
        font-size: 1rem;
        color: #444;
    }

    .img_profil{
        max-height: 350px;
        width: auto;
    }
</style>


<?php
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?>