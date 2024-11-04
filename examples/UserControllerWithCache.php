<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Borek\LaravelTableBuilder\Facades\TableBuilder;
use Borek\LaravelTableBuilder\Tables\Columns\TextColumn;
use Borek\LaravelTableBuilder\Tables\Columns\DateColumn;
use Borek\LaravelTableBuilder\Tables\Columns\BadgeColumn;
use Borek\LaravelTableBuilder\Tables\Filters\SelectFilter;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $table = TableBuilder::make(User::with(['roles', 'permissions']))
            ->addColumn((new TextColumn('name', 'Nazwa'))->sortable())
            ->addColumn((new TextColumn('email', 'Email'))->sortable())
            ->addColumn((new BadgeColumn('roles.name', 'Role')))
            ->addColumn((new DateColumn('created_at', 'Data utworzenia'))->sortable())
            ->searchable(['name', 'email', 'roles.name'])
            ->addFilter(new SelectFilter('role', 'Rola', [
                'admin' => 'Administrator',
                'user' => 'Użytkownik'
            ]))
            ->transform('created_at', fn($item) => $item->created_at?->format('Y-m-d H:i'))
            ->transform('roles.name', fn($item) => $item->roles->pluck('name')->join(', '))
            ->pagination(25, [25, 50, 100])
            // Włączamy cache na 60 minut
            ->cache('users_table', 3600)
            // Automatycznie invalidujemy cache gdy model User jest modyfikowany
            ->invalidateOn([
                'eloquent.created: ' . User::class,
                'eloquent.updated: ' . User::class,
                'eloquent.deleted: ' . User::class,
                'role.assigned',
                'role.removed'
            ]);

        if (app()->environment('local')) {
            $table->debug();
        }

        return Inertia::render('Users/Index', [
            'tableSchema' => $table->getSchema(),
            'tableData' => $table->getData($request->all()),
        ]);
    }
}
