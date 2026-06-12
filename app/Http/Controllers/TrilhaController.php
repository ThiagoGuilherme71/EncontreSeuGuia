<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Dificuldade;
use App\Models\Trilha;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrilhaController extends Controller
{
    public function buscar(Request $request)
    {
        return redirect()->route('landing-page', ['busca' => $request->input('nome')]);
    }

    public function getAllTrilhas()
    {
        return Trilha::all();
    }

    public function getTrilha($id)
    {
        return Trilha::where('id', $id)->first();
    }

    public function exibir($id)
    {
        $trilha = Trilha::with('dificuldade')->findOrFail($id);

        $guias = $trilha->guiasAtivos()
            ->select('guias.id', 'guias.nome', 'guias.anos_experiencia', 'guias.link_instagram')
            ->with('idiomas:id,nome_idioma')
            ->get()
            ->map(function ($guia) {
                $guia->media_avaliacoes = Avaliacao::where('id_guia', $guia->id)->avg('nota');
                $guia->total_avaliacoes = Avaliacao::where('id_guia', $guia->id)->count();
                return $guia;
            });

        // avaliações públicas de trilhas concluídas nessa trilha
        $avaliacoes = Avaliacao::with(['user:id,nome', 'guia:id,nome'])
            ->whereIn('id_agendamento', \App\Models\Agendamento::where('id_trilha', $trilha->id)->pluck('id'))
            ->latest()
            ->limit(6)
            ->get();

        return Inertia::render('Trilha/Show', [
            'trilha' => $trilha,
            'guias' => $guias,
            'avaliacoes' => $avaliacoes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Trilha/Create', [
            'dificuldades' => Dificuldade::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:trilhas,nome',
            'descricao' => 'required|string|max:5000',
            'id_dificuldade' => 'required|exists:dificuldades,id',
            'cidade' => 'required|string|max:255',
            'foto' => 'nullable|image|max:4096',
        ]);

        $guia = $request->user('guia');

        $trilha = Trilha::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'id_dificuldade' => $request->id_dificuldade,
            'cidade' => $request->cidade,
            'criado_por_guia' => $guia->id,
        ]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('trilhas', 'public');
            $trilha->update(['foto' => 'storage/' . $path]);
        }

        // o criador já entra inscrito na trilha
        $guia->trilhas()->syncWithoutDetaching([$trilha->id]);

        return redirect()->route('guia-dash')
            ->with('success', 'Trilha criada e aprovada automaticamente! (sandbox)');
    }

    public function edit(Request $request, $id)
    {
        $guia = $request->user('guia');
        // qualquer guia inscrito pode editar (aprovação automática no sandbox)
        $trilha = $guia->trilhas()->with('dificuldade')->findOrFail($id);

        return Inertia::render('Trilha/Edit', [
            'trilha' => $trilha,
            'dificuldades' => Dificuldade::all(),
            'sou_criador' => $trilha->criado_por_guia === $guia->id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $guia = $request->user('guia');
        $trilha = $guia->trilhas()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255|unique:trilhas,nome,' . $trilha->id,
            'descricao' => 'required|string|max:5000',
            'id_dificuldade' => 'required|exists:dificuldades,id',
            'cidade' => 'required|string|max:255',
            'foto' => 'nullable|image|max:4096',
        ]);

        $trilha->update($request->only('nome', 'descricao', 'id_dificuldade', 'cidade'));

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('trilhas', 'public');
            $trilha->update(['foto' => 'storage/' . $path]);
        }

        $msg = $trilha->criado_por_guia === $guia->id
            ? 'Edição aprovada automaticamente! (sandbox)'
            : 'Solicitação de edição aprovada automaticamente! (sandbox)';

        return redirect()->route('guia-dash')->with('success', $msg);
    }

    public function congelar(Request $request, $id)
    {
        $guia = $request->user('guia');
        $guia->trilhas()->findOrFail($id);
        $guia->trilhas()->updateExistingPivot($id, ['congelada' => true]);

        return back()->with('success', 'Inscrição congelada. Você não aparece mais como guia disponível nessa trilha.');
    }

    public function reativar(Request $request, $id)
    {
        $guia = $request->user('guia');
        $guia->trilhas()->findOrFail($id);
        $guia->trilhas()->updateExistingPivot($id, ['congelada' => false]);

        return back()->with('success', 'Inscrição reativada!');
    }
}
