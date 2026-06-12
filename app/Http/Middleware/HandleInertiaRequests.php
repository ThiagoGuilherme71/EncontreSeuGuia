<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        $unreadCount = 0;
        if ($user || $guia) {
            $type = $user ? 'user' : 'guia';
            $id = $user ? $user->id : $guia->id;
            $unreadCount = \App\Models\Notificacao::where('notificavel_type', $type)
                ->where('notificavel_id', $id)
                ->whereNull('lida_em')
                ->count();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'nome' => $user->nome,
                    'email' => $user->email,
                    'role' => 'user',
                ] : null,
                'guia' => $guia ? [
                    'id' => $guia->id,
                    'nome' => $guia->nome,
                    'email' => $guia->email,
                    'role' => 'guia',
                ] : null,
            ],
            'notifications' => [
                'unread_count' => $unreadCount,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}
