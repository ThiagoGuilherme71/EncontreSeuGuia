<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\Guia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuiaController extends Controller
{
    /**
     * Painel do guia: trilhas, propostas pendentes/aceitas e histórico.
     *
     * No modo sandbox, agendamentos aceitos com data já passada são
     * marcados como concluídos automaticamente ao abrir o painel.
     */
    public function index(Request $request)
    {
        $guia = $request->user('guia');

        Agendamento::where('status', 'accepted')
            ->whereDate('data', '<', today())
            ->update(['status' => 'completed']);

        $trilhasCriadas = $guia->trilhas()
            ->with('dificuldade')
            ->where('criado_por_guia', $guia->id)
            ->get();

        $trilhasCadastradas = $guia->trilhas()
            ->with('dificuldade')
            ->where(function ($q) use ($guia) {
                $q->whereNull('criado_por_guia')->orWhere('criado_por_guia', '!=', $guia->id);
            })
            ->get();

        $propostas = Agendamento::with(['trilha:id,nome,cidade,foto', 'user:id,nome'])
            ->where('id_guia', $guia->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderBy('data')
            ->get();

        $historico = Agendamento::with(['trilha:id,nome,cidade,foto', 'user:id,nome', 'avaliacao'])
            ->where('id_guia', $guia->id)
            ->whereIn('status', ['completed', 'rejected', 'cancelled'])
            ->orderByDesc('data')
            ->get();

        return Inertia::render('Guia/Dashboard', [
            'trilhas_criadas' => $trilhasCriadas,
            'trilhas_cadastradas' => $trilhasCadastradas,
            'propostas' => $propostas,
            'historico' => $historico,
        ]);
    }

    /**
     * Retorna todos os guias (endpoint utilitário).
     */
    public function getAllGuias()
    {
        return Guia::all();
    }

    /**
     * Página de conta do guia com a média e o total de avaliações.
     */
    public function exibirConta(Request $request)
    {
        $guia = $request->user('guia');

        $mediaAvaliacoes = Avaliacao::where('id_guia', $guia->id)->avg('nota');
        $totalAvaliacoes = Avaliacao::where('id_guia', $guia->id)->count();

        return Inertia::render('Guia/Conta', [
            'perfil' => $guia->only('id', 'nome', 'email', 'telefone', 'cep', 'endereco', 'anos_experiencia', 'link_instagram', 'link_facebook', 'data_nascimento', 'foto'),
            'idiomas' => $guia->idiomas()->pluck('nome_idioma'),
            'media_avaliacoes' => $mediaAvaliacoes ? round($mediaAvaliacoes, 1) : null,
            'total_avaliacoes' => $totalAvaliacoes,
        ]);
    }
}
