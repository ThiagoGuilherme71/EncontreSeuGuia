<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Registra a avaliação de um agendamento concluído (uma por
     * agendamento) e notifica o guia.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $user = $request->user('web');

        $agendamento = Agendamento::with('trilha:id,nome')
            ->where('id_users', $user->id)
            ->where('status', 'completed')
            ->findOrFail($id);

        abort_if($agendamento->avaliacao()->exists(), 422, 'Você já avaliou essa trilha.');

        Avaliacao::create([
            'id_users' => $user->id,
            'id_guia' => $agendamento->id_guia,
            'id_agendamento' => $agendamento->id,
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        Notificacao::notificar(
            'guia',
            $agendamento->id_guia,
            'avaliacao_recebida',
            'Você recebeu uma avaliação ⭐',
            "{$user->nome} avaliou a trilha {$agendamento->trilha->nome} com {$request->nota} estrela(s).",
            ['agendamento_id' => $agendamento->id]
        );

        return back()->with('success', 'Avaliação enviada. Obrigado!');
    }
}
