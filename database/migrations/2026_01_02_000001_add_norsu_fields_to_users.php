<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id', 20)->nullable()->unique()->after('last_name');
            $table->string('year_level', 20)->nullable()->after('phone');
            $table->string('college', 150)->nullable()->after('year_level');
            $table->string('program', 255)->nullable()->after('college');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'year_level', 'college', 'program']);
        });
    }
};
