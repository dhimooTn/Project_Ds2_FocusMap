<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusMap - Créer un compte</title>
    <!-- Favicon (version simplifiée pour JPG/PNG) -->
    <link rel="icon" href="{{ asset('build/assets/images/logo-MindMap.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('build/assets/images/logo-MindMap.jpg') }}" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/auth/register.css', 'resources/js/auth/register.js'])

</head>

<body class="d-flex align-items-center justify-content-center p-3">
    <div class="bubble" style="width: 120px; height: 120px; top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 80px; height: 80px; top: 20%; right: 15%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 150px; height: 150px; bottom: 15%; right: 10%; animation-delay: 2s;"></div>
    <div class="bubble" style="width: 100px; height: 100px; bottom: 10%; left: 15%; animation-delay: 3s;"></div>

    <div class="signup-card bg-white p-5 position-relative z-3">
        <div class="text-center mb-5">
            <div class="logo mb-2">🧠 FocusMap</div>
            <h1 class="h2 fw-semibold text-dark mb-2">Créer un compte FocusMap</h1>
            <p class="text-muted">Commence ton voyage vers tes objectifs !</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mb-4">
            @csrf
            <div class="floating-label">
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder=" " value="{{ old('name') }}" required autocomplete="name" autofocus />
                <div class="icon">
                    <i class="bi bi-person"></i>
                </div>
                <label for="name">Nom complet</label>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="floating-label">
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder=" " value="{{ old('email') }}" required autocomplete="email" />
                <div class="icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <label for="email">Adresse email</label>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="floating-label">
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder=" " required
                    autocomplete="new-password" />
                <div class="icon">
                    <i class="bi bi-lock"></i>
                </div>
                <label for="password">Mot de passe</label>
                <div class="password-toggle">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="floating-label">
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control"
                    placeholder=" " required autocomplete="new-password" />
                <div class="icon">
                    <i class="bi bi-lock"></i>
                </div>
                <label for="password-confirm">Confirmer le mot de passe</label>
                <div class="password-toggle">
                    <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom text-white w-100 fw-medium">
                Créer mon compte
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted">
                Déjà membre ?
                <a href="{{ route('login.form') }}" class="text-decoration-none fw-medium" style="color: #6366f1;">
                    Se connecter
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>