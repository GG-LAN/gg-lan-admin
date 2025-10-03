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
        Schema::create('faceit_accounts', function (Blueprint $table) {
            $table->id();

            $table->integer("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string("nickname");
            $table->string("player_id")->nullable();
            $table->string("steam_id_64");
            $table->json("games");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faceit_accounts');
    }
};
