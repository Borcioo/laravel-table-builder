import { DateColumnConfig } from "../types/table-builder";

interface DateCellProps {
    value: string;
    column: DateColumnConfig;
}

export const DateCell = ({ value, column }: DateCellProps) => {
    if (!value) return "";

    return new Date(value).toLocaleDateString(undefined, {
        dateStyle: (column.format || "medium") as
            | "full"
            | "long"
            | "medium"
            | "short",
    });
};
