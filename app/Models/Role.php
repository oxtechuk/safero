<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label_ar',
        'label_en',
        'description_ar',
        'description_en',
        'is_system',
        'hierarchy_level',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'hierarchy_level' => 'integer',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_has_roles', 'role_id', 'user_id');
    }

    public function label(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return ($locale === 'en' && ! empty($this->label_en)) ? $this->label_en : $this->label_ar;
    }

    public function description(?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();

        return ($locale === 'en' && ! empty($this->description_en)) ? $this->description_en : $this->description_ar;
    }

    public function hasPermissionTo(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }

    public function givePermissionTo(Permission|string $permission): self
    {
        $perm = is_string($permission)
            ? Permission::firstOrCreate(['name' => $permission], [
                'label_ar' => $permission,
                'label_en' => $permission,
                'module' => explode('.', $permission)[0] ?? 'general',
            ])
            : $permission;

        $this->permissions()->syncWithoutDetaching([$perm->id]);

        return $this;
    }

    public function syncPermissions(array $permissionIds): self
    {
        $this->permissions()->sync($permissionIds);

        return $this;
    }
}
