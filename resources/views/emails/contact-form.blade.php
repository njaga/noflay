<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .header h1 {
            color: #4a4a4a;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #999;
            font-size: 14px;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .value {
            margin: 0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouveau message de contact</h1>
        </div>
        <div class="content">
            <p><span class="label">Nom:</span> <span class="value">{{ $contactForm['name'] }}</span></p>
            <p><span class="label">Email:</span> <span class="value">{{ $contactForm['email'] }}</span></p>
            <p><span class="label">Message:</span></p>
            <p class="value">{{ $contactForm['message'] }}</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Noflay. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
