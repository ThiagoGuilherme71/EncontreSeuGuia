@extends('layouts.app')

@section('title', 'Dashboard do Guia')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] text-white">

    <div class="container mx-auto mt-32 px-4">

        <!-- Agendamentos Futuros -->
        <div class="bg-green-700 text-white rounded-lg p-6 shadow-md">
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
        </div>

        <!-- Editar Agenda -->
        <div class="bg-green-800 text-white rounded-lg p-6 shadow-md mt-8">
            <h2 class="text-xl font-semibold mb-4 text-center">Editar Agenda</h2>
            <div class="flex justify-center">
                <!-- Calendário -->
                <div id="calendar" class="bg-white text-black rounded-lg shadow-lg p-4"></div>



            </div>
            <div class="flex justify-center gap-4 mt-4">
                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Editar</button>
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Fechar agenda</button>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Liberar agenda</button>
            </div>
        </div>



    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            if (calendarEl) { // Garante que o elemento exista
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', // Visualização inicial (mês)
                    editable: true, // Permite arrastar e soltar eventos
                    events: [
                        {
                            title: 'Trilha Fumaça',
                            start: '2025-04-10T10:00:00',
                            end: '2025-04-10T13:00:00',
                            color: 'green'
                        },
                        {
                            title: 'Trilha Pati',
                            start: '2025-04-12T08:00:00',
                            end: '2025-04-12T12:00:00',
                            color: 'blue'
                        }
                    ],
                    headerToolbar: {
                        left: 'prev,next today', // Botões de navegação
                        center: 'title', // Título central
                        right: 'dayGridMonth,timeGridWeek,timeGridDay' // Alternar visualizações
                    }
                });
                calendar.render(); // Renderiza o calendário na tela
            }
        });

    </script>
@endsection
