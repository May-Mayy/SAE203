<?php
include '../includes/admin_auth.php';
include '../includes/header.php';

// Recherche et filtres
$search = trim($_GET['search'] ?? '');
$themeFilter = $_GET['theme'] ?? '';
$yearFilter = $_GET['year'] ?? '';

// Pagination
$parPage = 20;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $parPage;

// Récupérer les thèmes distincts
$sqlThemes = "SELECT DISTINCT theme_name FROM lego_sets WHERE theme_name IS NOT NULL AND theme_name != '' ORDER BY theme_name ASC";
$themes = $conn->query($sqlThemes)->fetchAll(PDO::FETCH_COLUMN);

// Récupérer les années distinctes
$sqlYears = "SELECT DISTINCT year_released FROM lego_sets WHERE year_released IS NOT NULL AND year_released != '' ORDER BY year_released DESC";
$years = $conn->query($sqlYears)->fetchAll(PDO::FETCH_COLUMN);

// Préparer le WHERE dynamique
$where = [];
$params = [];

if ($search) {
    $where[] = "(LOWER(set_name) LIKE :search OR LOWER(theme_name) LIKE :search)";
    $params['search'] = '%' . strtolower($search) . '%';
}
if ($themeFilter) {
    $where[] = "theme_name = :theme";
    $params['theme'] = $themeFilter;
}
if ($yearFilter) {
    $where[] = "year_released = :year";
    $params['year'] = $yearFilter;
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Compter le nombre total de sets filtrés
$sqlCount = "SELECT COUNT(*) FROM lego_sets $whereSQL";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute($params);
$total = $stmtCount->fetchColumn();
$totalPages = max(1, ceil($total / $parPage));

// Récupérer les sets filtrés à afficher sur cette page
$sql = "SELECT * FROM lego_sets $whereSQL ORDER BY year_released DESC LIMIT :offset, :parPage";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':parPage', $parPage, PDO::PARAM_INT);
$stmt->execute();
$sets = $stmt->fetchAll();
?>

<h2>Gestion des Sets LEGO</h2>
<a href="set_edit.php" style="margin-bottom:1em;display:inline-block;">➕ Ajouter un set</a>

<form method="GET" style="margin-bottom: 2em; display: flex; gap: 1em; align-items: center;">
    <input 
        type="text" 
        name="search" 
        placeholder="Rechercher par nom ou thème..."
        value="<?= htmlspecialchars($search) ?>"
        style="padding: 0.5em; width: 250px;"
    >
    <select name="theme" style="padding: 0.5em;">
        <option value="">Tous les thèmes</option>
        <?php foreach ($themes as $theme): ?>
            <option value="<?= htmlspecialchars($theme) ?>" <?= $theme === $themeFilter ? 'selected' : '' ?>>
                <?= htmlspecialchars($theme) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="year" style="padding: 0.5em;">
        <option value="">Toutes les années</option>
        <?php foreach ($years as $year): ?>
            <option value="<?= htmlspecialchars($year) ?>" <?= $year == $yearFilter ? 'selected' : '' ?>>
                <?= htmlspecialchars($year) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" style="padding: 0.5em;">🔍 Filtrer</button>
    <?php if ($search || $themeFilter || $yearFilter): ?>
        <a href="sets_manage.php" style="padding:0.5em;color:#555;">Réinitialiser</a>
    <?php endif; ?>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>Image</th>
        <th>Nom</th>
        <th>Thème</th>
        <th>Année</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($sets as $set): ?>
        <tr>
            <td><img src="<?= htmlspecialchars($set['image_url']) ?>" style="height:40px"></td>
            <td><?= htmlspecialchars($set['set_name']) ?></td>
            <td><?= htmlspecialchars($set['theme_name']) ?></td>
            <td><?= htmlspecialchars($set['year_released']) ?></td>
            <td>
                <a href="set_edit.php?id=<?= $set['id_set_number'] ?>">Éditer</a> | 
                <a href="set_delete.php?id=<?= $set['id_set_number'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($totalPages > 1): ?>
<nav class="pagination" style="margin-top: 2em; display: flex; gap: 0.5em; align-items: center;">
    <?php
    $link = function($p) use ($search, $themeFilter, $yearFilter) {
        $params = [];
        if ($search) $params['search'] = $search;
        if ($themeFilter) $params['theme'] = $themeFilter;
        if ($yearFilter) $params['year'] = $yearFilter;
        $params['page'] = $p;
        return '<a href="?' . http_build_query($params) . '">' . $p . '</a>';
    };

    $window = 4;
    $start = max(1, $page - 2);
    $end = min($totalPages, $start + $window - 1);
    if ($end - $start < $window - 1) $start = max(1, $end - $window + 1);

    if ($page > 1) echo $link(1, '«');
    if ($page > 1) echo $link($page - 1, '<');

    for ($p = $start; $p <= $end; $p++) {
        if ($p == $page) {
            echo '<strong>' . $p . '</strong>';
        } else {
            echo $link($p);
        }
    }

    if ($page < $totalPages) echo $link($page + 1, '>');
    if ($page < $totalPages) echo $link($totalPages, '»');
    ?>
</nav>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
