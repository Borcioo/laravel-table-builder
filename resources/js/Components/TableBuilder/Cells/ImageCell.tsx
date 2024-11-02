import { ImageColumnConfig } from "../types/table-builder";

interface ImageCellProps {
    value: string;
    column: ImageColumnConfig;
}

export const ImageCell = ({ value, column }: ImageCellProps) => {
    return (
        <img
            src={value}
            alt=""
            width={column.width}
            height={column.height}
            className="object-cover"
        />
    );
};
