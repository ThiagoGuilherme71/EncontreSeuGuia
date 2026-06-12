<?php

namespace App\Http\Controllers;

use App\Models\Trilha;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user('web');

        // Sandbox: trilhas com data passada e aceitas viram "concluídas" automaticamente
        \App\Models\Agendamento::where('status', 'accepted')
            ->whereDate('data', '<', today())
            ->update(['status' => 'completed']);

        $agendamentos = \App\Models\Agendamento::with(['trilha:id,nome,cidade,foto', 'guia:id,nome'])
            ->where('id_users', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('User/Dashboard', [
            'perfil' => $user->only('id', 'nome', 'email', 'telefone', 'cpf', 'data_nascimento'),
            'agendamentos' => $agendamentos,
        ]);
    }

    public function landingPage(Request $request){
        $query = Trilha::with('dificuldade')->withCount(['guias' => function ($q) {
            $q->where('trilhas_guias.congelada', false);
        }]);

        if ($request->filled('cidade')) {
            $query->where('cidade', $request->cidade);
        }

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        $trilhas = $query->latest()->get();
        $cidades = Trilha::select('cidade')->distinct()->orderBy('cidade')->pluck('cidade');

        return Inertia::render('Home/Index', [
            'trilhas' => $trilhas,
            'cidades' => $cidades,
            'filtros' => [
                'cidade' => $request->cidade,
                'busca' => $request->busca,
            ],
        ]);
    }
}
