<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = [
        'notificavel_type', 'notificavel_id',
        'tipo', 'titulo', 'mensagem', 'data', 'lida_em',
    ];

    protected $casts = [
        'data' => 'array',
        'lida_em' => 'datetime',
    ];

    /**
     * Escopo: apenas notificações não lidas.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('lida_em');
    }

    /**
     * Cria uma notificação para um destinatário (trilheiro ou guia).
     */
    public static function notificar(string $type, int $id, string $tipo, string $titulo, string $mensagem, array $data = []): self
    {
        return self::create([
            'notificavel_type' => $type,
            'notificavel_id' => $id,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'data' => $data,
        ]);
    }
}
