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
        Schema::create('user_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->boolean('is_approved')->default(false); // Статус одобрения
            $table->string('image_path')->nullable(); // Путь к изображению (необязательное поле)
             $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('article_categories')
                  ->nullOnDelete(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_articles');
    }
};