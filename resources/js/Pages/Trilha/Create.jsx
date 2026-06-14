import { Head, Link, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import TrilhaForm from '@/Components/domain/TrilhaForm';
import { ChevronLeft, Sparkles, Search, MapPin, Plus, UserPlus } from 'lucide-react';
import { cn } from '@/lib/utils';
import Badge from '@/Components/ui/Badge';
import Button from '@/Components/ui/Button';

function TrilhaDisponivel({ trilha, onInscrever, loading }) {
    const dif = trilha.dificuldade?.descricao ?? '—';
    const difColor = {
        'Fácil': 'bg-[#D8EFE3] text-[#2D6A4F] border-[#2D6A4F]',
        'Moderado': 'bg-[#FEF3C7] text-[#92400E] border-[#92400E]',
        'Difícil': 'bg-[#FEE2E2] text-red-700 border-red-700',
    }[dif] ?? 'bg-[#F5EDD9] text-[#78716C] border-[#78716C]';

    return (
        <div className="flex items-center gap-3 p-3.5 rounded-xl border-2 border-[#E3CDA8] bg-white hover:border-[#2D6A4F] hover:shadow-[2px_2px_0px_#2D6A4F] transition-all">
            {/* thumb */}
            <div className="w-14 h-14 shrink-0 rounded-lg overflow-hidden border-2 border-[#E3CDA8] bg-[#F5EDD9] flex items-center justify-center">
                {trilha.foto
                    ? <img src={`/storage/${trilha.foto}`} alt={trilha.nome} className="w-full h-full object-cover" />
                    : <span className="text-2xl">⛰️</span>
                }
            </div>

            <div className="flex-1 min-w-0">
                <p className="font-semibold text-[#1C1917] text-sm truncate">{trilha.nome}</p>
                <p className="flex items-center gap-1 text-xs text-[#78716C] mt-0.5">
                    <MapPin size={11} /> {trilha.cidade}
                </p>
                <span className={cn('inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border mt-1', difColor)}>
                    {dif}
                </span>
            </div>

            <Button
                type="button"
                size="sm"
                onClick={() => onInscrever(trilha.id)}
                loading={loading === trilha.id}
            >
                <UserPlus size={14} /> Entrar
            </Button>
        </div>
    );
}

export default function Create({ dificuldades = [], trilhas_disponiveis = [], errors = {} }) {
    const [tab, setTab] = useState('entrar');
    const [busca, setBusca] = useState('');
    const [inscrevendo, setInscrevendo] = useState(null);

    const { data, setData, post, processing } = useForm({
        nome: '',
        descricao: '',
        estado: '',
        cidade: '',
        id_dificuldade: '',
        foto: null,
        distancia_km: '',
        tempo_estimado_horas: '',
        ponto_encontro_lat: null,
        ponto_encontro_lng: null,
        ponto_encontro_descricao: '',
        o_que_levar: [],
    });

    function submit(e) {
        e.preventDefault();
        post('/trilhas', { forceFormData: true });
    }

    function inscrever(id) {
        setInscrevendo(id);
        router.post(`/trilhas/${id}/inscrever`, {}, {
            onFinish: () => setInscrevendo(null),
        });
    }

    const trilhasFiltradas = trilhas_disponiveis.filter((t) =>
        t.nome.toLowerCase().includes(busca.toLowerCase()) ||
        t.cidade.toLowerCase().includes(busca.toLowerCase())
    );

    return (
        <AppLayout>
            <Head title="Nova trilha" />

            <div className="max-w-xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href="/guia-dash"
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Dashboard
                </Link>

                <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917] mb-1">
                    Nova trilha
                </h1>
                <p className="text-sm text-[#78716C] mb-5">
                    Entre em trilhas já cadastradas ou crie uma nova.
                </p>

                {/* Abas */}
                <div className="flex gap-2 mb-6 border-2 border-[#1C1917] rounded-xl p-1 bg-[#F5EDD9]">
                    <button
                        type="button"
                        onClick={() => setTab('entrar')}
                        className={cn(
                            'flex-1 flex items-center justify-center gap-1.5 py-2 text-sm font-bold rounded-lg transition-all',
                            tab === 'entrar'
                                ? 'bg-[#2D6A4F] text-white shadow-[2px_2px_0px_#1C1917]'
                                : 'text-[#78716C] hover:text-[#1C1917]',
                        )}
                    >
                        <UserPlus size={15} />
                        Entrar em trilha
                        {trilhas_disponiveis.length > 0 && (
                            <span className={cn(
                                'ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full',
                                tab === 'entrar' ? 'bg-white/20 text-white' : 'bg-[#E3CDA8] text-[#78716C]',
                            )}>
                                {trilhas_disponiveis.length}
                            </span>
                        )}
                    </button>
                    <button
                        type="button"
                        onClick={() => setTab('criar')}
                        className={cn(
                            'flex-1 flex items-center justify-center gap-1.5 py-2 text-sm font-bold rounded-lg transition-all',
                            tab === 'criar'
                                ? 'bg-[#2D6A4F] text-white shadow-[2px_2px_0px_#1C1917]'
                                : 'text-[#78716C] hover:text-[#1C1917]',
                        )}
                    >
                        <Plus size={15} /> Criar nova
                    </button>
                </div>

                {tab === 'entrar' && (
                    <div className="flex flex-col gap-3">
                        {trilhas_disponiveis.length === 0 ? (
                            <div className="text-center py-10 text-[#78716C]">
                                <span className="text-4xl block mb-3">🗺️</span>
                                <p className="font-semibold">Você já está em todas as trilhas!</p>
                                <p className="text-sm mt-1">Crie uma nova trilha na aba ao lado.</p>
                            </div>
                        ) : (
                            <>
                                <div className="relative">
                                    <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-[#78716C]" />
                                    <input
                                        value={busca}
                                        onChange={(e) => setBusca(e.target.value)}
                                        placeholder="Buscar por nome ou cidade..."
                                        className="w-full pl-9 pr-4 py-2.5 text-sm border-2 border-[#E3CDA8] rounded-xl focus:outline-none focus:border-[#2D6A4F] bg-white"
                                    />
                                </div>

                                {trilhasFiltradas.length === 0 ? (
                                    <p className="text-center text-sm text-[#78716C] py-6">Nenhuma trilha encontrada para "{busca}".</p>
                                ) : (
                                    <div className="flex flex-col gap-2">
                                        {trilhasFiltradas.map((t) => (
                                            <TrilhaDisponivel
                                                key={t.id}
                                                trilha={t}
                                                onInscrever={inscrever}
                                                loading={inscrevendo}
                                            />
                                        ))}
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                )}

                {tab === 'criar' && (
                    <>
                        <div className="flex items-start gap-2.5 bg-[#D8EFE3] border-2 border-[#2D6A4F] rounded-xl p-3.5 mb-6 text-sm text-[#2D6A4F]">
                            <Sparkles size={16} className="shrink-0 mt-0.5" />
                            <p>Sandbox: sua trilha é <strong>aprovada automaticamente</strong> ao salvar.</p>
                        </div>

                        <TrilhaForm
                            data={data}
                            setData={setData}
                            errors={errors}
                            onSubmit={submit}
                            processing={processing}
                            submitLabel="Criar trilha"
                            dificuldades={dificuldades}
                        />
                    </>
                )}
            </div>
        </AppLayout>
    );
}
