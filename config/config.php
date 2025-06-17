<?php
// Fonction pour charger le fichier .env
function loadEnv($file) {
    $vars = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // ignore commentaires
        list($key, $value) = explode('=', $line, 2);
        $vars[trim($key)] = trim($value);
    }
    return $vars;
}

// Charger les variables du fichier .env
$env = loadEnv('.env');

// Connexion à la base avec les variables du .env
try {
    $conn = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
        $env['DB_USER'],
        $env['DB_PASS']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
