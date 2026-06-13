<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trilha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'id_dificuldade',
        'estado',
        'cidade',
        'foto',
        'criado_por_guia',
        'distancia_km',
        'tempo_estimado_horas',
        'ponto_encontro_lat',
        'ponto_encontro_lng',
        'ponto_encontro_descricao',
        'o_que_levar',
    ];

    protected $casts = [
        'id_dificuldade'       => 'integer',
        'distancia_km'         => 'float',
        'tempo_estimado_horas' => 'float',
        'ponto_encontro_lat'   => 'float',
        'ponto_encontro_lng'   => 'float',
        'o_que_levar'          => 'array',
    ];

    public function dificuldade()
    {
        return $this->belongsTo(Dificuldade::class, 'id_dificuldade');
    }

    public function guias()
    {
        return $this->belongsToMany(Guia::class, 'trilhas_guias', 'trilha_id', 'guia_id')
            ->withPivot('congelada', 'preco_por_pessoa');
    }

    public function guiasAtivos()
    {
        return $this->guias()->wherePivot('congelada', false);
    }

    public function criador()
    {
        return $this->belongsTo(Guia::class, 'criado_por_guia');
    }
}
