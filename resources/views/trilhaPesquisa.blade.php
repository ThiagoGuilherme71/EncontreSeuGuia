@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] mx-auto px-4 py-10">

        <div class="mt-20 max-w-4xl mx-auto px-4 py-10"> <!-- Adicionei `mt-20` para maior separação -->

            <div class="bg-gradient-to-br from-[#348360] via-[#4c826a] to-[#2e5f4d] p-10 rounded-2xl shadow-2xl mb-16 text-center">
                <h1 class="text-3xl font-bold mb-6 text-white tracking-wide uppercase">Trilha da Pedra Encantada</h1>
                <p class="text-lg text-gray-100 leading-relaxed mb-6">
                    A Trilha da Pedra Encantada é uma rota de nível intermediário, com duração média de 3 horas, passando por cachoeiras, mirantes e formações rochosas únicas. Ideal para quem busca aventura e belas paisagens, exigindo um bom preparo físico.
                </p>
                <div class="flex justify-center items-center gap-4">
                    <span class="text-sm text-gray-200 font-semibold">Nível de dificuldade:</span>
                    <span class="bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-2 rounded-full shadow-lg transform transition hover:scale-105">
                        Intermediário
                    </span>
                </div>
            </div>




            <!-- Título Guias -->
            <h2 class="text-2xl font-semibold mb-8 text-center dark:text-white">Guias Disponíveis</h2>

            @php
                $trilha = (object)[ 'id' => 1 ];
                $guias = [
                    (object)[ 'id' => 1, 'nome' => 'Carlos Silva', 'idade' => 32, 'experiencia' => 5, 'idiomas' => 'Português, Inglês', 'avaliacao' => 4 ],
                    (object)[ 'id' => 2, 'nome' => 'Ana Oliveira', 'idade' => 29, 'experiencia' => 3, 'idiomas' => 'Português, Espanhol', 'avaliacao' => 5 ],
                    (object)[ 'id' => 3, 'nome' => 'Lucas Andrade', 'idade' => 40, 'experiencia' => 10, 'idiomas' => 'Português', 'avaliacao' => 3 ],
                ];
            @endphp

            <div class="flex flex-col gap-6">
                @foreach($guias as $guia)
                    <div class="bg-[#348360] p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $guia->nome }}</h3>
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $guia->idade }} anos</span>
                        </div>

                        <div class="text-gray-700 dark:text-gray-300 text-sm mb-2">
                            Experiência: {{ $guia->experiencia }} anos<br>
                            Idiomas: {{ $guia->idiomas }}
                        </div>

                        <div class="flex items-center gap-1 mb-4">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $guia->avaliacao)
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.916 1.603-.916 1.902..." /></svg>
                                @else
                                    <svg class="h-4 w-4 text-yellow-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.916 1.603-.916 1.902..." /></svg>
                                @endif
                            @endfor
                        </div>

                        <a href="#" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-md transition">
                            Agendar com {{ $guia->nome }}
                        </a>
                    </div>
                @endforeach
            </div>

            @if(empty($guias))
                <p class="text-center mt-10 text-gray-500 dark:text-gray-400">Nenhum guia disponível no momento para esta trilha.</p>
            @endif
        </div>
    </div>
@endsection
