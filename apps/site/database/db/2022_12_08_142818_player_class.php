<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayerClass extends Migration
{
    public function up()
    {
        Schema::create('players_base', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('base_class')->index();
            $table->tinyInteger('gioi_tinh')->index()->default(1);
            $table->char('class_img', 255);
            // $table->char('className', 255);
            $table->float('base_str',14,2,false);
            $table->float('base_agi',14,2,false);
            $table->float('base_vit',14,2,false);
            $table->float('base_ene',14,2,false);

            $table->float('base_hp', 14, 2, false)->default(0);
            $table->float('base_max_hp', 14, 2, true)->default(400);
            $table->float('base_hp_regen', 6, 4, false)->default(0.01);
            $table->float('base_mp', 14, 2, false)->default(200);
            $table->float('base_max_mp', 12, 2, true)->default(200);
            $table->float('base_mp_regen', 6, 4, false)->default(0.01);
            $table->float('base_can_co', 6, 4, true)->default(1);
            $table->float('base_atk', 14, 2, false)->default(20);
            $table->float('base_def', 14, 2, false)->default(5);
            $table->float('base_atk_speed', 6, 4, false)->default(0.5);
            $table->float('base_luk', 6, 4, false)->default(1);
            $table->float('base_crit', 6, 4, false)->default(0);
            $table->float('base_crit_dmg', 7, 4, false)->default(0);
            $table->float('base_dodge', 6, 4, false)->default(0);

            $table->float('lv_up_max_hp', 14, 2, false)->default(10);
            $table->float('lv_up_max_mp', 14, 2, false)->default(2);
            $table->float('lv_up_atk', 14, 2, false)->default(1);
            $table->float('lv_up_def', 14, 2, false)->default(0.1);
            $table->float('lv_up_atk_speed', 14, 2, false)->default(0);
            $table->float('lv_up_luk', 14, 2, false)->default(0);
            $table->float('lv_up_crit', 14, 2, false)->default(0);
            $table->float('lv_up_crit_dmg', 14, 2, false)->default(0);
            $table->float('lv_up_dodge', 14, 2, false)->default(0);
            $table->bigInteger('lv_up_point')->default(5);


        });
    }

    public function down()
    {
        Schema::dropIfExists('players_base');
    }
}
