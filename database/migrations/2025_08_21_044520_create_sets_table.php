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
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('set_number');
            $table->unsignedTinyInteger('team_a_score')->default(0);
            $table->unsignedTinyInteger('team_b_score')->default(0);
            $table->foreignId('winner_team_id')->nullable()->constrained('teams');
            $table->boolean('is_completed')->default(false)->index();
            $table->timestamps();

            $table->unique(['match_id','set_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
