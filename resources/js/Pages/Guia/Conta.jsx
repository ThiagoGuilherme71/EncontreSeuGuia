import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Avatar from '@/Components/ui/Avatar';
import Badge from '@/Components/ui/Badge';
import { StarDisplay } from '@/Components/ui/StarRating';
import { ChevronLeft, Award, MapPin, AtSign, Link2, Languages, Phone, Mail } from 'lucide-react';

function Linha({ icon: Icon, label, children }) {
    if (!children) return null;
    return (
        <div className="flex items-center gap-3 py-2.5">
            <div className="w-9 h-9 rounded-xl bg-[#F5EDD9] flex items-center justify-center text-[#A27738] shrink-0">
                <Icon size={16} />
            </div>
            <div className="min-w-0">
                <p className="text-xs text-[#78716C]">{label}</p>
                <p className="text-sm font-semibold text-[#1C1917] break-words">{children}</p>
            </div>
        </div>
    );
}

export default function Conta({ perfil, idiomas = [], media_avaliacoes, total_avaliacoes = 0 }) {
    return (
        <AppLayout>
            <Head title="Minha conta" />

            <div className="max-w-xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href="/guia-dash"
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Dashboard
                </Link>

                {/* Header do perfil */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5 mb-4">
                    <div className="flex items-center gap-4">
                        <Avatar name={perfil.nome} size="xl" />
                        <div className="min-w-0">
                            <h1 className="font-display font-extrabold text-xl text-[#1C1917]">
                                {perfil.nome}
                            </h1>
                            <Badge variant="terra" size="sm" className="mt-1">
                                <Award size={11} /> Guia · {perfil.anos_experiencia ?? 0} anos de experiência
                            </Badge>
                            {total_avaliacoes > 0 ? (
                                <div className="flex items-center gap-1.5 mt-1.5">
                                    <StarDisplay value={Math.round(media_avaliacoes)} size={14} />
                                    <span className="text-xs text-[#78716C]">
                                        {media_avaliacoes} ({total_avaliacoes} {total_avaliacoes === 1 ? 'avaliação' : 'avaliações'})
                                    </span>
                                </div>
                            ) : (
                                <p className="text-xs text-[#78716C] mt-1.5">Sem avaliações ainda</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Dados */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5">
                    <h2 className="font-display font-bold text-[#1C1917] mb-2">Informações</h2>
                    <div className="divide-y divide-[#F5EDD9]">
                        <Linha icon={Mail} label="E-mail">{perfil.email}</Linha>
                        <Linha icon={Phone} label="Telefone">{perfil.telefone}</Linha>
                        <Linha icon={MapPin} label="Endereço">{perfil.endereco}</Linha>
                        <Linha icon={Languages} label="Idiomas">
                            {idiomas.length > 0 ? idiomas.join(', ') : null}
                        </Linha>
                        <Linha icon={AtSign} label="Instagram">{perfil.link_instagram}</Linha>
                        <Linha icon={Link2} label="Facebook">{perfil.link_facebook}</Linha>
                    </div>
                </div>

                <p className="text-xs text-[#78716C] text-center mt-4">
                    Edição de perfil chega em breve. 🛠️
                </p>
            </div>
        </AppLayout>
    );
}
