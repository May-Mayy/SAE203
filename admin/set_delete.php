<?php
include '../includes/admin_auth.php';

if (empty($_GET['id'])) {
    die("Aucun set spécifié.");
}
$id = $_GET['id'];

// Tu pourrais ajouter des vérifs supplémentaires (s'il existe, etc.)
$stmt = $conn->prepare("DELETE FROM lego_sets WHERE id_set_number = ?");
$stmt->execute([$id]);

header("Location: sets_manage.php");
exit;
