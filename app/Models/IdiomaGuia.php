<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdiomaGuia extends Model
{
    protected $table = 'idiomas_guias';
    public $timestamps = true;
    protected $fillable = ['guia_id', 'idioma_id'];
}
