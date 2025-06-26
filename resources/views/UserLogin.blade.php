<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.pexels.com/photos/8837499/pexels-photo-8837499.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            backdrop-filter: brightness(0.8);
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg p-3">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Connexion</h3>
                        <form action="/Vérification" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                 @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                 @enderror
                            </div>
                            <div class="mt-2 text-center">
                                <a href="/forgot-password" class="text-decoration-none">Mot de passe oublié?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="/">Créer un compte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
