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
    <h2>Bienvenue <?= htmlspecialchars($user['username']) ?> !</h2>
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
                    <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
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
                    <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
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
    <?php endif; ?>

<?php endif; ?>

<?php 
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php'; 
}
?>
