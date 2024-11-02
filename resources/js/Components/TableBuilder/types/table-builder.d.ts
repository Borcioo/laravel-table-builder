export type ColumnType = "text" | "date" | "icon" | "image" | "badge" | "boolean";

export type DateFormat = "full" | "long" | "medium" | "short";

export type Method = "get" | "post" | "put" | "patch" | "delete";

export type ActionVariant =
    | "default"
    | "destructive"
    | "outline"
    | "secondary"
    | "ghost"
    | "link";
export type ActionSize = "default" | "sm" | "lg" | "icon";

export interface BaseColumnConfig {
  key: string;
  label: string;
  type: ColumnType;
  sortable?: boolean;
  searchable?: boolean;
  width?: number;
  minWidth?: number;
  maxWidth?: number;
}

export interface TextColumnConfig extends BaseColumnConfig {
  type: "text";
}

export interface DateColumnConfig extends BaseColumnConfig {
  type: "date";
  format?: DateFormat;
}

export interface IconColumnConfig extends BaseColumnConfig {
  type: "icon";
  icon: string;
}

export interface ImageColumnConfig extends BaseColumnConfig {
  type: "image";
  width?: number;
  height?: number;
}

export interface BadgeColumnConfig extends BaseColumnConfig {
  type: "badge";
  color?: string;
  background?: string;
  colors?: Record<string, string>;
  backgrounds?: Record<string, string>;
}

export interface BooleanColumnConfig extends BaseColumnConfig {
  type: "boolean";
  trueValue?: string;
  falseValue?: string;
}

export type ColumnConfig =
  | TextColumnConfig
  | DateColumnConfig
  | IconColumnConfig
  | ImageColumnConfig
  | BadgeColumnConfig
  | BooleanColumnConfig;

  export interface FilterOption {
    value: string | number | boolean;
    label: string;
  }
  
  export interface BaseFilterConfig {
    key: string;
    label: string;
    type: string;
    attributes?: Record<string, any>;
  }
  
  export interface SelectFilterConfig extends BaseFilterConfig {
    type: 'select';
    options: FilterOption[];
  }
  
  export interface BooleanFilterConfig extends BaseFilterConfig {
    type: 'boolean';
    options: [
      { value: true, label: string },
      { value: false, label: string }
    ];
  }
  
  export interface TernaryFilterConfig extends BaseFilterConfig {
    type: 'ternary';
    options: [
      { value: '', label: string },
      { value: '1', label: string },
      { value: '0', label: string }
    ];
  }
  
  export interface DateFilterConfig extends BaseFilterConfig {
    type: 'date';
  }
  
  export interface TextInputFilterConfig extends BaseFilterConfig {
    type: 'text';
  }
  
  export interface NumberFilterConfig extends BaseFilterConfig {
    type: 'number';
  }
  
  export type FilterConfig = 
    | SelectFilterConfig 
    | BooleanFilterConfig 
    | TernaryFilterConfig 
    | DateFilterConfig 
    | TextInputFilterConfig 
    | NumberFilterConfig;

export interface BaseActionConfig {
  name: string;
  label: string;
  type: string;
  icon?: string;
  color?: string;
  variant?: "link" | "solid" | "outline";
  size?: "xs" | "sm" | "md" | "lg";
  confirmation?: {
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
  };
  permissions?: string[];
  visible?: boolean;
  disabled?: boolean;
}

export interface ActionConfig {
  name: string;
  label: string;
  type: 'button' | 'link';
  href?: string | null;
  route?: string | null;
  routeParams?: Record<string, any> | ((record: any) => Record<string, any>);
  method?: Method;
  icon?: string | null;
  variant?: ActionVariant;
  size?: ActionSize;
  preserveScroll?: boolean;
  preserveState?: boolean;
  replace?: boolean;
  only?: string[];
  confirm?: boolean;
  confirmText?: string;
  permissions?: string[];
  visible?: boolean | ((record: any) => boolean);
  disabled?: boolean | ((record: any) => boolean);
}

export interface ButtonActionConfig extends ActionConfig {
  type: 'button';
}

export interface LinkActionConfig extends ActionConfig {
  type: 'link';
}

export type TableAction = ButtonActionConfig | LinkActionConfig;

export interface TableConfig {
  columns: ColumnConfig[];
  filters?: FilterConfig[];
  actions: ActionConfig[];
  searchableColumns: string[];
  sortableColumns: string[];
  defaultPerPage: number;
  perPageOptions: number[];
}

export interface TableData<T = any> {
  schema: TableConfig;
  data: {
    data: T[];
    meta: {
      current_page: number;
      per_page: number;
      total: number;
      last_page: number;
    };
  };
}
