<?php

namespace Database\Seeders;

use App\Models\Dificuldade;
use App\Models\Guia;
use App\Models\Idioma;
use App\Models\Trilha;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Dificuldades
        foreach (['Fácil', 'Moderado', 'Difícil'] as $descricao) {
            Dificuldade::firstOrCreate(['descricao' => $descricao]);
        }

        $dificil = Dificuldade::where('descricao', 'Difícil')->first()->id;

        // Idiomas
        foreach (['Português', 'Inglês', 'Espanhol', 'Francês', 'Alemão', 'Italiano'] as $nome) {
            Idioma::firstOrCreate(['nome_idioma' => $nome]);
        }

        // Trilhas
        $fumacinha = $this->criarTrilha(
            nome: 'Cachoeira da Fumacinha',
            descricao: "Uma das cachoeiras mais altas do Brasil, com cerca de 340 metros de queda livre. A trilha sobe a serra a partir do Vale do Capão e o visual lá de cima é absolutamente de tirar o fôlego — num dia limpo dá para ver a fumaça se formando com o vento que carrega as gotículas d'água pelo abismo.\n\nA subida é exigente, com trechos de rocha escorregadia e inclinação acentuada no final, mas qualquer esforço se paga quando você chega à beira e olha para baixo. Leve bastante água, protetor solar e um calçado com boa aderência.\n\nDistância: ~12 km (ida e volta)\nDesnível: 400 m\nTempo médio: 5 a 7 horas",
            dificuldadeId: $dificil,
            cidade: 'Vale do Capão',
            estado: 'BA',
            imagemSeeder: 'cachoeira_fumacinha.jpg',
        );

        $pati = $this->criarTrilha(
            nome: 'Vale do Pati',
            descricao: "Considerada por muitos a travessia de trekking mais bonita do Brasil. São dias caminhando entre serras imponentes, cachoeiras que jorram das paredes de pedra e casas de nativos que recebem viajantes com comida feita no fogão à lenha e histórias passadas de geração em geração.\n\nO Vale do Pati é quase desabitado e sem acesso para carros, o que preserva uma tranquilidade raramente encontrada. A travessia completa passa por mirantes com vistas de 360° da Chapada Diamantina e por poços de águas cristalinas onde é possível nadar no meio da mata.\n\nDistância: ~70 km (travessia completa)\nDesnível acumulado: 3.200 m\nTempo médio: 3 a 5 dias\nÉpoca ideal: abril a setembro",
            dificuldadeId: $dificil,
            cidade: 'Andaraí',
            estado: 'BA',
            imagemSeeder: 'vale_do_pati.jpg',
        );

        // Guia
        $guia = Guia::firstOrCreate(
            ['email' => 'carlos.nascimento.guia@gmail.com'],
            [
                'nome'             => 'Carlos Eduardo Nascimento',
                'telefone'         => '(75) 98234-5671',
                'data_nascimento'  => '1983-09-14',
                'cpf'              => '235.489.126-77',
                'cep'              => '46900-000',
                'endereco'         => 'Rua da Praça, 45 — Lençóis, BA',
                'anos_experiencia' => 14,
                'link_instagram'   => '@carlosguia.chapada',
                'link_facebook'    => null,
                'doc_frente'       => null,
                'doc_verso'        => null,
                'password'         => bcrypt('123456'),
            ]
        );

        $guia->idiomas()->sync([
            Idioma::where('nome_idioma', 'Português')->first()->id,
            Idioma::where('nome_idioma', 'Inglês')->first()->id,
            Idioma::where('nome_idioma', 'Espanhol')->first()->id,
        ]);

        // Guia disponível nas duas trilhas
        $guia->trilhas()->syncWithoutDetaching([
            $fumacinha->id => ['congelada' => false],
            $pati->id      => ['congelada' => false],
        ]);

        // Trilheiro
        User::firstOrCreate(
            ['email' => 'thiagoguilherme.barbosaa@gmail.com'],
            [
                'nome'            => 'Thiago Guilherme Barbosa',
                'telefone'        => '(71) 98821-3047',
                'data_nascimento' => '2001-03-22',
                'cpf'             => '412.873.095-60',
                'password'        => bcrypt('123456'),
            ]
        );
    }

    private function criarTrilha(
        string $nome,
        string $descricao,
        int    $dificuldadeId,
        string $cidade,
        string $estado,
        string $imagemSeeder,
    ): Trilha {
        $fotoPath = $this->copiarFotoSeeder($imagemSeeder);

        return Trilha::firstOrCreate(
            ['nome' => $nome],
            [
                'descricao'       => $descricao,
                'id_dificuldade'  => $dificuldadeId,
                'cidade'          => $cidade,
                'estado'          => $estado,
                'foto'            => $fotoPath,
                'criado_por_guia' => null,
            ]
        );
    }

    private function copiarFotoSeeder(string $nomeArquivo): ?string
    {
        $origem = base_path("storage/app/seeders/{$nomeArquivo}");

        if (!file_exists($origem)) {
            return null;
        }

        $uuid    = Str::uuid()->toString();
        $destino = "trilhas/{$uuid}.jpg";

        Storage::disk('public')->put($destino, file_get_contents($origem));

        return "storage/{$destino}";
    }
}
