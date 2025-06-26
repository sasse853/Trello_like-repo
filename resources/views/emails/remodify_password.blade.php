<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation de votre mot de passe</title>
</head>
<body>
    <h1>Réinitialisation de votre mot de passe</h1>
    <p>Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.</p>
    <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe:</p>
    
    <a href="{{ route('password.reset', ['token' => $token]) }}">Réinitialiser mon mot de passe</a>
    
    <p>Ce lien expirera dans 24 heures.</p>
    <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise.</p>
</body>
</html>