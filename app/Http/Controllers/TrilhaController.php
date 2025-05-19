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

    public function getAllTrilhas()
    {
        $trilhas = Trilha::all();
        return $trilhas;
//         tipo de retorno:
//        "id" => 2
//        "nome" => "Thiago Guilherme"
//        "descricao" => "rth"
//        "id_dificuldade" => 1
//        "cidade" => "fh"
//        "foto" => "C:\Users\thiag\AppData\Local\Temp\php787A.tmp"
//        "created_at" => "2025-04-26 00:00:14"
//        "updated_at" => "2025-04-26 00:00:14"
    }
    public function getTrilha($id)
    {
        $trilha = Trilha::where('id', $id)->first();
        return $trilha;
//         tipo de retorno:
//        "id" => 2
//        "nome" => "Thiago Guilherme"
//        "descricao" => "rth"
//        "id_dificuldade" => 1
//        "cidade" => "fh"
//        "foto" => "C:\Users\thiag\AppData\Local\Temp\php787A.tmp"
//        "created_at" => "2025-04-26 00:00:14"
//        "updated_at" => "2025-04-26 00:00:14"
    }

    public function exibir($nome)
    {

        if ($nome) {
            $trilha = Trilha::where('nome', $nome)->first();

            $guias = $trilha->guias()->select('nome', 'anos_experiencia')->get();


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
            'id_dificuldade' => $request->id_dificuldade,
            'cidade' => $request->cidade,
        ]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('trilhas', 'public');
            $trilha->foto = 'storage/' . $path;
            $trilha->save();
        }

        return redirect()->route('guia-dash');

    }
}
