<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Autentica o usuário tentando primeiro o guard de trilheiro e,
     * em seguida, o de guia.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('landing-page');
        }

        if (Auth::guard('guia')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('guia-dash');
        }

        return back()->withErrors(['login' => 'Credenciais inválidas'])->onlyInput('email');
    }

    /**
     * Encerra a sessão do guard atualmente autenticado.
     */
    public function logout()
    {
        if (Auth::guard('guia')->check()) {
            Auth::guard('guia')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        return redirect()->route('login')->with('success', 'Você saiu com sucesso.');
    }
}
