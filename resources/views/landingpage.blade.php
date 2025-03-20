@extends('layouts.app')

@section('title', 'Encontre seu Guia')

@section('content')



    <!-- HERO SECTION -->
    <section class="relative w-full h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/cachoeira.jpg') }}');">
        <!-- Sobreposição escura -->
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>

        <!-- Conteúdo centralizado -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center text-white px-4">
            <!-- Logo -->
            <img src="{{ asset('images/logo.png-Photoroom.png') }}"  alt="Logo Encontre seu Guia" class="w-[300px] mb-6">

            <!-- Título -->
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
                ENCONTRE SEU GUIA
            </h1>

            <!-- Subtítulo -->
            <p class="text-lg md:text-xl mb-6 font-light max-w-xl drop-shadow-md">
                Somos a maior rede de Guias Turísticos da Chapada Diamantina.
            </p>

            <!-- Barra de busca -->
            <div class="w-full max-w-xl flex bg-white rounded-full overflow-hidden shadow-lg">
                <input
                    type="text"
                    placeholder="Informe a trilha desejada"
                    class="flex-grow px-6 py-3 text-gray-700 focus:outline-none rounded-l-full"
                >
                <button class="bg-[#348360] px-6 py-3 text-white font-bold hover:bg-[#2c6d52] transition-colors duration-300">
                    Buscar
                </button>
            </div>
        </div>
    </section>



@endsection
