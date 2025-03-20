<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('main');
    }

    // Processa o login
    public function login(Request $request)
    {
        //
    }

    // Faz o logout
    public function logout(Request $request)
    {
        //
    }


}
