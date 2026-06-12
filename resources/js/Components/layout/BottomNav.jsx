import { Link, usePage } from '@inertiajs/react';
import { Home, Search, CalendarDays, User, LayoutDashboard } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function BottomNav() {
    const { auth, url } = usePage().props;
    const isGuia = !!auth?.guia;
    const current = typeof window !== 'undefined' ? window.location.pathname : '';

    const userNav = [
        { href: '/landing-page', icon: Home,        label: 'Início' },
        { href: '/buscar-trilha', icon: Search,      label: 'Buscar' },
        { href: '/conta',         icon: CalendarDays, label: 'Reservas' },
        { href: '/conta',         icon: User,         label: 'Perfil' },
    ];

    const guiaNav = [
        { href: '/guia-dash',     icon: LayoutDashboard, label: 'Dashboard' },
        { href: '/buscar-trilha', icon: Search,          label: 'Trilhas' },
        { href: '/conta-guia',    icon: User,            label: 'Perfil' },
    ];

    const items = isGuia ? guiaNav : userNav;

    return (
        <nav className="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-[#FAFAF5] border-t-2 border-[#1C1917] safe-area-pb">
            <div className="flex">
                {items.map(({ href, icon: Icon, label }) => {
                    const active = current === href || current.startsWith(href + '/');
                    return (
                        <Link
                            key={href}
                            href={href}
                            className={cn(
                                'flex-1 flex flex-col items-center gap-0.5 py-2 px-1 transition-colors',
                                active ? 'text-[#2D6A4F]' : 'text-[#78716C] hover:text-[#1C1917]',
                            )}
                        >
                            <div className={cn(
                                'p-1.5 rounded-xl transition-all',
                                active && 'bg-[#D8EFE3]',
                            )}>
                                <Icon size={20} strokeWidth={active ? 2.5 : 2} />
                            </div>
                            <span className={cn('text-[10px]', active ? 'font-bold' : 'font-medium')}>
                                {label}
                            </span>
                        </Link>
                    );
                })}
            </div>
        </nav>
    );
}
