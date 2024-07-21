<!-- resources/views/emails/landlord_account_created.blade.php -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Création de compte bailleur</title>
</head>

<body>
    <h1>Bienvenue sur notre plateforme</h1>
    <p>Votre compte a été créé avec succès. Voici vos informations de connexion :</p>
    <p>Email : {{ $email }}</p>
    <p>Mot de passe : {{ $password }}</p>
    <p>Merci de vous connecter et de changer votre mot de passe dès que possible.</p>
    <p>Merci,</p>
    <p>L'équipe Noflay</p>
</body>

</html>
