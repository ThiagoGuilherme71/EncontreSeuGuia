


    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meu Projeto')</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet"> <!-- Tailwind CSS -->
</head>

<body class="m-0 p-0 flex flex-col min-h-screen bg-gray-100 font-sans">
<header class="fixed top-0 left-0 w-full bg-white/70 backdrop-blur-md shadow z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- LOGO -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}">
                    <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo Encontre seu Guia">
                </a>
            </div>

            <!-- NAV LINKS -->
            <nav class="hidden md:flex space-x-8">
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Agendamentos
                </a>
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Trilhas Disponíveis
                </a>
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Guias
                </a>
                <a href="#" class="text-gray-800 hover:text-[#348360] text-sm font-semibold transition">
                    Dicas & Galeria
                </a>
            </nav>

            <!-- ÍCONE DE USUÁRIO -->
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-800 hover:text-[#348360] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A13.937 13.937 0 0112 15c2.486 0 4.797.676 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
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


<!-- Rodapé -->
<footer class="bg-gray-800 text-white py-4">
    <div class="w-full text-center">
        <p>&copy; {{ date('Y') }} Meu Projeto. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>
