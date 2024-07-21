<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte</title>
</head>
<body>
    <h1>Bienvenue sur notre plateforme</h1>
    <p>Bonjour {{ $user->name }},</p>
    <p>Votre compte a été créé avec succès. Voici vos informations de connexion :</p>
    <ul>
        <li><strong>Email :</strong> {{ $user->email }}</li>
        <li><strong>Mot de passe :</strong> {{ $password }}</li>
    </ul>
    <p>Veuillez vous connecter et changer votre mot de passe dès que possible.</p>
    <p>Merci,</p>
    <p>L'équipe Noflay</p>
</body>
</html>
