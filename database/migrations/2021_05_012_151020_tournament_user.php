<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TournamentUser extends Migration{
    
    public function up(){
        Schema::create('tournament_user', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('tournament_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('tournament_user');
    }
}