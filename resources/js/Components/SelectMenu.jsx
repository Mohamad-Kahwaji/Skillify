import { useEffect, useRef, useState } from 'react';

export default function SelectMenu({ value, onChange, options, placeholder, icon = 'ti-adjustments-horizontal', disabled = false, className = '' }) {
    const [open, setOpen] = useState(false);
    const containerRef = useRef(null);
    const selected = options.find(option => String(option.value) === String(value));

    useEffect(() => {
        const closeOnOutsideClick = (event) => {
            if (!containerRef.current?.contains(event.target)) setOpen(false);
        };

        const closeOnEscape = (event) => {
            if (event.key === 'Escape') setOpen(false);
        };

        document.addEventListener('mousedown', closeOnOutsideClick);
        document.addEventListener('keydown', closeOnEscape);
        return () => {
            document.removeEventListener('mousedown', closeOnOutsideClick);
            document.removeEventListener('keydown', closeOnEscape);
        };
    }, []);

    const selectOption = (nextValue) => {
        onChange(nextValue);
        setOpen(false);
    };

    return (
        <div ref={containerRef} className={`skillify-listbox ${className}`}>
            <button
                type="button"
                className="skillify-listbox-trigger"
                aria-expanded={open}
                aria-haspopup="listbox"
                disabled={disabled}
                onClick={() => !disabled && setOpen(current => !current)}
            >
                <span className="skillify-listbox-label">
                    <i className={`ti ${icon}`} />
                    <span>{selected?.label ?? placeholder}</span>
                </span>
                <i className={`ti ${open ? 'ti-chevron-up' : 'ti-chevron-down'} skillify-listbox-chevron`} />
            </button>

            {open && (
                <div className="skillify-listbox-options" role="listbox">
                    {options.map(option => {
                        const active = String(option.value) === String(value);
                        return (
                            <button
                                key={option.value || '__empty'}
                                type="button"
                                role="option"
                                aria-selected={active}
                                className={`skillify-listbox-option${active ? ' is-active' : ''}`}
                                onClick={() => selectOption(option.value)}
                            >
                                <span>{option.label}</span>
                                {active && <i className="ti ti-check" />}
                            </button>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
