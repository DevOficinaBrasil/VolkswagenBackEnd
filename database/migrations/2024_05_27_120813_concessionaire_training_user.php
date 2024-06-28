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
        Schema::create('concessionaire_training_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concessionaire_id')->constrained(
                table: 'concessionaire'
            );
            $table->foreignId('common_user_id')->constrained(
                table: 'common_user'
            );
            $table->foreignId('trainings_id')->constrained(
                table: 'trainings'
            );
            $table->boolean('presence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concessionaire_training_user');
    }
};
