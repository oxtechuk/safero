<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Services\ModuleDiscoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        ModuleDiscoveryService::syncPermissionsToDatabase();

        $roles = Role::with(['permissions', 'users'])->orderByDesc('hierarchy_level')->get();
        $modules = ModuleDiscoveryService::all();
        $permissionsByModule = Permission::active()->get()->groupBy('module');

        return view('roles.index', compact('roles', 'modules', 'permissionsByModule'));
    }

    public function edit(Role $role): View
    {
        ModuleDiscoveryService::syncPermissionsToDatabase();

        $role->load('permissions');
        $modules = ModuleDiscoveryService::all();
        $permissionsByModule = Permission::active()->get()->groupBy('module');

        return view('roles.edit', compact('role', 'modules', 'permissionsByModule'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'label_ar' => ['required', 'string', 'max:100'],
            'label_en' => ['required', 'string', 'max:100'],
            'description_ar' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'label_ar' => $validated['label_ar'],
            'label_en' => $validated['label_en'],
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'تم تحديث صلاحيات الدور بنجاح: '.$role->label());
    }
}

