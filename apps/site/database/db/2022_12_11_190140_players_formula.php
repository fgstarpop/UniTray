<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayersFormula extends Migration
{
    public function up()
    {
        Schema::create('players_formula', function (Blueprint $table) {
            $table->bigInteger('class_id');
            $table->bigInteger('stat_id');
            $table->float('str_mul',6,2);
            $table->float('agi_mul',6,2);
            $table->float('vit_mul',6,2);
            $table->float('ene_mul',6,2);
            $table->primary(['class_id','stat_id']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('players_formula');
    }
}
