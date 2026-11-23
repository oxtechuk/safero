<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ModuleDiscoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $query = User::with(['roles', 'directPermissions'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('job_title_ar', 'like', "%{$search}%")
                    ->orWhere('job_title_en', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', $statusFilter === 'active');
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::orderByDesc('hierarchy_level')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admins' => User::where('role', 'admin')->orWhereHas('roles', fn ($q) => $q->where('name', 'admin'))->count(),
            'inspectors' => User::whereHas('roles', fn ($q) => $q->whereIn('name', ['inspection_manager', 'inspector']))->count(),
        ];

        return view('users.index', compact('users', 'roles', 'stats', 'search', 'roleFilter', 'statusFilter'));
    }

    public function create(): View
    {
        $roles = Role::orderByDesc('hierarchy_level')->get();
        $matrix = ModuleDiscoveryService::getMatrixForUser();

        return view('users.create', compact('roles', 'matrix'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'job_title_ar' => ['nullable', 'string', 'max:100'],
            'job_title_en' => ['nullable', 'string', 'max:100'],
            'role_id' => ['required', 'exists:roles,id'],
            'is_active' => ['nullable', 'boolean'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $selectedRole = Role::findOrFail($validated['role_id']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'job_title_ar' => $validated['job_title_ar'] ?? null,
            'job_title_en' => $validated['job_title_en'] ?? null,
            'role' => $selectedRole->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Handle signature upload
        if ($request->hasFile('signature') && $request->file('signature')->isValid()) {
            $file = $request->file('signature');
            $dir = public_path('signatures');
            if (! file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $filename = 'sig_user_'.$user->id.'_'.time().'.'.$ext;
            $file->move($dir, $filename);
            $user->update(['signature_path' => '/signatures/'.$filename]);
        }

        // Assign selected role
        $user->roles()->sync([$selectedRole->id]);

        // Sync custom permissions if provided
        if (! empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء حساب المستخدم بنجاح: '.$user->name);
    }

    public function show(User $user): View
    {
        $user->load(['roles.permissions', 'directPermissions']);
        $matrix = ModuleDiscoveryService::getMatrixForUser($user);

        return view('users.show', compact('user', 'matrix'));
    }

    public function edit(User $user): View
    {
        $user->load(['roles', 'directPermissions']);
        $roles = Role::orderByDesc('hierarchy_level')->get();
        $matrix = ModuleDiscoveryService::getMatrixForUser($user);

        return view('users.edit', compact('user', 'roles', 'matrix'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'job_title_ar' => ['nullable', 'string', 'max:100'],
            'job_title_en' => ['nullable', 'string', 'max:100'],
            'role_id' => ['required', 'exists:roles,id'],
            'is_active' => ['nullable', 'boolean'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
            'remove_signature' => ['nullable', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $selectedRole = Role::findOrFail($validated['role_id']);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'job_title_ar' => $validated['job_title_ar'] ?? null,
            'job_title_en' => $validated['job_title_en'] ?? null,
            'role' => $selectedRole->name,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Handle signature upload or removal
        if ($request->hasFile('signature') && $request->file('signature')->isValid()) {
            if ($user->signature_path && str_starts_with($user->signature_path, '/signatures/')) {
                $oldFile = public_path(ltrim($user->signature_path, '/\\'));
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $file = $request->file('signature');
            $dir = public_path('signatures');
            if (! file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $filename = 'sig_user_'.$user->id.'_'.time().'.'.$ext;
            $file->move($dir, $filename);
            $userData['signature_path'] = '/signatures/'.$filename;
        } elseif ($request->boolean('remove_signature')) {
            if ($user->signature_path && str_starts_with($user->signature_path, '/signatures/')) {
                $oldFile = public_path(ltrim($user->signature_path, '/\\'));
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $userData['signature_path'] = null;
        }

        $user->update($userData);

        // Update role
        $user->roles()->sync([$selectedRole->id]);

        // Sync custom direct permissions
        $user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث بيانات وصلاحيات المستخدم بنجاح: '.$user->name);
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'لا يمكنك حذف حسابك الشخصي المسجل به حالياً.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'لا يمكن حذف آخر مسؤول للنظام (Super Administrator).']);
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف حساب المستخدم بنجاح: '.$userName);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['user' => 'لا يمكنك تعطيل حسابك الشخصي المسجل به حالياً.']);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'تفعيل' : 'تعطيل';

        return redirect()->back()
            ->with('success', "تم {$statusText} حساب المستخدم: {$user->name}");
    }
}
