import { Dialog, DialogPanel, Transition, TransitionChild } from '@headlessui/react';
import { Fragment } from 'react';
import { X, Bell, CheckCheck, MapPin, Calendar, MessageCircle, Star } from 'lucide-react';
import { router } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { formatDate } from '@/lib/utils';

const tipoIcon = {
    proposta_aceita:       { icon: Calendar, color: 'text-[#2D6A4F] bg-[#D8EFE3]' },
    proposta_rejeitada:    { icon: X,        color: 'text-red-600 bg-red-50' },
    nova_mensagem:         { icon: MessageCircle, color: 'text-[#E07A45] bg-orange-50' },
    proposta_recebida:     { icon: Bell,     color: 'text-[#A27738] bg-[#F5EDD9]' },
    agendamento_cancelado: { icon: X,        color: 'text-gray-500 bg-gray-100' },
    avaliacao_recebida:    { icon: Star,     color: 'text-[#F2C94C] bg-[#FFF8E6]' },
};

function NotifItem({ notif, onClose }) {
    const cfg = tipoIcon[notif.tipo] ?? tipoIcon.proposta_recebida;
    const Icon = cfg.icon;
    const isUnread = !notif.lida_em;

    function handleClick() {
        router.patch(`/notificacoes/${notif.id}/ler`, {}, { preserveState: true });
        const url = notif.data?.url
            ?? (notif.data?.agendamento_id ? `/agendamentos/${notif.data.agendamento_id}` : null);
        if (url) {
            router.visit(url);
            onClose();
        }
    }

    return (
        <button
            onClick={handleClick}
            className={cn(
                'w-full text-left flex items-start gap-3 p-3 rounded-xl transition-colors',
                isUnread ? 'bg-[#F5EDD9] hover:bg-[#E3CDA8]' : 'hover:bg-gray-50',
            )}
        >
            <div className={cn('w-9 h-9 rounded-full flex items-center justify-center shrink-0', cfg.color)}>
                <Icon size={16} />
            </div>
            <div className="flex-1 min-w-0">
                <p className={cn('text-sm', isUnread ? 'font-semibold text-[#1C1917]' : 'text-[#1C1917]')}>
                    {notif.titulo}
                </p>
                <p className="text-xs text-[#78716C] line-clamp-2 mt-0.5">{notif.mensagem}</p>
                <p className="text-xs text-[#78716C] mt-1">{formatDate(notif.created_at)}</p>
            </div>
            {isUnread && (
                <div className="w-2 h-2 rounded-full bg-[#E07A45] shrink-0 mt-1.5" />
            )}
        </button>
    );
}

export default function NotificationDrawer({ open, onClose, notifications = [] }) {
    const unread = notifications.filter((n) => !n.lida_em);

    function markAll() {
        router.patch('/notificacoes/ler-todas', {}, { preserveState: true });
    }

    return (
        <Transition show={open} as={Fragment}>
            <Dialog onClose={onClose} className="relative z-50">
                <TransitionChild
                    as={Fragment}
                    enter="ease-out duration-200" enterFrom="opacity-0" enterTo="opacity-100"
                    leave="ease-in duration-150"  leaveFrom="opacity-100" leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/30" />
                </TransitionChild>

                <div className="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <TransitionChild
                        as={Fragment}
                        enter="transform ease-out duration-250"
                        enterFrom="translate-x-full" enterTo="translate-x-0"
                        leave="transform ease-in duration-200"
                        leaveFrom="translate-x-0" leaveTo="translate-x-full"
                    >
                        <DialogPanel className="w-full max-w-sm bg-[#FAFAF5] border-l-2 border-[#1C1917] flex flex-col">
                            {/* Header */}
                            <div className="flex items-center justify-between p-4 border-b-2 border-[#1C1917]">
                                <div className="flex items-center gap-2">
                                    <Bell size={18} className="text-[#2D6A4F]" />
                                    <h2 className="font-display font-bold text-[#1C1917]">Notificações</h2>
                                    {unread.length > 0 && (
                                        <span className="text-xs bg-[#E07A45] text-white font-bold px-1.5 py-0.5 rounded-full">
                                            {unread.length}
                                        </span>
                                    )}
                                </div>
                                <div className="flex items-center gap-2">
                                    {unread.length > 0 && (
                                        <button
                                            onClick={markAll}
                                            className="text-xs text-[#2D6A4F] hover:underline flex items-center gap-1"
                                        >
                                            <CheckCheck size={14} />
                                            Marcar todas
                                        </button>
                                    )}
                                    <button onClick={onClose} className="p-1 rounded-lg hover:bg-[#E3CDA8] text-[#78716C]">
                                        <X size={18} />
                                    </button>
                                </div>
                            </div>

                            {/* Lista */}
                            <div className="flex-1 overflow-y-auto p-3 flex flex-col gap-1">
                                {notifications.length === 0 ? (
                                    <div className="flex flex-col items-center justify-center py-16 text-[#78716C]">
                                        <Bell size={36} strokeWidth={1.5} className="mb-3 opacity-40" />
                                        <p className="text-sm font-medium">Nenhuma notificação</p>
                                        <p className="text-xs mt-1 opacity-70">Tudo em dia por aqui!</p>
                                    </div>
                                ) : (
                                    notifications.map((n) => (
                                        <NotifItem key={n.id} notif={n} onClose={onClose} />
                                    ))
                                )}
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </Transition>
    );
}
