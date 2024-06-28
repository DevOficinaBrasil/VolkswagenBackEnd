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
        Schema::create('auto_repair_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('common_user')->constrained(
                table: 'common_user'
            );
            $table->foreignId('auto_repair_id')->constrained(
                table: 'auto_repair'
            );
            $table->string('role');
            $table->timestamps();
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
