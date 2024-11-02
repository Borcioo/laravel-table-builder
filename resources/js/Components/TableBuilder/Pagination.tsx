import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { TableData } from "./types/table-builder";
import { ChevronLeft, ChevronRight } from "lucide-react";

type PaginationProps = {
    meta: TableData["data"]["meta"];
    perPage: number;
    perPageOptions: number[];
    onPerPageChange: (perPage: number) => void;
    labels?: {
        next?: string;
        previous?: string;
        page?: string;
        of?: string;
        show?: string;
        items?: string;
    };
};

export const Pagination = ({
    meta,
    perPage,
    perPageOptions,
    onPerPageChange,
    labels = {
        next: "Next",
        previous: "Previous",
        page: "Page",
        of: "of",
        show: "Show",
        items: "items",
    },
}: PaginationProps) => {
    const showPerPageSelect = meta.total > Math.min(...perPageOptions);

    return (
        <div className="flex items-center justify-between py-3 border-t">
            <div className="flex items-center gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    onClick={() =>
                        router.get(
                            route(route().current()!),
                            { page: meta.current_page - 1 },
                            { preserveState: true, preserveScroll: true }
                        )
                    }
                    disabled={meta.current_page === 1}
                >
                    <ChevronLeft className="w-4 h-4" />
                    <span className="ml-2">{labels.previous}</span>
                </Button>

                <Button
                    variant="outline"
                    size="sm"
                    onClick={() =>
                        router.get(
                            route(route().current()!),
                            { page: meta.current_page + 1 },
                            { preserveState: true, preserveScroll: true }
                        )
                    }
                    disabled={meta.current_page === meta.last_page}
                >
                    <span className="mr-2">{labels.next}</span>
                    <ChevronRight className="w-4 h-4" />
                </Button>

                <span className="text-sm text-muted-foreground">
                    {labels.page} {meta.current_page} {labels.of}{" "}
                    {meta.last_page}
                </span>
            </div>

            {showPerPageSelect && (
                <Select
                    value={perPage.toString()}
                    onValueChange={(value) => onPerPageChange(Number(value))}
                >
                    <SelectTrigger className="w-[180px]">
                        <SelectValue
                            placeholder={`${labels.show} ${perPage} ${labels.items}`}
                        />
                    </SelectTrigger>
                    <SelectContent>
                        {perPageOptions.map((pageSize) => (
                            <SelectItem
                                key={pageSize}
                                value={pageSize.toString()}
                            >
                                {labels.show} {pageSize} {labels.items}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            )}
        </div>
    );
};
