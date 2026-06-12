import { cn } from '@/lib/utils';

const variants = {
    verde:    'bg-[#D8EFE3] text-[#2D6A4F] border border-[#2D6A4F]',
    terra:    'bg-[#F5EDD9] text-[#A27738] border border-[#A27738]',
    laranja:  'bg-orange-50 text-[#E07A45] border border-[#E07A45]',
    amarelo:  'bg-yellow-50 text-yellow-700 border border-yellow-400',
    cinza:    'bg-gray-100 text-gray-600 border border-gray-300',
    dark:     'bg-[#1C1917] text-white border border-[#1C1917]',
    red:      'bg-red-50 text-red-700 border border-red-300',
    blue:     'bg-blue-50 text-blue-700 border border-blue-300',
};

const sizes = {
    sm: 'text-xs px-2 py-0.5 rounded-md',
    md: 'text-xs px-2.5 py-1 rounded-lg',
};

export default function Badge({ children, variant = 'verde', size = 'md', className, pulse }) {
    return (
        <span className={cn(
            'inline-flex items-center gap-1 font-semibold',
            variants[variant],
            sizes[size],
            pulse && 'badge-pulse',
            className,
        )}>
            {children}
        </span>
    );
}
