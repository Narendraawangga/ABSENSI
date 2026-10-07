<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('title');
            $table->text('description');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('category')->nullable();
            $table->enum('progress', ['Belum Dimulai', 'Sedang Dikerjakan', 'Selesai'])->default('Belum Dimulai');
            $table->text('constraints')->nullable();
            $table->text('learnings')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
