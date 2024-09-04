<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle demande d'aide</title>
</head>
<body>
    <h1>Nouvelle demande d'aide</h1>
    <p><strong>Nom:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>