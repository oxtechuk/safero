<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Core Built-in Modules Registry
    |--------------------------------------------------------------------------
    |
    | Each module defines its key, bilingual names, SVG icon, description,
    | submenus for navigation, and granular permissions.
    | Any new module added here or in app/Modules/ is auto-discovered.
    |
    */

    'certificates' => [
        'key' => 'certificates',
        'name_ar' => 'شهادات الفحص والمعايرة',
        'name_en' => 'Inspection Certificates',
        'icon' => 'branding/odoo-icons/quality_control.svg',
        'badge_color' => 'blue',
        'description_ar' => 'إدارة نماذج فحص الأوناش ومهمات الرفع والمعدات العامة وفق معايير LOLER و BS 7121.',
        'description_en' => 'Management of crane examinations, lifting accessories, and general cargo inspection under LOLER.',
        'route' => 'certificates.index',
        'permissions' => [
            [
                'name' => 'certificates.view',
                'action' => 'view',
                'label_ar' => 'عرض سجل شهادات الفحص',
                'label_en' => 'View Certificate Registry',
            ],
            [
                'name' => 'certificates.create',
                'action' => 'create',
                'label_ar' => 'إصدار شهادات فحص جديدة',
                'label_en' => 'Create New Certificates',
            ],
            [
                'name' => 'certificates.edit',
                'action' => 'edit',
                'label_ar' => 'تعديل بيانات الشهادات وقوائم الفحص',
                'label_en' => 'Edit Certificates & Checklists',
            ],
            [
                'name' => 'certificates.approve',
                'action' => 'approve',
                'label_ar' => 'اعتماد وتوقيع الشهادات الرسمية',
                'label_en' => 'Approve & Sign Official Certificates',
            ],
            [
                'name' => 'certificates.delete',
                'action' => 'delete',
                'label_ar' => 'إلغاء وحذف مسودات الشهادات',
                'label_en' => 'Delete & Revoke Certificates',
            ],
            [
                'name' => 'certificates.export',
                'action' => 'export',
                'label_ar' => 'تصدير وطباعة تقارير PDF المجمعة',
                'label_en' => 'Export & Batch Print Certificates',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'سجل الشهادات', 'label_en' => 'All Certificates', 'route' => 'certificates.index'],
            ['label_ar' => 'فحص الأوناش والروافع', 'label_en' => 'Cranes Inspection', 'route' => 'certificates.create', 'params' => ['type' => 'crane']],
            ['label_ar' => 'مهمات الرفع والحبال', 'label_en' => 'Lifting Accessories', 'route' => 'certificates.create', 'params' => ['type' => 'accessory']],
            ['label_ar' => 'معدات عامة وباليتات', 'label_en' => 'General Equipment', 'route' => 'certificates.create', 'params' => ['type' => 'general']],
        ],
    ],

    'training' => [
        'key' => 'training',
        'name_ar' => 'شهادات التدريب والسلامة',
        'name_en' => 'Safety & Training Certificates',
        'icon' => 'branding/odoo-icons/industry_certification.svg',
        'badge_color' => 'emerald',
        'description_ar' => 'إصدار شهادات الدورات التدريبية وإدارة قاعدة بيانات المتدربين والاعتمادات.',
        'description_en' => 'Issue professional training certifications and manage trainees database.',
        'route' => 'training-certificates.index',
        'permissions' => [
            [
                'name' => 'training.view',
                'action' => 'view',
                'label_ar' => 'عرض سجل شهادات التدريب والمتدربين',
                'label_en' => 'View Training Certificates & Trainees',
            ],
            [
                'name' => 'training.create',
                'action' => 'create',
                'label_ar' => 'إصدار شهادات تدريب وإضافة متدربين',
                'label_en' => 'Issue Training Certificates & Add Trainees',
            ],
            [
                'name' => 'training.edit',
                'action' => 'edit',
                'label_ar' => 'تعديل نتائج وتقييمات المتدربين',
                'label_en' => 'Edit Trainee Scores & Details',
            ],
            [
                'name' => 'training.approve',
                'action' => 'approve',
                'label_ar' => 'اعتماد وإصدار بطاقات ورخص التدريب',
                'label_en' => 'Approve & Issue Trainee Cards',
            ],
            [
                'name' => 'training.delete',
                'action' => 'delete',
                'label_ar' => 'حذف شهادات وسجلات التدريب',
                'label_en' => 'Delete Training Certificates',
            ],
            [
                'name' => 'training.export',
                'action' => 'export',
                'label_ar' => 'تصدير قوائم وبيانات المتدربين',
                'label_en' => 'Export Trainees Data & Reports',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'سجل شهادات التدريب', 'label_en' => 'Training Certificates', 'route' => 'training-certificates.index'],
            ['label_ar' => 'إصدار شهادة تدريب', 'label_en' => 'New Certificate', 'route' => 'training-certificates.create'],
            ['label_ar' => 'قاعدة بيانات المتدربين', 'label_en' => 'Trainees Database', 'route' => 'trainees.index'],
            ['label_ar' => 'تسجيل متدرب جديد', 'label_en' => 'Register Trainee', 'route' => 'trainees.create'],
        ],
    ],

    'customers' => [
        'key' => 'customers',
        'name_ar' => 'العملاء والشركات (CRM)',
        'name_en' => 'Customers & Companies',
        'icon' => 'branding/odoo-icons/contacts.svg',
        'badge_color' => 'amber',
        'description_ar' => 'دليل الشركات والعملاء، مواقع الفحص والمصانع وسجلات الاتصال.',
        'description_en' => 'Enterprise contacts, inspection sites, yards, and company records.',
        'route' => 'customers.index',
        'permissions' => [
            [
                'name' => 'customers.view',
                'action' => 'view',
                'label_ar' => 'عرض دليل الشركات والعملاء',
                'label_en' => 'View Customers Directory',
            ],
            [
                'name' => 'customers.create',
                'action' => 'create',
                'label_ar' => 'إضافة شركات وعملاء جدد',
                'label_en' => 'Create New Customers',
            ],
            [
                'name' => 'customers.edit',
                'action' => 'edit',
                'label_ar' => 'تعديل بيانات وعناوين ومواقع العملاء',
                'label_en' => 'Edit Customer Details & Sites',
            ],
            [
                'name' => 'customers.delete',
                'action' => 'delete',
                'label_ar' => 'حذف سجلات العملاء غير النشطة',
                'label_en' => 'Delete Customers',
            ],
            [
                'name' => 'customers.export',
                'action' => 'export',
                'label_ar' => 'تصدير دليل العملاء إلى Excel / CSV',
                'label_en' => 'Export Customer Directory',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'دليل العملاء والشركات', 'label_en' => 'Directory', 'route' => 'customers.index'],
            ['label_ar' => 'إضافة عميل جديد', 'label_en' => 'Add Customer', 'route' => 'customers.create'],
        ],
    ],

    'users' => [
        'key' => 'users',
        'name_ar' => 'المستخدمون والصلاحيات',
        'name_en' => 'Users & Access Control',
        'icon' => 'branding/odoo-icons/hr.svg',
        'badge_color' => 'purple',
        'description_ar' => 'إدارة حسابات المهندسين والفاحصين والأدوار ومصفوفة الصلاحيات المتقدمة.',
        'description_en' => 'User account management, roles, and fine-grained access control.',
        'route' => 'users.index',
        'permissions' => [
            [
                'name' => 'users.view',
                'action' => 'view',
                'label_ar' => 'عرض قائمة المستخدمين والمفتشين',
                'label_en' => 'View Users & Inspectors List',
            ],
            [
                'name' => 'users.create',
                'action' => 'create',
                'label_ar' => 'إضافة مستخدم جديد وتعيين الأدوار',
                'label_en' => 'Create User & Assign Roles',
            ],
            [
                'name' => 'users.edit',
                'action' => 'edit',
                'label_ar' => 'تعديل ملف المستخدم وتحديث الصلاحيات',
                'label_en' => 'Edit User Profile & Update Permissions',
            ],
            [
                'name' => 'users.delete',
                'action' => 'delete',
                'label_ar' => 'تعطيل أو حذف حساب المستخدم',
                'label_en' => 'Deactivate or Delete User',
            ],
            [
                'name' => 'roles.manage',
                'action' => 'manage',
                'label_ar' => 'إدارة وتعديل أدوار النظام ومصفوفة الصلاحيات',
                'label_en' => 'Manage Roles & Permissions Matrix',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'سجل المستخدمين', 'label_en' => 'Users List', 'route' => 'users.index'],
            ['label_ar' => 'إضافة مستخدم جديد', 'label_en' => 'New User', 'route' => 'users.create'],
            ['label_ar' => 'إدارة الأدوار', 'label_en' => 'Roles & Matrix', 'route' => 'roles.index'],
        ],
    ],

    'settings' => [
        'key' => 'settings',
        'name_ar' => 'إعدادات المنظومة والـ SaaS',
        'name_en' => 'Platform Settings & SaaS',
        'icon' => 'branding/odoo-icons/settings.svg',
        'badge_color' => 'slate',
        'description_ar' => 'تهيئة معايير الفحص ISO 17020، الشعار، الأختام، الترجمة، وبنية SaaS.',
        'description_en' => 'System configuration, ISO 17020 standards, branding, and SaaS architecture.',
        'route' => 'settings.index',
        'permissions' => [
            [
                'name' => 'settings.view',
                'action' => 'view',
                'label_ar' => 'عرض شاشة إعدادات النظام',
                'label_en' => 'View Platform Settings',
            ],
            [
                'name' => 'settings.edit',
                'action' => 'edit',
                'label_ar' => 'تعديل الإعدادات العامة والهوية ومعايير الفحص',
                'label_en' => 'Update Settings, Branding & Standards',
            ],
            [
                'name' => 'settings.saas',
                'action' => 'saas',
                'label_ar' => 'التحكم في معمارية SaaS وتفعيل المديولات المفتوحة',
                'label_en' => 'Manage SaaS Architecture & Pluggable Modules',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'الإعدادات العامة', 'label_en' => 'General', 'route' => 'settings.index', 'params' => ['tab' => 'general']],
            ['label_ar' => 'المستخدمون والشركات', 'label_en' => 'Users & Companies', 'route' => 'settings.index', 'params' => ['tab' => 'users']],
            ['label_ar' => 'اللغات والترجمة', 'label_en' => 'Localization', 'route' => 'settings.index', 'params' => ['tab' => 'localization']],
            ['label_ar' => 'قوالب الشهادات والشعار', 'label_en' => 'Branding', 'route' => 'settings.index', 'params' => ['tab' => 'branding']],
            ['label_ar' => 'منصة SaaS والمديولات', 'label_en' => 'SaaS Modules', 'route' => 'settings.index', 'params' => ['tab' => 'saas']],
        ],
    ],

    'dashboard' => [
        'key' => 'dashboard',
        'name_ar' => 'لوحة المؤشرات والتقارير',
        'name_en' => 'Dashboard & Analytics',
        'icon' => 'branding/odoo-icons/board.svg',
        'badge_color' => 'indigo',
        'description_ar' => 'مؤشرات الأداء التشغيلي، جداول الفحص الدورية، ومعدلات الإنجاز.',
        'description_en' => 'Operational KPI metrics, inspection schedules, and compliance ratios.',
        'route' => 'dashboard',
        'permissions' => [
            [
                'name' => 'dashboard.view',
                'action' => 'view',
                'label_ar' => 'عرض لوحة المؤشرات والتحليلات العامة',
                'label_en' => 'View Executive Dashboard & Analytics',
            ],
        ],
        'submenus' => [
            ['label_ar' => 'لوحة المؤشرات العامة', 'label_en' => 'Executive Dashboard', 'route' => 'dashboard'],
        ],
    ],
];
