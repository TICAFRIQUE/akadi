<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('users')->orderBy('name')->get();
        return view('admin.pages.permission.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique'   => 'Cette permission existe déjà.',
        ]);

        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        // Assigner automatiquement aux users developpeur / administrateur
        User::role(['developpeur', 'administrateur'])->get()->each(
            fn($u) => $u->givePermissionTo($permission)
        );

        // Vider le cache des permissions pour que les changements soient pris en compte immédiatement
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', 'Permission « ' . $request->name . ' » créée avec succès.');
    }

    public function edit(string $id)
    {
        $permission = Permission::withCount('users')->findOrFail($id);
        return view('admin.pages.permission.edit', compact('permission'));
    }

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique'   => 'Cette permission existe déjà.',
        ]);

        $permission->update(['name' => $request->name]);


        return back()->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['success' => true]);
    }
}
