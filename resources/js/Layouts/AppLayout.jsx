import { usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import Navbar from '@/Components/layout/Navbar';
import BottomNav from '@/Components/layout/BottomNav';
import { cn } from '@/lib/utils';
import useNotifications from '@/hooks/useNotifications';

export default function AppLayout({ children, className, hideBottomNav = false }) {
    const { auth, notifications: notifShared } = usePage().props;
    const currentUser = auth?.user || auth?.guia;

    const { notifications, unreadCount } = useNotifications({
        initialCount: notifShared?.unread_count ?? 0,
        enabled: !!currentUser,
    });

    return (
        <div className="min-h-screen bg-[#FAFAF5] flex flex-col">
            <Navbar
                auth={auth}
                notifications={notifications}
                unreadCount={unreadCount}
            />

            <main className={cn(
                'flex-1 pt-16',
                currentUser && !hideBottomNav && 'pb-16 sm:pb-0',
                className,
            )}>
                {children}
            </main>

            {currentUser && !hideBottomNav && <BottomNav />}

            {/* Footer (só desktop) */}
            {!hideBottomNav && (
                <footer className="hidden sm:block border-t-2 border-[#1C1917] bg-[#1C1917] text-[#E3CDA8]">
                    <div className="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <p className="font-display font-bold text-lg text-white">Encontre seu Guia</p>
                            <p className="text-sm opacity-70 mt-1">Conectando trilheiros e guias</p>
                        </div>
                        <p className="text-sm opacity-50">© 2026 Encontre seu Guia. Todos os direitos reservados.</p>
                    </div>
                </footer>
            )}
        </div>
    );
}
