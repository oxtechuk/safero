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
        Schema::dropIfExists('certificate_templates');

        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->string('code', 50)->nullable()->index();
            $table->string('equipment_type', 50)->default('general')->index(); // crane, accessory, general
            $table->text('equipment_description')->nullable();
            $table->string('reference_standard', 190)->nullable();
            $table->string('swl', 100)->nullable();
            $table->string('manufacturer', 190)->nullable();
            $table->string('model', 190)->nullable();
            $table->string('capacity', 100)->nullable();
            $table->string('dimensions', 190)->nullable();
            $table->string('safety_factor', 100)->nullable();
            $table->string('length', 100)->nullable();
            $table->unsignedSmallInteger('validity_months')->default(6);
            $table->text('tests_carried_out')->nullable();
            $table->text('defect_identification')->nullable();
            $table->text('repair_particulars')->nullable();
            $table->json('checklist_items')->nullable();
            $table->json('custom_specifications')->nullable(); // CMS tools, equipment, test devices, custom fields
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('usage_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
