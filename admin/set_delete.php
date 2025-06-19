<?php
include '../includes/admin_auth.php';
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

<?php
if (empty($_GET['id'])) {
    die("Aucun set spécifié.");
}
$id = $_GET['id'];

// Tu pourrais ajouter des vérifs supplémentaires (s'il existe, etc.)
$stmt = $conn->prepare("DELETE FROM lego_sets WHERE id_set_number = ?");
$stmt->execute([$id]);

header("Location: sets_manage.php");
exit;

?>