<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Guia - Encontre seu Guia</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet"> <!-- Tailwind CSS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="//unpkg.com/alpinejs" defer></script>


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <!-- FullCalendar principal -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <!-- Locale PT-BR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/pt-br.global.min.js"></script>


    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0  flex flex-col min-h-full bg-gray-100 font-sans" >
<div class="flex w-full ">
    <!-- LADO ESQUERDO -->
    <div class="w-2/3 h-full bg-[#E3CDA8] flex flex-col justify-center items-center p-8">
        <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre Seu Guia" class="w-48 mt-2">
        <h1 class="text-3xl font-bold text-[#348360] text-center" style="margin-top: -20px">Cadastro de Guias</h1>
        <p class="text-gray-600 mb-2 text-center">Preencha os dados abaixo para criar sua conta.</p>
        <form action="{{ route('signup.guia.submit')}}" method="POST" enctype="multipart/form-data" class="w-full max-w-lg">
            @csrf

            <!-- 1ª linha: E-mail -->
            <div class="mb-6">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome completo</label>
                <input type="text" id="nome" name="nome" required
                       class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
            </div>
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
            </div>

            <!-- 2ª linha: Telefone e Data de Nascimento -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="text" id="telefone" name="telefone" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>

                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="experiencia" class="block text-sm font-medium text-gray-700">Anos de Experiência</label>
                    <input type="number" id="experiencia" name="experiencia" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>

                <div x-data="{ open: false, selectedIdiomas: [] }" class="relative">
                    <label for="idiomas" class="block text-sm font-medium text-gray-700">Idiomas</label>
                    <button @click="open = !open" class="flex justify-between items-center w-full p-2 border rounded-md text-left bg-white">
                        <span class="text-gray-700" x-text="selectedIdiomas.length > 0 ? selectedIdiomas.join(', ') : 'Selecione os idiomas'"></span>
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>


                    <div x-show="open" class="absolute z-10 w-full bg-white border rounded-md shadow-lg mt-1">
                        <ul class="max-h-32 overflow-y-auto">
                            @foreach ($idiomas as $idioma)
                                <li class="p-2 hover:bg-gray-200 cursor-pointer text-gray-500" @click="selectedIdiomas.includes('{{ $idioma->nome_idioma }}') ? selectedIdiomas.splice(selectedIdiomas.indexOf('{{ $idioma->nome_idioma }}'), 1) : selectedIdiomas.push('{{ $idioma->nome_idioma }}')">
                                    <input type="checkbox" name="idiomas[]" value="{{ $idioma->id }}"
                                           :checked="selectedIdiomas.includes('{{ $idioma->nome_idioma }}')" class="mr-2">
                                    {{ $idioma->nome_idioma }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>




            <!-- 3ª linha: CPF e CEP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" id="cpf" name="cpf" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>

                <div>
                    <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                    <input type="text" id="cep" name="cep" required
                           class="mt-1 text-gray-600 block h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>
            </div>

            <!-- 4ª linha: Endereço Completo -->
            <div class="mb-6">
                <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                <input type="text" id="endereco" name="endereco" required
                       class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
            </div>

            <!-- 5ª linha: Link Instagram e Link Facebook -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="link_instagram" class="block text-sm font-medium text-gray-700">Link do Instagram</label>
                    <input type="url" id="link_instagram" name="link_instagram"
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>

                <div>
                    <label for="link_facebook" class="block text-sm font-medium text-gray-700">Link do Facebook</label>
                    <input type="url" id="link_facebook" name="link_facebook"
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>
            </div>

            <!-- 6ª linha: Documento Frente e Verso -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="doc_frente" class="block text-sm font-medium text-gray-700">Documento Frente</label>
                    <input type="file" id="doc_frente" name="doc_frente" required
                           class="mt-1 block w-full text-gray-600 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition px-3">
                </div>

                <div>
                    <label for="doc_verso" class="block text-sm font-medium text-gray-700">Documento Verso</label>
                    <input type="file" id="doc_verso" name="doc_verso" required
                           class="mt-1 block w-full text-gray-600 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition px-3">
                </div>
            </div>

            <!-- 7ª linha: Senha e Repetir Senha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block h-10 text-gray-600 w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repetir Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 block h-10 w-full text-gray-600 rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 px-3">
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
    <div class="w-1/3  relative bg-cover bg-center" style="background-image: url('{{ asset('images/cachoeira.jpg') }}')">
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
