<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="{{ asset('css/output.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="/">

    </head>
    <body class="h-screen w-screen bg-gray-100 font-sans">

    <div class="flex h-screen">

        <!-- LADO ESQUERDO -->
        <div class="w-1/3 bg-[#E3CDA8] flex flex-col justify-center items-center p-8">

            <!-- Logo -->
            <img src="{{ asset('images/logo.png-Photoroom.png') }}" alt="Logo Encontre Seu Guia" class="w-[300px] mb-6">
            <!-- Formulário -->
            <form action="{{ route('login.submit') }}" method="POST" class="w-full max-w-sm">
                @csrf
                @if ($errors->has('login'))
                    <div class="mb-4 bg-gray-100 w-24 font-bold text-red-500 text-sm rounded text-center">{{ $errors->first('login') }}</div>
                @endif


                <div class="mb-4">
                    <label for="email" class="block  text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 block h-10 text-black w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <div class="mb-4">
                    <label for="password" class="block  text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" id="password" minlength="6" name="password" required
                           class="mt-1 block h-10 w-full text-black rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                </div>

                <div class="mb-4 text-right">
                    <a href="#" class="text-sm text-green-700 hover:underline">Esqueceu a senha?</a>
                </div>

                <button type="submit"
                        class="w-full bg-[#348360] text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                    Entrar
                </button>

                <div class="text-right mt-4">
                    <button type="button" id="createAccount" class="bg-[#A27738] px-6 py-2 text-white font-bold rounded hover:bg-[#348360] transition">
                        Criar Conta
                    </button>
                </div>
{{--                pop-up hidde--}}
                <div id="popup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg p-6 w-[350px] shadow-lg text-center">
                        <h2 class="text-xl font-bold mb-4 text-gray-600">Escolha uma opção</h2>
                        <button id="signupGuia" onclick="redirectCreateAccount(0)" class="bg-[#348360] text-white px-4 py-2 rounded mb-2 w-full">
                            Criar Conta para Guia
                        </button>
                        <button id="signupTrilheiro"  onclick="redirectCreateAccount(1)" class="bg-[#348360] text-white px-4 py-2 rounded  w-full">
                            Criar Conta para Trilheiro
                        </button>
                        <button id="closePopup" class=" mt-4 px-4 py-2 rounded  text-red-500">
                            Fechar
                        </button>
                    </div>
                </div>


            </form>
        </div>
        <!-- LADO DIREITO -->
        <div id="rightSide" class="w-2/3 relative bg-cover bg-center transition-all duration-1000 ease-in-out transform"
        style="background-image: url('{{ asset('images/cachoeira.jpg') }}')">


        <div class="absolute inset-0 bg-black bg-opacity-30"></div>

            <!-- Conteúdo centralizado -->
            <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white p-8">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">ENCONTRE SEU GUIA</h1>
                <p class="text-lg mb-8">Somos a maior rede de Guias Turísticos da Chapada Diamantina</p>

                <!-- Campo de busca -->
                <form action="#" method="GET" class="w-full max-w-md flex">
                    <input type="text" placeholder="Informe a trilha desejada"
                           class="flex-grow rounded-l-md px-4 py-2 text-gray-800 focus:outline-none">
                    <button type="button" id="search"
                            class="bg-[#348360] px-4 py-2 rounded-r-md hover:bg-green-700 transition">
                        🔍
                    </button>
                </form>
            </div>

        </div>

    </div>

    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('search').addEventListener('click', function () {
            const rightSide = document.getElementById('rightSide');

            // Animação de saída (cima para fora)
            rightSide.classList.add('-translate-y-full', 'opacity-0');
            rightSide.classList.remove('opacity-100', 'translate-y-0');

            setTimeout(() => {
                // Faz uma requisição AJAX para obter o componente
                fetch('{{ route("guia-list") }}')
                    .then(response => response.text())
                    .then(html => {
                        rightSide.innerHTML = html;

                        // Transição de entrada
                        rightSide.classList.remove('-translate-y-full');
                        rightSide.classList.add('opacity-100', 'translate-y-0');
                    })
                    .catch(error => console.error('Erro ao carregar o componente:', error));
            }, 500); // Tempo da transição de saída
        });
    });

    const createAccountBtn = document.getElementById('createAccount');
    const popup = document.getElementById('popup');
    const closePopupBtn = document.getElementById('closePopup');

    // Exibir o pop-up ao clicar no botão
    createAccountBtn.addEventListener('click', () => {
        popup.classList.remove('hidden'); // Mostra o pop-up
    });

    // Fechar o pop-up ao clicar no botão "Fechar"
    closePopupBtn.addEventListener('click', () => {
        popup.classList.add('hidden'); // Esconde o pop-up
    });

    // Fechar o pop-up ao clicar fora dele
    popup.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.classList.add('hidden');
        }
    });

    function redirectCreateAccount(idChoice){
        // 0 = guia / 1 = Trilheiro
        if (idChoice === 0){
            window.location.href = "/signup-guia";
        }else{
            window.location.href = "/signup";
        }
    }

</script>

