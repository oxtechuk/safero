<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'role',
    'title',
    'job_title_ar',
    'job_title_en',
    'is_active',
    'signature_path',
    'avatar',
    'locale',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_has_roles', 'user_id', 'role_id');
    }

    public function directPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_has_permissions', 'user_id', 'permission_id')
            ->withPivot('is_granted');
    }

    public function isAdmin(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        return $this->hasRole('admin') || $this->hasRole('super_admin');
    }

    public function hasRole(string|array $roles): bool
    {
        $roleNames = is_array($roles) ? $roles : func_get_args();

        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(function ($role) use ($roleNames) {
                return in_array($role->name, $roleNames, true);
            });
        }

        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    public function assignRole(Role|string $role): self
    {
        $roleModel = is_string($role)
            ? Role::firstOrCreate(['name' => $role], [
                'label_ar' => $role,
                'label_en' => $role,
            ])
            : $role;

        $this->roles()->syncWithoutDetaching([$roleModel->id]);

        return $this;
    }

    public function removeRole(Role|string $role): self
    {
        $roleModel = is_string($role) ? Role::where('name', $role)->first() : $role;

        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }

        return $this;
    }

    public function syncRoles(array $roleIds): self
    {
        $this->roles()->sync($roleIds);

        // Keep legacy role attribute in sync
        $primaryRole = Role::whereIn('id', $roleIds)->first();
        if ($primaryRole) {
            $this->forceFill(['role' => $primaryRole->name])->saveQuietly();
        }

        return $this;
    }

    public function hasPermissionTo(string $permissionName): bool
    {
        // Super admin bypasses all checks
        if ($this->isAdmin()) {
            return true;
        }

        // 1. Direct permission override
        $direct = $this->directPermissions->firstWhere('name', $permissionName);
        if ($direct) {
            return (bool) $direct->pivot->is_granted;
        }

        // 2. Role-based permissions
        if ($this->relationLoaded('roles')) {
            foreach ($this->roles as $role) {
                if ($role->permissions->contains('name', $permissionName)) {
                    return true;
                }
            }
        } else {
            return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })->exists();
        }

        return false;
    }

    public function allPermissions(): Collection
    {
        if ($this->isAdmin()) {
            return Permission::active()->get();
        }

        $rolePermissions = $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten();

        $directPermissions = $this->directPermissions()
            ->wherePivot('is_granted', true)
            ->get();

        return $rolePermissions->merge($directPermissions)->unique('id')->values();
    }

    public function givePermissionTo(Permission|string $permission, bool $isGranted = true): self
    {
        $permModel = is_string($permission)
            ? Permission::firstOrCreate(['name' => $permission], [
                'label_ar' => $permission,
                'label_en' => $permission,
                'module' => explode('.', $permission)[0] ?? 'general',
            ])
            : $permission;

        $this->directPermissions()->syncWithoutDetaching([
            $permModel->id => ['is_granted' => $isGranted],
        ]);

        return $this;
    }

    public function syncPermissions(array $permissionIds): self
    {
        $syncData = [];
        foreach ($permissionIds as $id) {
            $syncData[$id] = ['is_granted' => true];
        }

        $this->directPermissions()->sync($syncData);

        return $this;
    }

    public function jobTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'en' && ! empty($this->job_title_en)) {
            return $this->job_title_en;
        }

        return $this->job_title_ar ?: ($this->title ?: 'فاحص معتمد');
    }

    public function primaryRoleName(?string $locale = null): string
    {
        $firstRole = $this->roles->first();
        if ($firstRole) {
            return $firstRole->label($locale);
        }

        return $this->role === 'admin' ? 'مدير النظام (Admin)' : 'مستخدم (User)';
    }
}
