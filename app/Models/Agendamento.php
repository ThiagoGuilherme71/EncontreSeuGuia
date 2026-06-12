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

    public function guia()
    {
        return $this->belongsTo(Guia::class, 'id_guia');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function trilha()
    {
        return $this->belongsTo(Trilha::class, 'id_trilha');
    }

    public function mensagens()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class, 'id_agendamento');
    }

    public function fotos()
    {
        return $this->hasMany(FotoAventura::class);
    }
}
