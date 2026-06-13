import { Head, Link, useForm } from '@inertiajs/react';
import { useMemo } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import Avatar from '@/Components/ui/Avatar';
import { ChevronLeft, Minus, Plus, MapPin, Award, Info, AlertTriangle } from 'lucide-react';
import { cn, formatCurrency, getDifficultyColor } from '@/lib/utils';

function detectarConflito(agendamentos, data, horario, duracaoTrilha) {
    if (!data || !horario) return null;
    const doDia = agendamentos.filter((a) => a.data === data);
    if (!doDia.length) return null;

    const [novaH, novaM] = horario.split(':').map(Number);
    const novoInicio = novaH * 60 + novaM;
    const novoDur    = Math.round((duracaoTrilha ?? 2) * 60);
    const novoFim    = novoInicio + novoDur;

    for (const ag of doDia) {
        const [h, m] = ag.horario.split(':').map(Number);
        const inicio = h * 60 + m;
        const dur    = Math.round((ag.duracao_horas ?? 2) * 60);
        const fim    = inicio + dur;

        if (novoInicio < fim && novoFim > inicio) {
            const livreH   = Math.floor(fim / 60);
            const livreM   = fim % 60;
            const livreStr = `${String(livreH).padStart(2, '0')}:${String(livreM).padStart(2, '0')}`;
            return { trilha: ag.trilha_nome, inicioOcupado: ag.horario, livreApartir: livreStr };
        }
    }
    return null;
}

