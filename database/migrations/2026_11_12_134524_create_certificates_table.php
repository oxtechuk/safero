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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('certificate_number')->unique()->index();
            $table->string('order_no')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->string('examination_address')->nullable();
            $table->date('thorough_examination_date');
            $table->date('next_examination_due_date');
            $table->string('reference_standard')->default('ASTM A6');

            $table->text('equipment_description');
            $table->string('equipment_id_range')->nullable();
            $table->string('date_of_manufacture')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('swl')->nullable();
            $table->integer('quantity')->default(1);

            $table->boolean('is_first_examination_after_installation')->default(false);
            $table->boolean('was_examination_within_6_months')->default(true);
            $table->boolean('has_equipment_installed_correctly')->default(true);
            $table->boolean('was_examination_within_12_months')->default(false);
            $table->boolean('in_accordance_with_scheme')->default(false);
            $table->boolean('after_exceptional_circumstances')->default(false);

            $table->text('defect_identification')->nullable();
            $table->boolean('is_defect_danger_to_process')->default(false);
            $table->text('repair_particulars')->nullable();
            $table->text('tests_carried_out')->nullable();
            $table->boolean('is_safe_to_operate')->default(true);

            $table->string('issuer_name')->default('Eng. Mohammed fathy');
            $table->string('authenticator_name')->default('Dr. Hany Nabil');
            $table->string('status')->default('issued');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
