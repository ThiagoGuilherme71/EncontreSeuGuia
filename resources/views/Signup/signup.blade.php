@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen">
        <!-- Lado Esquerdo -->
        <div class="w-1/2 bg-[#E6D2B5] flex flex-col justify-center items-center p-10">
            <img src="/logo.png" alt="Logo Encontre seu Guia" class="w-56 mb-6">
        </div>

        <!-- Lado Direito -->
        <div class="w-1/2 bg-[#3B7B63] flex justify-center items-center p-10">
            <div class="bg-[#A3B18A] rounded-lg p-8 w-full max-w-md shadow-md">
                <h1 class="text-center text-xl font-bold text-gray-800 mb-6">Cadastro Cliente</h1>
                <form action="{{ route('signup.submit') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="email" name="email" placeholder="E-mail" required
                               class="w-full p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                    </div>
                    <div class="mb-4">
                        <input type="text" name="telefone" placeholder="Telefone" required
                               class="w-full p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                    </div>
                    <div class="mb-4 flex gap-4">
                        <input type="text" name="cpf" placeholder="CPF" required
                               class="w-1/2 p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                        <input type="date" name="data_nascimento" placeholder="Data de Nascimento" required
                               class="w-1/2 p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Senha" required
                               class="w-full p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                    </div>
                    <div class="mb-6">
                        <input type="password" name="password_confirmation" placeholder="Repita a Senha" required
                               class="w-full p-3 rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-700">
                    </div>
                    <button type="submit"
                            class="w-full bg-[#A97142] hover:bg-[#8B5E34] text-white font-semibold py-2 rounded transition duration-300">
                        Criar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