export default function Create({ trilha, guia, preco_por_pessoa, agendamentos_guia = [], errors = {} }) {
    const { data, setData, post, processing } = useForm({
        id_trilha: trilha.id,
        id_guia: guia.id,
        data: '',
        horario: '08:00',
        num_pessoas: 1,
        observacoes: '',
    });

    const conflito = useMemo(
        () => detectarConflito(agendamentos_guia, data.data, data.horario, trilha.tempo_estimado_horas),
        [data.data, data.horario],
    );

    const total = data.num_pessoas * preco_por_pessoa;

    const amanha = new Date();
    amanha.setDate(amanha.getDate() + 1);
    const minData = amanha.toISOString().split('T')[0];

    function submit(e) {
        e.preventDefault();
        post('/agendamentos');
    }

    function stepPessoas(delta) {
        const next = Math.min(20, Math.max(1, data.num_pessoas + delta));
        setData('num_pessoas', next);
    }

    return (
        <AppLayout>
            <Head title={`Agendar ${trilha.nome}`} />

            <div className="max-w-2xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href={`/trilhas/${trilha.id}`}
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Voltar pra trilha
                </Link>

                <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917] mb-1">
                    Agendar trilha
                </h1>
                <p className="text-sm text-[#78716C] mb-6">
                    Preencha os dados e envie a proposta. O guia confirma a disponibilidade.
                </p>

                {/* Resumo trilha + guia */}
                <div className="bg-[#F5EDD9] rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-4 mb-6">
                    <div className="flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <h2 className="font-display font-bold text-[#1C1917]">{trilha.nome}</h2>
                            <p className="text-xs text-[#78716C] flex items-center gap-1 mt-0.5">
                                <MapPin size={12} className="text-[#E07A45]" /> {trilha.cidade}
                                {trilha.dificuldade && (
                                    <span className={cn('ml-2 px-1.5 py-0.5 rounded font-bold', getDifficultyColor(trilha.dificuldade.descricao))}>
                                        {trilha.dificuldade.descricao}
                                    </span>
                                )}
                            </p>
                        </div>
                        <div className="flex items-center gap-2">
                            <Avatar name={guia.nome} size="md" />
                            <div>
                                <p className="text-sm font-bold text-[#1C1917]">{guia.nome}</p>
                                <p className="text-xs text-[#A27738] flex items-center gap-1">
                                    <Award size={11} /> {guia.anos_experiencia} anos de experiência
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {conflito && (
                    <div className="rounded-2xl border-2 border-[#E07A45] bg-[#FFF5EE] p-4 flex items-start gap-3 mb-2">
                        <AlertTriangle size={18} className="text-[#E07A45] shrink-0 mt-0.5" />
                        <div>
                            <p className="text-sm font-bold text-[#E07A45]">Horário indisponível</p>
                            <p className="text-xs text-[#78716C] mt-0.5">
                                Este guia já tem <strong>{conflito.trilha}</strong> agendada às{' '}
                                <strong>{conflito.inicioOcupado}</strong> e estará disponível a partir das{' '}
                                <strong>{conflito.livreApartir}</strong>. Escolha outro horário ou outro guia.
                            </p>
                        </div>
                    </div>
                )}

                <form onSubmit={submit} className="flex flex-col gap-5">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input
                            label="Data da trilha"
                            type="date"
                            min={minData}
                            value={data.data}
                            onChange={(e) => setData('data', e.target.value)}
                            error={errors.data}
                            required
                        />
                        <Input
                            label="Horário de saída"
                            type="time"
                            value={data.horario}
                            onChange={(e) => setData('horario', e.target.value)}
                            error={errors.horario}
                            required
                        />
                    </div>

                    {/* Stepper de pessoas */}
                    <div className="flex flex-col gap-1">
                        <label className="text-sm font-semibold text-[#1C1917]">
                            Número de pessoas <span className="text-red-500">*</span>
                        </label>
                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                onClick={() => stepPessoas(-1)}
                                disabled={data.num_pessoas <= 1}
                                className="w-10 h-10 rounded-xl border-2 border-[#1C1917] bg-white shadow-[2px_2px_0px_#1C1917] flex items-center justify-center disabled:opacity-40 disabled:shadow-none active:translate-y-px"
                            >
                                <Minus size={16} />
                            </button>
                            <span className="font-display font-bold text-2xl text-[#1C1917] w-12 text-center">
                                {data.num_pessoas}
                            </span>
                            <button
                                type="button"
                                onClick={() => stepPessoas(1)}
                                disabled={data.num_pessoas >= 20}
                                className="w-10 h-10 rounded-xl border-2 border-[#1C1917] bg-white shadow-[2px_2px_0px_#1C1917] flex items-center justify-center disabled:opacity-40 disabled:shadow-none active:translate-y-px"
                            >
                                <Plus size={16} />
                            </button>
                        </div>
                        {errors.num_pessoas && <p className="text-xs text-red-600">{errors.num_pessoas}</p>}
                    </div>

                    <Textarea
                        label="Observações"
                        value={data.observacoes}
                        onChange={(e) => setData('observacoes', e.target.value)}
                        error={errors.observacoes}
                        placeholder="Alguma restrição, dúvida ou pedido especial pro guia? (opcional)"
                        rows={3}
                    />

                    {/* Resumo de valor */}
                    <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-4">
                        <div className="flex justify-between text-sm text-[#78716C]">
                            <span>{formatCurrency(preco_por_pessoa)} × {data.num_pessoas} {data.num_pessoas === 1 ? 'pessoa' : 'pessoas'}</span>
                            <span>{formatCurrency(total)}</span>
                        </div>
                        <div className="flex justify-between font-display font-bold text-lg text-[#1C1917] mt-2 pt-2 border-t-2 border-dashed border-[#E3CDA8]">
                            <span>Total</span>
                            <span className="text-[#2D6A4F]">{formatCurrency(total)}</span>
                        </div>
                        <p className="flex items-start gap-1.5 text-xs text-[#78716C] mt-2">
                            <Info size={13} className="shrink-0 mt-0.5" />
                            Você só paga depois que o guia aceitar a proposta.
                        </p>
                    </div>

                    <Button
                        type="submit"
                        loading={processing}
                        disabled={!!conflito}
                        size="lg"
                        className="w-full"
                    >
                        Enviar proposta 🥾
                    </Button>
                </form>
            </div>
        </AppLayout>
    );
}
