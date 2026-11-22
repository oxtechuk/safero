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
        Schema::dropIfExists('inspections');

        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('certificate_template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->string('equipment_type', 100)->default('crane');
            $table->string('equipment_name', 190);
            $table->string('reference_standard', 190)->nullable();
            $table->string('doc_number', 50)->nullable(); // e.g. SAF-INS-01
            $table->string('asset_tag', 100)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('manufacturer', 190)->nullable();
            $table->string('model_no', 190)->nullable();
            $table->string('serial_no', 190)->nullable();
            $table->string('year_of_manufacture', 50)->nullable();
            $table->string('swl', 100)->nullable();
            $table->string('inspection_type', 50)->default('periodic'); // initial, periodic, post_repair, post_incident
            $table->date('inspection_date');
            $table->date('next_inspection_date')->nullable();
            $table->string('inspector_name', 190)->nullable();
            $table->string('inspector_cert_ref', 190)->nullable();
            $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();

            // Mobile checklist and results
            $table->json('checklist_results')->nullable();

            // Overall declaration & results
            $table->enum('overall_condition', ['safe', 'unsafe'])->default('safe');
            $table->text('defects_identified')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->boolean('repeat_examination_required')->default(false);

            // Status and link to certificate
            $table->enum('status', ['draft', 'completed', 'certified'])->default('draft')->index();
            $table->foreignId('certificate_id')->nullable()->constrained('certificates')->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
