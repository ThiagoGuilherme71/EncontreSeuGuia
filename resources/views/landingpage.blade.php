@extends('layouts.app')

@section('title', 'Encontre seu Guia')

@section('content')



    <!-- HERO SECTION -->
    <div class="w-full">

        <!-- Primeira Seção: Landing Page -->
        <section class="relative h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/cachoeira.jpg') }}');">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>

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
        </section>

        <!-- Segunda Seção: Principais Trilhas (Carrossel) -->
        <section class="relative h-screen bg-[#A27738] bg-opacity-90 overflow-hidden">

            <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white p-8">
                <h2 class="text-3xl md:text-4xl justify-center items-center text-center font-bold mb-8">Principais Trilhas</h2>

                <!-- Carrossel de Trilhas -->
                <div class="flex space-x-4 overflow-x-auto max-w-6xl px-4 py-2">
                    <!-- Card 1 -->
                    <div class="flex-none bg-white rounded-lg shadow-lg w-64 text-gray-800 overflow-hidden" style="margin: 1%">
                        <img src="{{ asset('images/cachoeira.jpg') }}" alt="Trilha 1" class="h-48 w-full object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">Trilha da Fumaça</h3>
                            <p class="text-sm mb-4">Ibicoara - 18 km - Difícil</p>
                            <p class="text-sm">Prepare-se para uma aventura inesquecível no Parque Nacional da Chapada Diamantina.</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="flex-none bg-white rounded-lg shadow-lg w-64 text-gray-800 overflow-hidden" style="margin: 1%">
                        <img src="{{ asset('images/cachoeira.jpg') }}" alt="Trilha 2" class="h-48 w-full object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">Trilha do Pati</h3>
                            <p class="text-sm mb-4">Andaraí - 25 km - Médio</p>
                            <p class="text-sm">Paisagens exuberantes e pernoites em vilas isoladas no coração da Chapada.</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="flex-none bg-white rounded-lg shadow-lg w-64 text-gray-800 overflow-hidden" style="margin: 1%">
                        <img src="{{ asset('images/cachoeira.jpg') }}" alt="Trilha 3" class="h-48 w-full object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">Cachoeira do Buracão</h3>
                            <p class="text-sm mb-4">Ibicoara - 4 km - Fácil</p>
                            <p class="text-sm">Experiência única de nadar dentro do cânion até a impressionante cachoeira.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Terceira Seção: Dicas & Galeria -->
        <section class="bg-[#348360] h-screen py-16 px-4 text-white">
            <div class="max-w-6xl mx-auto">

                <!-- Título -->
                <h2 class="text-3xl font-bold text-center p-6">Dicas & Galeria</h2>

                <!-- Grid Principal -->
                <div class="flex flex-wrap justify-center" style="margin-top: 10%">

                    <!-- Dicas -->
                    <div class="bg-[#D9C4A6] rounded-xl p-8 text-gray-800 shadow-lg">
                        <h3 class="text-2xl font-semibold mb-6 text-center">Itens Essenciais para Trilhas</h3>

                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🎒</span>
                                <span><strong>Mochila de Trilha:</strong> Leve para carregar os itens de forma confortável.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">💧</span>
                                <span><strong>Água:</strong> Beba ao menos 2 litros por dia para manter a hidratação.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🍫</span>
                                <span><strong>Snacks e Lanches:</strong> Barras de cereal, frutas secas ou castanhas para manter a energia.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🥾</span>
                                <span><strong>Roupas e Botas de Trilha:</strong> Roupas leves, botas adequadas e capa de chuva.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🦟</span>
                                <span><strong>Repelente e Protetor Solar:</strong> Proteção contra insetos e raios UV.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🗺️</span>
                                <span><strong>Mapa ou GPS:</strong> Para se localizar nas trilhas mais longas.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">🩹</span>
                                <span><strong>Kit de Primeiros Socorros:</strong> Curativos e itens básicos para emergências.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-xl mr-4">♻️</span>
                                <span><strong>Sacolinhas para Lixo:</strong> Para manter a natureza limpa.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Galeria de Fotos -->
                    <div class="flex justify-center">
                            <!-- Galeria Flexível no Estilo Stack -->
                            <div class="flex flex-wrap justify-center gap-4 rounded p-4 max-w-full">
                                <!-- Mensagem de Aviso -->
                                <p class="browser-warning-gallery">
                                    Se isso parecer estranho para você, pode ser porque este navegador não suporta a propriedade CSS 'aspect-ratio'.
                                </p>

                                <!-- Stack Card 1 -->
                                <div class="stack-gallery ">
                                    <div class="card-gallery">
                                        <div class="image-gallery">
                                            <img src="{{ asset('images/cachoeira.jpg') }}" alt="Foto 1" class="object-cover w-full h-full">
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>

                </div>
            </div>
        </section>
        <section class="card-container" style="background-color: #E3CDA8">

            <!-- Card 1 -->
            <div class="card">
                <div class="card-border-top"></div>
                <div class="img">
                    <img src="images/guiaAnimado.png" alt="Guia João">
                </div>
                <span>João Silva</span>
                <p class="job">Trilhas de Montanha</p>
                <button>Agendar</button>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <div class="card-border-top"></div>
                <div class="img">
                    <img src="images/guiaAnimado.png" alt="Guia Maria">
                </div>
                <span>Maria Oliveira</span>
                <p class="job">Cachoeiras & Camping</p>
                <button>Agendar</button>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <div class="card-border-top"></div>
                <div class="img">
                    <img src="images/guiaAnimado.png" alt="Guia Carlos">
                </div>
                <span>Carlos Souza</span>
                <p class="job">Expedições no Cerrado</p>
                <button>Agendar</button>
            </div>

        </section>



    </div>


    <style>
        .card-container {
            display: flex;
            justify-content: center;
            gap: 40px; /* antes 20px */
            flex-wrap: wrap;
            padding: 80px 40px; /* antes 40px 20px */
            background-color: #f3f4f6;
        }

        /* Card */
        .card {
            width: 380px; /* antes 190px */
            height: 600px; /* antes 300px */
            background: #3405a3;
            border-radius: 30px; /* antes 15px */
            box-shadow: 2px 10px 120px 0px #100a886b; /* antes 1px 5px 60px */
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-20px); /* antes -10px */
            box-shadow: 2px 20px 120px 0px #100a886b;
        }

        .card-border-top {
            width: 60%;
            height: 6%; /* antes 3% */
            background: #6b64f3;
            border-radius: 0px 0px 30px 30px; /* antes 15px */
        }

        .card span {
            font-weight: 600;
            color: white;
            text-align: center;
            display: block;
            padding-top: 20px; /* antes 10px */
            font-size: 32px; /* antes 16px */
        }

        .card .job {
            font-weight: 400;
            color: white;
            display: block;
            text-align: center;
            padding-top: 6px; /* antes 3px */
            font-size: 24px; /* antes 12px */
        }

        .img {
            width: 160px; /* antes 80px */
            height: 160px; /* antes 80px */
            background: #6b64f3;
            border-radius: 50%;
            margin-top: 50px; /* antes 25px */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card button {
            padding: 16px 50px; /* antes 8px 25px */
            border-radius: 16px; /* antes 8px */
            border: none;
            margin-top: 40px; /* antes 20px */
            background: #6b64f3;
            color: white;
            font-weight: 600;
            font-size: 20px; /* antes 10px ~ não tinha, coloquei proporcional */
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .card button:hover {
            background: #534bf3;
        }

        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    <style>
        img-gallery {
            display: block;
            max-width: 60%;
        }

        .stack-gallery {
            width: 55%;
            max-width: 650px;
            transition: 0.25s ease;
        }

        .stack-gallery:hover {
            transform: rotate(5deg);
        }

        .stack-gallery:hover .card-gallery:before {
            transform: translateY(-2%) rotate(-4deg);
        }

        .stack-gallery:hover .card-gallery:after {
            transform: translateY(2%) rotate(4deg);
        }

        .card-gallery {
            aspect-ratio: 3 / 2;
            border: 4px solid #E3CDA8;
            background-color: #A27738;
            position: relative;
            transition: 0.15s ease;
            cursor: pointer;
            padding: 5% 5% 15% 5%;
        }

        .card-gallery:before,
        .card-gallery:after {
            content: "";
            display: block;
            position: absolute;
            height: 100%;
            width: 100%;
            border: 4px solid #E3CDA8;
            background-color: #A27738;
            transform-origin: center center;
            z-index: -1;
            transition: 0.15s ease;
            top: 0;
            left: 0;
        }

        .card-gallery:before {
            transform: translateY(-2%) rotate(-6deg);
        }

        .card-gallery:after {
            transform: translateY(2%) rotate(6deg);
        }

        .image-gallery {
            width: 100%;
            border: 4px solid #E3CDA8;
            background-color: #A27738;
            aspect-ratio: 1 / 1;
            position: relative;
        }

        .browser-warning-gallery {
            margin-bottom: 4rem;
        }

        @supports (aspect-ratio: 1 / 1) {
            .browser-warning-gallery {
                display: none;
            }
        }

    </style>

@endsection
