<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Guia - Encontre seu Guia</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet"> <!-- Tailwind CSS -->
</head>
<body class="m-0 p-0 flex flex-col min-h-screen bg-gray-100 font-sans">
    <div class="flex w-full">
        <!-- LADO ESQUERDO -->
        <div class="w-2/3 h-screen bg-[#E3CDA8] flex flex-col justify-center items-center p-8">
            <!-- Logo -->
            <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre Seu Guia" class="w-48 mt-2">

            <!-- Título -->
            <h1 class="text-3xl font-bold text-[#348360] text-center mb-4">Cadastro de Trilheiros</h1>

            <!-- Descrição -->
            <p class="text-gray-600 mb-6 text-center">
                Preencha os dados abaixo para criar sua conta.
            </p>

            <!-- Formulário -->
            <form action="{{route('signup.submit')}}" method="POST" enctype="multipart/form-data" class="w-full max-w-lg">
                @csrf

                <!-- 1ª linha: Nome Completo -->
                <div class="mb-6">
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 2ª linha: E-mail -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 3ª linha: CPF -->
                <div class="mb-6">
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" id="cpf" name="cpf" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 4ª linha: Telefone e Data de Nascimento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" id="telefone" name="telefone" required
                               class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>

                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required
                               class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>

                <!-- 5ª linha: Senha -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" minlength="6" id="password" name="password" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- 6ª linha: Repetir Senha -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repetir Senha</label>
                    <input type="password" minlength="6" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <!-- Botão de Envio -->
                <button type="submit"
                        class="w-full bg-[#348360] text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                    Criar Conta
                </button>

                <div class="text-center mt-4">
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
    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-6 galery-footer">
        <div class="container mx-auto flex flex-col md:flex-row gap-8 md:gap-0">

            <!-- Logo (Esquerda) -->
            <div class="w-48 flex justify-center md:justify-start ">
                <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre seu Guia" class="galery-footer-logo-img" style="width: 9rem">
            </div>

            <!-- Texto Central -->
            <div class=" text-center galery-footer-text">
                <p class="mb-2 galery-footer-text-mission">
                    Encontre seu Guia – Conectando você à natureza com segurança e experiência. Encontre guias qualificados,
                    explore trilhas incríveis e viva aventuras inesquecíveis.
                </p>
                <p class="text-green-400 galery-footer-slogan">🌿 Descubra. Conecte-se. Explore. 🌿</p>
            </div>

            <!-- Direitos e Contato (Direita) -->
            <div class=" text-center md:text-right galery-footer-contact items-right">
                <p class="galery-footer-rights">© 2025 Encontre seu Guia. Todos os direitos reservados.</p>
                <div class="mt-2 galery-footer-info">
                    <p>📍 contato@encontreseuguia.com</p>
                    <p>📞 (XX) XXXXX-XXXX</p>
                </div>
            </div>

        </div>
    </footer>
</body>
</html>
