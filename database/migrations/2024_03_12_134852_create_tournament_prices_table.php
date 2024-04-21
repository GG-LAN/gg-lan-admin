<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_prices', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->integer("id", true);
            $table->string("name");
            $table->string("price_id");
            $table->integer('tournament_id');
            $table->enum("type", ["normal", "last_week"]);
            $table->boolean("active")->default(false);
            $table->timestamps();

            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_prices');
    }
}
