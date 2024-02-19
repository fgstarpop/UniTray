<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayersStasId extends Migration
{
    public function up()
    {
        Schema::create('players_stat_id', function (Blueprint $table) {
            $table->id();
            $table->char('stat_string',255);
            $table->char('stat_name',255);


        });
    }
    public function down()
    {
        Schema::dropIfExists('players_stat_id');
    }
}
