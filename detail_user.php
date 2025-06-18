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
                <a href="detail_set.php?id=<?= urlencode($set['id_set_number']) ?>">
                    <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
                </a>
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

<?php include 'includes/footer.php'; ?>
