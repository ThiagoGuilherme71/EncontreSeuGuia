<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoAventura extends Model
{
    protected $table = 'fotos_aventura';

    protected $fillable = [
        'agendamento_id', 'postado_por_type', 'postado_por_id', 'path', 'thumb_path', 'legenda',
    ];

    /**
     * Agendamento ao qual a foto pertence.
     */
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }

    /**
     * Autor da foto, resolvido conforme o tipo (trilheiro ou guia).
     */
    public function getAutorAttribute()
    {
        return $this->postado_por_type === 'user'
            ? User::select('id', 'nome', 'foto')->find($this->postado_por_id)
            : Guia::select('id', 'nome', 'foto')->find($this->postado_por_id);
    }
}
