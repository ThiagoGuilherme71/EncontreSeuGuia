import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import GuiaCard from '@/Components/domain/GuiaCard';
import EmptyState from '@/Components/ui/EmptyState';
import Badge from '@/Components/ui/Badge';
import Avatar from '@/Components/ui/Avatar';
import { StarDisplay } from '@/Components/ui/StarRating';
import { Tabs, TabList, Tab, TabPanel } from '@/Components/ui/Tabs';
import useAuth from '@/hooks/useAuth';
import { MapPin, Mountain, ChevronLeft, Users, AlertTriangle, MessagesSquare, Camera, BookOpen } from 'lucide-react';
import { cn, getDifficultyColor, formatDate } from '@/lib/utils';

function AventuraCard({ aventura }) {
    return (
        <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] overflow-hidden">
            {/* Quem foi */}
            <div className="flex items-center gap-3 p-3.5 bg-[#F5EDD9] border-b-2 border-[#1C1917]">
                <div className="flex -space-x-2">
                    <Avatar
                        src={aventura.user?.foto ? `/${aventura.user.foto}` : null}
                        name={aventura.user?.nome}
                        size="md"
                        className="ring-2 ring-[#F5EDD9]"
                    />
                    <Avatar
                        src={aventura.guia?.foto ? `/${aventura.guia.foto}` : null}
                        name={aventura.guia?.nome}
                        size="md"
                        className="ring-2 ring-[#F5EDD9]"
                    />
                </div>
                <div className="min-w-0">
                    <p className="text-sm text-[#1C1917]">
                        <strong>{aventura.user?.nome?.split(' ')[0]}</strong> explorou com o guia{' '}
                        <strong>{aventura.guia?.nome?.split(' ')[0]}</strong>
                    </p>
                    <p className="text-xs text-[#78716C]">{formatDate(aventura.data)}</p>
                </div>
            </div>

            {/* Fotos */}
            <div className={cn(
                'grid gap-1 p-1',
                aventura.fotos.length === 1 ? 'grid-cols-1' : aventura.fotos.length === 2 ? 'grid-cols-2' : 'grid-cols-3',
            )}>
                {aventura.fotos.map((foto) => (
                    <div key={foto.id} className={cn(
                        'relative overflow-hidden rounded-lg',
                        aventura.fotos.length === 1 ? 'aspect-video' : 'aspect-square',
                    )}>
                        <img
                            src={`/${aventura.fotos.length === 1 ? foto.path : (foto.thumb_path ?? foto.path)}`}
                            alt={foto.legenda ?? 'Foto da aventura'}
                            loading="lazy"
                            className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                ))}
            </div>

            {/* Legendas */}
            {aventura.fotos.some((f) => f.legenda) && (
                <div className="px-3.5 pb-3 pt-1">
                    {aventura.fotos.filter((f) => f.legenda).slice(0, 2).map((f) => (
                        <p key={f.id} className="text-xs text-[#44403C]">
                            <span className="font-bold">
                                {(f.postado_por_type === 'user' ? aventura.user?.nome : aventura.guia?.nome)?.split(' ')[0]}
                            </span>{' '}
                            {f.legenda}
                        </p>
                    ))}
                </div>
            )}
        </div>
    );
}

