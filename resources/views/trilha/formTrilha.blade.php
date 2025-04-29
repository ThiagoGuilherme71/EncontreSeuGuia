@extends('layouts.app-guia')

@section('title', 'Dashboard do guia')

@section('content')
    <div class="min-h-screen w-full bg-[#A27738] text-white">

        <div id="formTrilha" class=" bg-opacity-90  text-white " style="margin-top: 150px; ">
            <div class="max-w-3xl mx-auto  bg-gray-100 rounded-xl shadow-lg p-8 " >
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Criar Nova Trilha</h2>

                <form action="{{ route('trilhas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Proteção contra CSRF -->

                    <!-- Nome da trilha -->
                    <div class="mb-6">
                        <label for="nome" class="block text-lg font-semibold text-gray-700">Nome da Trilha</label>
                        <input type="text" id="nome" name="nome" class="w-full mt-2 p-3 rounded-lg border border-gray-300 text-gray-800" placeholder="Ex: Trilha da Pedra Encantada" required>
                    </div>

                    <!-- Descrição -->
                    <div class="mb-6">
                        <label for="descricao" class="block text-lg font-semibold text-gray-700">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="4" class="w-full mt-2 p-3 rounded-lg border border-gray-300 text-gray-800" placeholder="Descreva a trilha..." required></textarea>
                    </div>

                    <!-- Dificuldade -->
                    <div class="mb-6">
                        <label for="id_dificuldade" class="block text-lg font-semibold text-gray-700">Nível de Dificuldade</label>
                        <select id="id_dificuldade" name="id_dificuldade" class="w-full mt-2 p-3 rounded-lg border border-gray-300 text-gray-800" required>
                            <option value="">Selecione</option>
                            <option value="1">Fácil</option>
                            <option value="2">Intermediário</option>
                            <option value="3">Avançado</option>
                        </select>
                    </div>

                    <!-- Cidade -->
                    <div class="mb-6">
                        <label for="cidade" class="block text-lg font-semibold text-gray-700">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="w-full mt-2 p-3 rounded-lg border border-gray-300 text-gray-800" placeholder="Ex: Salvador" required>
                    </div>

                    <!-- Foto -->
                    <div class="mb-6">
                        <label for="foto" class="block text-lg font-semibold text-gray-700">Foto da Trilha</label>
                        <input type="file" id="foto" name="foto" class="w-full mt-2 p-3 rounded-lg border border-gray-300 text-gray-800" accept="image/*">
                    </div>

                    <!-- Botão -->
                    <div class="text-center">
                        <button type="submit" class="bg-[#348360] hover:bg-[#2d6e54] text-white font-bold px-6 py-3 rounded-lg transition duration-300">
                            Criar Trilha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
