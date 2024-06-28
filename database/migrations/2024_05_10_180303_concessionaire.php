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
        Schema::create('concessionaire', function (Blueprint $table) {
            $table->id();
            $table->string('CNPJ')->unique();
            $table->string('fantasy_name');
            $table->string('manager_name');
            $table->string('certify_name');
            $table->string('email');
            $table->string('phone');
            $table->string('DN');
            $table->timestamps();
            $table->bigInteger('concessionaire_login_id')->unique();
            $table->foreignId('concessionaire_address')->constrained(
                table: 'address'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concessionaire');
    }
};
