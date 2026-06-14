<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use App\Models\Idioma;
use App\Models\IdiomaGuia;
use App\Models\Trilha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SignupController extends Controller
{
    /**
     * Exibe o formulário de cadastro de trilheiro.
     */
    public function showSignupForm()
    {
        return Inertia::render('Auth/Signup');
    }

    /**
     * Cria a conta do trilheiro e já o autentica.
     */
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

        auth()->login($user);

        return redirect()->route('landing-page')->with('success', 'Cadastro realizado com sucesso!');
    }

    /**
     * Exibe o formulário de cadastro de guia com idiomas e trilhas disponíveis.
     */
    public function showGuiaSignupForm()
    {
        $idiomas = Idioma::all();
        $trilhas = Trilha::select('id', 'nome', 'cidade')->get();
        return Inertia::render('Auth/SignupGuia', [
            'idiomas' => $idiomas,
            'trilhas' => $trilhas,
        ]);
    }

    /**
     * Cria a conta do guia, vincula idiomas e trilhas e já o autentica.
     */
    public function signupGuia(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:guias,email',
            'telefone' => 'required|string|max:18',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|unique:guias,cpf',
            'password' => 'required|string|min:6|confirmed',
            'doc_frente' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_verso' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $guia = Guia::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'cpf' => $request->cpf,
            'cep' => $request->cep,
            'anos_experiencia' => $request->experiencia ?: null,
            'endereco' => $request->endereco,
            'link_instagram' => $request->link_instagram,
            'link_facebook' => $request->link_facebook,
            'doc_frente' => $request->hasFile('doc_frente') ? $request->file('doc_frente')->store('docs/guias', 'public') : null,
            'doc_verso' => $request->hasFile('doc_verso') ? $request->file('doc_verso')->store('docs/guias', 'public') : null,
            'password' => Hash::make($request->password),
        ]);
        if ($request->idiomas) {
            $guia->idiomas()->sync($request->idiomas);
        }
        if ($request->trilhas) {
            $guia->trilhas()->sync($request->trilhas);
        }

        auth('guia')->login($guia);
        return redirect()->route('guia-dash')->with('success', 'Cadastro realizado com sucesso!');
    }
}
