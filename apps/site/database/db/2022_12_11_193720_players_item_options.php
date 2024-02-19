<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayersItemOptions extends Migration
{
    public function up()
    {
        Schema::create('players_item_options', function (Blueprint $table) {
            $table->id();
            $table->char('option_name',255);
            $table->bigInteger('option_stat',false,true);


        });
    }
    public function down()
    {
        Schema::dropIfExists('players_item_options');
    }
}
