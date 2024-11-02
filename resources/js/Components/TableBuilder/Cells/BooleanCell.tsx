import { BooleanColumnConfig } from "../types/table-builder";

interface BooleanCellProps {
    value: boolean;
    column: BooleanColumnConfig;
}

export const BooleanCell = ({ value, column }: BooleanCellProps) => {
    return value ? column.trueValue || "Yes" : column.falseValue || "No";
};
