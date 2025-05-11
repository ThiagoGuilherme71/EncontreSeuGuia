<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdiomaGuia extends Model
{
    protected $table = 'idiomas_guias'; // Nome da tabela no banco
    public $timestamps = true; // Habilita os timestamps
    protected $fillable = ['guia_id', 'idioma_id'];
}
