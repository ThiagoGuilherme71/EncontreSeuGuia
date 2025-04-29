<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('guia.main-guia');
    }
    public function list()
    {
        return view('guia-list');
    }
    public function landingPage(){
        return view('landingpage');
    }
    public function exibirConta()
    {
        // Mock da variável guia
        $guia = (object) [
            'id' => 1,
            'nome' => 'João Silva',
            'email' => 'joao.silva@example.com',
            'telefone' => '(71) 91234-5678',
            'data_nascimento' => '1985-08-15',
            'cpf' => '123.456.789-00',
            'cep' => '40000-000',
            'endereco' => 'Rua das Trilhas, 123, Salvador - BA',
            'link_instagram' => 'https://instagram.com/joaosilva',
            'link_facebook' => 'https://facebook.com/joaosilva',
            'foto' => null, // Ou 'caminho/para/foto.jpg' para simular uma foto existente
            'doc_frente' => null, // Mock para arquivo de documento frente
            'doc_verso' => null, // Mock para arquivo de documento verso
        ];

        return view('conta.perfilGuia', compact('guia'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
