# SAE203

## Inscription avec confirmation par email

Le formulaire `inscription.php` permet maintenant de créer un compte qui doit être validé par email. Lors de l'enregistrement, un token de confirmation est généré et envoyé à l'adresse indiquée. L'utilisateur doit cliquer sur le lien reçu pour activer son compte.

La table `SAE203_user` contient les colonnes suivantes :

```
id, username, email, password, confirmation_token, is_confirmed
```

Le script `confirm.php` traite le lien de confirmation reçu par email et active le compte en mettant `is_confirmed` à `1`.

Le fichier `connexion.php` vérifie que cette colonne vaut `1` avant d'autoriser la connexion.
