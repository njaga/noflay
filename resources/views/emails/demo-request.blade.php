<!DOCTYPE html>
<html>
<head>
    <title>Demande de Démo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .container:hover {
            transform: translateY(-5px);
        }
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(to right, #4f46e5, #8b5cf6);
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 15px 0;
            line-height: 1.6;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #999;
            font-size: 14px;
        }
        .label {
            font-weight: 500;
            color: #333;
        }
        .value {
            margin: 0;
            color: #555;
        }
        .icon {
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        .footer a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
        }
        .button:hover {
            background-color: #8b5cf6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouvelle Demande de Démo</h1>
        </div>
        <div class="content">
            <p>Bonjour Ndiaga Ndiaye,</p>
            <p>Vous avez reçu une nouvelle demande de démo sur l'application Noflay. Ci-dessous les informations de l'utilisateur :</p>
            <p><span class="label">Nom :</span> <span class="value">{{ $demoRequest['name'] }}</span></p>
            <p><span class="label">Email :</span> <span class="value">{{ $demoRequest['email'] }}</span></p>
            <p><span class="label">Téléphone :</span> <span class="value">{{ $demoRequest['phone'] }}</span></p>
            <p><span class="label">Entreprise :</span> <span class="value">{{ $demoRequest['company'] }}</span></p>
            <a href="#" class="button">Voir la demande complète</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 Noflay. Tous droits réservés.</p>
            <p>Pour plus d'informations, visitez notre <a href="https://noflay.com">site web</a>.</p>
        </div>
    </div>
</body>
</html>
