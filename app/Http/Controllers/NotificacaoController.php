<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    /**
     * Lista as 50 notificações mais recentes do usuário autenticado.
     */
    public function index(Request $request)
    {
        [$type, $id] = $this->resolveNotificavel($request);

        return Notificacao::where('notificavel_type', $type)
            ->where('notificavel_id', $id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

    /**
     * Marca uma notificação do próprio usuário como lida.
     */
    public function markRead(Request $request, $id)
    {
        [$type, $notificavelId] = $this->resolveNotificavel($request);

        Notificacao::where('id', $id)
            ->where('notificavel_type', $type)
            ->where('notificavel_id', $notificavelId)
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back();
    }

    /**
     * Marca todas as notificações não lidas do usuário como lidas.
     */
    public function markAllRead(Request $request)
    {
        [$type, $notificavelId] = $this->resolveNotificavel($request);

        Notificacao::where('notificavel_type', $type)
            ->where('notificavel_id', $notificavelId)
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back();
    }

    /**
     * Resolve o tipo e o id do destinatário a partir do guard autenticado.
     *
     * @return array{0: string, 1: int}
     */
    private function resolveNotificavel(Request $request): array
    {
        $user = $request->user('web');

        return $user
            ? ['user', $user->id]
            : ['guia', $request->user('guia')->id];
    }
}
