<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID пользователя
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // ID курса
            $table->string('name'); // Имя пользователя
            $table->string('email'); // Email пользователя
            $table->string('phone')->nullable()->default(null);
            $table->integer('age'); // Возраст
            $table->boolean('attended_previous_courses')->default(false); // Посещали ли курсы
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Статус заявки
            $table-> text('message')->nullable();
            $table->foreignId('promotion_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
