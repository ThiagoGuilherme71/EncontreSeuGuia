<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrilhaController extends Controller
{
    public function trilhaSelecionada(){
        return view('Trilha.trilhaPesquisa');
    }

    public function buscar(Request $request)
    {
        $nome = $request->input('nome');


        if ($nome) {
            return redirect()->route('trilhas.exibir', ['nome' => 'pedra-encantada']);
        }

        // fallback ou mensagem de erro
        return redirect()->back()->with('error', 'Trilha não encontrada');
    }

    public function exibir($nome)
    {
        // Simulação (poderia buscar no banco com: Trilha::where('slug', $nome)->first())
        if ($nome) {
            $trilha = (object)[
                'id' => 1,
                'nome' => 'Trilha da Pedra Encantada',
                'descricao' => 'A Trilha da Pedra Encantada é uma rota de nível intermediário...',
                'nivel' => 'Intermediário',
            ];

            $guias = [
                (object)[ 'id' => 1, 'nome' => 'Carlos Silva', 'idade' => 32, 'experiencia' => 5, 'idiomas' => 'Português, Inglês', 'avaliacao' => 4 ],
                (object)[ 'id' => 2, 'nome' => 'Ana Oliveira', 'idade' => 29, 'experiencia' => 3, 'idiomas' => 'Português, Espanhol', 'avaliacao' => 5 ],
                (object)[ 'id' => 3, 'nome' => 'Lucas Andrade', 'idade' => 40, 'experiencia' => 10, 'idiomas' => 'Português', 'avaliacao' => 3 ],
            ];

            return view('Trilha.trilhaPesquisa', compact('trilha', 'guias'));
        }

        return abort(404);
    }
}
