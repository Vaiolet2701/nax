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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('course_category_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('min_people')->nullable();
            $table->integer('max_people')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable(); // Может быть NULL
            $table->text('animals')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_name')->nullable(); 

            // Новые поля для повторных курсов
            $table->boolean('is_repeated')->default(false); // Является ли курс повторным
            $table->unsignedBigInteger('parent_course_id')->nullable()->comment('ID исходного курса, если это повтор');
            
            $table->boolean('is_active')->default(true); 
            $table->text('provided_equipment')->nullable();
            $table->text('required_equipment')->nullable(); 
            $table->timestamps();

            // Внешний ключ для категории
            $table->foreign('course_category_id')
                  ->references('id')
                  ->on('course_categories')
                  ->onDelete('cascade');

            // Внешний ключ для преподавателя с установкой NULL при удалении
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // При удалении преподавателя ставится NULL

            // Внешний ключ на родительский курс
            $table->foreign('parent_course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
