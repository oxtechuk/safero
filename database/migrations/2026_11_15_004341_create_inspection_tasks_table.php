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
        Schema::create('inspection_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number', 50)->unique();
            $table->string('title_ar', 255);
            $table->string('title_en', 255);
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('equipment_type', 50)->default('crane');
            $table->text('equipment_description')->nullable();
            $table->string('equipment_identifier', 100)->nullable();
            $table->foreignId('assigned_inspector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('scheduled_date');
            $table->date('due_date')->nullable();
            $table->string('status', 30)->default('scheduled')->index();
            $table->string('priority', 20)->default('normal');
            $table->string('examination_location', 255)->nullable();
            $table->json('checklist_items')->nullable();
            $table->foreignId('certificate_id')->nullable()->constrained('certificates')->nullOnDelete();
            $table->string('google_sheet_row_id', 50)->nullable();
            $table->timestamp('synced_to_sheets_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_tasks');
    }
};
