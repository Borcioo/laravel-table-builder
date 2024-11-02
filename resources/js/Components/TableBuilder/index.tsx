import { router } from "@inertiajs/react";
import { debounce } from "lodash";
import { useState } from "react";
import { Filters } from "./Filters";
import { Pagination } from "./Pagination";
import { Table } from "./Table";
import { TableConfig } from "./types/table-builder";
import { Loader2 } from "lucide-react";
import { cn } from "@/lib/utils";

export type DataTableQuery = {
  search?: string;
  filters?: Record<string, any>;
  sortColumn?: string;
  sortDirection?: "asc" | "desc";
  perPage?: number;
};

export type DataTableData<T> = {
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
};

interface DataTableProps<T> {
  schema: TableConfig;
  data: DataTableData<T>;
  query: DataTableQuery;
  labels?: {
    search?: string;
    filters?: {
      filters?: string;
      clearAll?: string;
      noFilters?: string;
    };
  };
}

export function DataTable<T>({
  schema,
  data,
  query,
  labels = {
    search: "Search",
    filters: {
      filters: "Filters",
      clearAll: "Clear all",
      noFilters: "No filters applied",
    },
  },
}: DataTableProps<T>) {
  console.log(data);
  console.log(schema);
  const [loading, setLoading] = useState(false);
  const [search, setSearch] = useState(query?.search ?? "");
  const [filters, setFilters] = useState(query?.filters ?? {});
  const [sortColumn, setSortColumn] = useState<string | null>(
    query?.sortColumn ?? null
  );
  const [sortDirection, setSortDirection] = useState<"asc" | "desc">(
    query?.sortDirection ?? "asc"
  );
  const [perPage, setPerPage] = useState(
    query?.perPage ?? schema.defaultPerPage ?? 10
  );

  const reloadTable = debounce((params: any) => {
    router.visit(route(route().current()!, params), {
      preserveScroll: true,
      preserveState: true,
      replace: true,
      onStart: () => {
        setLoading(true);
      },
      onProgress: () => {
        setLoading(true);
      },
      onFinish: () => {
        setLoading(false);
      },
    });
  }, 300);

  const handleSearch = (value: string) => {
    setSearch(value);
    reloadTable({ ...getCurrentParams(), search: value || undefined });
  };

  const handleFilters = (newFilters: object) => {
    setFilters(newFilters);

    const params = getCurrentParams();

    Object.keys(params).forEach((key) => {
      if (key.startsWith("filters[")) {
        delete params[key];
      }
    });

    if (Object.keys(newFilters).length > 0) {
      Object.entries(newFilters).forEach(([key, value]) => {
        if (value !== "" && value !== null && value !== undefined) {
          params[`filters[${key}]`] = value;
        }
      });
    }

    reloadTable(params);
  };

  const handleSort = (column: string) => {
    const newDirection =
      sortColumn === column && sortDirection === "asc" ? "desc" : "asc";
    setSortColumn(column);
    setSortDirection(newDirection);
    reloadTable({
      ...getCurrentParams(),
      sortColumn: column,
      sortDirection: newDirection,
    });
  };

  const handlePerPageChange = (value: number) => {
    setPerPage(value);
    reloadTable({ ...getCurrentParams(), perPage: value });
  };

  const getCurrentParams = () => {
    const params: Record<string, any> = {};

    if (search) {
      params.search = search;
    }

    if (Object.keys(filters).length > 0) {
      Object.entries(filters).forEach(([key, value]) => {
        if (value !== "" && value !== null && value !== undefined) {
          params[`filters[${key}]`] = value;
        }
      });
    }

    if (sortColumn) {
      params.sortColumn = sortColumn;
    }

    if (sortDirection) {
      params.sortDirection = sortDirection;
    }

    if (perPage !== schema.defaultPerPage) {
      params.perPage = perPage;
    }

    return params;
  };

  return (
    <div className="relative">
      <div className={cn("space-y-4", loading && "pointer-events-none")}>
        <Filters
          schema={schema.filters}
          value={filters}
          onChange={handleFilters}
          search={search}
          onSearch={handleSearch}
          loading={loading}
          labels={{
            search: labels.search,
            ...labels.filters,
          }}
        />
        <Table
          schema={schema}
          data={data.data as Record<string, unknown>[]}
          sortColumn={sortColumn}
          sortDirection={sortDirection}
          onSort={handleSort}
        />
        <Pagination
          meta={data.meta}
          perPage={perPage}
          perPageOptions={schema.perPageOptions ?? [10, 20, 50, 100]}
          onPerPageChange={handlePerPageChange}
        />
      </div>
      {loading && (
        <div
          className="absolute inset-0 z-10 flex items-center justify-center rounded-md pointer-events-auto bg-black/50 backdrop-blur-sm"
          aria-hidden="true"
        >
          <p className="flex items-center gap-2 text-sm text-white select-none">
            Loading... <Loader2 className="w-4 h-4 animate-spin" />
          </p>
        </div>
      )}
    </div>
  );
}
