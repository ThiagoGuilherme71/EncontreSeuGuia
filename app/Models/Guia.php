<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guia extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cep',
        'endereco',
        'anos_experiencia',
        'link_instagram',
        'link_facebook',
        'doc_frente',
        'doc_verso',
        'password',
        'data_nascimento',
        'cpf',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Idiomas falados pelo guia.
     */
    public function idiomas()
    {
        return $this->belongsToMany(Idioma::class, 'idiomas_guias');
    }

    /**
     * Trilhas em que o guia está inscrito, com os dados do pivô.
     */
    public function trilhas()
    {
        return $this->belongsToMany(Trilha::class, 'trilhas_guias', 'guia_id', 'trilha_id')
            ->withPivot('congelada', 'preco_por_pessoa');
    }
}
