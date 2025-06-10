<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица для хранения вопросов теста
        Schema::create('survival_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->json('options'); // Варианты ответов в формате JSON
            $table->integer('correct_option'); // Индекс правильного ответа
            $table->text('explanation')->nullable(); // Пояснение к правильному ответу
            $table->timestamps();
        });

        // Таблица для хранения результатов теста
        Schema::create('survival_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('score');
            $table->integer('total_questions');
            $table->float('percentage');
            $table->string('session_id')->nullable(); // Для неавторизованных пользователей
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survival_test_results');
        Schema::dropIfExists('survival_questions');
    }
};