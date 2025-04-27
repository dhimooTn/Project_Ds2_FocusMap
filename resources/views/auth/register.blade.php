<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusMap - Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #f5f7ff 0%, #e8eaff 100%);
            min-height: 100vh;
        }
        .logo {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            color: #6366f1;
        }
        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .floating-label input {
            height: 56px;
            padding-left: 48px;
            font-size: 16px;
            border-radius: 8px;
        }
        .floating-label label {
            position: absolute;
            top: 50%;
            left: 48px;
            transform: translateY(-50%);
            transition: all 0.3s;
            pointer-events: none;
            color: #9ca3af;
        }
        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown) ~ label {
            top: 8px;
            left: 16px;
            font-size: 12px;
            color: #6366f1;
        }
        .floating-label .icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .floating-label input:focus ~ .icon {
            color: #6366f1;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(165, 180, 252, 0.5), rgba(99, 102, 241, 0.3));
            animation: float 8s infinite ease-in-out;
        }
        .signup-card {
            width: 100%;
            max-width: 28rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-primary-custom {
            background: linear-gradient(to right, #6366f1, #a5b4fc);
            border: none;
            border-radius: 8px;
            padding: 12px;
        }
        .btn-primary-custom:hover {
            opacity: 0.9;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>
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
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder=" "
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    autofocus
                />
                <div class="icon">
                    <i class="bi bi-person"></i>
                </div>
                <label for="name">Nom complet</label>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="floating-label">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder=" "
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                />
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
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder=" "
                    required
                    autocomplete="new-password"
                />
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

            <div class="floating-label">
                <input
                    type="password"
                    id="password-confirm"
                    name="password_confirmation"
                    class="form-control"
                    placeholder=" "
                    required
                    autocomplete="new-password"
                />
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
                <a href="{{ route('login') }}" class="text-decoration-none fw-medium" style="color: #6366f1;">
                    Se connecter
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle password visibility
            document.getElementById("togglePassword").addEventListener("click", function () {
                const passwordInput = document.getElementById("password");
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
            });

            document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
                const confirmPasswordInput = document.getElementById("password-confirm");
                const type = confirmPasswordInput.getAttribute("type") === "password" ? "text" : "password";
                confirmPasswordInput.setAttribute("type", type);
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
            });
        });
    </script>
</body>
</html>