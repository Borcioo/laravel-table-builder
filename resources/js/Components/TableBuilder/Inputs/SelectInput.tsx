import { FilterOption } from "../types/table-builder";

interface SelectInputProps {
    label: string;
    value: string | number | boolean;
    options: FilterOption[];
    onChange: (value: string | number | boolean) => void;
}

export function SelectInput({
    label,
    value,
    options,
    onChange,
}: SelectInputProps) {
    return (
        <div className="flex flex-col gap-1">
            <label className="text-sm font-medium text-gray-700">{label}</label>
            <select
                value={String(value)}
                onChange={(e) => {
                    const val = e.target.value;
                    if (val === "true") onChange(true);
                    else if (val === "false") onChange(false);
                    else if (val === "") onChange("");
                    else if (!isNaN(Number(val))) onChange(Number(val));
                    else onChange(val);
                }}
                className="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Wszystkie</option>
                {options.map((option) => (
                    <option
                        key={String(option.value)}
                        value={String(option.value)}
                    >
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
}
