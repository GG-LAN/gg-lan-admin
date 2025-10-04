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
        Schema::table('faceit_accounts', function (Blueprint $table) {
            $table->dropColumn(["games"]);

            $table->string("steam_id_64")->nullable()->change();

            $table->integer("elo_cs2")->default(0)->after("steam_id_64");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faceit_accounts', function (Blueprint $table) {
            $table->dropColumn(["elo_cs2"]);

            $table->string("steam_id_64")->change();
            $table->json("games")->after("steam_id_64");
        });
    }
};
