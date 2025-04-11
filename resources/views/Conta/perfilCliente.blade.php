@extends('layouts.app') {{-- ou o layout principal que você estiver usando --}}

@section('title', 'Meu Perfil')

@section('content')
    <section class="bg-[#348360] h-screen w-full py-16 px-4 ">
        <div class="w-full max-w-3xl b mx-auto px-4 py-32 text-gray-600"> {{-- py-32 para descer abaixo do header --}}
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Meu Perfil</h2>

                {{-- FOTO --}}
                <div class="flex flex-col items-center mb-6">
                    {{-- Imagem atual ou pré-visualização --}}
                    <img id="previewImage"
                         src="{{ $cliente->foto ? asset('storage/fotos/' . $cliente->foto) : asset('images/default-user.png') }}"
                         alt="Foto do Cliente"
                         class="w-32 h-32 rounded-full object-cover mb-2 shadow">

                    <label for="foto" class="block text-sm font-medium text-gray-700">Alterar Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="mt-1 text-sm text-gray-600"
                           onchange="previewFoto(event)">
                </div>


                {{-- FORMULÁRIO --}}
                <form method="POST" action="#">
                    @csrf
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" value="{{ $cliente->nome }}" disabled class="w-full px-4 py-2 border rounded-md bg-gray-100" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" value="{{ $cliente->email }}" disabled class="w-full px-4 py-2 border rounded-md bg-gray-100" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" value="{{ $cliente->telefone }}" class="w-full px-4 py-2 border rounded-md" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                            <input type="date" value="{{ $cliente->data_nascimento }}" class="w-full px-4 py-2 border rounded-md" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">CPF</label>
                            <input type="text" value="{{ $cliente->cpf }}" disabled class="w-full px-4 py-2 border rounded-md bg-gray-100" />
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
