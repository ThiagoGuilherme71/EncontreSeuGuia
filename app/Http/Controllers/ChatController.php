<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\ChatMessage;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Exibe o chat de um agendamento e marca como lidas as mensagens
     * recebidas da outra parte. O chat só fica disponível após o aceite.
     */
    public function show(Request $request, $id)
    {
        $agendamento = Agendamento::with(['trilha:id,nome,cidade', 'guia:id,nome', 'user:id,nome'])
            ->findOrFail($id);

        [$senderType, $senderId] = $this->resolveSender($request, $agendamento);

        abort_if(in_array($agendamento->status, ['pending', 'rejected']), 403, 'O chat é liberado quando a proposta for aceita.');

        ChatMessage::where('agendamento_id', $agendamento->id)
            ->where('sender_type', '!=', $senderType)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return Inertia::render('Chat/Show', [
            'agendamento' => $agendamento,
            'mensagens' => ChatMessage::where('agendamento_id', $agendamento->id)
                ->orderBy('created_at')
                ->get(),
            'eu' => $senderType,
            'pode_enviar' => $this->podeEnviar($agendamento),
        ]);
    }

    /**
     * Registra uma nova mensagem e notifica a outra parte.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'mensagem' => 'required|string|max:2000',
        ]);

        $agendamento = Agendamento::with('trilha:id,nome')->findOrFail($id);
        [$senderType, $senderId] = $this->resolveSender($request, $agendamento);

        abort_unless($this->podeEnviar($agendamento), 403, 'O chat está fechado para novas mensagens.');

        ChatMessage::create([
            'agendamento_id' => $agendamento->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'mensagem' => $request->mensagem,
        ]);

        $this->notificarOutraParte($agendamento, $senderType);

        return back();
    }

    /**
     * Resolve se quem acessa é o trilheiro ou o guia do agendamento.
     *
     * @return array{0: string, 1: int}
     */
    private function resolveSender(Request $request, Agendamento $agendamento): array
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        if ($user && $agendamento->id_users === $user->id) {
            return ['user', $user->id];
        }
        if ($guia && $agendamento->id_guia === $guia->id) {
            return ['guia', $guia->id];
        }

        abort(403);
    }

    /**
     * Envio liberado do aceite até o fim do dia anterior à trilha.
     */
    private function podeEnviar(Agendamento $agendamento): bool
    {
        return $agendamento->status === 'accepted'
            && now()->lt($agendamento->data->copy()->subDay()->endOfDay());
    }

    /**
     * Notifica a outra parte, evitando acumular notificações não lidas do
     * mesmo chat.
     */
    private function notificarOutraParte(Agendamento $agendamento, string $senderType): void
    {
        $destinoType = $senderType === 'user' ? 'guia' : 'user';
        $destinoId = $senderType === 'user' ? $agendamento->id_guia : $agendamento->id_users;
        $remetente = $senderType === 'user' ? $agendamento->user->nome : $agendamento->guia->nome;

        $jaExiste = Notificacao::where('notificavel_type', $destinoType)
            ->where('notificavel_id', $destinoId)
            ->where('tipo', 'nova_mensagem')
            ->whereNull('lida_em')
            ->where('data->agendamento_id', $agendamento->id)
            ->exists();

        if ($jaExiste) {
            return;
        }

        Notificacao::notificar(
            $destinoType,
            $destinoId,
            'nova_mensagem',
            'Nova mensagem 💬',
            "{$remetente} mandou mensagem sobre {$agendamento->trilha->nome}.",
            [
                'agendamento_id' => $agendamento->id,
                'url' => "/chat/{$agendamento->id}",
            ]
        );
    }
}
