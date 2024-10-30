# Laravel Table Builder

Laravel Table Builder to pakiet umożliwiający łatwe tworzenie tabel z wyszukiwaniem, sortowaniem, paginacją i filtrami dla aplikacji Laravel 11, współpracujący z Inertia i React. Poniższa dokumentacja przedstawia podstawowe komponenty, przykłady użycia oraz dostępne opcje konfiguracji.

## Spis Treści

- [Użycie](#użycie)
  - [Podstawowe użycie](#podstawowe-użycie)
  - [Tabela z filtrami](#z-filtrami)
  - [Tabela z akcjami](#z-akcjami)
- [Dostępne Komponenty](#dostępne-komponenty)
  - [Kolumny](#kolumny)
  - [Filtry](#filtry)
  - [Akcje](#akcje)
- [Przykłady Implementacji](#przykłady-implementacji)
- [Paginacja](#paginacja)
- [Dostosowywanie Wyglądu](#dostosowywanie-wyglądu)
  - [Kolumna Badge](#kolumna-badge)
  - [Przyciski Akcji](#przyciski-akcji)
- [Testowanie i Formatowanie Kodu](#testowanie-i-formatowanie-kodu)
- [Wymagania](#wymagania)
- [Licencja](#licencja)

---

## Struktura Projektu

Pakiet Laravel Table Builder składa się z następujących głównych komponentów:

## Użycie

### Podstawowe użycie

```php
use Borek\LaravelTableBuilder\Facades\TableBuilder;

TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->dateColumn('created_at', 'Data utworzenia')
    ->searchable(['name'])
    ->sortable(['created_at']);
```

### Z filtrami

```php
TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->selectFilter('status', 'Status', [
        'active' => 'Aktywny',
        'inactive' => 'Nieaktywny'
    ])
    ->ternaryFilter('verified', 'Zweryfikowany');
```

### Z akcjami

```php
TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->buttonAction('edit', 'Edytuj', [
        'route' => 'users.edit',
        'method' => 'get'
    ]);
```

## Dostępne Komponenty

### Kolumny

- `textColumn()`
- `dateColumn()`
- `iconColumn()`
- `imageColumn()`
- `badgeColumn()`
- `booleanColumn()`

### Filtry

- `textFilter()`
- `selectFilter()`
- `numberFilter()`
- `ternaryFilter()`
- `booleanFilter()`

### Akcje

- `buttonAction()`

## Przykłady Implementacji

### Podstawowa tabela z wyszukiwaniem i sortowaniem

```php
TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->textColumn('email', 'Email')
    ->dateColumn('created_at', 'Data utworzenia')
    ->searchable(['name', 'email'])
    ->sortable(['name', 'created_at']);
```

### Tabela z kolumną badge i własnymi kolorami

```php
TableBuilder::make(User::query())
    ->badgeColumn('status', 'Status')
        ->colors([
            'active' => 'green',
            'pending' => 'yellow',
            'blocked' => 'red'
        ]);
```

### Tabela z akcjami i potwierdzeniem

```php
TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->buttonAction('edit', 'Edytuj', [
        'route' => 'users.edit',
        'method' => 'get',
        'icon' => 'edit',
        'variant' => 'primary'
    ])
    ->buttonAction('delete', 'Usuń', [
        'route' => 'users.destroy',
        'method' => 'delete',
        'icon' => 'trash',
        'variant' => 'danger',
        'confirm' => true,
        'confirmText' => 'Czy na pewno chcesz usunąć tego użytkownika?'
    ]);
```

## Paginacja

```php
TableBuilder::make(User::query())
    ->textColumn('name', 'Nazwa')
    ->defaultPerPage(25)
    ->perPageOptions([10, 25, 50, 100]);
```

## Dostosowywanie Wyglądu

### Kolumna Badge

```php
->badgeColumn('status', 'Status')
    ->colors([
        'active' => 'bg-emerald-100 text-emerald-800',
        'pending' => 'bg-amber-100 text-amber-800',
        'blocked' => 'bg-rose-100 text-rose-800'
    ]);
```

### Przyciski Akcji

```php
->buttonAction('edit', 'Edytuj', [
    'variant' => 'primary',
    'size' => 'sm',
    'icon' => 'edit'
]);
```

## Testowanie i Formatowanie Kodu

Uruchom testy:

```bash
composer test
```

Sformatuj kod:

```bash
composer format
```

## Wymagania

- PHP ^8.2
- Laravel ^11.0

## Licencja

Pakiet jest objęty licencją [GNU General Public License v3.0 (GPL-3.0)](https://www.gnu.org/licenses/gpl-3.0.html).
