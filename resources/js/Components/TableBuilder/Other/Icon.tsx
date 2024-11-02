import * as LucideIcons from "lucide-react";
import { cn } from "@/lib/utils";

interface IconProps {
    name: string;
    label?: string;
    className?: string;
}

export const Icon = ({ name, label, className }: IconProps) => {
    const iconName = name
        .split("-")
        .map(
            (part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase()
        )
        .join("");

    const LucideIcon = (LucideIcons as Record<string, any>)[iconName];

    if (!LucideIcon) {
        console.warn(`Icon "${name}" (${iconName}) not found in Lucide icons`);
        return (
            <LucideIcons.HelpCircle
                className={cn("w-4 h-4", label ? "mr-2" : "", className)}
            />
        );
    }

    return (
        <LucideIcon className={cn("w-4 h-4", label ? "mr-2" : "", className)} />
    );
};
