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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_a_id')->constrained('teams');
            $table->foreignId('team_b_id')->constrained('teams');
            $table->dateTime('match_date')->nullable()->index();
            $table->string('venue')->nullable();
            $table->enum('status', ['scheduled','live','completed','cancelled'])->default('scheduled')->index();
            $table->foreignId('winner_team_id')->nullable()->constrained('teams');
            $table->unsignedTinyInteger('team_a_sets_won')->default(0);
            $table->unsignedTinyInteger('team_b_sets_won')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
