<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('jersey_number');
            $table->enum('position', ['setter','outside_hitter','middle_blocker','opposite','libero','defensive_specialist'])->nullable();
            $table->boolean('is_captain')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['team_id','jersey_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
