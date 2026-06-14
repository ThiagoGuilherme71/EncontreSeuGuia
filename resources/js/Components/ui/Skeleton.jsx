import { cn } from '@/lib/utils';

function Shimmer({ className }) {
    return (
        <div className={cn(
            'animate-pulse bg-gradient-to-r from-[#E3CDA8] via-[#F5EDD9] to-[#E3CDA8]',
            'bg-[length:200%_100%]',
            'rounded-xl',
            className,
        )} />
    );
}

export function SkeletonText({ lines = 3, className }) {
    return (
        <div className={cn('flex flex-col gap-2', className)}>
            {Array.from({ length: lines }).map((_, i) => (
                <Shimmer
                    key={i}
                    className={cn('h-4', i === lines - 1 && 'w-3/4')}
                />
            ))}
        </div>
    );
}

export function SkeletonCard({ className }) {
    return (
        <div className={cn('rounded-2xl border-2 border-[#E3CDA8] p-4 flex flex-col gap-3', className)}>
            <Shimmer className="h-40 w-full rounded-xl" />
            <Shimmer className="h-5 w-2/3" />
            <Shimmer className="h-4 w-full" />
            <Shimmer className="h-4 w-4/5" />
            <div className="flex gap-2 mt-1">
                <Shimmer className="h-6 w-16 rounded-lg" />
                <Shimmer className="h-6 w-20 rounded-lg" />
            </div>
        </div>
    );
}

export function SkeletonListItem({ className }) {
    return (
        <div className={cn('flex items-center gap-3 p-3', className)}>
            <Shimmer className="w-10 h-10 rounded-full shrink-0" />
            <div className="flex-1 flex flex-col gap-2">
                <Shimmer className="h-4 w-1/3" />
                <Shimmer className="h-3 w-2/3" />
            </div>
        </div>
    );
}

export default Shimmer;
