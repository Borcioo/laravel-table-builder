import { Link } from "@inertiajs/react";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Icon } from "./Other/Icon";
import { ActionConfig } from "./types/table-builder";

export function Actions({
    actions,
    record,
}: {
    actions: ActionConfig[];
    record: Record<string, any>;
}) {
    const replaceUrlParams = (
        url: string,
        record: Record<string, any>
    ): string => {
        return url.replace(/:(\w+)/g, (_, param) => {
            return record[param]?.toString() || "";
        });
    };

    const handleAction = (action: ActionConfig) => {
        if (action.confirm && !window.confirm(action.confirmText)) {
            return;
        }

        const params =
            typeof action.routeParams === "function"
                ? action.routeParams(record)
                : action.routeParams;

        let url = action.route ? route(action.route, params) : action.href;

        if (!url) return;

        url = replaceUrlParams(url, record);

        router.visit(url, {
            method: action.method,
            preserveScroll: action.preserveScroll,
            preserveState: action.preserveState,
            replace: action.replace,
            only: action.only,
        });
    };

    return (
        <div className="flex space-x-2">
            {actions.map((action) => {
                const isVisible =
                    typeof action.visible === "function"
                        ? action.visible(record)
                        : action.visible ?? true;

                if (!isVisible) return null;

                const params =
                    typeof action.routeParams === "function"
                        ? action.routeParams(record)
                        : action.routeParams;

                let url = action.route
                    ? route(action.route, params)
                    : action.href;

                if (url) {
                    url = replaceUrlParams(url, record);
                }

                return action.type === "link" ? (
                    <Link
                        aria-label={action.name}
                        key={action.name}
                        href={url || "#"}
                        method={action.method}
                        preserveScroll={action.preserveScroll}
                        preserveState={action.preserveState}
                        replace={action.replace}
                        only={action.only}
                    >
                        <Button variant={action.variant} size={action.size}>
                            {action.icon && (
                                <Icon name={action.icon} label={action.label} />
                            )}
                            {action.label}
                        </Button>
                    </Link>
                ) : (
                    <Button
                        aria-label={action.name}
                        key={action.name}
                        variant={action.variant}
                        size={action.size}
                        onClick={() => handleAction(action)}
                    >
                        {action.icon && (
                            <Icon name={action.icon} label={action.label} />
                        )}
                        {action.label}
                    </Button>
                );
            })}
        </div>
    );
}
