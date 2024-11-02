import { Input } from "@/Components/ui/input";
import { Search as SearchIcon } from "lucide-react";

interface SearchProps {
    value: string;
    onChange: (value: string) => void;
    label?: string;
}

export const Search = ({ value, onChange, label = "Search" }: SearchProps) => {
    return (
        <div className="relative w-[280px]">
            <SearchIcon className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
                type="text"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                placeholder={label}
                className="h-8 pl-8"
            />
        </div>
    );
};
