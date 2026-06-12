import { cn } from '@/lib/utils';
import { Star } from 'lucide-react';
import { useState } from 'react';

export function StarDisplay({ value = 0, size = 16, className }) {
    return (
        <div className={cn('flex items-center gap-0.5', className)}>
            {[1, 2, 3, 4, 5].map((star) => (
                <Star
                    key={star}
                    size={size}
                    className={star <= value ? 'fill-[#F2C94C] text-[#F2C94C]' : 'text-[#E3CDA8]'}
                />
            ))}
        </div>
    );
}

export function StarInput({ value = 0, onChange, size = 24, className }) {
    const [hovered, setHovered] = useState(0);

    return (
        <div className={cn('flex items-center gap-1', className)}>
            {[1, 2, 3, 4, 5].map((star) => (
                <button
                    key={star}
                    type="button"
                    onClick={() => onChange?.(star)}
                    onMouseEnter={() => setHovered(star)}
                    onMouseLeave={() => setHovered(0)}
                    className="transition-transform hover:scale-110 active:scale-95"
                >
                    <Star
                        size={size}
                        className={star <= (hovered || value)
                            ? 'fill-[#F2C94C] text-[#F2C94C]'
                            : 'text-[#E3CDA8]'
                        }
                    />
                </button>
            ))}
        </div>
    );
}
