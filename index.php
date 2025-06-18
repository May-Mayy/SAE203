<?php
include './config/config.php';
include './includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$nbSets = $pdo->query("SELECT COUNT(*) FROM lego_sets")->fetchColumn();
$nbUsers = $pdo->query("SELECT COUNT(*) FROM SAE203_user")->fetchColumn();


$lastCommented = $pdo->query("
    SELECT s.id_set_number, s.set_name, s.image_url
    FROM SAE203_comment c
    JOIN lego_sets s ON s.id_set_number = c.id_set_number
    ORDER BY c.date_commentaire DESC
    LIMIT 3
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestionnaire de Sets LEGO</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .intro {
            text-align: center;
            padding: 30px;
            background: linear-gradient(to right, #ffe600, #ff3030);
            color: white;
            border-radius: 10px;
            margin: 40px auto;
            max-width: 700px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .intro h2 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .intro p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stats p {
            font-size: 20px;
            font-weight: bold;
            background-color: #ffffff;
            color: #ff3030;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

<section class="intro">
    <h2>Bienvenue sur le gestionnaire de sets LEGO</h2>
    <p>Découvrez, gérez et partagez votre collection LEGO avec la communauté.</p>
    <div class="stats">
        <p>Nombre de sets : <?= $nbSets ?></p>
        <p>Nombre d'utilisateurs : <?= $nbUsers ?></p>
    </div>
</section>

</body>
</html>
