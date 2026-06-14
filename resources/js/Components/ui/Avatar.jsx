import { cn } from '@/lib/utils';
import { getInitials } from '@/lib/utils';

const sizes = {
    sm: 'w-8 h-8 text-xs',
    md: 'w-10 h-10 text-sm',
    lg: 'w-14 h-14 text-base',
    xl: 'w-20 h-20 text-xl',
};

export default function Avatar({ src, name, size = 'md', className, border = true }) {
    const initials = getInitials(name);

    return (
        <div className={cn(
            'relative shrink-0 rounded-full overflow-hidden flex items-center justify-center',
            'bg-[#E3CDA8] text-[#A27738] font-bold font-display',
            border && 'border-2 border-[#1C1917]',
            sizes[size],
            className,
        )}>
            {src ? (
                <img
                    src={src}
                    alt={name}
                    className="w-full h-full object-cover"
                    onError={(e) => { e.target.style.display = 'none'; }}
                />
            ) : (
                <span>{initials}</span>
            )}
        </div>
    );
}
