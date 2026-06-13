import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export function formatDate(date, options = {}) {
    if (!date) return '';
    // Extrai só "YYYY-MM-DD" e parseia como horário local para evitar
    // que a conversão UTC→local mude o dia (ex: T00:00:00Z vira dia anterior no Brasil)
    const str = typeof date === 'string' ? date : date.toISOString();
    const d = new Date(str.substring(0, 10) + 'T00:00:00');
    if (isNaN(d.getTime())) return '';
    return d.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        ...options,
    });
}

export function formatDateLong(date) {
    return formatDate(date, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

export function formatCurrency(value) {
    if (value == null) return 'R$ —';
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

export function formatTime(time) {
    if (!time) return '';
    return time.slice(0, 5);
}

export function getDifficultyColor(descricao) {
    const map = {
        'Fácil':       'bg-[#D8EFE3] text-[#2D6A4F] border-[#2D6A4F]',
        'Moderado':    'bg-[#FEF3C7] text-[#92400E] border-[#92400E]',
        'Difícil':     'bg-[#FEE2E2] text-red-700   border-red-700',
        'Muito Difícil': 'bg-[#FCA5A5] text-red-900 border-red-900',
    };
    return map[descricao] ?? 'bg-[#F5EDD9] text-[#78716C] border-[#78716C]';
}

export function getStatusLabel(status) {
    const map = {
        pending: 'Pendente',
        accepted: 'Aceito',
        rejected: 'Rejeitado',
        cancelled: 'Cancelado',
        completed: 'Concluído',
    };
    return map[status] ?? status;
}

export function getStatusColor(status) {
    const map = {
        pending: 'bg-yellow-100 text-yellow-800',
        accepted: 'bg-verde-pale text-verde',
        rejected: 'bg-red-100 text-red-700',
        cancelled: 'bg-gray-100 text-gray-600',
        completed: 'bg-blue-100 text-blue-700',
    };
    return map[status] ?? 'bg-gray-100 text-gray-600';
}

export function getInitials(name) {
    if (!name) return '?';
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((n) => n[0].toUpperCase())
        .join('');
}
