# SAE203

## Inscription avec confirmation par email

Le formulaire `inscription.php` permet maintenant de créer un compte qui doit être validé par email. Lors de l'enregistrement, un token de confirmation est généré et envoyé à l'adresse indiquée. L'utilisateur doit cliquer sur le lien reçu pour activer son compte.

Deux champs doivent être ajoutés à la table `SAE203_user` :

```sql
ALTER TABLE SAE203_user
    ADD COLUMN confirmation_token VARCHAR(64) DEFAULT NULL,
    ADD COLUMN is_confirmed TINYINT(1) DEFAULT 0;
```

Le script `confirm.php` traite le lien de confirmation. Une fois validé, le champ `is_confirmed` passe à `1`.

Le fichier `connexion.php` vérifie désormais que l'adresse email a été confirmée avant d'autoriser la connexion.
