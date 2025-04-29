@extends('layouts.app-guia')

@section('title', 'Perfil do guia')

@section('content')
    <section class="bg-[#348360] h-full w-full py-16 px-4 ">
        <div class="w-full h-1/3 max-w-4xl mx-auto px-4 py-32 text-gray-600"> {{-- py-32 para descer abaixo do header --}}
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Meu Perfil de Guia</h2>

                {{-- FOTO --}}
                <div class="flex flex-col items-center mb-6">
                    <img id="previewImage"
                         src="{{ $guia->foto ? asset('storage/fotos/' . $guia->foto) : asset('images/default-user.png') }}"
                         alt="Foto do Guia"
                         class="w-32 h-32 rounded-full object-cover mb-2 shadow">

                    <label for="foto" class="block text-sm font-medium text-gray-700">Alterar Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="mt-1 text-sm text-gray-600 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition"
                           onchange="previewFoto(event)">
                </div>

                {{-- FORMULÁRIO --}}
                <form method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        <!-- Nome Completo -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                            <input type="text" id="nome" name="nome" value="{{ $guia->nome }}"
                                   class="w-full px-4 py-2 border rounded-md" />
                        </div>

                        <!-- E-mail -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                            <input type="email" id="email" name="email" value="{{ $guia->email }}"
                                   class="w-full px-4 py-2 border rounded-md bg-gray-100" disabled />
                        </div>

                        <!-- Telefone e Data de Nascimento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                <input type="text" id="telefone" name="telefone" value="{{ $guia->telefone }}"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>

                            <div>
                                <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                <input type="date" id="data_nascimento" name="data_nascimento" value="{{ $guia->data_nascimento }}"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>
                        </div>

                        <!-- CPF e CEP -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                                <input type="text" id="cpf" name="cpf" value="{{ $guia->cpf }}"
                                       class="w-full px-4 py-2 border rounded-md bg-gray-100" disabled />
                            </div>

                            <div>
                                <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                                <input type="text" id="cep" name="cep" value="{{ $guia->cep }}"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>
                        </div>

                        <!-- Endereço Completo -->
                        <div>
                            <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                            <input type="text" id="endereco" name="endereco" value="{{ $guia->endereco }}"
                                   class="w-full px-4 py-2 border rounded-md" />
                        </div>

                        <!-- Links de Redes Sociais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="link_instagram" class="block text-sm font-medium text-gray-700">Link do Instagram</label>
                                <input type="url" id="link_instagram" name="link_instagram" value=" {{ $guia->link_instagram }}"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>

                            <div>
                                <label for="link_facebook" class="block text-sm font-medium text-gray-700">Link do Facebook</label>
                                <input type="url" id="link_facebook" name="link_facebook" value="@if(isset($guia)) {{ $guia->link_facebook }} @endif"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>
                        </div>

                        <!-- Documentos Frente e Verso -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="doc_frente" class="block text-sm font-medium text-gray-700">Documento Frente</label>
                                <input type="file" id="doc_frente" name="doc_frente"
                                       class="w-full text-sm text-gray-500 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition" />
                            </div>

                            <div>
                                <label for="doc_verso" class="block text-sm font-medium text-gray-700">Documento Verso</label>
                                <input type="file" id="doc_verso" name="doc_verso"
                                       class="w-full text-sm text-gray-500 file:bg-[#348360] file:text-white hover:file:bg-green-700 transition" />
                            </div>
                        </div>

                        <!-- Senhas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repetir Senha</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 border rounded-md" />
                            </div>
                        </div>

                        <!-- Botão de Salvar -->
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
