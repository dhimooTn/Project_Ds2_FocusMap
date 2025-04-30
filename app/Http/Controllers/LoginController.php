<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/Auth/LoginController.php
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Vous êtes connecté avec succès.');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log the user out
        auth()->logout();
        
        // Invalidate the session to clear any session data
        $request->session()->invalidate();
        
        // Regenerate the CSRF token to prevent session fixation attacks
        $request->session()->regenerateToken();
        
        // Redirect the user to the login page with a success message
        return redirect()->route('login.form')->with('success', 'Vous êtes déconnecté avec succès.');
    }
    
}
