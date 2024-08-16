<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchased_places', function (Blueprint $table) {
            $table->boolean('paid')->nullable()->after('tournament_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_places', function (Blueprint $table) {
            $table->dropColumn('paid');
        });
    }
};