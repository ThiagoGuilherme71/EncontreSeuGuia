<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index(Request $request)
    {
        [$type, $id] = $this->resolveNotificavel($request);

        return Notificacao::where('notificavel_type', $type)
            ->where('notificavel_id', $id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

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

    public function markAllRead(Request $request)
    {
        [$type, $notificavelId] = $this->resolveNotificavel($request);

        Notificacao::where('notificavel_type', $type)
            ->where('notificavel_id', $notificavelId)
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back();
    }

    private function resolveNotificavel(Request $request): array
    {
        $user = $request->user('web');

        return $user
            ? ['user', $user->id]
            : ['guia', $request->user('guia')->id];
    }
}
