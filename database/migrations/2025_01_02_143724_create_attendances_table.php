<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained('student_profiles');
            $table->foreignId('school_class_id')->constrained('school_classes');
            $table->foreignId('school_subject_id')->constrained('school_subjects');
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'sick', 'permitted']);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add composite unique index
            $table->unique(['student_profile_id', 'school_subject_id', 'date'], 
                         'attendance_unique_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
