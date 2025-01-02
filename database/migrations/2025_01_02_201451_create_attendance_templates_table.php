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
        Schema::create('attendance_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes');
            $table->foreignId('school_subject_id')->constrained('school_subjects');
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles');
            $table->string('name');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint
            $table->unique(['school_class_id', 'school_subject_id', 'day', 'start_time'], 'unique_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_templates');
    }
};
