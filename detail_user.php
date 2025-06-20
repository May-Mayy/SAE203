<?php
include 'config/config.php';
include 'includes/header.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "<p>Utilisateur inconnu.</p>";
    include 'includes/footer.php';
    exit;
}

// Récupération des infos utilisateur
$stmt =  $conn->prepare("SELECT * FROM SAE203_user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user): ?>
    <p>Utilisateur introuvable.</p>
<?php else: ?>
    <h2>Profil de <?= htmlspecialchars($user['username']) ?></h2>
    <p>Date d'inscription : <?= htmlspecialchars($user['date_inscription'] ?? 'inconnue') ?></p>

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

    // Commentaires/notes
    $sqlC = "SELECT c.*, s.set_name 
             FROM SAE203_comment c 
             JOIN lego_sets s ON c.id_lego_set = s.id_set_number
             WHERE c.id_user = ?
             ORDER BY c.post_date DESC";
    $stmtC = $conn->prepare($sqlC);
    $stmtC->execute([$id]);
    $comments = $stmtC->fetchAll();
    ?>

    <h3>💖 Wishlist</h3>
    <?php if (!$wishlist): ?>
        <p>Pas de sets dans la wishlist.</p>
    <?php else: ?>
        <div class="sets-list">
            <?php foreach ($wishlist as $set): ?>
                <div class="set-card">
                    <div class="img-wrapper">
                        <a href="detail_set.php?id=<?= urlencode($set['id_set_number']) ?>">
                            <img class="img_profil" src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
                        </a>
                    </div>
                    <h4><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h4>
                    <p>Quantité : <?= $set['quantity'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3>📦 Sets possédés</h3>
    <?php if (!$owned): ?>
        <p>Pas de sets possédés.</p>
    <?php else: ?>
        <div class="sets-list">
            <?php foreach ($owned as $set): ?>
                <div class="set-card">
                    <a href="detail_set.php?id=<?= urlencode($set['id_set_number']) ?>">
                        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
                    </a>
                    <h4><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h4>
                    <p>Quantité : <?= $set['quantity'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3>📝 Avis & notes</h3>
    <?php if (!$comments): ?>
        <p>Aucun commentaire pour le moment.</p>
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
    <?php endif; ?>

<?php endif; ?>
<style>

    body{
                background-color: #f9f9f9;

    }
    .img_profil {
        max-height: 350px;
        width: auto;
    }

    .set-card img {
        max-width: 20%;
        padding-top: 40px;
    }

    .sets-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
        background-color: #f9f9f9;
    }

    .set-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        min-height: 400px;
    }

    .set-card:hover {
        transform: scale(1.03);
    }

    .set-card img {
        max-width: 100%;
        height: auto;
        margin-bottom: 1rem;
        border-radius: 5px;
    }

    .set-card h3 {
        font-size: 1rem;
        margin: 0.5rem 0 1rem;
        color: #333;
        min-height: 3.5em;
        /* force l’espace du titre pour garder l’alignement */
    }

    .set-card form {
        margin-top: auto;
        /* pousse le bouton en bas */
    }

    .set-card button {
        width: 100%;
        padding: 0.5rem 1rem;
        background-color: #ffd500;
        /* LEGO yellow */
        color: #000;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .set-card button:hover {
        background-color: #ffbb00;
    }

    .img-wrapper {
        height: 160px;
        /* ajuste selon la taille moyenne de tes images */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        border-radius: 5px;
    }
</style>
<!-- <?php include 'includes/footer.php'; ?> -->