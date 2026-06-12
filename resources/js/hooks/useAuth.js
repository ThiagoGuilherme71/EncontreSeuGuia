import { usePage } from '@inertiajs/react';

export default function useAuth() {
    const { auth } = usePage().props;
    const user = auth?.user ?? null;
    const guia = auth?.guia ?? null;
    const currentUser = user || guia;
    const isGuia = !!guia;
    const isUser = !!user;
    const isLoggedIn = !!(user || guia);

    return { user, guia, currentUser, isGuia, isUser, isLoggedIn };
}
