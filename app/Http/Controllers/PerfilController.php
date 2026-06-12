<?php

namespace App\Http\Controllers;

use App\Models\Idioma;
use App\Support\ImageResizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class PerfilController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        if ($guia) {
            return Inertia::render('Perfil/Edit', [
                'role' => 'guia',
                'perfil' => $guia->only('id', 'nome', 'email', 'telefone', 'cep', 'endereco', 'anos_experiencia', 'link_instagram', 'link_facebook', 'foto'),
                'idiomas_disponiveis' => Idioma::all(),
                'meus_idiomas' => $guia->idiomas()->pluck('idiomas.id'),
            ]);
        }

        return Inertia::render('Perfil/Edit', [
            'role' => 'user',
            'perfil' => $user->only('id', 'nome', 'email', 'telefone', 'data_nascimento', 'foto'),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        return $guia
            ? $this->updateGuia($request, $guia)
            : $this->updateUser($request, $user);
    }

    private function updateUser(Request $request, $user)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:18',
            'data_nascimento' => 'required|date',
            'foto' => 'nullable|image|max:5120',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $dados = $request->only('nome', 'telefone', 'data_nascimento');

        if ($request->filled('password')) {
            $dados['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $dados['foto'] = ImageResizer::save($request->file('foto'), 'perfis', 600)['path'];
        }

        $user->update($dados);

        return redirect()->route('conta.cliente')->with('success', 'Perfil atualizado!');
    }

    private function updateGuia(Request $request, $guia)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:18',
            'cep' => 'nullable|string|max:12',
            'endereco' => 'nullable|string|max:255',
            'anos_experiencia' => 'nullable|integer|min:0|max:80',
            'link_instagram' => 'nullable|string|max:255',
            'link_facebook' => 'nullable|string|max:255',
            'idiomas' => 'nullable|array',
            'idiomas.*' => 'exists:idiomas,id',
            'foto' => 'nullable|image|max:5120',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $dados = $request->only('nome', 'telefone', 'cep', 'endereco', 'anos_experiencia', 'link_instagram', 'link_facebook');

        if ($request->filled('password')) {
            $dados['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $dados['foto'] = ImageResizer::save($request->file('foto'), 'perfis', 600)['path'];
        }

        $guia->update($dados);

        if ($request->has('idiomas')) {
            $guia->idiomas()->sync($request->idiomas ?? []);
        }

        return redirect()->route('conta.guia')->with('success', 'Perfil atualizado!');
    }
}
