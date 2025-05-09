<?php

namespace Database\Seeders;

use App\Models\Idioma;
use Illuminate\Database\Seeder;
use App\Models\Trilha;
use App\Models\Dificuldade;
use App\Models\Guia;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criando valores fixos na tabela 'dificuldades'
        $dificuldades = ['Fácil', 'Intermediária', 'Difícil'];
        foreach ($dificuldades as $descricao) {
            Dificuldade::create(['descricao' => $descricao]);
        }

        // Pegando IDs das dificuldades criadas
        $dificuldadeIds = Dificuldade::pluck('id')->toArray();

        // Criando 6 trilhas associadas a dificuldades
        for ($i = 1; $i <= 6; $i++) {
            Trilha::create([
                'nome' => "Trilha $i",
                'descricao' => "Descrição da Trilha $i",
                'id_dificuldade' => $dificuldadeIds[array_rand($dificuldadeIds)], // Define uma dificuldade aleatória
                'cidade' => "Cidade $i",
            ]);
        }

        // Criando 6 guias
        for ($i = 1; $i <= 6; $i++) {
            Guia::create([
                'nome' => "Guia $i",
                'email' => "guia$i@example.com",
                'telefone' => "(71) 90000-000$i",
                'cep' => "40000-00$i",
                'endereco' => "Rua dos Guias, $i",
                'link_instagram' => "https://instagram.com/guia$i",
                'link_facebook' => "https://facebook.com/guia$i",
                'doc_frente' => "doc_frente$i.jpg",
                'doc_verso' => "doc_verso$i.jpg",
                'password' => bcrypt("senha$i"),
                'data_nascimento' => "198$i-01-15",
                'cpf' => "123.456.78$i-00",
            ]);
        }
        // Lista dos principais idiomas do mundo
        $idiomas = [
            'Inglês', 'Mandarim', 'Hindi', 'Espanhol', 'Francês', 'Árabe',
            'Bengali', 'Russo', 'Português', 'Urdu', 'Indonésio', 'Alemão',
            'Japonês', 'Turco', 'Tâmil', 'Coreano', 'Vietnamita', 'Persa'
        ];

        // Inserindo os idiomas na tabela
        foreach ($idiomas as $nome) {
            Idioma::create(['nome_idioma' => $nome]);
        }
    }
}
