<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    use HasFactory;

    protected $table = 'idiomas'; // Define a tabela correta
    protected $fillable = ['nome_idioma']; // Permite atribuição em massa
}
