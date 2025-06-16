<?php
$host = 'web-mmi2.iutbeziers.fr';
$dbname = 'emilie.monard';
$username = 'emilie.monard';
$password = '22411865';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
session_start();
?>
