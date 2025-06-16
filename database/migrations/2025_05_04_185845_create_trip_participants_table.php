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
    Schema::create('trip_participants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->integer('age');
        $table->string('phone');
        $table->text('notes')->nullable();
        $table->string('status')->default('pending'); // статус участия
        $table->timestamps();
        
        $table->unique(['trip_id', 'user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_participants');
    }
};
