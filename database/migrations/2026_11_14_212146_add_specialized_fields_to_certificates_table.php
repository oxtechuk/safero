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
            $table->string('equipment_type')->default('general')->after('equipment_description')->index(); // crane, accessory, general
            $table->string('manufacturer')->nullable()->after('equipment_type');
            $table->string('model')->nullable()->after('manufacturer');
            $table->string('capacity')->nullable()->after('model');
            $table->string('chassis_no')->nullable()->after('capacity');
            $table->string('main_wire')->nullable()->after('chassis_no');
            $table->string('hook_block')->nullable()->after('main_wire');
            $table->string('load_test_cert_no')->nullable()->after('hook_block');
            $table->date('date_of_load_test')->nullable()->after('load_test_cert_no');
            $table->date('last_examination_date')->nullable()->after('date_of_load_test');
            $table->text('test_point')->nullable()->after('last_examination_date');
            $table->string('safety_factor')->nullable()->after('test_point');
            $table->string('length')->nullable()->after('safety_factor');
            $table->json('checklist_items')->nullable()->after('tests_carried_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'equipment_type',
                'manufacturer',
                'model',
                'capacity',
                'chassis_no',
                'main_wire',
                'hook_block',
                'load_test_cert_no',
                'date_of_load_test',
                'last_examination_date',
                'test_point',
                'safety_factor',
                'length',
                'checklist_items',
            ]);
        });
    }
};
