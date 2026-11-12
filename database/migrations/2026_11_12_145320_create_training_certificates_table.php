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
        Schema::create('training_certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('certificate_serial')->unique()->index();
            $table->foreignId('trainee_id')->nullable()->constrained('trainees')->nullOnDelete();
            $table->string('trainee_name')->index();
            $table->string('training_scope')->default('Safe Lifting & Rigging Operations');
            $table->string('program_title')->default('Defensive Driving');
            $table->integer('hours')->default(12);
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('instructor_name')->default('Dr. Hany Nabil');
            $table->string('training_manager_name')->default('Gehan Mahmoud');
            $table->text('accreditation_text')->nullable();
            $table->string('status')->default('issued')->index(); // issued, draft, revoked
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_certificates');
    }
};
