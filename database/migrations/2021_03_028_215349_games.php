<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Games extends Migration{
    
    public function up(){
        Schema::create('games', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->integer('id', true);
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('places');
            $table->text('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('games');
    }
}