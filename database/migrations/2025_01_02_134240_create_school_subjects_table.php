<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for class-subject relationship
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_subject');
        Schema::dropIfExists('school_subjects');
    }
};