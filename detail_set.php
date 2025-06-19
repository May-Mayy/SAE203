<?php
session_start();
require 'config/config.php';
include 'includes/header.php';

if (empty($_GET['id'])) {
    die("Aucun identifiant de set fourni.");
}
$id_set_number = $_GET['id'];

// Récupérer le set
$stmt = $conn->prepare("SELECT * FROM lego_sets WHERE id_set_number = :id_set_number");
$stmt->execute(['id_set_number' => $id_set_number]);
$set = $stmt->fetch();

if (!$set): ?>
    <p>Ce set n'existe pas ou est introuvable.</p>
    <a href="sets.php">← Retour à la liste</a>
<?php
else:
    $id_user = $_SESSION['id_user'] ?? null;
    $isWishlisted = false;
    $isOwned = false;
    if ($id_user) {
        // Déjà en wishlist ?
        $stmt = $conn->prepare("SELECT 1 FROM SAE203_wishlisted_set WHERE id_user = :id_user AND id_set_number = :id_set_number");
        $stmt->execute(['id_user' => $id_user, 'id_set_number' => $id_set_number]);
        $isWishlisted = (bool)$stmt->fetch();

        // Déjà possédé ?
        $stmt = $conn->prepare("SELECT 1 FROM SAE203_owned_set WHERE id_user = :id_user AND id_set_number = :id_set_number");
        $stmt->execute(['id_user' => $id_user, 'id_set_number' => $id_set_number]);
        $isOwned = (bool)$stmt->fetch();
    }

    // NOTE MOYENNE pour ce set
    $sqlAvg = "SELECT AVG(rate) as avg_note, COUNT(*) as nb_notes FROM SAE203_comment WHERE id_lego_set = ?";
    $stmtAvg = $conn->prepare($sqlAvg);
    $stmtAvg->execute([$set['id_set_number']]);
    $note = $stmtAvg->fetch();

    // LISTE DES UTILISATEURS qui possèdent ce set
    $sqlOwners = "SELECT u.id, u.username
                  FROM SAE203_owned_set o
                  JOIN SAE203_user u ON o.id_user = u.id
                  WHERE o.id_set_number = ?";
    $stmtOwners = $conn->prepare($sqlOwners);
    $stmtOwners->execute([$set['id_set_number']]);
    $owners = $stmtOwners->fetchAll();

    // LISTE DES UTILISATEURS qui souhaitent ce set (wishlist)
    $sqlWishers = "SELECT u.id, u.username
                   FROM SAE203_wishlisted_set w
                   JOIN SAE203_user u ON w.id_user = u.id
                   WHERE w.id_set_number = ?";
    $stmtWishers = $conn->prepare($sqlWishers);
    $stmtWishers->execute([$set['id_set_number']]);
    $wishers = $stmtWishers->fetchAll();
