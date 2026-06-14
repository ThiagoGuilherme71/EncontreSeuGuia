<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'agendamento_id', 'sender_type', 'sender_id', 'mensagem', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Agendamento ao qual a mensagem pertence.
     */
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }
}
