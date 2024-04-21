<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Teams extends Migration{
    
    public function up(){
        Schema::create('teams', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->integer('id', true);
            $table->string("name");
            $table->text("description")->nullable();
            $table->text('image')->nullable();
            $table->integer('tournament_id');
            $table->enum('registration_state', ['not_full', 'pending', 'registered'])->default('not_full');
            $table->timestamp("registration_state_updated_at")->nullable();
            $table->timestamps();

            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('teams');
    }
}