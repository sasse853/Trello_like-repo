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

  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

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

    .password-toggle {
      cursor: pointer;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
    }

    .password-field {
      position: relative;
    }

    .match-indicator {
      font-size: 0.875rem;
      margin-top: 5px;
    }

    .password-match {
      color: #198754;
    }

    .password-no-match {
      color: #dc3545;
    }

    .form-control {
      padding-right: 45px;
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
            <form action="/Traitement" method="POST" id="registrationForm">
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
                <div class="password-field">
                  <input type="password" class="form-control" id="password" name="password" required>
                  <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                </div>
                @error('password')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
                <div class="password-field">
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                  <i class="bi bi-eye-slash password-toggle" id="toggleConfirmPassword"></i>
                </div>
                <div id="passwordMatchIndicator" class="match-indicator" style="display: none;"></div>
                @if ($errors->has('confirm_password'))
                  <div class="alert alert-danger">
                    {{ $errors->first('confirm_password') }}
                  </div>
                @endif
              </div>
              <button type="submit" class="btn btn-success w-100" id="submitBtn">S'inscrire</button>
            </form>
            <div class="text-center mt-3">
              <a href="/login">Déjà un compte ? Se connecter</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const passwordField = document.getElementById('password');
      const confirmPasswordField = document.getElementById('confirm_password');
      const togglePassword = document.getElementById('togglePassword');
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
      const submitBtn = document.getElementById('submitBtn');

      // Fonction pour basculer la visibilité du mot de passe
      function togglePasswordVisibility(inputField, toggleIcon) {
        if (inputField.type === 'password') {
          inputField.type = 'text';
          toggleIcon.classList.remove('bi-eye-slash');
          toggleIcon.classList.add('bi-eye');
        } else {
          inputField.type = 'password';
          toggleIcon.classList.remove('bi-eye');
          toggleIcon.classList.add('bi-eye-slash');
        }
      }

      // Fonction pour vérifier la correspondance des mots de passe
      function checkPasswordMatch() {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        if (confirmPassword === '') {
          passwordMatchIndicator.style.display = 'none';
          submitBtn.disabled = false;
          return;
        }

        passwordMatchIndicator.style.display = 'block';
        
        if (password === confirmPassword) {
          passwordMatchIndicator.textContent = '✓ Les mots de passe correspondent';
          passwordMatchIndicator.className = 'match-indicator password-match';
          confirmPasswordField.classList.remove('is-invalid');
          confirmPasswordField.classList.add('is-valid');
          submitBtn.disabled = false;
        } else {
          passwordMatchIndicator.textContent = '✗ Les mots de passe ne correspondent pas';
          passwordMatchIndicator.className = 'match-indicator password-no-match';
          confirmPasswordField.classList.remove('is-valid');
          confirmPasswordField.classList.add('is-invalid');
          submitBtn.disabled = true;
        }
      }

      // Événements pour basculer la visibilité des mots de passe
      togglePassword.addEventListener('click', function() {
        togglePasswordVisibility(passwordField, togglePassword);
      });

      toggleConfirmPassword.addEventListener('click', function() {
        togglePasswordVisibility(confirmPasswordField, toggleConfirmPassword);
      });

      // Événements pour vérifier la correspondance des mots de passe
      passwordField.addEventListener('input', checkPasswordMatch);
      confirmPasswordField.addEventListener('input', checkPasswordMatch);

      // Prévenir la soumission si les mots de passe ne correspondent pas
      document.getElementById('registrationForm').addEventListener('submit', function(e) {
        if (passwordField.value !== confirmPasswordField.value) {
          e.preventDefault();
          alert('Les mots de passe ne correspondent pas. Veuillez vérifier vos saisies.');
        }
      });
    });
  </script>

</body>
</html>