export default function Show({ trilha, guias = [], avaliacoes = [], aventuras = [] }) {
    const { isLoggedIn, isGuia } = useAuth();

    function handleAgendar(guia) {
        // se não-logado, o middleware redireciona pro login e volta depois (intended)
        router.visit(`/agendamentos/criar?trilha=${trilha.id}&guia=${guia.id}`);
    }

    return (
        <AppLayout>
            <Head title={trilha.nome} />

            {/* Hero foto */}
            <div className="relative h-56 md:h-80 bg-[#D8EFE3] border-b-2 border-[#1C1917] overflow-hidden">
                {trilha.foto ? (
                    <img
                        src={`/${trilha.foto}`}
                        alt={trilha.nome}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center text-[#2D6A4F]">
                        <Mountain size={72} strokeWidth={1} />
                    </div>
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent" />

                {/* Voltar */}
                <Link
                    href="/landing-page"
                    className="absolute top-4 left-4 flex items-center gap-1 bg-white text-sm font-semibold px-3 py-1.5 rounded-xl border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917] hover:-translate-y-0.5 transition-transform"
                >
                    <ChevronLeft size={15} /> Voltar
                </Link>
            </div>

            <div className="max-w-5xl mx-auto px-4 py-6 md:py-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    {/* Coluna principal */}
                    <div className="lg:col-span-2">
                        <div className="flex flex-wrap items-center gap-2 mb-2">
                            {trilha.dificuldade && (
                                <span className={cn(
                                    'text-xs font-bold px-2.5 py-1 rounded-lg',
                                    getDifficultyColor(trilha.dificuldade.descricao),
                                )}>
                                    {trilha.dificuldade.descricao}
                                </span>
                            )}
                            <Badge variant="terra" size="sm">
                                <MapPin size={11} /> {trilha.cidade}
                            </Badge>
                        </div>

                        <h1 className="font-display font-extrabold text-2xl md:text-4xl text-[#1C1917] mb-4">
                            {trilha.nome}
                        </h1>

                        <Tabs defaultValue="sobre">
                            <TabList variant="pills">
                                <Tab value="sobre" variant="pills">
                                    <span className="flex items-center gap-1.5">
                                        <BookOpen size={14} /> Sobre
                                    </span>
                                </Tab>
                                <Tab value="aventuras" variant="pills">
                                    <span className="flex items-center gap-1.5">
                                        <Camera size={14} /> Aventuras ({aventuras.length})
                                    </span>
                                </Tab>
                            </TabList>

                            <TabPanel value="sobre" className="mt-4">
                                {trilha.descricao && (
                                    <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5">
                                        <h2 className="font-display font-bold text-base text-[#1C1917] mb-2">
                                            Sobre a trilha
                                        </h2>
                                        <p className="text-sm md:text-base text-[#44403C] leading-relaxed whitespace-pre-line">
                                            {trilha.descricao}
                                        </p>
                                    </div>
                                )}

                                {/* Aviso de conexão (lugar remoto) */}
                                <div className="mt-4 flex items-start gap-2.5 bg-[#FFF8E6] border-2 border-[#F2C94C] rounded-xl p-3.5 text-sm text-[#78716C]">
                                    <AlertTriangle size={16} className="text-[#F2C94C] shrink-0 mt-0.5" />
                                    <p>
                                        Trilhas costumam ficar em áreas remotas com sinal fraco.
                                        Combine os detalhes com seu guia antes de sair!
                                    </p>
                                </div>

                                {/* Avaliações públicas */}
                                {avaliacoes.length > 0 && (
                                    <div className="mt-6">
                                        <div className="flex items-center gap-2 mb-3">
                                            <MessagesSquare size={18} className="text-[#E07A45]" />
                                            <h2 className="font-display font-bold text-lg text-[#1C1917]">
                                                O que dizem os trilheiros
                                            </h2>
                                        </div>
                                        <div className="flex flex-col gap-3">
                                            {avaliacoes.map((av) => (
                                                <div
                                                    key={av.id}
                                                    className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-4"
                                                >
                                                    <div className="flex items-center gap-3">
                                                        <Avatar src={av.user?.foto ? `/${av.user.foto}` : null} name={av.user?.nome} size="md" />
                                                        <div className="flex-1 min-w-0">
                                                            <p className="text-sm font-bold text-[#1C1917] line-clamp-1">
                                                                {av.user?.nome}
                                                            </p>
                                                            <div className="flex items-center gap-2">
                                                                <StarDisplay value={av.nota} size={13} />
                                                                <span className="text-xs text-[#78716C]">
                                                                    guiado por {av.guia?.nome} · {formatDate(av.created_at)}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {av.comentario && (
                                                        <p className="text-sm text-[#44403C] mt-2.5 whitespace-pre-line">
                                                            "{av.comentario}"
                                                        </p>
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </TabPanel>

                            <TabPanel value="aventuras" className="mt-4">
                                {aventuras.length > 0 ? (
                                    <div className="flex flex-col gap-4">
                                        {aventuras.map((av) => (
                                            <AventuraCard key={av.id} aventura={av} />
                                        ))}
                                    </div>
                                ) : (
                                    <EmptyState
                                        icon={Camera}
                                        title="Nenhuma aventura registrada ainda"
                                        description="Quando alguém concluir essa trilha e postar fotos, elas aparecem aqui. Seja o primeiro! 📸"
                                    />
                                )}
                            </TabPanel>
                        </Tabs>
                    </div>

                    {/* Guias disponíveis */}
                    <div className="lg:col-span-1">
                        <div className="flex items-center gap-2 mb-3">
                            <Users size={18} className="text-[#2D6A4F]" />
                            <h2 className="font-display font-bold text-lg text-[#1C1917]">
                                Guias disponíveis
                            </h2>
                        </div>

                        {guias.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {guias.map((guia) => (
                                    <GuiaCard
                                        key={guia.id}
                                        guia={guia}
                                        onAgendar={isGuia ? null : handleAgendar}
                                    />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={Users}
                                title="Nenhum guia ainda"
                                description="Essa trilha ainda não tem guias cadastrados."
                                className="py-10"
                            />
                        )}

                        {!isLoggedIn && guias.length > 0 && (
                            <p className="text-xs text-[#78716C] text-center mt-3">
                                Para agendar, você precisa{' '}
                                <Link href="/" className="text-[#2D6A4F] font-bold hover:underline">
                                    entrar na sua conta
                                </Link>.
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
