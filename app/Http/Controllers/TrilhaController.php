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
            ->withPivot('preco_por_pessoa')
            ->with('idiomas:id,nome_idioma')
            ->get()
            ->map(function ($guia) {
                $guia->media_avaliacoes = Avaliacao::where('id_guia', $guia->id)->avg('nota');
                $guia->total_avaliacoes = Avaliacao::where('id_guia', $guia->id)->count();
                $guia->preco_por_pessoa = $guia->pivot->preco_por_pessoa;
                return $guia;
            });

        // avaliações públicas de trilhas concluídas nessa trilha
        $avaliacoes = Avaliacao::with(['user:id,nome,foto', 'guia:id,nome,foto'])
            ->whereIn('id_agendamento', \App\Models\Agendamento::where('id_trilha', $trilha->id)->pluck('id'))
            ->latest()
            ->limit(6)
            ->get();

        // aventuras: trilhas concluídas com fotos postadas pelos participantes
        $aventuras = \App\Models\Agendamento::with(['user:id,nome,foto', 'guia:id,nome,foto', 'fotos'])
            ->where('id_trilha', $trilha->id)
            ->where('status', 'completed')
            ->whereHas('fotos')
            ->orderByDesc('data')
            ->limit(20)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'data' => $a->data,
                'user' => $a->user,
                'guia' => $a->guia,
                'fotos' => $a->fotos->map(fn ($f) => [
                    'id' => $f->id,
                    'path' => $f->path,
                    'thumb_path' => $f->thumb_path,
                    'legenda' => $f->legenda,
                    'postado_por_type' => $f->postado_por_type,
                ])->values(),
            ]);

        return Inertia::render('Trilha/Show', [
            'trilha' => $trilha,
            'guias' => $guias,
            'avaliacoes' => $avaliacoes,
            'aventuras' => $aventuras,
        ]);
    }

    public function create(Request $request)
    {
        $guia = $request->user('guia');
        $inscritas = $guia->trilhas()->pluck('trilhas.id');

        $disponiveis = Trilha::with('dificuldade')
            ->whereNotIn('id', $inscritas)
            ->orderBy('cidade')
            ->orderBy('nome')
            ->get(['id', 'nome', 'estado', 'cidade', 'id_dificuldade', 'foto']);

        return Inertia::render('Trilha/Create', [
            'dificuldades' => Dificuldade::all(),
            'trilhas_disponiveis' => $disponiveis,
        ]);
    }

    public function inscrever(Request $request, $id)
    {
        $guia = $request->user('guia');
        $trilha = Trilha::findOrFail($id);

        if ($guia->trilhas()->where('trilha_id', $id)->exists()) {
            return back()->withErrors(['trilha' => 'Você já está inscrito nesta trilha.']);
        }

        $guia->trilhas()->syncWithoutDetaching([$trilha->id]);

        return redirect()->route('guia-dash')
            ->with('success', "Você agora é guia de \"{$trilha->nome}\"!");
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'                      => 'required|string|max:255|unique:trilhas,nome',
            'descricao'                 => 'required|string|max:5000',
            'id_dificuldade'            => 'required|exists:dificuldades,id',
            'estado'                    => 'required|string|size:2',
            'cidade'                    => 'required|string|max:255',
            'foto'                      => 'required|image|max:4096',
            'distancia_km'              => 'nullable|numeric|min:0|max:9999',
            'tempo_estimado_horas'      => 'nullable|numeric|min:0|max:999',
            'ponto_encontro_lat'        => 'nullable|numeric|between:-90,90',
            'ponto_encontro_lng'        => 'nullable|numeric|between:-180,180',
            'ponto_encontro_descricao'  => 'nullable|string|max:500',
            'o_que_levar'               => 'nullable|array',
            'o_que_levar.*'             => 'string|max:100',
        ], [
            'foto.uploaded' => 'A foto é muito grande. O servidor aceita no máximo 4MB.',
            'foto.max'      => 'A foto deve ter no máximo 4MB.',
        ]);

        $guia = $request->user('guia');

        $trilha = Trilha::create([
            'nome'                     => $request->nome,
            'descricao'                => $request->descricao,
            'id_dificuldade'           => $request->id_dificuldade,
            'estado'                   => $request->estado,
            'cidade'                   => $request->cidade,
            'criado_por_guia'          => $guia->id,
            'distancia_km'             => $request->distancia_km,
            'tempo_estimado_horas'     => $request->tempo_estimado_horas,
            'ponto_encontro_lat'       => $request->ponto_encontro_lat,
            'ponto_encontro_lng'       => $request->ponto_encontro_lng,
            'ponto_encontro_descricao' => $request->ponto_encontro_descricao,
            'o_que_levar'              => $request->o_que_levar ?? [],
        ]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $trilha->update(['foto' => \App\Support\ImageResizer::save($request->file('foto'), 'trilhas', 1600)['path']]);
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
            'nome'                     => 'required|string|max:255|unique:trilhas,nome,' . $trilha->id,
            'descricao'                => 'required|string|max:5000',
            'id_dificuldade'           => 'required|exists:dificuldades,id',
            'estado'                   => 'required|string|size:2',
            'cidade'                   => 'required|string|max:255',
            'foto'                     => 'nullable|image|max:4096',
            'distancia_km'             => 'nullable|numeric|min:0|max:9999',
            'tempo_estimado_horas'     => 'nullable|numeric|min:0|max:999',
            'ponto_encontro_lat'       => 'nullable|numeric|between:-90,90',
            'ponto_encontro_lng'       => 'nullable|numeric|between:-180,180',
            'ponto_encontro_descricao' => 'nullable|string|max:500',
            'o_que_levar'              => 'nullable|array',
            'o_que_levar.*'            => 'string|max:100',
        ], [
            'foto.uploaded' => 'A foto é muito grande. O servidor aceita no máximo 4MB.',
            'foto.max'      => 'A foto deve ter no máximo 4MB.',
        ]);

        $trilha->update($request->only(
            'nome', 'descricao', 'id_dificuldade', 'estado', 'cidade',
            'distancia_km', 'tempo_estimado_horas',
            'ponto_encontro_lat', 'ponto_encontro_lng', 'ponto_encontro_descricao',
        ));
        $trilha->update(['o_que_levar' => $request->o_que_levar ?? []]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $trilha->update(['foto' => \App\Support\ImageResizer::save($request->file('foto'), 'trilhas', 1600)['path']]);
        }

        $msg = $trilha->criado_por_guia === $guia->id
            ? 'Edição aprovada automaticamente! (sandbox)'
            : 'Solicitação de edição aprovada automaticamente! (sandbox)';

        return redirect()->route('guia-dash')->with('success', $msg);
    }

    public function atualizarInscricao(Request $request, $id)
    {
        $guia = $request->user('guia');
        $guia->trilhas()->findOrFail($id);

        $request->validate([
            'preco_por_pessoa' => 'nullable|numeric|min:0|max:99999',
        ]);

        $guia->trilhas()->updateExistingPivot($id, [
            'preco_por_pessoa' => $request->preco_por_pessoa,
        ]);

        return back()->with('success', 'Inscrição atualizada!');
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
