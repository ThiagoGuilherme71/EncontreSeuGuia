import { Link, router } from '@inertiajs/react';
import { Bell, Menu, X, LogOut, User, LayoutDashboard, Map } from 'lucide-react';
import { useState, useEffect, useRef } from 'react';
import { cn } from '@/lib/utils';
import Avatar from '@/Components/ui/Avatar';
import NotificationDrawer from '@/Components/layout/NotificationDrawer';

export default function Navbar({ auth, notifications = [], unreadCount = 0 }) {
    const [drawerOpen, setDrawerOpen] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);
    const profileRef = useRef(null);

    useEffect(() => {
        function handleClickOutside(e) {
            if (profileRef.current && !profileRef.current.contains(e.target)) {
                setProfileOpen(false);
            }
        }
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const currentUser = auth?.user || auth?.guia;
    const isGuia = !!auth?.guia;

    function logout() {
        router.get('/logout');
    }

    return (
        <>
            <header className="fixed top-0 left-0 right-0 z-40 bg-[#FAFAF5]/90 backdrop-blur-md border-b-2 border-[#1C1917]">
                <div className="max-w-7xl mx-auto px-4 h-16 flex items-center gap-4">

                    {/* Logo */}
                    <Link href={isGuia ? '/guia-dash' : '/'} className="flex items-center gap-2 shrink-0">
                        <div className="w-10 h-10 rounded-xl bg-[#2D6A4F] border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917] flex items-center justify-center p-1.5 shrink-0">
                            <img
                                src="/images/logo-montanha.svg"
                                alt="Trilhas"
                                className="w-full h-full"
                            />
                        </div>
                        <span className="font-display font-bold text-[#1C1917] hidden sm:block text-lg leading-none">
                            Encontre seu Guia
                        </span>
                    </Link>

                    {/* Nav links (desktop) */}
                    {currentUser && (
                        <nav className="hidden sm:flex items-center gap-1 ml-2">
                            {isGuia ? (
                                <Link
                                    href="/guia-dash"
                                    className="flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-xl text-[#1C1917] hover:bg-[#E3CDA8] transition-colors"
                                >
                                    <LayoutDashboard size={15} /> Meu dashboard
                                </Link>
                            ) : (
                                <Link
                                    href="/conta"
                                    className="flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-xl text-[#1C1917] hover:bg-[#E3CDA8] transition-colors"
                                >
                                    <Map size={15} /> Minhas trilhas
                                </Link>
                            )}
                        </nav>
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
                            <div ref={profileRef} className="relative hidden sm:block">
                                <button
                                    onClick={() => setProfileOpen((o) => !o)}
                                    className="flex items-center gap-2 p-1.5 rounded-xl hover:bg-[#E3CDA8] border-2 border-transparent hover:border-[#1C1917] transition-all"
                                >
                                    <Avatar name={currentUser.nome} size="sm" />
                                    <span className="text-sm font-semibold text-[#1C1917] max-w-[120px] truncate hidden md:block">
                                        {currentUser.nome?.split(' ')[0]}
                                    </span>
                                </button>
                                {/* Dropdown */}
                                {profileOpen && (
                                    <div className="absolute right-0 top-full mt-2 w-44 bg-white border-2 border-[#1C1917] rounded-xl shadow-[3px_3px_0px_#1C1917] overflow-hidden">
                                        <Link
                                            href={isGuia ? '/conta-guia' : '/conta'}
                                            onClick={() => setProfileOpen(false)}
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
                                )}
                            </div>
                        ) : (
                            <Link
                                href="/login"
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
                    <div className="sm:hidden border-t-2 border-[#1C1917] bg-[#FAFAF5] px-4 py-3 flex flex-col gap-1">
                        {currentUser && (
                            <>
                                {isGuia ? (
                                    <Link
                                        href="/guia-dash"
                                        onClick={() => setMobileOpen(false)}
                                        className="flex items-center gap-2 py-2.5 px-2 rounded-xl text-sm font-semibold hover:bg-[#E3CDA8] transition-colors"
                                    >
                                        <LayoutDashboard size={16} /> Meu dashboard
                                    </Link>
                                ) : (
                                    <Link
                                        href="/conta"
                                        onClick={() => setMobileOpen(false)}
                                        className="flex items-center gap-2 py-2.5 px-2 rounded-xl text-sm font-semibold hover:bg-[#E3CDA8] transition-colors"
                                    >
                                        <Map size={16} /> Minhas trilhas
                                    </Link>
                                )}
                                <Link
                                    href={isGuia ? '/conta-guia' : '/conta'}
                                    onClick={() => setMobileOpen(false)}
                                    className="flex items-center gap-2 py-2.5 px-2 rounded-xl text-sm font-medium hover:bg-[#E3CDA8] transition-colors"
                                >
                                    <User size={16} /> Meu perfil
                                </Link>
                                <button
                                    onClick={logout}
                                    className="flex items-center gap-2 py-2.5 px-2 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors"
                                >
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
