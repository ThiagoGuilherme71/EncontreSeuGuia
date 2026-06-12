import { Head, router } from '@inertiajs/react';
import { useState, useEffect, useRef } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import TrilhaCard from '@/Components/domain/TrilhaCard';
import EmptyState from '@/Components/ui/EmptyState';
import Button from '@/Components/ui/Button';
import { Search, MapPin, Compass, X, ChevronDown } from 'lucide-react';
import { cn } from '@/lib/utils';

function Hero({ busca, onSearch }) {
    const [value, setValue] = useState(busca ?? '');

    function submit(e) {
        e.preventDefault();
        onSearch(value);
    }

    return (
        <section className="relative bg-[#2D6A4F] border-b-2 border-[#1C1917] overflow-hidden">
            {/* Doodles de fundo */}
            <div className="absolute inset-0 opacity-[0.07] pointer-events-none">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="topo" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                            <path d="M0 60 L20 30 L40 50 L60 20 L80 45" stroke="white" strokeWidth="2" fill="none"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#topo)"/>
                </svg>
            </div>
            <div className="absolute top-8 right-8 w-16 h-16 border-4 border-white/15 rounded-2xl rotate-12 hidden md:block" />
            <div className="absolute bottom-6 left-10 w-10 h-10 border-4 border-[#F2C94C]/40 rounded-full hidden md:block" />

            <div className="relative max-w-7xl mx-auto px-4 py-12 md:py-20 text-center">
                <h1 className="font-display font-extrabold text-3xl md:text-5xl text-white leading-tight">
                    Encontre sua próxima<br />
                    <span className="text-[#F2C94C]">aventura</span> 🥾
                </h1>
                <p className="text-white/80 mt-3 md:mt-4 max-w-md mx-auto text-sm md:text-base">
                    Conectamos você aos melhores guias locais para explorar trilhas com segurança.
                </p>

                {/* Search */}
                <form onSubmit={submit} className="mt-6 md:mt-8 max-w-lg mx-auto">
                    <div className="relative">
                        <Search size={18} className="absolute left-4 top-1/2 -translate-y-1/2 text-[#78716C]" />
                        <input
                            value={value}
                            onChange={(e) => setValue(e.target.value)}
                            placeholder="Buscar trilha por nome..."
                            className="w-full pl-11 pr-24 py-3.5 rounded-2xl border-2 border-[#1C1917] shadow-[4px_4px_0px_#1C1917] text-sm bg-white focus:outline-none"
                        />
                        <button
                            type="submit"
                            className="absolute right-2 top-1/2 -translate-y-1/2 bg-[#E07A45] text-white text-sm font-bold px-4 py-2 rounded-xl border-2 border-[#1C1917] hover:bg-[#c96835] transition-colors"
                        >
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </section>
    );
}

function CityFilter({ cidades, atual, onChange }) {
    return (
        <div className="flex items-center gap-2 overflow-x-auto pb-2 -mb-2 scrollbar-hide">
            <button
                onClick={() => onChange(null)}
                className={cn(
                    'shrink-0 flex items-center gap-1.5 text-sm font-semibold px-3.5 py-2 rounded-xl border-2 transition-all',
                    !atual
                        ? 'bg-[#1C1917] text-white border-[#1C1917] shadow-[2px_2px_0px_#A27738]'
                        : 'bg-white text-[#1C1917] border-[#E3CDA8] hover:border-[#1C1917]',
                )}
            >
                <Compass size={14} />
                Todas
            </button>
            {cidades.map((cidade) => (
                <button
                    key={cidade}
                    onClick={() => onChange(cidade === atual ? null : cidade)}
                    className={cn(
                        'shrink-0 flex items-center gap-1.5 text-sm font-semibold px-3.5 py-2 rounded-xl border-2 transition-all',
                        cidade === atual
                            ? 'bg-[#2D6A4F] text-white border-[#1C1917] shadow-[2px_2px_0px_#1C1917]'
                            : 'bg-white text-[#1C1917] border-[#E3CDA8] hover:border-[#1C1917]',
                    )}
                >
                    <MapPin size={14} />
                    {cidade}
                </button>
            ))}
        </div>
    );
}

