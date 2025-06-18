<?php include './config.php'; ?>
<?php include './include.php'; ?>

<section class="intro">
    <h2>Bienvenue sur le gestionnaire de sets LEGO</h2>
    <p>Découvrez, gérez et partagez votre collection LEGO avec la communauté.</p>
    <div class="stats">
        <?php
        $setsCount = $pdo->query("SELECT COUNT(*) FROM sets")->fetchColumn();
        $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        ?>
        <p>Nombre de sets : <?= $setsCount ?></p>
        <p>Nombre d'utilisateurs : <?= $usersCount ?></p>
    </div>
</section>

<?php include './includes.php'; ?>
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
