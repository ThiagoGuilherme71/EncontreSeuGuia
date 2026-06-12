<?php

namespace Database\Seeders;

use App\Models\Idioma;
use Illuminate\Database\Seeder;
use App\Models\Trilha;
use App\Models\Dificuldade;
use App\Models\Guia;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Dificuldades (alinhadas com o frontend)
        $dificuldades = ['Fácil', 'Moderado', 'Difícil'];
        foreach ($dificuldades as $descricao) {
            Dificuldade::firstOrCreate(['descricao' => $descricao]);
        }

        $facil = Dificuldade::where('descricao', 'Fácil')->first()->id;
        $moderado = Dificuldade::where('descricao', 'Moderado')->first()->id;
        $dificil = Dificuldade::where('descricao', 'Difícil')->first()->id;

        // Trilhas da Chapada Diamantina
        $trilhas = [
            [
                'nome' => 'Cachoeira da Fumaça',
                'descricao' => "Uma das cachoeiras mais altas do Brasil, com cerca de 340 metros de queda livre. A trilha sobe a serra a partir do Vale do Capão e o visual lá de cima é de tirar o fôlego.\n\nDistância: ~12km (ida e volta)\nTempo médio: 6 horas",
                'id_dificuldade' => $dificil,
                'cidade' => 'Vale do Capão',
            ],
            [
                'nome' => 'Morro do Pai Inácio',
                'descricao' => "O cartão-postal da Chapada Diamantina. Subida curta e tranquila que recompensa com uma vista panorâmica de 360° dos vales e morros da região. Imperdível no pôr do sol.\n\nDistância: ~1km\nTempo médio: 40 minutos",
                'id_dificuldade' => $facil,
                'cidade' => 'Palmeiras',
            ],
            [
                'nome' => 'Vale do Pati',
                'descricao' => "Considerada uma das travessias mais bonitas do Brasil. São dias caminhando entre serras, cachoeiras e casas de nativos que servem comida feita no fogão à lenha.\n\nDistância: ~70km (travessia completa)\nTempo médio: 3 a 5 dias",
                'id_dificuldade' => $dificil,
                'cidade' => 'Andaraí',
            ],
            [
                'nome' => 'Poço do Diabo',
                'descricao' => "Poço de águas escuras no Rio Mucugezinho, cercado por paredões. Ótimo para banho e para quem quer se aventurar na tirolesa e no rapel.\n\nDistância: ~2km\nTempo médio: 1 hora",
                'id_dificuldade' => $facil,
                'cidade' => 'Lençóis',
            ],
            [
                'nome' => 'Cachoeira do Buracão',
                'descricao' => "Um cânion estreito onde a água despenca 85 metros em meio a paredes de pedra. A chegada nadando pelo cânion é uma experiência única.\n\nDistância: ~6km\nTempo médio: 4 horas",
                'id_dificuldade' => $moderado,
                'cidade' => 'Ibicoara',
            ],
            [
                'nome' => 'Gruta da Pratinha',
                'descricao' => "Águas azul-cristalinas onde é possível flutuar e fazer snorkel observando os peixes. Conjunto de grutas com acesso fácil, ideal para famílias.\n\nDistância: acesso direto\nTempo médio: 2 horas",
                'id_dificuldade' => $facil,
                'cidade' => 'Iraquara',
            ],
        ];

        foreach ($trilhas as $dados) {
            Trilha::firstOrCreate(['nome' => $dados['nome']], $dados);
        }

        // Idiomas
        $idiomas = ['Português', 'Inglês', 'Espanhol', 'Francês', 'Alemão', 'Italiano'];
        foreach ($idiomas as $nome) {
            Idioma::firstOrCreate(['nome_idioma' => $nome]);
        }

        // Guias (senha: 123456)
        $guias = [
            ['nome' => 'João do Capão',     'email' => 'joao@guia.com',    'anos_experiencia' => 12, 'cidade_base' => 'Vale do Capão'],
            ['nome' => 'Maria das Trilhas', 'email' => 'maria@guia.com',   'anos_experiencia' => 8,  'cidade_base' => 'Lençóis'],
            ['nome' => 'Pedro Caminhante',  'email' => 'pedro@guia.com',   'anos_experiencia' => 15, 'cidade_base' => 'Andaraí'],
            ['nome' => 'Ana da Serra',      'email' => 'ana@guia.com',     'anos_experiencia' => 5,  'cidade_base' => 'Palmeiras'],
            ['nome' => 'Carlos Trekking',   'email' => 'carlos@guia.com',  'anos_experiencia' => 20, 'cidade_base' => 'Ibicoara'],
            ['nome' => 'Lúcia Aventura',    'email' => 'lucia@guia.com',   'anos_experiencia' => 3,  'cidade_base' => 'Iraquara'],
        ];

        foreach ($guias as $i => $dados) {
            $guia = Guia::firstOrCreate(
                ['email' => $dados['email']],
                [
                    'nome' => $dados['nome'],
                    'telefone' => '(75) 9' . rand(1000, 9999) . '-' . rand(1000, 9999),
                    'cep' => '465' . rand(10, 99) . '-000',
                    'endereco' => $dados['cidade_base'] . ', Chapada Diamantina - BA',
                    'anos_experiencia' => $dados['anos_experiencia'],
                    'link_instagram' => '@' . strtolower(explode(' ', $dados['nome'])[0]) . '.guia',
                    'link_facebook' => null,
                    'doc_frente' => 'sandbox/doc_frente.jpg',
                    'doc_verso' => 'sandbox/doc_verso.jpg',
                    'password' => bcrypt('123456'),
                    'data_nascimento' => (1975 + $i * 4) . '-0' . ($i + 1) . '-15',
                    'cpf' => sprintf('%03d.%03d.%03d-%02d', rand(100, 999), rand(100, 999), rand(100, 999), rand(10, 99)),
                ]
            );

            // Idiomas: todos falam português, alguns falam mais
            $idiomaIds = [Idioma::where('nome_idioma', 'Português')->first()->id];
            if ($i % 2 === 0) $idiomaIds[] = Idioma::where('nome_idioma', 'Inglês')->first()->id;
            if ($i % 3 === 0) $idiomaIds[] = Idioma::where('nome_idioma', 'Espanhol')->first()->id;
            $guia->idiomas()->sync($idiomaIds);
        }

        // Vincular guias às trilhas (cada trilha com até 3 guias)
        $todasTrilhas = Trilha::all();
        $todosGuias = Guia::all();
        foreach ($todasTrilhas as $i => $trilha) {
            $guiasDaTrilha = $todosGuias->slice($i % 4, 3)->pluck('id')->toArray();
            $trilha->guias()->sync($guiasDaTrilha);
        }

        // Usuário trilheiro de teste (senha: 123456)
        User::firstOrCreate(
            ['email' => 'teste@teste.com'],
            [
                'nome' => 'Thiago Trilheiro',
                'telefone' => '(75) 99999-0000',
                'data_nascimento' => '1995-06-15',
                'cpf' => '111.222.333-44',
                'password' => bcrypt('123456'),
            ]
        );
    }
}
