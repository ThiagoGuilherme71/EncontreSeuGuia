<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    use HasFactory;

    protected $table = 'idiomas';
    protected $fillable = ['nome_idioma'];

    /**
     * Guias que falam este idioma.
     */
    public function guias()
    {
        return $this->belongsToMany(Guia::class, 'idiomas_guias');
    }
}
