<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('label_ar', 100);
            $table->string('label_en', 100);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_system')->default(false);
            $table->unsignedInteger('hierarchy_level')->default(10);
            $table->timestamps();
        });

        // 2. Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('module', 50)->index();
            $table->string('action', 30)->default('view');
            $table->string('label_ar', 150);
            $table->string('label_en', 150);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Role <-> Permission Pivot Table
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. User <-> Role Pivot Table
        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->primary(['user_id', 'role_id']);
        });

        // 5. User <-> Direct Permission Pivot Table
        Schema::create('user_has_permissions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->boolean('is_granted')->default(true);
            $table->primary(['user_id', 'permission_id']);
        });

        // 6. Augment Users Table with Enterprise Profile Fields
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 50)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'job_title_ar')) {
                $table->string('job_title_ar', 100)->nullable()->after('title');
            }
            if (! Schema::hasColumn('users', 'job_title_en')) {
                $table->string('job_title_en', 100)->nullable()->after('job_title_ar');
            }
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 255)->nullable()->after('signature_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['phone', 'job_title_ar', 'job_title_en', 'is_active', 'avatar'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::dropIfExists('user_has_permissions');
        Schema::dropIfExists('user_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
