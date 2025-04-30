<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusMap - Connexion</title>
    <!-- Favicon (version simplifiée pour JPG/PNG) -->
    <link rel="icon" href="{{ asset('build/assets/images/logo-MindMap.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('build/assets/images/logo-MindMap.jpg') }}" type="image/jpeg">

    <!-- Bootstrap & icônes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Ton CSS via Vite -->
    @vite(['resources/css/auth/login.css', 'resources/js/auth/login.js'])

</head>

<body class="d-flex align-items-center justify-content-center p-3">
    <!-- Bulles décoratives -->
    <div class="bubble" style="width: 120px; height: 120px; top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 80px; height: 80px; top: 20%; right: 15%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 150px; height: 150px; bottom: 15%; right: 10%; animation-delay: 2s;"></div>
    <div class="bubble" style="width: 100px; height: 100px; bottom: 10%; left: 15%; animation-delay: 3s;"></div>

    <!-- Carte de connexion -->
    <div class="login-card bg-white p-5 position-relative z-3">
        <div class="text-center mb-5">
            <div class="logo mb-2">🧠 FocusMap</div>
            <h1 class="h2 fw-semibold text-dark mb-2">Se connecter à FocusMap</h1>
            <p class="text-muted">Accédez à votre espace personnel</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mb-4">
            @csrf
            <div class="floating-label">
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder=" " value="{{ old('email') }}" required autocomplete="email" autofocus />
                <div class="icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <label for="email">Adresse email</label>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="floating-label">
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder=" " required
                    autocomplete="current-password" />
                <div class="icon">
                    <i class="bi bi-lock"></i>
                </div>
                <label for="password">Mot de passe</label>
                <div class="password-toggle">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom text-white w-100 fw-medium">
                Se connecter
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted">
                Pas encore membre ?
                <a href="{{ route('register') }}" class="text-decoration-none fw-medium" style="color: #6366f1;">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>