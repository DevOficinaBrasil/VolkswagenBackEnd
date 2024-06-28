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
        Schema::create('trainings_concessionaire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained(
                table: 'trainings'
            );
            $table->foreignId('concessionaire_id')->constrained(
                table: 'concessionaire'
            );
            $table->integer('vacancies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
