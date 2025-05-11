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

            <!-- 1ª linha: E-mail -->
            <div class="mb-6">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome completo</label>
                <input type="text" id="nome" name="nome" required
                       class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
            </div>
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
            </div>

            <!-- 2ª linha: Telefone e Data de Nascimento -->
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="experiencia" class="block text-sm font-medium text-gray-700">Anos de Experiência</label>
                    <input type="number" id="experiencia" name="experiencia" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>


            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="experiencia" class="block text-sm font-medium text-gray-700">Selecione os Idiomas</label>
                    <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($idiomas as $idioma)
                            <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                <div class="flex items-center ps-3 text-black">
                                    <input id="idioma-{{ $idioma->id }}" type="checkbox" name="idiomas[]" value="{{ $idioma->id }}"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="idioma-{{ $idioma->id }}" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $idioma->nome_idioma }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            <!-- 3ª linha: CPF e CEP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" id="cpf" name="cpf" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <div>
                    <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                    <input type="text" id="cep" name="cep" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
            </div>

            <!-- 4ª linha: Endereço Completo -->
            <div class="mb-6">
                <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                <input type="text" id="endereco" name="endereco" required
                       class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
            </div>

            <!-- 5ª linha: Link Instagram e Link Facebook -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="link_instagram" class="block text-sm font-medium text-gray-700">Link do Instagram</label>
                    <input type="url" id="link_instagram" name="link_instagram"
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <div>
                    <label for="link_facebook" class="block text-sm font-medium text-gray-700">Link do Facebook</label>
                    <input type="url" id="link_facebook" name="link_facebook"
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>
            </div>

            <!-- 6ª linha: Documento Frente e Verso -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="doc_frente" class="block text-sm font-medium text-gray-700">Documento Frente</label>
                    <input type="file" id="doc_frente" name="doc_frente" required
                           class="mt-1 block w-full text-gray-600 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition">
                </div>

                <div>
                    <label for="doc_verso" class="block text-sm font-medium text-gray-700">Documento Verso</label>
                    <input type="file" id="doc_verso" name="doc_verso" required
                           class="mt-1 block w-full text-gray-600 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition">
                </div>
            </div>

            <!-- 7ª linha: Senha e Repetir Senha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repetir Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 block h-10 w-full text-gray-600 rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
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
    <div class="w-1/3 h-screen relative bg-cover bg-center" style="background-image: url('{{ asset('images/cachoeira.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white p-8">
            <h2 class="text-4xl md:text-5xl font-bold">Conecte-se com a Natureza</h2>
            <p class="text-lg mb-8">Participe da maior rede de trilheiros e guias da Chapada Diamantina.</p>
            <a href="{{ route('login') }}" class="bg-[#A27738] w-48 text-white px-8 py-4 rounded hover:bg-[#348360] transition font-bold">Voltar para Login</a>
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
