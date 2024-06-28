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
        Schema::create('auto_repair', function (Blueprint $table) {
            $table->id();
            $table->string('CNPJ')->unique();
            $table->string('fantasy_name');
            $table->string('branch_activity');
            $table->string('auto_repair_phone');
            $table->timestamps();
            $table->foreignId('auto_repair_address')->constrained(
                table: 'address'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_repair');
    }
};
