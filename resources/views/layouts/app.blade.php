

<!-- Cabeçalho -->
{{--<header class="bg-green-600 text-white py-4">--}}
{{--    <div class="container mx-auto px-4">--}}
{{--        <h1 class="text-2xl font-bold">--}}
{{--            <a href="{{ url('/') }}">Meu Projeto</a>--}}
{{--        </h1>--}}
{{--    </div>--}}
{{--</header>--}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meu Projeto')</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet"> <!-- Tailwind CSS -->
</head>
<body class="m-0 p-0 flex flex-col min-h-screen bg-gray-100 font-sans">

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
