interface TernaryInputProps {
    label: string;
    value: string;
    onChange: (value: string) => void;
}

export function TernaryInput({ label, value, onChange }: TernaryInputProps) {
    return (
        <div className="flex flex-col gap-1">
            <label className="text-sm font-medium text-gray-700">{label}</label>
            <select
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Wszystkie</option>
                <option value="1">Tak</option>
                <option value="0">Nie</option>
            </select>
        </div>
    );
}
