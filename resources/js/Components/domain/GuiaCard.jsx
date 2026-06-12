import { Award, Languages } from 'lucide-react';
import Avatar from '@/Components/ui/Avatar';
import Button from '@/Components/ui/Button';
import { StarDisplay } from '@/Components/ui/StarRating';
import { cn } from '@/lib/utils';

export default function GuiaCard({ guia, onAgendar, className }) {
    const media = guia.media_avaliacoes ? Math.round(guia.media_avaliacoes) : 0;

    return (
        <div className={cn(
            'bg-white rounded-2xl border-2 border-[#1C1917] p-4',
            'shadow-[3px_3px_0px_#1C1917] transition-all duration-200',
            className,
        )}>
            <div className="flex items-start gap-3">
                <Avatar name={guia.nome} size="lg" />

                <div className="flex-1 min-w-0">
                    <h4 className="font-display font-bold text-[#1C1917] line-clamp-1">{guia.nome}</h4>

                    {guia.total_avaliacoes > 0 ? (
                        <div className="flex items-center gap-1.5 mt-0.5">
                            <StarDisplay value={media} size={13} />
                            <span className="text-xs text-[#78716C]">({guia.total_avaliacoes})</span>
                        </div>
                    ) : (
                        <span className="text-xs text-[#78716C]">Sem avaliações ainda</span>
                    )}

                    <div className="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2">
                        {guia.anos_experiencia != null && (
                            <span className="flex items-center gap-1 text-xs text-[#A27738] font-medium">
                                <Award size={13} />
                                {guia.anos_experiencia} {guia.anos_experiencia === 1 ? 'ano' : 'anos'} de experiência
                            </span>
                        )}
                        {guia.idiomas?.length > 0 && (
                            <span className="flex items-center gap-1 text-xs text-[#78716C]">
                                <Languages size={13} />
                                {guia.idiomas.map((i) => i.nome_idioma).join(', ')}
                            </span>
                        )}
                    </div>
                </div>
            </div>

            {onAgendar && (
                <Button
                    onClick={() => onAgendar(guia)}
                    className="w-full mt-4"
                    size="sm"
                >
                    Agendar com {guia.nome?.split(' ')[0]}
                </Button>
            )}
        </div>
    );
}
