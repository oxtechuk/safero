<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ModuleDiscoveryService
{
    /**
     * Get all registered and auto-discovered modules.
     *
     * @return array<string, array>
     */
    public static function all(): array
    {
        $modules = config('modules', []);

        // Dynamic Pluggable Modules Directory: app/Modules/
        $modulesPath = app_path('Modules');
        if (File::isDirectory($modulesPath)) {
            $directories = File::directories($modulesPath);
            foreach ($directories as $dir) {
                $manifestFile = $dir.'/module.json';
                if (File::exists($manifestFile)) {
                    $jsonContent = json_decode(File::get($manifestFile), true);
                    if ($jsonContent && isset($jsonContent['key'])) {
                        $modules[$jsonContent['key']] = $jsonContent;
                    }
                }
            }
        }

        return $modules;
    }

    /**
     * Synchronize all declared permissions across all modules into the database
     * and initialize default system roles with their permissions.
     */
    public static function syncPermissionsToDatabase(): void
    {
        $modules = self::all();
        $allCreatedPermissions = [];

        foreach ($modules as $moduleKey => $module) {
            if (empty($module['permissions']) || ! is_array($module['permissions'])) {
                continue;
            }

            foreach ($module['permissions'] as $perm) {
                $model = Permission::updateOrCreate(
                    ['name' => $perm['name']],
                    [
                        'module' => $moduleKey,
                        'action' => $perm['action'] ?? 'view',
                        'label_ar' => $perm['label_ar'] ?? $perm['name'],
                        'label_en' => $perm['label_en'] ?? $perm['name'],
                        'is_active' => true,
                    ]
                );

                $allCreatedPermissions[$perm['name']] = $model->id;
            }
        }

        // Initialize / ensure standard system roles exist
        self::ensureSystemRoles($allCreatedPermissions);
    }

    /**
     * Ensure system roles exist with their baseline permission mappings.
     *
     * @param  array<string, int>  $permissionMap
     */
    public static function ensureSystemRoles(array $permissionMap): void
    {
        $rolesDefinition = [
            'admin' => [
                'label_ar' => 'مسؤول النظام العام (Super Admin)',
                'label_en' => 'Super Administrator',
                'description_ar' => 'صلاحيات وصول وتحكم كاملة في كافة مديولات وإعدادات النظام والمستخدمين.',
                'description_en' => 'Full administrative access to all modules, system configurations, and users.',
                'hierarchy_level' => 100,
                'permissions' => array_keys($permissionMap),
            ],
            'inspection_manager' => [
                'label_ar' => 'مدير عمليات الفحص الهندسي',
                'label_en' => 'Inspection Operations Manager',
                'description_ar' => 'إدارة شاملة لشهادات الفحص، مراجعة واعتماد وتوقيع تقارير الأوناش والمهمات.',
                'description_en' => 'Full control over inspection certificates, approval, and official signing.',
                'hierarchy_level' => 80,
                'permissions' => [
                    'certificates.view', 'certificates.create', 'certificates.edit', 'certificates.approve',
                    'certificates.delete', 'certificates.export', 'customers.view', 'customers.create',
                    'customers.edit', 'customers.export', 'dashboard.view',
                ],
            ],
            'inspector' => [
                'label_ar' => 'مهندس / فاحص معتمد (Lifting Inspector)',
                'label_en' => 'Certified Lifting Inspector',
                'description_ar' => 'فحص المعدات والأوناش ميدانياً، إدخال قوائم الفحص والمسودات دون اعتماد نهائي.',
                'description_en' => 'Field equipment examination, filling checklists and creating drafts.',
                'hierarchy_level' => 50,
                'permissions' => [
                    'certificates.view', 'certificates.create', 'certificates.edit', 'certificates.export',
                    'customers.view', 'dashboard.view',
                ],
            ],
            'training_officer' => [
                'label_ar' => 'مسؤول التدريب والسلامة المهنية',
                'label_en' => 'Safety & Training Officer',
                'description_ar' => 'إصدار شهادات التدريب وإدارة سجلات المتدربين والاعتمادات.',
                'description_en' => 'Issue training certifications and manage trainees database.',
                'hierarchy_level' => 50,
                'permissions' => [
                    'training.view', 'training.create', 'training.edit', 'training.approve',
                    'training.delete', 'training.export', 'customers.view', 'dashboard.view',
                ],
            ],
            'viewer' => [
                'label_ar' => 'مشاهد / مدقق خارجي (Auditor / Viewer)',
                'label_en' => 'Auditor / Read-Only Viewer',
                'description_ar' => 'عرض السجلات والتقارير والتحقق من الشهادات دون صلاحيات تعديل.',
                'description_en' => 'Read-only access to records and certificate verification.',
                'hierarchy_level' => 10,
                'permissions' => [
                    'certificates.view', 'training.view', 'customers.view', 'dashboard.view',
                ],
            ],
        ];

        foreach ($rolesDefinition as $roleName => $data) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'label_ar' => $data['label_ar'],
                    'label_en' => $data['label_en'],
                    'description_ar' => $data['description_ar'],
                    'description_en' => $data['description_en'],
                    'is_system' => true,
                    'hierarchy_level' => $data['hierarchy_level'],
                ]
            );

            // Sync role permissions
            $rolePermissionIds = [];
            foreach ($data['permissions'] as $permName) {
                if (isset($permissionMap[$permName])) {
                    $rolePermissionIds[] = $permissionMap[$permName];
                }
            }

            if (! empty($rolePermissionIds)) {
                $role->permissions()->syncWithoutDetaching($rolePermissionIds);
            }
        }
    }

    /**
     * Get the permission matrix formatted for UI checkbox presentation.
     *
     * @return array<string, array>
     */
    public static function getMatrixForUser(?User $user = null): array
    {
        self::syncPermissionsToDatabase();

        $modules = self::all();
        $permissions = Permission::active()->get()->groupBy('module');

        $userDirectPermissions = $user ? $user->directPermissions->keyBy('id') : collect();
        $userEffectivePermissions = $user ? $user->allPermissions()->keyBy('name') : collect();

        $matrix = [];

        foreach ($modules as $moduleKey => $module) {
            $modulePermissions = $permissions->get($moduleKey, collect());

            $matrix[$moduleKey] = [
                'key' => $moduleKey,
                'name_ar' => $module['name_ar'],
                'name_en' => $module['name_en'],
                'icon' => $module['icon'] ?? 'branding/odoo-icons/base_icon.svg',
                'badge_color' => $module['badge_color'] ?? 'blue',
                'description_ar' => $module['description_ar'] ?? '',
                'description_en' => $module['description_en'] ?? '',
                'permissions' => $modulePermissions->map(function ($perm) use ($user, $userDirectPermissions, $userEffectivePermissions) {
                    $hasDirect = $user && $userDirectPermissions->has($perm->id);
                    $isGranted = $user ? $userEffectivePermissions->has($perm->name) : false;

                    return [
                        'id' => $perm->id,
                        'name' => $perm->name,
                        'action' => $perm->action,
                        'label_ar' => $perm->label_ar,
                        'label_en' => $perm->label_en,
                        'has_direct' => $hasDirect,
                        'is_granted' => $isGranted,
                    ];
                })->toArray(),
            ];
        }

        return $matrix;
    }
}
