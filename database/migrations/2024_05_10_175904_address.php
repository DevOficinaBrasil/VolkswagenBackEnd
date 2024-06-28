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
        Schema::create('state', function (Blueprint $table) {
            $table->id();
            $table->string('value')->index();
        });

        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('value')->index();
            $table->foreignId('state_id')->constrained(
                table: 'state'
            );
        });

        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('cep');
            $table->timestamps();
            $table->foreignId('city_id')->constrained(
                table: 'city'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state');
        Schema::dropIfExists('city');
        Schema::dropIfExists('address');
    }
};
