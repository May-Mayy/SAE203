<?php
include '../includes/header.php';
include '../includes/admin_auth.php';
?>

<div class="page-container">


    <a href="../profil.php" style="
    display: inline-block;
    margin-bottom: 1.5em;
    color: #007bff;
    background: #f5f7ff;
    border-radius: 6px;
    padding: 0.5em 1.3em;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    transition: background 0.2s, color 0.2s;
" onmouseover="this.style.background='#dde8ff';this.style.color='#0056b3'" onmouseout="this.style.background='#f5f7ff';this.style.color='#007bff'">
        &#8592; Retour à mon profil
    </a>


    <h2>Tableau de bord administrateur</h2>
    <ul>
        <li><a href="sets_manage.php">Gérer les sets LEGO</a></li>
        <li><a href="comments_manage.php">Gérer les commentaires</a></li>
        <li><a href="users_manage.php">Gérer les utilisateurs</a></li>
    </ul>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    .page-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* Titre */
    .page-container h2 {
        font-family: 'Press Start 2P';
        font-size: 1.5rem;
        color: #d82323;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    /* Lien retour profil */
    .page-container .back-link {
        display: inline-block;
        margin-bottom: 2rem;
        color: #007bff;
        background: #f5f7ff;
        border-radius: 6px;
        padding: 0.5em 1.3em;
        font-weight: bold;
        text-decoration: none;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.10);
        transition: background 0.2s, color 0.2s;
    }

    .page-container .back-link:hover {
        background: #dde8ff;
        color: #0056b3;
    }

    /* Liste des liens d'admin */
    .page-container ul {
        list-style: none;
        padding-left: 0;
        margin-top: 1rem;
    }

    .page-container li {
        margin-bottom: 1rem;
    }

    .page-container li a {
        display: block;
        padding: 0.7em 1em;
        background-color: #ffd500;
        color: #000;
        text-decoration: none;
        border-radius: 12px;
        font-weight: bold;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .page-container li a:hover {
        background-color: #ffbb00;
    }
</style>

<!-- <?php include '../includes/footer.php'; ?> -->