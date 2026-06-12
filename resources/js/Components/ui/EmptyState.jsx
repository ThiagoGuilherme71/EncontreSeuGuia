import { cn } from '@/lib/utils';

export default function EmptyState({ icon: Icon, title, description, action, className }) {
    return (
        <div className={cn(
            'flex flex-col items-center justify-center text-center py-16 px-6',
            className,
        )}>
            {Icon && (
                <div className="mb-4 w-16 h-16 rounded-2xl bg-[#F5EDD9] border-2 border-[#E3CDA8] flex items-center justify-center text-[#A27738]">
                    <Icon size={28} strokeWidth={1.5} />
                </div>
            )}
            <p className="font-display font-bold text-lg text-[#1C1917] mb-1">{title}</p>
            {description && (
                <p className="text-sm text-[#78716C] max-w-xs mb-4">{description}</p>
            )}
            {action}
        </div>
    );
}
