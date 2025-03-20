<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

class SignupController extends Controller
{
    public function showSignupForm()
    {
        return view('Signup.signup.bl');
    }

    public function signup(Request $request)
    {

    }

    public function showGuiaSignupForm()
    {
        return view('Signup.signup-guia');
    }

    public function signupGuia(Request $request)
    {
        // Lógica para processar o cadastro do guia
    }

}
