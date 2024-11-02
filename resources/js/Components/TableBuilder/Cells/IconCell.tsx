import { IconColumnConfig } from "../types/table-builder";
import { Icon } from "../Other/Icon";

interface IconCellProps {
    value: string;
    column: IconColumnConfig;
}

export const IconCell = ({ value, column }: IconCellProps) => {
    return <Icon name={value} />;
};
