<?php include 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="bienvenue">
<section class="intro">
    <h2 id="text">Bienvenue sur le gestionnaire de sets LEGO</h2>
    <p>Découvrez, gérez et partagez votre collection LEGO avec la communauté.</p>
    <div class="stats">
        <?php
        $setsCount =  $conn->query("SELECT COUNT(*) FROM lego_sets")->fetchColumn();
        $usersCount =  $conn->query("SELECT COUNT(*) FROM SAE203_user")->fetchColumn();
        ?>
        <p>Nombre de sets : <?= $setsCount ?></p>
        <p>Nombre d'utilisateurs : <?= $usersCount ?></p>
    </div>
</section>
</div>
<head>
    <link rel="stylesheet" href="style/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

</head>

<style>
    .intro {
        text-align: center;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 600px;
    }
    .intro h2 {
        font-size: 24px;
        margin-bottom: 10px;
    
    }
    .intro p {
        font-size: 16px;
        margin-bottom: 20px;
    }
    .stats {
        display: flex;
        justify-content: space-around;
    }
    .stats p {
        font-size: 18px;
        color: #333;
    }
    .bienvenue {
        margin-top: 100px;
       
    }
