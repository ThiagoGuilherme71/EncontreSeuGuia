@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] mx-auto px-4 py-10">

        <div class="mt-20 max-w-4xl mx-auto px-4 py-10"> <!-- Adicionei `mt-20` para maior separação -->

            <div class="bg-gradient-to-br from-[#348360] via-[#4c826a] to-[#2e5f4d] p-10 rounded-2xl shadow-2xl mb-16 text-center">
                <h1 class="text-3xl font-bold mb-6 text-white tracking-wide uppercase">{{ $trilha->nome }}</h1>
                <p class="text-lg text-gray-100 leading-relaxed mb-6">
                    {{ $trilha->descricao }}
                </p>
                <div class="flex justify-center items-center gap-4">
                    <span class="text-sm text-gray-200 font-semibold">Nível de dificuldade:</span>
                    <span class="bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-2 rounded-full shadow-lg transform transition hover:scale-105">
            {{ $trilha->nivel }}
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

            <div x-data="{ open: false, selectedGuide: null }">
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
                                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="..." /></svg>
                                    @else
                                        <svg class="h-4 w-4 text-yellow-200" fill="currentColor" viewBox="0 0 20 20"><path d="..." /></svg>
                                    @endif
                                @endfor
                            </div>

                            <button
                                @click="open = true; selectedGuide = '{{ $guia->nome }}'"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-md transition"
                            >
                                Agendar com {{ $guia->nome }}
                            </button>
                        </div>
                    @endforeach

                </div>

                <!-- Modal -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4">
                        <h2 class="text-xl font-bold text-gray-800">Agendar com <span x-text="selectedGuide"></span></h2>

                        <form @submit.prevent="alert('Agendamento enviado!'); open = false;">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium text-gray-700">Data</label>
                                <input type="date" class="border rounded px-3 py-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" required>

                                <label class="text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" class="border rounded px-3 text-black py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>

                                <label class="text-sm font-medium text-gray-700">Descrição</label>
                                <textarea class="border rounded px-3 py-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" rows="3" required></textarea>
                            </div>

                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" @click="open = false"
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Cancelar</button>
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Agendar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        @if(empty($guias))
                <p class="text-center mt-10 text-gray-500 dark:text-gray-400">Nenhum guia disponível no momento para esta trilha.</p>
            @endif
        </div>
    </div>
@endsection
