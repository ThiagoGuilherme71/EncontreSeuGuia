<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function showSignupForm()
    {
        return view('Signup.signup');
    }

    public function signup(Request $request)
    {

        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:18',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|unique:users,cpf',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
        ]);

        // Autenticar e redirecionar para o dashboard
        auth()->login($user);

        return redirect()->route('landing-page')->with('success', 'Cadastro realizado com sucesso!');

    }

    public function showGuiaSignupForm()
    {
        return view('Signup.signup-guia');
    }

    public function signupGuia(Request $request)
    {
        // Lógica para processar o cadastro do guia
    }

}
