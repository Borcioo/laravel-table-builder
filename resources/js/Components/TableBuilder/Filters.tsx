import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { Filter, Search as SearchIcon, X } from "lucide-react";
import { NumberInput } from "./Inputs/NumberInput";
import { SelectInput } from "./Inputs/SelectInput";
import { TernaryInput } from "./Inputs/TernaryInput";
import { TextInput } from "./Inputs/TextInput";
import {
    FilterConfig,
    FilterOption,
    SelectFilterConfig,
} from "./types/table-builder";
import { debounce } from "lodash";
import { useCallback, useState, useEffect } from "react";

type FilterValue = string | number | boolean;

interface FiltersProps {
    schema?: FilterConfig[];
    value: Record<string, FilterValue>;
    onChange: (values: Record<string, FilterValue>) => void;
    search: string;
    onSearch: (value: string) => void;
    labels?: {
        search?: string;
        filters?: string;
        clearAll?: string;
        noFilters?: string;
    };
    children?: React.ReactNode;
    loading?: boolean;
}

export function Filters({
    schema,
    value,
    onChange,
    search,
    onSearch,
    labels = {
        search: "Search",
        filters: "Filters",
        clearAll: "Clear all",
        noFilters: "No filters applied",
    },
    children,
    loading,
}: FiltersProps) {
    if (!schema?.length) return null;

    const activeFiltersCount = Object.keys(value).filter(
        (key) => value[key] !== ""
    ).length;

    const [isOpen, setIsOpen] = useState(false);
    const [searchValue, setSearchValue] = useState(search);

    useEffect(() => {
        if (loading) {
            setIsOpen(false);
        }
    }, [loading]);

    const handleChange = (key: string, newValue: FilterValue) => {
        onChange({ ...value, [key]: newValue });
        setIsOpen(false);
    };

    const handleClearFilter = (key: string) => {
        const newFilters = { ...value };
        delete newFilters[key];
        onChange(newFilters);
        setIsOpen(false);
    };

    const handleClearAll = () => {
        onChange({});
        setIsOpen(false);
    };

    const getFilterLabel = (filter: FilterConfig, value: unknown): string => {
        if ("options" in filter) {
            const option = filter.options.find(
                (opt: FilterOption) => opt.value === value
            );
            return option ? option.label : String(value);
        }
        return String(value);
    };

    const debouncedSearch = useCallback(
        debounce((value: string) => {
            onSearch(value);
        }, 300),
        [onSearch]
    );

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        setSearchValue(value);
        debouncedSearch(value);
    };

    const handleSearchClear = () => {
        setSearchValue("");
        onSearch("");
    };

    return (
        <div className="space-y-2">
            <div className="flex items-center gap-2">
                <div className="relative flex-1">
                    <SearchIcon className="absolute w-4 h-4 left-2 top-2 text-muted-foreground" />
                    <Input
                        type="text"
                        value={searchValue}
                        onChange={handleSearchChange}
                        placeholder={labels.search}
                        className="h-8 pl-8 pr-8"
                    />
                    {searchValue && (
                        <Button
                            variant="ghost"
                            size="sm"
                            className="absolute w-6 h-6 p-0 right-1 top-1 hover:bg-transparent"
                            onClick={handleSearchClear}
                        >
                            <X className="w-4 h-4" />
                        </Button>
                    )}
                </div>
                <Popover open={isOpen} onOpenChange={setIsOpen}>
                    <PopoverTrigger asChild>
                        <Button
                            variant="outline"
                            size="sm"
                            className="h-8 border-dashed whitespace-nowrap"
                        >
                            <Filter className="w-4 h-4 mr-2" />
                            {labels.filters}
                            {activeFiltersCount > 0 && (
                                <Badge
                                    variant="secondary"
                                    className="h-4 px-1 ml-2 rounded-sm"
                                >
                                    {activeFiltersCount}
                                </Badge>
                            )}
                        </Button>
                    </PopoverTrigger>
                    <PopoverContent className="w-[280px] p-4" align="end">
                        <ScrollArea className="h-[300px]">
                            <div className="p-1 space-y-4">
                                {schema.map((filter) => {
                                    const currentValue = (value[filter.key] ??
                                        "") as FilterValue;
                                    return (
                                        <div key={filter.key}>
                                            {filter.type === "select" && (
                                                <SelectInput
                                                    label={filter.label}
                                                    value={currentValue}
                                                    options={
                                                        (
                                                            filter as SelectFilterConfig
                                                        ).options
                                                    }
                                                    onChange={(v) =>
                                                        handleChange(
                                                            filter.key,
                                                            v
                                                        )
                                                    }
                                                />
                                            )}
                                            {filter.type === "text" && (
                                                <TextInput
                                                    label={filter.label}
                                                    value={String(currentValue)}
                                                    onChange={(v) =>
                                                        handleChange(
                                                            filter.key,
                                                            v
                                                        )
                                                    }
                                                />
                                            )}
                                            {filter.type === "number" && (
                                                <NumberInput
                                                    label={filter.label}
                                                    value={
                                                        currentValue as number
                                                    }
                                                    onChange={(v) =>
                                                        handleChange(
                                                            filter.key,
                                                            v
                                                        )
                                                    }
                                                />
                                            )}
                                            {filter.type === "ternary" && (
                                                <TernaryInput
                                                    label={filter.label}
                                                    value={String(currentValue)}
                                                    onChange={(v) =>
                                                        handleChange(
                                                            filter.key,
                                                            v
                                                        )
                                                    }
                                                />
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </ScrollArea>
                    </PopoverContent>
                </Popover>
                {children}
            </div>

            {activeFiltersCount > 0 ? (
                <div className="flex flex-wrap items-center gap-2">
                    {Object.entries(value).map(([key, val]) => {
                        if (!val) return null;
                        const filter = schema.find((f) => f.key === key);
                        if (!filter) return null;

                        return (
                            <Badge
                                key={key}
                                variant="secondary"
                                className="flex items-center gap-1"
                            >
                                {filter.label}: {getFilterLabel(filter, val)}
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    className="w-4 h-4 p-0 hover:bg-transparent"
                                    onClick={() => handleClearFilter(key)}
                                >
                                    <X className="w-3 h-3" />
                                </Button>
                            </Badge>
                        );
                    })}
                    <Button variant="ghost" size="sm" onClick={handleClearAll}>
                        {labels.clearAll}
                    </Button>
                </div>
            ) : null}
        </div>
    );
}
