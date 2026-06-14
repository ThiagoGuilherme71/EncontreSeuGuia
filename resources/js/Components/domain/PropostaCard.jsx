import { Link } from '@inertiajs/react';
import { MapPin, CalendarDays, Clock, Users, Mountain } from 'lucide-react';
import { cn, formatDate, formatTime, formatCurrency, getStatusLabel, getStatusColor } from '@/lib/utils';

export default function PropostaCard({ agendamento, papel = 'user', className }) {
    const outraParte = papel === 'user' ? agendamento.guia : agendamento.user;

    return (
        <Link
            href={`/agendamentos/${agendamento.id}`}
            className={cn(
                'group flex gap-3 bg-white rounded-2xl border-2 border-[#1C1917] p-3',
                'shadow-[3px_3px_0px_#1C1917] hover:shadow-[4px_4px_0px_#1C1917]',
                'hover:-translate-y-0.5 transition-all duration-200',
                className,
            )}
        >
            {/* Thumb */}
            <div className="w-20 h-20 rounded-xl bg-[#D8EFE3] border-2 border-[#1C1917] overflow-hidden shrink-0">
                {agendamento.trilha?.foto ? (
                    <img
                        src={`/${agendamento.trilha.foto}`}
                        alt={agendamento.trilha?.nome}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center text-[#2D6A4F]">
                        <Mountain size={28} strokeWidth={1.5} />
                    </div>
                )}
            </div>

            {/* Info */}
            <div className="flex-1 min-w-0">
                <div className="flex items-start justify-between gap-2">
                    <h4 className="font-display font-bold text-sm text-[#1C1917] line-clamp-1 group-hover:text-[#2D6A4F] transition-colors">
                        {agendamento.trilha?.nome}
                    </h4>
                    <span className={cn(
                        'text-[10px] font-bold px-2 py-0.5 rounded-md shrink-0',
                        getStatusColor(agendamento.status),
                    )}>
                        {getStatusLabel(agendamento.status)}
                    </span>
                </div>

                <p className="text-xs text-[#78716C] mt-0.5">
                    {papel === 'user' ? 'Guia' : 'Trilheiro'}: <strong>{outraParte?.nome}</strong>
                </p>

                <div className="flex flex-wrap gap-x-3 gap-y-0.5 mt-1.5 text-xs text-[#78716C]">
                    <span className="flex items-center gap-1">
                        <CalendarDays size={12} /> {formatDate(agendamento.data)}
                    </span>
                    <span className="flex items-center gap-1">
                        <Clock size={12} /> {formatTime(agendamento.horario)}
                    </span>
                    <span className="flex items-center gap-1">
                        <Users size={12} /> {agendamento.num_pessoas}
                    </span>
                </div>

                {agendamento.total_valor && (
                    <p className="text-xs font-bold text-[#2D6A4F] mt-1">
                        {formatCurrency(agendamento.total_valor)}
                        {agendamento.pago_em && <span className="text-[#78716C] font-normal"> · pago</span>}
                    </p>
                )}
            </div>
        </Link>
    );
}
