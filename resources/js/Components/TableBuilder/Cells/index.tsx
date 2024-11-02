import { ColumnConfig } from "../types/table-builder";
import { DateCell } from "./DateCell";
import { BooleanCell } from "./BooleanCell";
import { BadgeCell } from "./BadgeCell";
import { IconCell } from "./IconCell";
import { ImageCell } from "./ImageCell";

interface CellProps {
    value: any;
    column: ColumnConfig;
}

export const Cell = ({ value, column }: CellProps) => {
    if (value === null || value === undefined) return "";

    switch (column.type) {
        case "date":
            return <DateCell value={value} column={column} />;
        case "boolean":
            return <BooleanCell value={value} column={column} />;
        case "badge":
            return <BadgeCell value={value} column={column} />;
        case "icon":
            return <IconCell value={value} column={column} />;
        case "image":
            return <ImageCell value={value} column={column} />;
        default:
            return value;
    }
};
