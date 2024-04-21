<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tournaments extends Migration{
    
    public function up(){
        Schema::create('tournaments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->text('description');
            $table->integer('game_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('places');
            $table->string('cashprize')->nullable();
            $table->string('status');
            $table->text('image')->nullable();
            $table->string("type")->nullable();
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('tournaments');
    }
}