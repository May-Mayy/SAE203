<?php
require 'config/config.php';
include 'includes/header.php';

// Recherche
$search = trim($_GET['search'] ?? '');

// Pagination
$parPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $parPage;

// Requête pour compter les résultats
if ($search) {
    $sqlCount = "SELECT COUNT(*) FROM lego_sets WHERE set_name LIKE :search OR theme_name LIKE :search";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute(['search' => "%$search%"]);
    $total = $stmtCount->fetchColumn();
} else {
    $sqlCount = "SELECT COUNT(*) FROM lego_sets";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute();
    $total = $stmtCount->fetchColumn();
}

$totalPages = max(1, ceil($total / $parPage));

// Requête d'affichage
if ($search) {
    $sql = "SELECT * FROM lego_sets 
            WHERE set_name LIKE :search OR theme_name LIKE :search 
            ORDER BY year_released DESC
            LIMIT :offset, :parPage";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':parPage', $parPage, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $sql = "SELECT * FROM lego_sets 
            ORDER BY year_released DESC
            LIMIT :offset, :parPage";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':parPage', $parPage, PDO::PARAM_INT);
    $stmt->execute();
}
$sets = $stmt->fetchAll();
?>

<h2>Liste des Sets LEGO</h2>

<form method="GET" style="margin-bottom: 2em;">
    <input 
        type="text" 
        name="search" 
        placeholder="Rechercher par nom ou thème..."
        value="<?= htmlspecialchars($search) ?>"
        style="padding: 0.5em; width: 250px;"
    >
    <button type="submit" style="padding: 0.5em;">🔍 Rechercher</button>
</form>

<div class="sets-list">
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
</div>

<?php if ($totalPages > 1): ?>
<nav class="pagination" style="margin-top: 2em; display: flex; gap: 0.5em; align-items: center;">
    <?php
    $link = function($p, $txt = null) use ($search) {
        $params = [];
        if ($search) $params['search'] = $search;
        $params['page'] = $p;
        $txt = $txt ?? $p;
        return '<a href="?' . http_build_query($params) . '">' . $txt . '</a>';
    };

    $window = 4;
    $start = max(1, $page - 2);
    $end = min($totalPages, $start + $window - 1);
    if ($end - $start < $window - 1) $start = max(1, $end - $window + 1);

    // << flèche vers début
    if ($page > 1) echo $link(1, '«');
    // Page précédente
    if ($page > 1) echo $link($page - 1, '<');

    // Pages du milieu
    for ($p = $start; $p <= $end; $p++) {
        if ($p == $page) {
            echo '<strong>' . $p . '</strong>';
        } else {
            echo $link($p);
        }
    }

    // Page suivante
    if ($page < $totalPages) echo $link($page + 1, '>');
    // >> flèche vers fin
    if ($page < $totalPages) echo $link($totalPages, '»');
    ?>
</nav>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

<head>
    <link rel="stylesheet" href="../SAE203/style/header.css">
    <link rel="stylesheet" href="../SAE203/style/sets.css">
    <link rel="icon" type="image/png" href="/images/lego-logo-1.png">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>