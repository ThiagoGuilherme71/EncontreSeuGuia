<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('main');
    }

    // Processa o login
    public function login(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tentativa de login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('landing-page'); // Redireciona após login bem-sucedido
        }

        // Se falhar, retorna com erro
        return back()->withErrors(['login' => 'Credenciais inválidas'])->onlyInput('email');
    }

    // Logout do usuário
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Você saiu com sucesso.');
    }



}
