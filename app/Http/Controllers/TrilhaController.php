<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Trilha;
use Illuminate\Http\Request;

class TrilhaController extends Controller
{
    public function trilhaSelecionada(){
        return view('trilha.trilhaPesquisa');
    }

    public function buscar(Request $request)
    {
        $nome = $request->input('nome');


        if ($nome) {
            return redirect()->route('trilhas.exibir', ['nome' => 'pedra-encantada']);
        }

        // fallback ou mensagem de erro
        return redirect()->back()->with('error', 'trilha não encontrada');
    }

    public function exibir($nome)
    {

        if ($nome) {
            $trilha = Trilha::all()->where('nome', $nome);
//            $trilha = (object)[
//                'id' => 1,
//                'nome' => 'trilha da Pedra Encantada',
//                'descricao' => 'A trilha da Pedra Encantada é uma rota de nível intermediário...',
//                'nivel' => 'Intermediário',
//            ];
            $guias = Guia::select('nome', )->get();
            $guias = [
                (object)[ 'id' => 1, 'nome' => 'Carlos Silva', 'idade' => 32, 'experiencia' => 5, 'idiomas' => 'Português, Inglês', 'avaliacao' => 4 ],
                (object)[ 'id' => 2, 'nome' => 'Ana Oliveira', 'idade' => 29, 'experiencia' => 3, 'idiomas' => 'Português, Espanhol', 'avaliacao' => 5 ],
                (object)[ 'id' => 3, 'nome' => 'Lucas Andrade', 'idade' => 40, 'experiencia' => 10, 'idiomas' => 'Português', 'avaliacao' => 3 ],
            ];

            return view('trilha.trilhaPesquisa', compact('trilha', 'guias'));
        }

        return abort(404);
    }
    public function create(){
    return view('trilha.formTrilha');
    }
    public function store(Request $request){
        $trilha = Trilha::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'id_dificuldade' => $request->id_dificuldade, // ID da dificuldade relacionada
            'cidade' => $request->cidade,
            'foto' =>$request->foto
        ]);
        return redirect()->route('guia-dash');

    }
}
