<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trilha extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'id_dificuldade',
        'cidade',
        'foto',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id_dificuldade' => 'integer',
    ];

    /**
     * Relationships
     */

    // Relacionamento com dificuldades (assume que há um modelo `Dificuldade`)
    public function dificuldade()
    {
        return $this->belongsTo(Dificuldade::class, 'id_dificuldade');
    }

}
