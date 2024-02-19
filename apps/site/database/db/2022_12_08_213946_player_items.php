<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayerItems extends Migration
{
    public function up()
    {
        Schema::create('players_items', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->index();
            $table->tinyInteger('items_type', false, true);
            $table->tinyInteger('items_rare', false, true);
            $table->bigInteger('items_name');
            $table->char('items_img',255);
            $table->bigInteger('items_level')->default(1);
            $table->string('items_des');
            $table->boolean('item_is_stack')->default(false);
            $table->bigInteger('items_stack',false,false)->default(1);
            $table->tinyInteger('items_stack_max',false,true)->default(255);
            $table->integer('require_level')->default(0);
            $table->integer('require_str')->default(0);
            $table->integer('require_agi')->default(0);
            $table->integer('require_vit')->default(0);
            $table->integer('require_ene')->default(0);
            $table->integer('require_gioi_tinh')->default(0);
            $table->integer('require_class')->default(0);
            $table->boolean('items_is_equip')->default(false);
            $table->boolean('items_is_use')->default(true);
            $table->boolean('items_is_sell')->default(true);
            $table->boolean('items_is_quest')->default(false);
            $table->boolean('items_is_drop')->default(true);
            $table->boolean('items_is_trade')->default(false);
            $table->boolean('items_is_buff')->default(false);
            $table->boolean('items_is_random')->default(true);
            $table->integer('items_duration')->default(0);
            $table->float('items_price',14,2,true)->default(0);
            $table->float('items_price_sell',14,2,true)->default(0);
            $table->tinyInteger('items_option1',false,true)->default(0);

            $table->float('items_value1_max',16,4,true)->default(0);
            $table->float('items_value1_min',16,4,true)->default(0);

            $table->tinyInteger('items_option2',false,true)->default(0);

            $table->float('items_value2_max',16,4,true)->default(0);
            $table->float('items_value2_min',16,4,true)->default(0);

            $table->tinyInteger('items_option3',false,true)->default(0);

            $table->float('items_value3_max',16,4,true)->default(0);
            $table->float('items_value3_min',16,4,true)->default(0);

            $table->tinyInteger('items_option4',false,true)->default(0);

            $table->float('items_value4_max',16,4,true)->default(0);
            $table->float('items_value4_min',16,4,true)->default(0);

            $table->tinyInteger('items_option5',false,true)->default(0);

            $table->float('items_value5_max',16,4,true)->default(0);
            $table->float('items_value5_min',16,4,true)->default(0);

            $table->tinyInteger('items_option6',false,true)->default(0);

            $table->float('items_value6_max',16,4,true)->default(0);
            $table->float('items_value6_min',16,4,true)->default(0);

            $table->tinyInteger('items_option7',false,true)->default(0);

            $table->float('items_value7_max',16,4,true)->default(0);
            $table->float('items_value7_min',16,4,true)->default(0);

            $table->tinyInteger('items_option8',false,true)->default(0);
            $table->float('items_value8_max',16,4,true)->default(0);
            $table->float('items_value8_min',16,4,true)->default(0);
        });
    }
    public function down()
    {
        Schema::dropIfExists('players_items');
    }
}
