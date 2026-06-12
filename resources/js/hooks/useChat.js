import { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';

export default function useChat({ agendamentoId, enabled = true, interval = 5000 } = {}) {
    const [messages, setMessages] = useState([]);
    const [loading, setLoading] = useState(true);
    const timerRef = useRef(null);

    const fetchMessages = useCallback(async () => {
        if (!agendamentoId) return;
        try {
            const res = await axios.get(`/chat/${agendamentoId}/mensagens`);
            setMessages(res.data);
        } catch {
            // silently fail
        } finally {
            setLoading(false);
        }
    }, [agendamentoId]);

    useEffect(() => {
        if (!enabled || !agendamentoId) return;
        fetchMessages();

        const handleVisibility = () => {
            if (document.visibilityState === 'visible') {
                fetchMessages();
                timerRef.current = setInterval(fetchMessages, interval);
            } else {
                clearInterval(timerRef.current);
            }
        };

        timerRef.current = setInterval(fetchMessages, interval);
        document.addEventListener('visibilitychange', handleVisibility);
        return () => {
            clearInterval(timerRef.current);
            document.removeEventListener('visibilitychange', handleVisibility);
        };
    }, [enabled, agendamentoId, fetchMessages]);

    async function sendMessage(mensagem) {
        const res = await axios.post(`/chat/${agendamentoId}/mensagem`, { mensagem });
        setMessages((prev) => [...prev, res.data]);
        return res.data;
    }

    return { messages, loading, sendMessage, refetch: fetchMessages };
}
