<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Guia - Encontre seu Guia</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet"> <!-- Tailwind CSS -->
</head>
<body class="m-0 p-0 flex flex-col min-h-screen bg-gray-100 font-sans">
<div class="flex w-full h-full">
    <!-- LADO ESQUERDO -->
    <div class="w-2/3 h-screen bg-[#E3CDA8] flex flex-col justify-center items-center p-8">
        <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre Seu Guia" class="w-48 mt-2">
        <h1 class="text-3xl font-bold text-[#348360] text-center" style="margin-top: -20px">Cadastro de Guias</h1>
        <p class="text-gray-600 mb-2 text-center">Preencha os dados abaixo para criar sua conta.</p>
        <form action="#" method="POST" enctype="multipart/form-data" class="w-full max-w-lg">
            @csrf
            <div class="mb-6">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome completo</label>
                <input type="text" id="nome" name="nome" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
            </div>
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="text" id="telefone" name="telefone" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" id="cpf" name="cpf" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
                <div>
                    <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                    <input type="text" id="cep" name="cep" required class="mt-1 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
            </div>
            <button type="submit" class="w-full bg-[#348360] text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                Criar Conta
            </button>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-green-700 hover:underline">Já tem uma conta? Entrar</a>
            </div>
        </form>
    </div>
    <!-- LADO DIREITO -->
    <div class="w-1/3 h-screen relative bg-cover bg-center" style="background-image: url('{{ asset('images/cachoeira.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white p-8">
            <h2 class="text-4xl md:text-5xl font-bold">Conecte-se com a Natureza</h2>
            <p class="text-lg mb-8">Participe da maior rede de trilheiros e guias da Chapada Diamantina.</p>
            <a href="{{ route('login') }}" class="bg-[#A27738] w-48 text-white px-8 py-4 rounded hover:bg-[#348360] transition font-bold">Voltar para Login</a>
        </div>
    </div>
</div>
</body>
</html>
