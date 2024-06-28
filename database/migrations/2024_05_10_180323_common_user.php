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
        Schema::create('common_user', function (Blueprint $table) {
            $table->id();
            $table->string('document')->unique();
            $table->string('name');
            $table->string('gender');
            $table->date('born_at');
            $table->string('email');
            $table->string('phone');
            $table->timestamps();
            $table->bigInteger('user_login_id')->unique();
            $table->foreignId('common_user_address')->constrained(
                table: 'address'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_user');
    }
};
