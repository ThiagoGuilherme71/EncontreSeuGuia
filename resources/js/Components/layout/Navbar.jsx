import { Link, router } from '@inertiajs/react';
import { Bell, Menu, X, Search, LogOut, User, Mountain } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import Avatar from '@/Components/ui/Avatar';
import NotificationDrawer from '@/Components/layout/NotificationDrawer';

export default function Navbar({ auth, notifications = [], unreadCount = 0 }) {
    const [drawerOpen, setDrawerOpen] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [search, setSearch] = useState('');

    const currentUser = auth?.user || auth?.guia;
    const isGuia = !!auth?.guia;

    function handleSearch(e) {
        e.preventDefault();
        if (search.trim()) {
            router.get('/buscar-trilha', { nome: search });
        }
    }

    function logout() {
        router.get('/logout');
    }

    return (
        <>
            <header className="fixed top-0 left-0 right-0 z-40 bg-[#FAFAF5]/90 backdrop-blur-md border-b-2 border-[#1C1917]">
                <div className="max-w-7xl mx-auto px-4 h-16 flex items-center gap-4">

                    {/* Logo */}
                    <Link href={isGuia ? '/guia-dash' : '/landing-page'} className="flex items-center gap-2 shrink-0">
                        <div className="w-8 h-8 rounded-lg bg-[#2D6A4F] border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917] flex items-center justify-center">
                            <Mountain size={16} className="text-white" strokeWidth={2.5} />
                        </div>
                        <span className="font-display font-bold text-[#1C1917] hidden sm:block text-lg leading-none">
                            Trilhas
                        </span>
                    </Link>

                    {/* Search (desktop) */}
                    {!isGuia && (
                        <form onSubmit={handleSearch} className="hidden md:flex flex-1 max-w-sm">
                            <div className="relative w-full">
                                <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-[#78716C]" />
                                <input
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Buscar trilhas..."
                                    className="w-full pl-9 pr-4 py-2 text-sm border-2 border-[#1C1917] rounded-xl bg-white focus:outline-none focus:border-[#2D6A4F] shadow-[2px_2px_0px_#1C1917]"
                                />
                            </div>
                        </form>
                    )}

                    {/* Spacer */}
                    <div className="flex-1" />

                    {/* Ações */}
                    <div className="flex items-center gap-2">

                        {/* Notificações */}
                        {currentUser && (
                            <button
                                onClick={() => setDrawerOpen(true)}
                                className="relative p-2 rounded-xl hover:bg-[#E3CDA8] transition-colors border-2 border-transparent hover:border-[#1C1917]"
                            >
                                <Bell size={20} className="text-[#1C1917]" />
                                {unreadCount > 0 && (
                                    <span className={cn(
                                        'absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1',
                                        'bg-[#E07A45] text-white text-[10px] font-bold rounded-full',
                                        'flex items-center justify-center badge-pulse border border-white',
                                    )}>
                                        {unreadCount > 9 ? '9+' : unreadCount}
                                    </span>
                                )}
                            </button>
                        )}

                        {/* Avatar / Login */}
                        {currentUser ? (
                            <div className="relative group hidden sm:block">
                                <button className="flex items-center gap-2 p-1.5 rounded-xl hover:bg-[#E3CDA8] border-2 border-transparent hover:border-[#1C1917] transition-all">
                                    <Avatar name={currentUser.nome} size="sm" />
                                    <span className="text-sm font-semibold text-[#1C1917] max-w-[120px] truncate hidden md:block">
                                        {currentUser.nome?.split(' ')[0]}
                                    </span>
                                </button>
                                {/* Dropdown */}
                                <div className="absolute right-0 top-full mt-2 w-44 bg-white border-2 border-[#1C1917] rounded-xl shadow-[3px_3px_0px_#1C1917] overflow-hidden opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all">
                                    <Link
                                        href={isGuia ? '/conta-guia' : '/conta'}
                                        className="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-[#F5EDD9] transition-colors"
                                    >
                                        <User size={15} /> Meu perfil
                                    </Link>
                                    <button
                                        onClick={logout}
                                        className="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    >
                                        <LogOut size={15} /> Sair
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <Link
                                href="/"
                                className="text-sm font-semibold text-[#2D6A4F] border-2 border-[#2D6A4F] px-3 py-1.5 rounded-xl hover:bg-[#D8EFE3] shadow-[2px_2px_0px_#2D6A4F] transition-all"
                            >
                                Entrar
                            </Link>
                        )}

                        {/* Mobile menu toggle */}
                        <button
                            onClick={() => setMobileOpen(!mobileOpen)}
                            className="sm:hidden p-2 rounded-xl hover:bg-[#E3CDA8] border-2 border-transparent hover:border-[#1C1917]"
                        >
                            {mobileOpen ? <X size={20} /> : <Menu size={20} />}
                        </button>
                    </div>
                </div>

                {/* Mobile menu */}
                {mobileOpen && (
                    <div className="sm:hidden border-t-2 border-[#1C1917] bg-[#FAFAF5] px-4 py-3 flex flex-col gap-2">
                        {!isGuia && (
                            <form onSubmit={handleSearch}>
                                <div className="relative">
                                    <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-[#78716C]" />
                                    <input
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        placeholder="Buscar trilhas..."
                                        className="w-full pl-9 pr-4 py-2 text-sm border-2 border-[#1C1917] rounded-xl bg-white focus:outline-none"
                                    />
                                </div>
                            </form>
                        )}
                        {currentUser && (
                            <>
                                <Link href={isGuia ? '/conta-guia' : '/conta'} className="flex items-center gap-2 py-2 text-sm font-medium">
                                    <User size={16} /> Meu perfil
                                </Link>
                                <button onClick={logout} className="flex items-center gap-2 py-2 text-sm font-medium text-red-600">
                                    <LogOut size={16} /> Sair
                                </button>
                            </>
                        )}
                    </div>
                )}
            </header>

            <NotificationDrawer
                open={drawerOpen}
                onClose={() => setDrawerOpen(false)}
                notifications={notifications}
            />
        </>
    );
}
