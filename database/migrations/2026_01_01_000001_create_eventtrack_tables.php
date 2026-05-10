<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role + status + phone to Laravel's default users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->after('id');
            $table->string('last_name', 100)->after('first_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('profile_pic')->nullable();
            $table->enum('role', ['admin', 'organizer', 'attendee'])->default('attendee');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->string('color_hex', 7)->default('#1A56A0');
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('address');
            $table->string('city', 100)->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->unsignedInteger('max_capacity')->nullable();
            $table->boolean('is_free')->default(true);
            $table->decimal('fee_amount', 10, 2)->default(0.00);
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('registration_code', 20)->unique();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->unique(['event_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('time_in');
            $table->datetime('time_out')->nullable();
            $table->enum('scan_method', ['qr_scan', 'manual'])->default('qr_scan');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_code', 50)->unique();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 100);
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('categories');
    }
};
