<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] ?? 'Notification' }}</title>
</head>
<body>
    <h1>{{ $data['title'] ?? 'Bonjour !' }}</h1>
    <p>{{ $data['content'] ?? 'Contenu du message' }}</p>
    <p>Merci,<br>{{ config('app.name') }}</p>
</body>
</html>