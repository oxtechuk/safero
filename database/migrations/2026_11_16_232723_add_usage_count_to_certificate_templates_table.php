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
        if (! Schema::hasColumn('certificate_templates', 'usage_count')) {
            Schema::table('certificate_templates', function (Blueprint $table) {
                $table->unsignedInteger('usage_count')->default(0)->after('is_default');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('certificate_templates', 'usage_count')) {
            Schema::table('certificate_templates', function (Blueprint $table) {
                $table->dropColumn('usage_count');
            });
        }
    }
};
