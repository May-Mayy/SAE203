<?php
session_start();
include 'includes/header.php';
?>

<div class="connexion-background">
    <div class="connexion-card">
        <h2>Connexion</h2>
        <form method="POST" action="connexion.php" class="login-form">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Mot de passe">
            <button type="submit">Se connecter</button>
        </form>
    </div>
</div>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    .connexion-background {
        min-height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        background-image: url('includes/images/fond_4.png');
        background-repeat: repeat;
        background-size: 250px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    /* Carte blanche contenant le formulaire */
    .connexion-card {
        background-color: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    /* Titre */
    .connexion-card h2 {
        font-family: 'Press Start 2P';

        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #d82323;
    }

    /* Formulaire stylé */
    .login-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .login-form input {
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .login-form input:focus {
        border-color: #ffd500;
        outline: none;
    }

    .login-form button {
        padding: 0.8rem;
        background-color: #ffd500;
        border: none;
        border-radius: 20px;
        font-weight: bold;
        font-size: 1rem;
        color: #000;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-form button:hover {
        background-color: #ffbb00;
    }
</style>


<?php
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?>