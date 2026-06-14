<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Trilha;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    /**
     * Painel do trilheiro com seus agendamentos.
     *
     * No modo sandbox, agendamentos aceitos com data já passada são
     * marcados como concluídos automaticamente ao abrir o painel.
     */
    public function index(Request $request)
    {
        $user = $request->user('web');

        Agendamento::where('status', 'accepted')
            ->whereDate('data', '<', today())
            ->update(['status' => 'completed']);

        $agendamentos = Agendamento::with(['trilha:id,nome,cidade,foto', 'guia:id,nome'])
            ->where('id_users', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('User/Dashboard', [
            'perfil' => $user->only('id', 'nome', 'email', 'telefone', 'cpf', 'data_nascimento', 'foto'),
            'agendamentos' => $agendamentos,
        ]);
    }

    /**
     * Página inicial pública com a listagem e os filtros de trilhas.
     */
    public function landingPage(Request $request)
    {
        $query = Trilha::with('dificuldade')->withCount(['guias' => function ($q) {
            $q->where('trilhas_guias.congelada', false);
        }]);

        if ($request->filled('cidade')) {
            $query->where('cidade', $request->cidade);
        }

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        $trilhas = $query->latest()->paginate(12)->withQueryString();
        $cidades = Trilha::select('cidade')->distinct()->orderBy('cidade')->pluck('cidade');

        return Inertia::render('Home/Index', [
            'trilhas' => $trilhas->items(),
            'paginacao' => [
                'pagina_atual' => $trilhas->currentPage(),
                'ultima_pagina' => $trilhas->lastPage(),
                'total' => $trilhas->total(),
            ],
            'cidades' => $cidades,
            'filtros' => [
                'cidade' => $request->cidade,
                'busca' => $request->busca,
            ],
        ]);
    }
}
