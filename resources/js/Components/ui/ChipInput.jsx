import { useState, useRef } from 'react';
import { X } from 'lucide-react';

export default function ChipInput({ label, value = [], onChange, placeholder = 'Digite e pressione Enter...', error }) {
    const [input, setInput] = useState('');
    const inputRef = useRef(null);

    function addChip(text) {
        const trimmed = text.trim();
        if (!trimmed || value.includes(trimmed)) return;
        onChange([...value, trimmed]);
        setInput('');
    }

    function removeChip(index) {
        onChange(value.filter((_, i) => i !== index));
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addChip(input);
        } else if (e.key === 'Backspace' && input === '' && value.length > 0) {
            removeChip(value.length - 1);
        }
    }

    return (
        <div className="flex flex-col gap-1.5">
            {label && (
                <label className="text-sm font-semibold text-[#1C1917]">{label}</label>
            )}
            <div
                onClick={() => inputRef.current?.focus()}
                className={`min-h-[44px] flex flex-wrap gap-1.5 items-center px-3 py-2 rounded-xl border-2 bg-white cursor-text transition-colors ${
                    error ? 'border-red-500' : 'border-[#E3CDA8] focus-within:border-[#2D6A4F]'
                }`}
            >
                {value.map((chip, i) => (
                    <span
                        key={i}
                        className="flex items-center gap-1 bg-[#D8EFE3] text-[#2D6A4F] border border-[#2D6A4F] text-xs font-semibold px-2 py-1 rounded-lg"
                    >
                        {chip}
                        <button
                            type="button"
                            onClick={() => removeChip(i)}
                            className="hover:text-red-600 transition-colors ml-0.5"
                        >
                            <X size={11} strokeWidth={2.5} />
                        </button>
                    </span>
                ))}
                <input
                    ref={inputRef}
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    onKeyDown={handleKeyDown}
                    onBlur={() => addChip(input)}
                    placeholder={value.length === 0 ? placeholder : ''}
                    className="flex-1 min-w-[120px] text-sm outline-none bg-transparent text-[#1C1917] placeholder:text-[#A8A29E]"
                />
            </div>
            {error && <p className="text-xs text-red-600">{error}</p>}
            <p className="text-xs text-[#A8A29E]">Pressione Enter ou vírgula para adicionar</p>
        </div>
    );
}
