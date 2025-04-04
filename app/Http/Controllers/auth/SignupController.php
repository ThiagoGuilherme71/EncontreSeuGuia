<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Guia;
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
//        $request->validate([
//            'nome' => 'required',
//            'email' => 'required|email|unique:guias,email',
//            'telefone' => 'required|string|max:18',
//            'data_nascimento' => 'required|date',
//            'cpf' => 'required|string|unique:guias,cpf',
//            'cep' => 'required|string|unique:guias,cep',
//            'endereco' => 'required',
//            'link_instagram' => 'required',
//            'link_facebook' => 'required',
//            'doc_frente' => 'required',
//            'doc_verso' => 'required',
//            'password' => 'required|string|min:6|confirmed',
//        ]);


        $guia = Guia::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'cpf' => $request->cpf,
            'cep' => $request->cep,
            'endereco' => $request->endereco,
            'link_instagram' => $request->link_instagram,
            'link_facebook' => $request->link_facebook,
            'doc_frente' => $request->doc_frente,
            'doc_verso' => $request->doc_verso,
            'password' => Hash::make($request->password),
        ]);

        auth()->login($guia);
        // tem que mudar essa view e criar um dash do guia
        return redirect()->route('guia-dash')->with('success', 'Cadastro realizado com sucesso!');
    }

}
