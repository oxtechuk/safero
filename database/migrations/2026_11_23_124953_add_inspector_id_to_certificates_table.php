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
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('inspector_id')->nullable()->after('issuer_name')->constrained('users')->nullOnDelete();
        });

        Schema::table('training_certificates', function (Blueprint $table) {
            $table->foreignId('instructor_id')->nullable()->after('instructor_name')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['inspector_id']);
            $table->dropColumn('inspector_id');
        });

        Schema::table('training_certificates', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropColumn('instructor_id');
        });
    }
};
