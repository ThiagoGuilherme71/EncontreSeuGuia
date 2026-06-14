<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = [
        'id_guia', 'id_users', 'id_trilha',
        'status', 'horario', 'data',
        'num_pessoas', 'observacoes', 'motivo_rejeicao', 'total_valor', 'pago_em',
    ];

    protected $casts = [
        'data' => 'date',
        'pago_em' => 'datetime',
    ];

    /**
     * Guia responsável pelo agendamento.
     */
    public function guia()
    {
        return $this->belongsTo(Guia::class, 'id_guia');
    }

    /**
     * Trilheiro que fez o agendamento.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    /**
     * Trilha agendada.
     */
    public function trilha()
    {
        return $this->belongsTo(Trilha::class, 'id_trilha');
    }

    /**
     * Mensagens de chat do agendamento.
     */
    public function mensagens()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Avaliação feita após a conclusão.
     */
    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class, 'id_agendamento');
    }

    /**
     * Fotos da aventura postadas pelos participantes.
     */
    public function fotos()
    {
        return $this->hasMany(FotoAventura::class);
    }
}
