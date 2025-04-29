@extends('layouts.app-guia')

@section('title', 'Dashboard do guia')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] text-white">

    <div class="container mx-auto mt-32 px-4">

        <!-- Agendamentos Futuros -->
        <section class="bg-[#348360] h-full text-white rounded-lg p-6 shadow-md">
            <h2 class="text-xl font-semibold mb-4 text-center">Agendamentos Futuros</h2>
            <!-- Carrossel -->
            <div x-data="{ activeSlide: 0, cards: [1, 2, 3, 4, 5] }" class="relative w-full overflow-hidden">
                <!-- Slider -->
                <div class="flex items-center justify-center transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
                    <!-- Cards gerados dinamicamente -->
                    <template x-for="card in cards" :key="card">
                        <div class="flex-none w-full md:w-1/3 p-4">
                            <div class="bg-gray-700 max-w-[300px] rounded-xl hover:bg-gray-900 hover:scale-110 duration-700 p-5">
                                <figure class="w-10 h-10 p-2 bg-green-800 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </figure>

                                <h4 class="py-2 text-white font-bold">Fumaçinha</h4>
                                <p class="text-base leading-7 text-white font-semibold space-y-4">28/02/2025</p>
                                <p class="text-sm leading-7 text-slate-300 space-y-4">Thiago Guilherme - Às 7:00</p>
                                <p class="text-xs leading-7 text-gray-400">Observações: @resource ../...</p>
                                <div class="pt-5 pb-2 flex justify-center">
                                    <button class="w-36 h-10 font-semibold rounded-md bg-green-600 hover:bg-green-700 text-white duration-500">
                                        Conversar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Botão Anterior -->
                <button @click="activeSlide = activeSlide > 0 ? activeSlide - 1 : cards.length - 3"
                        class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-full hover:bg-gray-900 z-10">
                    &#10094;
                </button>

                <!-- Botão Próximo -->
                <button @click="activeSlide = activeSlide < cards.length - 3 ? activeSlide + 1 : 0"
                        class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-full hover:bg-gray-900 z-10">
                    &#10095;
                </button>
            </div>
        </section>

        <!-- Editar Agenda -->
        <section class="bg-[#348360] h-full text-white rounded-lg p-6 shadow-md mt-8 mb-8" >
            <h2 class="text-xl font-semibold mb-4 text-center">Editar Agenda</h2>
            <div class="flex justify-center">
                <!-- Calendário -->
                <div id="calendar" class="bg-white text-black rounded-lg shadow-lg p-4"></div>
            </div>
            <div class="flex justify-center gap-4 mt-4">
                <button id="btn-ocupar" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Ocupar Tudo</button>
                <button id="btn-liberar" class=" bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Liberar Tudo</button>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var eventos = [];

                if (calendarEl) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'pt-br', // 🇧🇷 Locale correto
                        initialView: 'dayGridMonth',
                        editable: true,
                        selectable: true,
                        events: eventos,
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        dateClick: function (info) {
                            let dataSelecionada = info.dateStr;
                            let eventoExistente = eventos.find(evento => evento.start === dataSelecionada);

                            if (eventoExistente) {
                                Swal.fire({
                                    title: 'Já existe um evento nesse dia.',
                                    text: 'Deseja remover?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sim, remover',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        eventos = eventos.filter(evento => evento.start !== dataSelecionada);
                                        calendar.removeAllEvents();
                                        calendar.addEventSource(eventos);
                                        Swal.fire('Removido!', 'O evento foi removido.', 'success');
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Adicionar Evento',
                                    input: 'text',
                                    inputLabel: 'Nome da trilha ou evento',
                                    inputPlaceholder: 'Digite aqui...',
                                    showCancelButton: true,
                                    confirmButtonText: 'Salvar',
                                    cancelButtonText: 'Cancelar',
                                    inputValidator: (value) => {
                                        if (!value) {
                                            return 'Você precisa digitar um nome!';
                                        }
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        eventos.push({
                                            title: result.value,
                                            start: dataSelecionada,
                                            color: 'green'
                                        });
                                        calendar.removeAllEvents();
                                        calendar.addEventSource(eventos);

                                        Swal.fire('Salvo!', 'Seu evento foi adicionado.', 'success');
                                    }
                                });
                            }
                        }



                    });


                    calendar.render();
                   document.getElementById('btn-ocupar').addEventListener('click', function () {
                        let dataAtual = new Date();
                        eventos = [];

                        for (let i = 0; i < 30; i++) {
                            let dataStr = dataAtual.toISOString().split('T')[0];
                            eventos.push({
                                title: 'Ocupado',
                                start: dataStr,
                                color: 'red'
                            });
                            dataAtual.setDate(dataAtual.getDate() + 1);
                        }

                        calendar.removeAllEvents();
                        calendar.addEventSource(eventos);
                    });

                    document.getElementById('btn-liberar').addEventListener('click', function () {
                        eventos = [];
                        calendar.removeAllEvents();
                    });
                }

            });
        </script>


@endsection
