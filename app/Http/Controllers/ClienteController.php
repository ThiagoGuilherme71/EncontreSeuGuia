<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Trilha;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cliente = (object)[
            'id' => 1,
            'nome' => 'João da Chapada',
            'email' => 'joao@aventura.com',
            'telefone' => '(75) 91234-5678',
            'data_nascimento' => '1990-05-15',
            'cpf' => '123.456.789-00',
            'foto' => null, // ou 'perfil.jpg' se quiser simular uma foto existente
        ];

        return view('auth.perfilCliente', compact('cliente'));
    }

    public function landingPage(){
        $trilhas = Trilha::all();
        $guias = Guia::all();

        return view('landingpage' , compact('guias', 'trilhas'));
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