export default function Index({ trilhas = [], paginacao = {}, cidades = [], filtros = {} }) {
    const [lista, setLista] = useState(trilhas);
    const [carregando, setCarregando] = useState(false);

    // página 1 substitui a lista; páginas seguintes acumulam
    useEffect(() => {
        if (paginacao.pagina_atual === 1) {
            setLista(trilhas);
        } else {
            setLista((atual) => {
                const ids = new Set(atual.map((t) => t.id));
                return [...atual, ...trilhas.filter((t) => !ids.has(t.id))];
            });
        }
    }, [trilhas, paginacao.pagina_atual]);

    function applyFilters(next) {
        router.get('/landing-page', {
            cidade: next.cidade ?? undefined,
            busca: next.busca || undefined,
        }, { preserveState: true, preserveScroll: true });
    }

    function carregarMais() {
        setCarregando(true);
        router.get('/landing-page', {
            cidade: filtros.cidade ?? undefined,
            busca: filtros.busca || undefined,
            page: (paginacao.pagina_atual ?? 1) + 1,
        }, {
            preserveState: true,
            preserveScroll: true,
            only: ['trilhas', 'paginacao'],
            onFinish: () => setCarregando(false),
        });
    }

    const hasFiltro = filtros.cidade || filtros.busca;
    const temMais = (paginacao.pagina_atual ?? 1) < (paginacao.ultima_pagina ?? 1);

    return (
        <AppLayout>
            <Head title="Trilhas" />

            <Hero
                busca={filtros.busca}
                onSearch={(busca) => applyFilters({ ...filtros, busca })}
            />

            <div className="max-w-7xl mx-auto px-4 py-6 md:py-10">

                {/* Filtro por cidade */}
                <div className="mb-6">
                    <h2 className="font-display font-bold text-lg md:text-xl text-[#1C1917] mb-3">
                        Explorar por cidade
                    </h2>
                    <CityFilter
                        cidades={cidades}
                        atual={filtros.cidade}
                        onChange={(cidade) => applyFilters({ ...filtros, cidade })}
                    />
                </div>

                {/* Resultado ativo */}
                {hasFiltro && (
                    <div className="flex items-center gap-2 mb-4 text-sm text-[#78716C]">
                        <span>
                            {paginacao.total ?? lista.length} {(paginacao.total ?? lista.length) === 1 ? 'trilha encontrada' : 'trilhas encontradas'}
                            {filtros.busca && <> para <strong className="text-[#1C1917]">"{filtros.busca}"</strong></>}
                            {filtros.cidade && <> em <strong className="text-[#1C1917]">{filtros.cidade}</strong></>}
                        </span>
                        <button
                            onClick={() => applyFilters({})}
                            className="flex items-center gap-1 text-[#E07A45] font-semibold hover:underline"
                        >
                            <X size={13} /> Limpar
                        </button>
                    </div>
                )}

                {/* Grid de trilhas */}
                {lista.length > 0 ? (
                    <>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                            {lista.map((trilha) => (
                                <TrilhaCard key={trilha.id} trilha={trilha} />
                            ))}
                        </div>

                        {temMais && (
                            <div className="flex justify-center mt-8">
                                <Button
                                    variant="outline"
                                    onClick={carregarMais}
                                    loading={carregando}
                                >
                                    <ChevronDown size={16} /> Carregar mais trilhas
                                </Button>
                            </div>
                        )}
                    </>
                ) : (
                    <EmptyState
                        icon={Compass}
                        title="Nenhuma trilha encontrada"
                        description={hasFiltro
                            ? 'Tente ajustar os filtros ou buscar por outro nome.'
                            : 'Ainda não há trilhas cadastradas. Volte em breve!'}
                    />
                )}
            </div>
        </AppLayout>
    );
}
