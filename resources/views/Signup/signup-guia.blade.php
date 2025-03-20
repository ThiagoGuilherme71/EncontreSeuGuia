@extends('layouts.app')

@section('content')
    <div class="flex w-full" >
        <!-- LADO ESQUERDO -->
        <div class="w-2/3 h-screen bg-[#E3CDA8] flex flex-col justify-center items-center p-8" >
            <!-- Logo -->
            <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre Seu Guia" class="w-48 mt-2">

            <!-- Título -->
            <h1 class="text-3xl font-bold text-[#348360] text-center" style="margin-top: -20px">Cadastro de</h1>

            <!-- Descrição -->
            <p class="text-gray-700 mb-2 text-center">
                Preencha os dados abaixo para criar sua conta.
            </p>

            <!-- Formulário -->
            <form action="#" method="POST" enctype="multipart/form-data" class="w-full max-w-lg">
                @csrf

                <!-- 1ª linha: E-mail -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 2ª linha: Telefone e Data de Nascimento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" id="telefone" name="telefone" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>

                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>

                <!-- 3ª linha: CPF e CEP -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" id="cpf" name="cpf" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>

                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                        <input type="text" id="cep" name="cep" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>

                <!-- 4ª linha: Endereço Completo -->
                <div class="mb-6">
                    <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                    <input type="text" id="endereco" name="endereco" required
                           class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 5ª linha: Link Instagram e Link Facebook -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="link_insta" class="block text-sm font-medium text-gray-700">Link do Instagram</label>
                        <input type="url" id="link_insta" name="link_insta"
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>

                    <div>
                        <label for="link_face" class="block text-sm font-medium text-gray-700">Link do Facebook</label>
                        <input type="url" id="link_face" name="link_face"
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>

                <!-- 6ª linha: Documento Frente e Verso -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="documento_frente" class="block text-sm font-medium text-gray-700">Documento Frente</label>
                        <input type="file" id="documento_frente" name="documento_frente" required
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition">
                    </div>

                    <div>
                        <label for="documento_verso" class="block text-sm font-medium text-gray-700">Documento Verso</label>
                        <input type="file" id="documento_verso" name="documento_verso" required
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition">
                    </div>
                </div>

                <!-- 7ª linha: Senha e Repetir Senha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                        <input type="password" id="password" name="password" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repetir Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>



                <button type="submit"
                        class="w-full bg-[#348360] text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                    Criar Conta
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-green-700 hover:underline">
                        Já tem uma conta? Entrar
                    </a>
                </div>
            </form>
        </div>

        <!-- LADO DIREITO -->
        <div class="w-1/3 h-screen relative bg-cover bg-center"
             style="background-image: url('{{ asset('images/cachoeira.jpg') }}')">

            <!-- Overlay escuro -->
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>

            <!-- Conteúdo centralizado -->
            <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white p-8">
                <h2 class="text-4xl md:text-5xl font-bold">Conecte-se com a Natureza</h2>
                <p class="text-lg mb-8">Participe da maior rede de trilheiros e guias da Chapada Diamantina.</p>
                <a href="{{ route('login') }}" class="bg-[#A27738] w-48 text-white px-8 py-4 rounded hover:bg-[#348360] transition font-bold">
                    Voltar para Login
                </a>
            </div>
        </div>
    </div>
@endsection
