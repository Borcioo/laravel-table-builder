import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    getSortedRowModel,
    useReactTable,
} from "@tanstack/react-table";
import { useMemo } from "react";
import { Actions } from "./Actions";
import { Cell } from "./Cells";
import { ColumnConfig, TableConfig } from "./types/table-builder";
import {
    Table as UITable,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { ArrowDown, ArrowUp } from "lucide-react";
import { cn } from "@/lib/utils";

interface TableProps<TData> {
    schema: TableConfig;
    data: TData[];
    onSort: (column: string) => void;
    sortColumn: string | null;
    sortDirection: "asc" | "desc";
    labels?: {
        actions?: string;
    };
}

export const Table = <TData extends Record<string, unknown>>({
    schema,
    data,
    onSort,
    sortColumn,
    sortDirection,
    labels = {
        actions: "Actions",
    },
}: TableProps<TData>) => {
    const columns = useMemo<ColumnDef<TData>[]>(() => {
        const tableColumns: ColumnDef<TData>[] = schema.columns.map(
            (column: ColumnConfig) => ({
                id: column.key,
                accessorFn: (row) => row[column.key],
                size: column.width ?? undefined,
                minSize: column.minWidth ?? undefined,
                maxSize: column.maxWidth ?? undefined,
                enableResizing: false,
                header: () => (
                    <div
                        className={cn(
                            "flex items-center gap-2",
                            column.sortable && "cursor-pointer"
                        )}
                        onClick={() => column.sortable && onSort(column.key)}
                    >
                        {column.label}
                        {sortColumn === column.key && (
                            <span className="w-4 h-4">
                                {sortDirection === "asc" ? (
                                    <ArrowUp className="w-4 h-4" />
                                ) : (
                                    <ArrowDown className="w-4 h-4" />
                                )}
                            </span>
                        )}
                    </div>
                ),
                cell: ({ getValue }) => {
                    const value = getValue();
                    return <Cell value={value} column={column} />;
                },
            })
        );

        if (schema.actions?.length) {
            tableColumns.push({
                id: "actions",
                header: labels.actions,
                size: 1,
                minSize: 80,
                maxSize: 80,
                enableResizing: false,
                cell: ({ row }) => (
                    <Actions actions={schema.actions!} record={row.original} />
                ),
            });
        }

        return tableColumns;
    }, [schema, sortColumn, sortDirection, labels]);

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getSortedRowModel: getSortedRowModel(),
        manualSorting: true,
        columnResizeMode: "onChange",
        state: {
            sorting: sortColumn
                ? [{ id: sortColumn, desc: sortDirection === "desc" }]
                : [],
        },
    });

    return (
        <div className="border rounded-md">
            <div className="overflow-auto">
                <UITable>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => (
                                    <TableHead
                                        key={header.id}
                                        style={{
                                            width:
                                                header.column.columnDef.size ===
                                                1
                                                    ? "1%"
                                                    : "auto",
                                            minWidth: `${header.column.columnDef.minSize}px`,
                                            maxWidth: header.column.columnDef
                                                .maxSize
                                                ? `${header.column.columnDef.maxSize}px`
                                                : undefined,
                                        }}
                                        className="whitespace-nowrap"
                                    >
                                        {flexRender(
                                            header.column.columnDef.header,
                                            header.getContext()
                                        )}
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows.map((row) => (
                            <TableRow key={row.id}>
                                {row.getVisibleCells().map((cell) => (
                                    <TableCell
                                        key={cell.id}
                                        style={{
                                            width:
                                                cell.column.columnDef.size === 1
                                                    ? "1%"
                                                    : cell.column.columnDef
                                                          .size,
                                            minWidth: `${cell.column.columnDef.minSize}px`,
                                            maxWidth: cell.column.columnDef
                                                .maxSize
                                                ? `${cell.column.columnDef.maxSize}px`
                                                : undefined,
                                        }}
                                        className="whitespace-nowrap"
                                    >
                                        {flexRender(
                                            cell.column.columnDef.cell,
                                            cell.getContext()
                                        )}
                                    </TableCell>
                                ))}
                            </TableRow>
                        ))}
                    </TableBody>
                </UITable>
            </div>
        </div>
    );
};
