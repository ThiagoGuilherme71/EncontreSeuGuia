import { Link } from '@inertiajs/react';
import { MapPin, Users, Mountain } from 'lucide-react';
import Badge from '@/Components/ui/Badge';
import { cn, getDifficultyColor } from '@/lib/utils';

export default function TrilhaCard({ trilha, className }) {
    const dificuldade = trilha.dificuldade?.descricao;

    return (
        <Link
            href={`/trilhas/${trilha.id}`}
            className={cn(
                'group block bg-white rounded-2xl border-2 border-[#1C1917] overflow-hidden',
                'shadow-[3px_3px_0px_#1C1917] hover:shadow-[5px_5px_0px_#1C1917]',
                'hover:-translate-y-0.5 transition-all duration-200',
                className,
            )}
        >
            {/* Foto */}
            <div className="relative h-44 bg-[#D8EFE3] overflow-hidden border-b-2 border-[#1C1917]">
                {trilha.foto ? (
                    <img
                        src={`/${trilha.foto}`}
                        alt={trilha.nome}
                        loading="lazy"
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center text-[#2D6A4F]">
                        <Mountain size={48} strokeWidth={1.2} />
                    </div>
                )}
                {dificuldade && (
                    <span className={cn(
                        'absolute top-3 right-3 text-xs font-bold px-2.5 py-1 rounded-lg border-2 border-[#1C1917]',
                        'bg-white shadow-[2px_2px_0px_#1C1917]',
                    )}>
                        {dificuldade}
                    </span>
                )}
            </div>

            {/* Conteúdo */}
            <div className="p-4">
                <h3 className="font-display font-bold text-lg text-[#1C1917] group-hover:text-[#2D6A4F] transition-colors line-clamp-1">
                    {trilha.nome}
                </h3>

                <div className="flex items-center gap-1.5 text-sm text-[#78716C] mt-1">
                    <MapPin size={14} className="text-[#E07A45] shrink-0" />
                    <span className="line-clamp-1">{trilha.cidade}</span>
                </div>

                {trilha.descricao && (
                    <p className="text-sm text-[#78716C] mt-2 line-clamp-2">
                        {trilha.descricao}
                    </p>
                )}

                <div className="flex items-center justify-between mt-3 pt-3 border-t border-[#E3CDA8]">
                    <div className="flex items-center gap-1.5 text-xs text-[#78716C]">
                        <Users size={13} />
                        <span>
                            {trilha.guias_count ?? 0} {(trilha.guias_count ?? 0) === 1 ? 'guia disponível' : 'guias disponíveis'}
                        </span>
                    </div>
                    <span className="text-xs font-bold text-[#2D6A4F] group-hover:underline">
                        Ver trilha →
                    </span>
                </div>
            </div>
        </Link>
    );
}
