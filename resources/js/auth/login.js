document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");

    // Toggle password visibility
    if (togglePassword) {
        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("ri-eye-line");
            this.classList.toggle("ri-eye-off-line");
        });
    }

    // Form validation
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            let isValid = true;

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                emailInput.classList.add("is-invalid");
                isValid = false;
            } else {
                emailInput.classList.remove("is-invalid");
            }

            // Password validation
            if (passwordInput.value.trim() === "") {
                passwordInput.classList.add("is-invalid");
                isValid = false;
            } else {
                passwordInput.classList.remove("is-invalid");
            }

            if (isValid) {
                // Here you would typically submit the form to your server
                alert("Connexion réussie ! Bienvenue sur FocusMap.");
                // form.submit(); // Uncomment to actually submit the form
            }
        });

        // Real-time validation
        emailInput.addEventListener("input", function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value)) {
                this.classList.remove("is-invalid");
            }
        });

        passwordInput.addEventListener("input", function () {
            if (this.value.trim() !== "") {
                this.classList.remove("is-invalid");
            }
        });
    }
});