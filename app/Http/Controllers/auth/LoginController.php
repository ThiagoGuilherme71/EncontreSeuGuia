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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tenta autenticar como trilheiro
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('landing-page');
        }

        // Se falhar, tenta autenticar como guia
        if (Auth::guard('guia')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('guia-dash');
        }

        return back()->withErrors(['login' => 'Credenciais inválidas'])->onlyInput('email');
    }

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
