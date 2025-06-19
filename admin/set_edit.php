<?php
include '../includes/admin_auth.php';
include '../includes/header.php';
?>

// ← Flèche retour vers le dashboard (juste ici)
<?php if (basename($_SERVER['PHP_SELF']) !== 'dashboard.php'): ?>
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
<?php endif; ?>

// Récupérer le set si on édite

<?php
$id = $_GET['id'] ?? null;
$set = null;
$editMode = false;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM lego_sets WHERE id_set_number = ?");
    $stmt->execute([$id]);
    $set = $stmt->fetch();
    $editMode = true;
}

// Traitement du formulaire (insert/update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_set_number'   => $_POST['id_set_number'],
        'set_name'        => $_POST['set_name'],
        'theme_name'      => $_POST['theme_name'],
        'year_released'   => $_POST['year_released'],
        'number_of_parts' => $_POST['number_of_parts'],
        'image_url'       => $_POST['image_url'],
    ];

    if ($editMode) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE lego_sets SET set_name=?, theme_name=?, year_released=?, number_of_parts=?, image_url=? WHERE id_set_number=?");
        $stmt->execute([
            $data['set_name'],
            $data['theme_name'],
            $data['year_released'],
            $data['number_of_parts'],
            $data['image_url'],
            $id
        ]);
        echo "<p style='color:green;'>Set mis à jour !</p>";
    } else {
        // INSERT (le numéro de set doit être UNIQUE et NON NULL !)
        $stmt = $conn->prepare("INSERT INTO lego_sets (id_set_number, set_name, theme_name, year_released, number_of_parts, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['id_set_number'],
            $data['set_name'],
            $data['theme_name'],
            $data['year_released'],
            $data['number_of_parts'],
            $data['image_url']
        ]);
        echo "<p style='color:green;'>Set ajouté !</p>";
    }
    echo '<a href="sets_manage.php">Retour à la liste</a>';
    include '../includes/footer.php';
    exit;
}
?>
<h2><?= $editMode ? "Éditer" : "Ajouter" ?> un set LEGO</h2>
<form method="POST">
    <label>Numéro du set :
        <input type="text" name="id_set_number" required value="<?= htmlspecialchars($set['id_set_number'] ?? '') ?>" <?= $editMode ? 'readonly' : '' ?>>
        <?= $editMode ? '<small>(non modifiable)</small>' : '' ?>
    </label><br>
    <label>Nom du set : <input type="text" name="set_name" required value="<?= htmlspecialchars($set['set_name'] ?? '') ?>"></label><br>
    <label>Thème : <input type="text" name="theme_name" required value="<?= htmlspecialchars($set['theme_name'] ?? '') ?>"></label><br>
    <label>Année : <input type="number" name="year_released" required value="<?= htmlspecialchars($set['year_released'] ?? '') ?>"></label><br>
    <label>Nombre de pièces : <input type="number" name="number_of_parts" required value="<?= htmlspecialchars($set['number_of_parts'] ?? '') ?>"></label><br>
    <label>URL de l'image : <input type="text" name="image_url" required value="<?= htmlspecialchars($set['image_url'] ?? '') ?>"></label><br>
    <button type="submit"><?= $editMode ? "Mettre à jour" : "Ajouter" ?></button>
    <a href="sets_manage.php">Annuler</a>
</form>
<?php include '../includes/footer.php'; ?>
