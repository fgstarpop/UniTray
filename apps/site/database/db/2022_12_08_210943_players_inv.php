<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayersInv extends Migration
{
    public function up()
    {
        Schema::create('players_inv', function (Blueprint $table) {
            $table->bigInteger('user_id')->index();
            $table->uuid('uuid')->primary()->index();
            $table->bigInteger('item_type');
            $table->bigInteger('item_name');
            $table->bigInteger('item_level');
            $table->tinyInteger('item_rare', false, true);
            $table->char('item_img',255);
            $table->string('item_des');
            $table->boolean('item_is_stack')->default(false);
            $table->bigInteger('item_stack',false,false)->default(1);
            $table->tinyInteger('item_stack_max',false,true)->default(255);
            $table->integer('require_level')->default(0);
            $table->integer('require_str')->default(0);
            $table->integer('require_agi')->default(0);
            $table->integer('require_vit')->default(0);
            $table->integer('require_ene')->default(0);
            $table->integer('require_gioi_tinh')->default(0);
            $table->integer('require_class')->default(0);
            $table->boolean('item_is_equip')->default(false);
            $table->boolean('item_is_use')->default(true);
            $table->boolean('item_is_used')->default(false);
            $table->boolean('item_is_sell')->default(true);
            $table->boolean('item_is_drop')->default(true);
            $table->boolean('item_is_trade')->default(false);
            $table->boolean('item_is_buff')->default(false);
            $table->integer('item_duration')->default(0);
            $table->float('item_price',14,2,true)->default(0);
            $table->float('item_price_sell',14,2,true)->default(0);
            $table->tinyInteger('item_option1',false,true)->default(0);
            $table->float('item_value1',16,4,true)->default(0);
            $table->float('item_value1_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option2',false,true)->default(0);
            $table->float('item_value2',16,4,true)->default(0);
            $table->float('item_value2_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option3',false,true)->default(0);
            $table->float('item_value3',16,4,true)->default(0);
            $table->float('item_value3_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option4',false,true)->default(0);
            $table->float('item_value4',16,4,true)->default(0);
            $table->float('item_value4_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option5',false,true)->default(0);
            $table->float('item_value5',16,4,true)->default(0);
            $table->float('item_value5_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option6',false,true)->default(0);
            $table->float('item_value6',16,4,true)->default(0);
            $table->float('item_value6_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option7',false,true)->default(0);
            $table->float('item_value7',16,4,true)->default(0);
            $table->float('item_value7_destroy',16,4,true)->default(0);
            $table->tinyInteger('item_option8',false,true)->default(0);
            $table->float('item_value8',16,4,true)->default(0);
            $table->float('item_value8_destroy',16,4,true)->default(0);
        });
    }
    public function down()
    {
        Schema::dropIfExists('players_inv');
    }
}
