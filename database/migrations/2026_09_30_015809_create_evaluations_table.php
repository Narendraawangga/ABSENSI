<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->integer('month');
            $table->integer('year');
            $table->integer('discipline_score')->default(0);
            $table->integer('attendance_score')->default(0);
            $table->integer('responsibility_score')->default(0);
            $table->integer('communication_score')->default(0);
            $table->integer('teamwork_score')->default(0);
            $table->integer('initiative_score')->default(0);
            $table->integer('technical_score')->default(0);
            $table->integer('completion_score')->default(0);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
