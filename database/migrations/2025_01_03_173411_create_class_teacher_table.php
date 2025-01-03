<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['class_id', 'teacher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_teacher');
    }
};