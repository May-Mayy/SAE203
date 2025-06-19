<?php
include '../includes/admin_auth.php';
include '../includes/header.php';
?>


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
<?php include '../includes/footer.php'; ?>
