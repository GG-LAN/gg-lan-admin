<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeamUser extends Migration{
    
    public function up(){
        Schema::create('team_user', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->id();
            $table->integer('user_id');
            $table->integer('team_id');
            $table->boolean('captain')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('team_user');
    }
}