# Laravel Table Builder

A package for Laravel 11 that provides advanced data tables with search, sort, pagination, and filters, designed to work with Inertia.js and React.

## Installation

Since this package is not published on Packagist yet, you need to install it from GitHub.

1. Add the repository to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/borek/laravel-table-builder"
    }
]
```

2. Install the package using Composer:

```bash
 composer require borek/laravel-table-builder:dev-main
```

### Setup

1. Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag=table-builder-config
```

2. Install required dependencies:

```bash
php artisan table-builder:install-dependencies
```

3. Install React components:

```bash
php artisan table-builder:install-components
```

You can also specify a custom path for components:

```bash
php artisan table-builder:install-components --path=Custom/Path
```

## Requirements

- PHP ^8.2
- Laravel ^11.0
- Inertia.js with React
- shadcn/ui components

## Basic Usage

### Backend (Controller)

```php
use Borek\LaravelTableBuilder\Facades\TableBuilder;

    public function index(Request $request)
    {
        $query = User::with('roles');
        $table = TableBuilder::make($query);

        $table
            ->addColumn((new TextColumn('name', 'Name'))->sortable())
            ->addColumn((new TextColumn('email', 'Email'))->sortable())
            ->addColumn((new BadgeColumn('roles.name', 'Role'))->sortable()->colors([
                'admin' => 'rgba(239, 68, 68, 1)',
                'agent' => 'rgba(234, 179, 8, 1)',
                'user' => 'rgba(34, 197, 94, 1)',
            ])->backgrounds([
                'admin' => 'rgba(239, 68, 68, 0.1)',
                'agent' => 'rgba(234, 179, 8, 0.1)',
                'user' => 'rgba(34, 197, 94, 0.1)',
            ]))
            ->addColumn((new DateColumn('created_at', 'Data utworzenia'))->sortable())
            ->addFilter(new SelectFilter('roles.name', 'Role', [
                'admin' => 'Administrator',
                'user' => 'User',
            ]))
            ->addAction(new ButtonAction('edit', '', [
                'icon' => 'edit',
                'variant' => 'primary',
                'route' => 'admin.users.edit',
                'routeParams' => ['user' => ':id'],
                'type' => 'link',
            ]))
            ->searchable(['name', 'email']);


        $data = $table->getData($request->all());

        return Inertia::render('Users/Index', [
            'tableSchema' => $table->getSchema(),
            'tableData' => [
                'data' => UserResource::collection($data['data'])->jsonSerialize(),
                'meta' => $data['meta']
            ],
            'query' => $request->only([
                'search',
                'filters',
                'sortColumn',
                'sortDirection',
                'perPage'
            ]),
        ]);
    }
```

### Frontend (React)

```tsx
import {
  DataTable,
  DataTableData,
  DataTableQuery,
} from "@/Components/TableBuilder";
import { TableConfig } from "@/Components/TableBuilder/types/table-builder";
import { PageProps } from "@/types";

interface UsersPageProps extends PageProps {
  tableSchema: TableConfig;
  tableData: DataTableData<any>;
  query: DataTableQuery;
}

export default function Index({
  tableSchema,
  tableData,
  query,
}: UsersPageProps) {
  return (
    <div className="py-12">
      <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <DataTable schema={tableSchema} data={tableData} query={query} />
      </div>
    </div>
  );
}
```

## Features

- 📊 Sortable columns
- 🔍 Global search
- 🎯 Column filters
- 📱 Responsive design
- 🎨 Customizable UI (based on shadcn/ui)
- 📄 Server-side pagination

## Configuration

After publishing the config file, you can customize the default paths in `config/table-builder.php`:

```php
return [
    'components_path' => 'Components/TableBuilder', // Default path for components
    'default_path' => 'Components/TableBuilder'     // Fallback path if config is not published
];
```

## Available Commands

| Command                                               | Description                                             |
| ----------------------------------------------------- | ------------------------------------------------------- |
| `table-builder:install-dependencies`                  | Installs required npm packages and shadcn/ui components |
| `table-builder:install-components`                    | Copies React components to your project                 |
| `table-builder:install-components --path=Custom/Path` | Installs components to a custom path                    |

## License

This package is open-sourced software licensed under the [GNU General Public License v3.0 (GPL-3.0)](https://www.gnu.org/licenses/gpl-3.0.html).
