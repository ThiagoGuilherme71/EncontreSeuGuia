<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrilhaController extends Controller
{
    public function trilhaSelecionada(){
        return view('trilhaPesquisa');
    }
}
