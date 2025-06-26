<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Authentification Page</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    body {
      background-image: url('https://images.pexels.com/photos/760711/pexels-photo-760711.jpeg');
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
        <div class="card shadow-lg p-3 animate__animated animate__fadeInDown">
          <div class="card-body">
            <h3 class="text-center mb-4">Inscription</h3>
            <form action="/Traitement" method="POST">
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" required>
                @error('name')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
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
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                @if ($errors->has('confirm_password'))
                  <div class="alert alert-danger">
                    {{ $errors->first('confirm_password') }}
                  </div>
                @endif
              </div>
              <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            </form>
            <div class="text-center mt-3">
              <a href="/login">Déjà un compte ? Se connecter</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
