import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import GuiaCard from '@/Components/domain/GuiaCard';
import EmptyState from '@/Components/ui/EmptyState';
import Badge from '@/Components/ui/Badge';
import Avatar from '@/Components/ui/Avatar';
import { StarDisplay } from '@/Components/ui/StarRating';
import useAuth from '@/hooks/useAuth';
import { MapPin, Mountain, ChevronLeft, Users, AlertTriangle, MessagesSquare } from 'lucide-react';
import { cn, getDifficultyColor, formatDate } from '@/lib/utils';

export default function Show({ trilha, guias = [], avaliacoes = [] }) {
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

                        <h1 className="font-display font-extrabold text-2xl md:text-4xl text-[#1C1917]">
                            {trilha.nome}
                        </h1>

                        {trilha.descricao && (
                            <div className="mt-4 bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5">
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
                                                <Avatar name={av.user?.nome} size="md" />
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
