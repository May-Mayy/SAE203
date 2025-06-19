<?php
require 'config/config.php';
include 'includes/header.php';

// Recherche et filtre
$search = trim($_GET['search'] ?? '');
$themeFilter = $_GET['theme'] ?? '';
$yearMin = $_GET['year_min'] ?? '';
$yearMax = $_GET['year_max'] ?? '';

// Pagination
$parPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $parPage;

// 1. Récupérer les thèmes distincts
$sqlThemes = "SELECT DISTINCT theme_name FROM lego_sets WHERE theme_name IS NOT NULL AND theme_name != '' ORDER BY theme_name ASC";
$themes = $conn->query($sqlThemes)->fetchAll(PDO::FETCH_COLUMN);

// 1b. Récupérer toutes les années distinctes
$sqlYears = "SELECT DISTINCT year_released FROM lego_sets WHERE year_released IS NOT NULL AND year_released != '' ORDER BY year_released DESC";
$years = $conn->query($sqlYears)->fetchAll(PDO::FETCH_COLUMN);

// 2. Préparer le WHERE dynamique
$where = [];
$params = [];

// Recherche insensible à la casse sur set_name **et** theme_name
if ($search) {
    $where[] = "(LOWER(set_name) LIKE :search OR LOWER(theme_name) LIKE :search)";
    $params['search'] = '%' . strtolower($search) . '%';
}
if ($themeFilter) {
    $where[] = "theme_name = :theme";
    $params['theme'] = $themeFilter;
}
if ($yearMin) {
    $where[] = "year_released >= :year_min";
    $params['year_min'] = $yearMin;
}
if ($yearMax) {
    $where[] = "year_released <= :year_max";
    $params['year_max'] = $yearMax;
}
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// 3. Compter les résultats
$sqlCount = "SELECT COUNT(*) FROM lego_sets $whereSQL";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute($params);
$total = $stmtCount->fetchColumn();
$totalPages = max(1, ceil($total / $parPage));

// 4. Récupérer les sets à afficher
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

<h2>Liste des Sets LEGO</h2>

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
    <select name="year_min" style="padding: 0.5em;">
        <option value="">Année min</option>
        <?php foreach ($years as $year): ?>
            <option value="<?= htmlspecialchars($year) ?>" <?= ($year == $yearMin) ? 'selected' : '' ?>>
                <?= htmlspecialchars($year) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="year_max" style="padding: 0.5em;">
        <option value="">Année max</option>
        <?php foreach ($years as $year): ?>
            <option value="<?= htmlspecialchars($year) ?>" <?= ($year == $yearMax) ? 'selected' : '' ?>>
                <?= htmlspecialchars($year) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" style="padding: 0.5em;">🔍 Rechercher</button>
    <?php if ($search || $themeFilter || $yearMin || $yearMax): ?>
        <a href="sets.php" style="padding:0.5em;color:#555;">Réinitialiser</a>
    <?php endif; ?>
</form>

<div class="sets-list">
<?php if (count($sets) === 0): ?>
    <div style="padding:2em; text-align:center; color:#c00; font-size:1.2em;">
        Aucun LEGO ne correspond à votre recherche 😢
    </div>
<?php else: ?>
    <?php foreach ($sets as $set): ?>
        <div class="set-card">
            <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>">
            <h3><?= htmlspecialchars($set['set_name']) ?> (<?= htmlspecialchars($set['id_set_number']) ?>)</h3>
            <form action="detail_set.php" method="GET">
                <input type="hidden" name="id" value="<?= htmlspecialchars($set['id_set_number']) ?>">
                <button type="submit">🔍 Visualiser</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<nav class="pagination" style="margin-top: 2em; display: flex; gap: 0.5em; align-items: center;">
    <?php
    $link = function($p, $txt = null) use ($search, $themeFilter, $yearMin, $yearMax) {
        $params = [];
        if ($search) $params['search'] = $search;
        if ($themeFilter) $params['theme'] = $themeFilter;
        if ($yearMin) $params['year_min'] = $yearMin;
        if ($yearMax) $params['year_max'] = $yearMax;
        $params['page'] = $p;
        $txt = $txt ?? $p;
        return '<a href="?' . http_build_query($params) . '">' . $txt . '</a>';
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

<?php include 'includes/footer.php'; ?>
