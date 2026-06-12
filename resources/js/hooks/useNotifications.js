import { useState, useEffect, useRef } from 'react';
import axios from 'axios';

export default function useNotifications({ initialCount = 0, enabled = true, interval = 15000 } = {}) {
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(initialCount);
    const [loaded, setLoaded] = useState(false);
    const timerRef = useRef(null);

    async function fetchNotifications() {
        try {
            const res = await axios.get('/notificacoes');
            setNotifications(res.data);
            setUnreadCount(res.data.filter((n) => !n.lida_em).length);
            setLoaded(true);
        } catch {
            // silently fail — network issues are common on trail locations
        }
    }

    useEffect(() => {
        if (!enabled) return;
        fetchNotifications();
        timerRef.current = setInterval(fetchNotifications, interval);
        return () => clearInterval(timerRef.current);
    }, [enabled]);

    return { notifications, unreadCount, refetch: fetchNotifications, loaded };
}