?>

    <h2><?= htmlspecialchars($set['set_name']) ?></h2>
    <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
        <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" style="max-width: 300px; border: 1px solid #ccc; background: #f9f9f9;">
        <div>
            <ul style="list-style: none; padding: 0; font-size: 16px;">
                <li><strong>Année :</strong> <?= htmlspecialchars($set['year_released']) ?></li>
                <li><strong>Pièces :</strong> <?= htmlspecialchars($set['number_of_parts']) ?></li>
                <li><strong>Thème :</strong> <?= htmlspecialchars($set['theme_name']) ?></li>
                <li><strong>Numéro de set :</strong> <?= htmlspecialchars($set['id_set_number']) ?></li>
            </ul>
            
            <!-- Affichage Note Moyenne -->
            <div style="margin-bottom:1em;">
                <strong>Note moyenne : </strong>
                <?php if ($note['nb_notes'] > 0): ?>
                    <span style="color:gold; font-size:1.3em;">
                        <?= str_repeat('⭐', round($note['avg_note'])) ?>
                        <span style="color:#555; font-size:1em;">
                            (<?= number_format($note['avg_note'], 2, ',', ' ') ?>/5, 
                            <?= $note['nb_notes'] ?> avis)
                        </span>
                    </span>
                <?php else: ?>
                    <span style="color:#888;">Aucune note pour ce set</span>
                <?php endif; ?>
            </div>

            <!-- Liste des possesseurs -->
            <div style="margin-bottom:1em;">
                <strong>Utilisateurs possédant ce set :</strong>
                <?php if (count($owners) > 0): ?>
                    <ul style="margin:0.7em 0 0 1.1em; padding:0;">
                    <?php foreach ($owners as $owner): ?>
                        <li>
                            <a href="detail_user.php?id=<?= $owner['id'] ?>" style="color:#006fdc;">
                                <?= htmlspecialchars($owner['username']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span style="color:#888;">Personne ne possède encore ce set</span>
                <?php endif; ?>
            </div>

            <!-- Liste des wishers -->
            <div style="margin-bottom:2em;">
                <strong>Utilisateurs qui voudraient ce set :</strong>
                <?php if (count($wishers) > 0): ?>
                    <ul style="margin:0.7em 0 0 1.1em; padding:0;">
                    <?php foreach ($wishers as $wisher): ?>
                        <li>
                            <a href="detail_user.php?id=<?= $wisher['id'] ?>" style="color:#00a04a;">
                                <?= htmlspecialchars($wisher['username']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span style="color:#888;">Personne n’a ajouté ce set à sa wishlist</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($id_user): ?>
        <form method="POST" action="ajouter_set.php" style="display:inline;">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" name="action" value="wishlist" <?= $isWishlisted ? 'disabled' : '' ?>>
                <?= $isWishlisted ? "Déjà dans la wishlist" : "💖 Ajouter à la wishlist" ?>
            </button>
        </form>
        <form method="POST" action="ajouter_set.php" style="display:inline;">
            <input type="hidden" name="id_set_number" value="<?= htmlspecialchars($set['id_set_number']) ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" name="action" value="possede" <?= $isOwned ? 'disabled' : '' ?>>
                <?= $isOwned ? "Déjà possédé" : "📦 Ajouter à mes sets" ?>
            </button>
        </form>
    <?php else: ?>
        <p><em>Connecte-toi pour ajouter ce set à ta collection !</em></p>
    <?php endif; ?>

    <a href="sets.php" style="text-decoration: none; color: #007BFF;">← Retour à la liste des sets</a>
    
    <!-- SECTION COMMENTAIRES & NOTES -->
    <hr style="margin:2em 0;">
    <section class="comments" style="margin-bottom: 2em;">
        <h3>Commentaires & notes</h3>

        <?php
        // Traitement de l'ajout/modif de commentaire
        if ($id_user && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['rate'])) {
            $comment = trim($_POST['comment']);
            $rate = intval($_POST['rate']);
            if ($rate >= 1 && $rate <= 5 && $comment !== '') {
                // Vérifier si déjà un commentaire pour ce set
                $check = $conn->prepare("SELECT id FROM SAE203_comment WHERE id_user = ? AND id_lego_set = ?");
                $check->execute([$id_user, $id_set_number]);
                if ($check->fetch()) {
                    // Update
                    $update = $conn->prepare("UPDATE SAE203_comment SET text=?, rate=?, post_date=NOW() WHERE id_user=? AND id_lego_set=?");
                    $update->execute([$comment, $rate, $id_user, $id_set_number]);
                    echo "<p style='color:green;'>Commentaire mis à jour !</p>";
                } else {
                    // Insert
                    $insert = $conn->prepare("INSERT INTO SAE203_comment (id_user, id_lego_set, text, rate, post_date) VALUES (?, ?, ?, ?, NOW())");
                    $insert->execute([$id_user, $id_set_number, $comment, $rate]);
                    echo "<p style='color:green;'>Commentaire ajouté !</p>";
                }
            } else {
                echo "<p style='color:red;'>Merci de remplir tous les champs et de mettre une note valide.</p>";
            }
        }

        // Récupérer tous les commentaires pour ce set
        $stmt = $conn->prepare("SELECT c.*, u.username FROM SAE203_comment c JOIN SAE203_user u ON c.id_user = u.id WHERE id_lego_set = ? ORDER BY post_date DESC");
        $stmt->execute([$id_set_number]);
        $comments = $stmt->fetchAll();

        // Récupérer mon commentaire pour pré-remplir si besoin
        $myComment = null;
        if ($id_user) {
            $stmt = $conn->prepare("SELECT * FROM SAE203_comment WHERE id_user = ? AND id_lego_set = ?");
            $stmt->execute([$id_user, $id_set_number]);
            $myComment = $stmt->fetch();
        }
        ?>

        <?php if ($id_user): ?>
            <form method="POST" style="margin-bottom: 1.5em;">
                <label>Votre note :
                    <select name="rate" required>
                        <option value="">-</option>
                        <?php for ($i=1;$i<=5;$i++): ?>
                            <option value="<?= $i ?>" <?= isset($myComment['rate']) && $myComment['rate'] == $i ? 'selected' : '' ?>>
                                <?= str_repeat('⭐', $i) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </label><br>
                <textarea name="comment" placeholder="Votre commentaire..." required rows="3" style="width:100%;margin-top:0.5em;"><?= htmlspecialchars($myComment['text'] ?? '') ?></textarea>
                <br>
                <button type="submit" style="margin-top:0.5em;"><?= $myComment ? 'Mettre à jour' : 'Envoyer' ?></button>
            </form>
        <?php else: ?>
            <p><em>Connectez-vous pour laisser un commentaire.</em></p>
        <?php endif; ?>

        <?php if (count($comments) === 0): ?>
            <p>Aucun commentaire pour ce set. Soyez le premier à donner votre avis !</p>
        <?php else: ?>
            <?php foreach($comments as $c): ?>
                <div style="border-bottom:1px solid #ccc;padding:0.5em 0;">
                    <strong>
                        <a href="detail_user.php?id=<?= $c['id_user'] ?>" style="color:#007BFF;"><?= htmlspecialchars($c['username']) ?></a>
                    </strong>
                    <span><?= str_repeat('⭐', intval($c['rate'])) ?></span>
                    <span style="color:#888;font-size:0.9em;">(<?= date('d/m/Y', strtotime($c['post_date'])) ?>)</span>
                    <div><?= nl2br(htmlspecialchars($c['text'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>