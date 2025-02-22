<?php

use App\Models\Tournament;
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
        Schema::table('purchased_places', function (Blueprint $table) {
            $table->boolean('paid')->default(false)->change();
            $table->integer("tournament_price_id")->nullable()->change();
            $table->foreignIdFor(Tournament::class)->nullable()->after("user_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_places', function (Blueprint $table) {
            $table->integer("tournament_price_id")->change();
            $table->dropColumn([
                "paid",
                "tournament_id",
            ]);
        });
    }
};
