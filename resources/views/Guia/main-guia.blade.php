@extends('layouts.app')

@section('title', 'Dashboard do Guia')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] text-white">

    <div class="container mx-auto mt-32 px-4">

        <!-- Agendamentos Futuros -->
        <section class="bg-green-700 h-full text-white rounded-lg p-6 shadow-md">
            <h2 class="text-xl font-semibold mb-4 text-center">Agendamentos Futuros</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-amber-100 text-gray-900 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Fumaçinha</h3>
                    <p class="text-sm">28/02/2025</p>
                    <p class="text-sm">Thiago Guilherme</p>
                    <p class="text-sm">Às 7:00</p>
                    <p class="text-xs text-gray-700">Observações: @resource ../...</p>
                    <button class="mt-2 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                        Conversar
                    </button>
                </div>
                <div class="bg-amber-100 text-gray-900 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Fumaçinha</h3>
                    <p class="text-sm">28/02/2025</p>
                    <p class="text-sm">Thiago Guilherme</p>
                    <p class="text-sm">Às 7:00</p>
                    <p class="text-xs text-gray-700">Observações: @resource ../...</p>
                    <button class="mt-2 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                        Conversar
                    </button>
                </div>
            </div>
        </section>
        <!-- Editar Agenda -->
        <section class="bg-green-800 h-full text-white rounded-lg p-6 shadow-md mt-8 mb-8" >
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
