import { Badge } from "@/Components/ui/badge";
import { BadgeColumnConfig } from "../types/table-builder";

interface BadgeCellProps {
    value: string;
    column: BadgeColumnConfig;
}

export const BadgeCell = ({ value, column }: BadgeCellProps) => {
    const getStyles = () => {
        const baseStyles = {
            display: "inline-flex",
            alignItems: "center",
            borderRadius: "9999px",
            padding: "0.25rem 0.75rem",
            fontSize: "0.875rem",
            lineHeight: "1.25rem",
            fontWeight: 500,
        };

        if (column.colors?.[value] || column.backgrounds?.[value]) {
            const color = column.colors?.[value];
            return {
                ...baseStyles,
                color,
                backgroundColor: column.backgrounds?.[value],
                border: `1px solid ${color}`,
            };
        }

        if (column.color || column.background) {
            return {
                ...baseStyles,
                color: column.color,
                backgroundColor: column.background,
                border: `1px solid ${column.color}`,
            };
        }

        return null;
    };

    const styles = getStyles();

    if (!styles) {
        return <Badge variant="secondary">{value}</Badge>;
    }

    return <span style={styles}>{value}</span>;
};
