<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meu Projeto')</title>
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

<body class="m-0 p-0 flex flex-col min-h-screen bg-gray-100 font-sans">
<header class="fixed top-0 left-0 w-full bg-white/70 backdrop-blur-md shadow z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-24">

            <!-- LOGO -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}">
                    <img class="h-24 w-auto" src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre seu Guia">
                </a>
            </div>

            <!-- NAV LINKS -->
            <nav class="hidden md:flex space-x-8">
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Agendamentos
                </a>
                <a href="{{ route('trilhas.create') }}" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Adicionar Trilhas
                </a>
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Historico
                </a>

            </nav>

            <!-- ÍCONE DE USUÁRIO -->
            <div class="flex items-center space-x-4">
                <a href="{{route('conta.cliente')}}" class="text-gray-800 hover:text-[#348360] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A13.937 13.937 0 0112 15c2.486 0 4.797.676 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
                <a  href="{{route('logout')}}" class="flex items-center gap-2 text-black px-4 py-2 rounded-lg hover:bg-grey-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1"/>
                    </svg>
                    Sair
                </a>

            </div>

            <!-- MENU MOBILE FUTURAMENTE (opcional) -->

        </div>
    </div>
</header>

{{--    MENU SUPERIOR--}}
<!-- Área principal -->
<main class="flex-grow flex justify-center items-center">
    @yield('content')
</main>




</body>

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

</html>
