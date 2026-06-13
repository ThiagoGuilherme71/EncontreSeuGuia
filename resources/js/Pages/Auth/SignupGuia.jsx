import { useForm, Link } from '@inertiajs/react';
import { useState } from 'react';
import AuthLayout from '@/Layouts/AuthLayout';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { cn } from '@/lib/utils';
import { Check, ChevronLeft, ChevronRight } from 'lucide-react';

function ChipSelect({ options, selected, onToggle, labelKey }) {
    return (
        <div className="flex flex-wrap gap-2">
            {options.map((opt) => {
                const active = selected.includes(opt.id);
                return (
                    <button
                        key={opt.id}
                        type="button"
                        onClick={() => onToggle(opt.id)}
                        className={cn(
                            'flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-xl border-2 transition-all font-medium',
                            active
                                ? 'bg-[#2D6A4F] text-white border-[#1C1917] shadow-[2px_2px_0px_#1C1917]'
                                : 'bg-white text-[#1C1917] border-[#E3CDA8] hover:border-[#1C1917]',
                        )}
                    >
                        {active && <Check size={14} />}
                        {opt[labelKey]}
                    </button>
                );
            })}
        </div>
    );
}

export default function SignupGuia({ idiomas = [], trilhas = [], errors = {} }) {
    const [step, setStep] = useState(1);

    const { data, setData, post, processing } = useForm({
        nome: '',
        email: '',
        telefone: '',
        data_nascimento: '',
        cpf: '',
        cep: '',
        endereco: '',
        experiencia: '',
        link_instagram: '',
        link_facebook: '',
        doc_frente: '',
        doc_verso: '',
        password: '',
        password_confirmation: '',
        idiomas: [],
        trilhas: [],
    });

    function toggle(field, id) {
        setData(field, data[field].includes(id)
            ? data[field].filter((x) => x !== id)
            : [...data[field], id]);
    }

    function submit(e) {
        e.preventDefault();
        post('/signup-guia-submit');
    }

    const step1Valid = data.nome && data.email && data.telefone && data.data_nascimento && data.cpf && data.password && data.password === data.password_confirmation;

    return (
        <AuthLayout
            title="Seja um guia"
            subtitle={step === 1 ? 'Etapa 1 de 2 — Dados pessoais' : 'Etapa 2 de 2 — Perfil de guia'}
        >
            {/* Progresso */}
            <div className="flex gap-2 mb-6">
                {[1, 2].map((s) => (
                    <div
                        key={s}
                        className={cn(
                            'h-1.5 flex-1 rounded-full transition-colors',
                            s <= step ? 'bg-[#2D6A4F]' : 'bg-[#E3CDA8]',
                        )}
                    />
                ))}
            </div>

            <form onSubmit={submit} className="flex flex-col gap-4">
                {step === 1 && (
                    <>
                        <Input
                            label="Nome completo"
                            value={data.nome}
                            onChange={(e) => setData('nome', e.target.value)}
                            error={errors.nome}
                            required
                            autoFocus
                        />
                        <Input
                            label="E-mail"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            error={errors.email}
                            required
                        />
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input
                                label="Telefone"
                                value={data.telefone}
                                onChange={(e) => setData('telefone', e.target.value)}
                                error={errors.telefone}
                                placeholder="(00) 00000-0000"
                                required
                            />
                            <Input
                                label="Data de nascimento"
                                type="date"
                                value={data.data_nascimento}
                                onChange={(e) => setData('data_nascimento', e.target.value)}
                                error={errors.data_nascimento}
                                required
                            />
                        </div>
                        <Input
                            label="CPF"
                            value={data.cpf}
                            onChange={(e) => setData('cpf', e.target.value)}
                            error={errors.cpf}
                            placeholder="000.000.000-00"
                            required
                        />
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input
                                label="Senha"
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                error={errors.password}
                                placeholder="Mín. 6 caracteres"
                                required
                            />
                            <Input
                                label="Confirmar senha"
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                required
                            />
                        </div>

                        <Button
                            type="button"
                            onClick={() => setStep(2)}
                            disabled={!step1Valid}
                            className="w-full mt-2"
                            size="lg"
                        >
                            Continuar <ChevronRight size={16} />
                        </Button>
                    </>
                )}

                {step === 2 && (
                    <>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input
                                label="CEP"
                                value={data.cep}
                                onChange={(e) => setData('cep', e.target.value)}
                                error={errors.cep}
                                placeholder="00000-000"
                            />
                            <Input
                                label="Anos de experiência"
                                type="number"
                                min="0"
                                value={data.experiencia}
                                onChange={(e) => setData('experiencia', e.target.value)}
                                error={errors.experiencia}
                            />
                        </div>
                        <Input
                            label="Endereço"
                            value={data.endereco}
                            onChange={(e) => setData('endereco', e.target.value)}
                            error={errors.endereco}
                        />
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
                                placeholder="/seuperfil"
                            />
                        </div>

                        <div className="flex flex-col gap-1.5">
                            <label className="text-sm font-semibold text-[#1C1917]">Idiomas que você fala</label>
                            <ChipSelect
                                options={idiomas}
                                selected={data.idiomas}
                                onToggle={(id) => toggle('idiomas', id)}
                                labelKey="nome_idioma"
                            />
                        </div>

                        {trilhas.length > 0 && (
                            <div className="flex flex-col gap-1.5">
                                <label className="text-sm font-semibold text-[#1C1917]">Trilhas que você guia</label>
                                <div className="max-h-40 overflow-y-auto pr-1">
                                    <ChipSelect
                                        options={trilhas}
                                        selected={data.trilhas}
                                        onToggle={(id) => toggle('trilhas', id)}
                                        labelKey="nome"
                                    />
                                </div>
                            </div>
                        )}

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div className="flex flex-col gap-1.5">
                                <label className="text-sm font-semibold text-[#1C1917]">
                                    Documento (frente)
                                    <span className="text-[#78716C] font-normal"> — opcional</span>
                                </label>
                                <input
                                    type="file"
                                    accept="image/*,.pdf"
                                    onChange={(e) => setData('doc_frente', e.target.files[0])}
                                    className="text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-[#1C1917] file:bg-[#F5EDD9] file:text-[#1C1917] file:font-semibold file:cursor-pointer hover:file:bg-[#E3CDA8] cursor-pointer"
                                />
                            </div>
                            <div className="flex flex-col gap-1.5">
                                <label className="text-sm font-semibold text-[#1C1917]">
                                    Documento (verso)
                                    <span className="text-[#78716C] font-normal"> — opcional</span>
                                </label>
                                <input
                                    type="file"
                                    accept="image/*,.pdf"
                                    onChange={(e) => setData('doc_verso', e.target.files[0])}
                                    className="text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-[#1C1917] file:bg-[#F5EDD9] file:text-[#1C1917] file:font-semibold file:cursor-pointer hover:file:bg-[#E3CDA8] cursor-pointer"
                                />
                            </div>
                        </div>

                        <div className="flex gap-3 mt-2">
                            <Button
                                type="button"
                                variant="secondary"
                                onClick={() => setStep(1)}
                                size="lg"
                            >
                                <ChevronLeft size={16} /> Voltar
                            </Button>
                            <Button type="submit" loading={processing} className="flex-1" size="lg">
                                Criar conta de guia
                            </Button>
                        </div>
                    </>
                )}

                <p className="text-center text-sm text-[#78716C]">
                    Já tem conta?{' '}
                    <Link href="/login" className="text-[#2D6A4F] font-semibold hover:underline">
                        Entrar
                    </Link>
                </p>
            </form>
        </AuthLayout>
    );
}
