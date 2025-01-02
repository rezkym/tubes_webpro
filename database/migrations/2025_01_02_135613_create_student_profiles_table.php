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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes');
            $table->string('student_number')->unique();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->date('enrollment_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
