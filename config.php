<?php
$host = 'web-mmi2.iutbeziers.fr';
$dbname = 'maylis.gaidot';
$username = 'maylis.gaidot';
$password = '22407104';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
session_start();
?>
