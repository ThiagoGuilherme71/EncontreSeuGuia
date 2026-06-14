import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import Avatar from '@/Components/ui/Avatar';
import { cn } from '@/lib/utils';
import { ChevronLeft, Camera, Check } from 'lucide-react';

export default function Edit({ role, perfil, idiomas_disponiveis = [], meus_idiomas = [], errors = {} }) {
    const isGuia = role === 'guia';
    const [preview, setPreview] = useState(perfil.foto ? `/${perfil.foto}` : null);

    const { data, setData, post, processing } = useForm({
        nome: perfil.nome ?? '',
        telefone: perfil.telefone ?? '',
        data_nascimento: perfil.data_nascimento ?? '',
        cep: perfil.cep ?? '',
        endereco: perfil.endereco ?? '',
        anos_experiencia: perfil.anos_experiencia ?? '',
        link_instagram: perfil.link_instagram ?? '',
        link_facebook: perfil.link_facebook ?? '',
        idiomas: [...meus_idiomas],
        foto: null,
        password: '',
        password_confirmation: '',
    });

    function handleFoto(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        setData('foto', file);
        setPreview(URL.createObjectURL(file));
    }

    function toggleIdioma(id) {
        setData('idiomas', data.idiomas.includes(id)
            ? data.idiomas.filter((x) => x !== id)
            : [...data.idiomas, id]);
    }

    function submit(e) {
        e.preventDefault();
        post('/perfil', { forceFormData: true });
    }

    return (
        <AppLayout>
            <Head title="Editar perfil" />

            <div className="max-w-xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href={isGuia ? '/conta-guia' : '/conta'}
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Voltar
                </Link>

                <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917] mb-6">
                    Editar perfil
                </h1>

                <form onSubmit={submit} className="flex flex-col gap-5">

                    {/* Avatar */}
                    <div className="flex items-center gap-4">
                        <div className="relative">
                            <Avatar src={preview} name={data.nome} size="xl" />
                            <label className="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-[#2D6A4F] text-white border-2 border-[#1C1917] flex items-center justify-center cursor-pointer hover:bg-[#1f4d39] transition-colors shadow-[2px_2px_0px_#1C1917]">
                                <Camera size={14} />
                                <input type="file" accept="image/*" onChange={handleFoto} className="hidden" />
                            </label>
                        </div>
                        <div>
                            <p className="text-sm font-semibold text-[#1C1917]">Foto de perfil</p>
                            <p className="text-xs text-[#78716C]">JPG ou PNG, até 5MB.</p>
                            {errors.foto && <p className="text-xs text-red-600 mt-0.5">{errors.foto}</p>}
                        </div>
                    </div>

                    <Input
                        label="Nome completo"
                        value={data.nome}
                        onChange={(e) => setData('nome', e.target.value)}
                        error={errors.nome}
                        required
                    />

                    <Input
                        label="E-mail"
                        value={perfil.email}
                        disabled
                        hint="O e-mail não pode ser alterado."
                    />

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input
                            label="Telefone"
                            value={data.telefone}
                            onChange={(e) => setData('telefone', e.target.value)}
                            error={errors.telefone}
                            required
                        />
                        {!isGuia && (
                            <Input
                                label="Data de nascimento"
                                type="date"
                                value={data.data_nascimento?.slice(0, 10) ?? ''}
                                onChange={(e) => setData('data_nascimento', e.target.value)}
                                error={errors.data_nascimento}
                                required
                            />
                        )}
                        {isGuia && (
                            <Input
                                label="Anos de experiência"
                                type="number"
                                min="0"
                                value={data.anos_experiencia}
                                onChange={(e) => setData('anos_experiencia', e.target.value)}
                                error={errors.anos_experiencia}
                            />
                        )}
                    </div>

                    {isGuia && (
                        <>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <Input
                                    label="CEP"
                                    value={data.cep}
                                    onChange={(e) => setData('cep', e.target.value)}
                                    error={errors.cep}
                                />
                                <Input
                                    label="Endereço"
                                    value={data.endereco}
                                    onChange={(e) => setData('endereco', e.target.value)}
                                    error={errors.endereco}
                                />
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <Input
                                    label="Instagram"
                                    value={data.link_instagram}
                                    onChange={(e) => setData('link_instagram', e.target.value)}
                                    placeholder="@seuperfil"
                                />
                                <Input
                                    label="Facebook"
                                    value={data.link_facebook}
                                    onChange={(e) => setData('link_facebook', e.target.value)}
                                />
                            </div>

                            <div className="flex flex-col gap-1.5">
                                <label className="text-sm font-semibold text-[#1C1917]">Idiomas que você fala</label>
                                <div className="flex flex-wrap gap-2">
                                    {idiomas_disponiveis.map((idioma) => {
                                        const ativo = data.idiomas.includes(idioma.id);
                                        return (
                                            <button
                                                key={idioma.id}
                                                type="button"
                                                onClick={() => toggleIdioma(idioma.id)}
                                                className={cn(
                                                    'flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-xl border-2 transition-all font-medium',
                                                    ativo
                                                        ? 'bg-[#2D6A4F] text-white border-[#1C1917] shadow-[2px_2px_0px_#1C1917]'
                                                        : 'bg-white text-[#1C1917] border-[#E3CDA8] hover:border-[#1C1917]',
                                                )}
                                            >
                                                {ativo && <Check size={14} />}
                                                {idioma.nome_idioma}
                                            </button>
                                        );
                                    })}
                                </div>
                            </div>
                        </>
                    )}

                    {/* Senha */}
                    <div className="bg-[#F5EDD9] rounded-2xl border-2 border-[#E3CDA8] p-4">
                        <p className="text-sm font-semibold text-[#1C1917] mb-3">
                            Trocar senha <span className="font-normal text-[#78716C]">(deixe em branco pra manter)</span>
                        </p>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input
                                label="Nova senha"
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                error={errors.password}
                                placeholder="Mín. 6 caracteres"
                            />
                            <Input
                                label="Confirmar nova senha"
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                            />
                        </div>
                    </div>

                    <Button type="submit" loading={processing} size="lg" className="w-full">
                        Salvar alterações
                    </Button>
                </form>
            </div>
        </AppLayout>
    );
}
