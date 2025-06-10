<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('course_user', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('course_id');
        
        $table->enum('status', [
            'pending',      // В ожидании
            'rejected',     // Отказан
            'in_progress',  // В процессе
            'completed'    // Завершен
        ])->default('pending');
        $table->boolean('attended')->nullable()->comment('Посетил ли курс');
$table->dateTime('checked_in_at')->nullable()->comment('Время прихода на курс');
$table->text('teacher_comment')->nullable()->comment('Комментарий преподавателя');
        $table->integer('progress')->default(0);
        $table->string('phone')->nullable();
        $table->integer('age')->nullable();
        $table->boolean('attended_previous_courses')->default(false);
        $table->text('message')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->dateTime('completed_at')->nullable();

        $table->decimal('original_price', 10, 2)->nullable();
        $table->decimal('discounted_price', 10, 2)->nullable();

        $table->timestamps();

        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
              
        $table->foreign('course_id')
              ->references('id')
              ->on('courses')
              ->onDelete('cascade');

        $table->unique(['user_id', 'course_id']);
    });
}

};