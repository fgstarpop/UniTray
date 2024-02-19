<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayerItemsType extends Migration
{
    public function up()
    {
        Schema::create('players_items_type', function (Blueprint $table) {
            $table->id('id');
            $table->char('items_type_name',255);


        });
    }
    public function down()
    {
        Schema::dropIfExists('players_items_type');
    }
}
