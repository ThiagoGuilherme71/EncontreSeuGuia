<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'id_users', 'id_guia', 'id_agendamento', 'nota', 'comentario',
    ];

    /**
     * Trilheiro que fez a avaliação.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    /**
     * Guia avaliado.
     */
    public function guia()
    {
        return $this->belongsTo(Guia::class, 'id_guia');
    }
}
