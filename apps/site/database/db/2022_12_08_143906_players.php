<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Players extends Migration
{
    public function up()
    {
        Schema::create('players_charater', function (Blueprint $table) {
            //player tables
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->bigInteger('user_id')->unique()->index();
            $table->tinyInteger('class', false, true);
            $table->tinyInteger('gioi_tinh')->default(1);
            $table->bigInteger('exp')->default(0);
            $table->bigInteger('max_exp', false, true)->default(0);
            $table->bigInteger('level', false, true)->default(1);
            $table->integer('level_ruong', false, true)->default(1);
            $table->float('str',14,2,false);
            $table->float('sum_str',14,2,false)->default(0);
            $table->float('agi',14,2,false);
            $table->float('sum_agi',14,2,false)->default(0);
            $table->float('vit',14,2,false);
            $table->float('sum_vit',14,2,false)->default(0);
            $table->float('ene',14,2,false);
            $table->float('sum_ene',14,2,false)->default(0);
            $table->bigInteger('point')->default(0);
            $table->bigInteger('total_point')->default(0);
            $table->bigInteger('pk')->default(0);
            $table->float('hp', 14, 2, false);
            $table->float('max_hp', 14, 2, true);
            $table->float('sum_max_hp', 14, 2, true)->default(0);
            $table->float('hp_regen', 6, 4, false);
            $table->float('sum_hp_regen', 6, 4, false)->default(0);
            $table->float('mp', 14, 2, false);
            $table->float('max_mp', 14, 2, true);
            $table->float('sum_max_mp', 14, 2, true)->default(0);
            $table->float('mp_regen', 6, 4, false);
            $table->float('sum_mp_regen', 6, 4, false)->default(0);
            $table->float('can_co', 7, 4, true);
            $table->float('sum_can_co', 7, 4, true)->default(0);
            $table->float('atk', 14, 2, false);
            $table->float('sum_atk', 14, 2, false)->default(0);
            $table->float('def', 14, 2, false);
            $table->float('sum_def', 14, 2, false)->default(0);
            $table->float('atk_speed', 6, 4, false);
            $table->float('sum_atk_speed', 6, 4, false)->default(0);
            $table->float('luk', 7, 4, false);
            $table->float('sum_luk', 7, 4, false)->default(0);
            $table->float('crit', 6, 4, false);
            $table->float('sum_crit', 6, 4, false)->default(0);
            $table->float('crit_dmg', 7, 4, false);
            $table->float('sum_crit_dmg', 7, 4, false)->default(0);
            $table->float('dodge', 6, 4, false);
            $table->float('sum_dodge', 6, 4, false)->default(0);
            $table->float('linh_thach', 16, 2, false)->default(0);
            $table->boolean('is_online')->default(false)->index('is_online');
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_ruong')->default(false);
            $table->boolean('is_collect')->default(false);
            $table->boolean('is_vip')->default(false);
            $table->dateTime('vip_time')->nullable();
            $table->dateTime('ban_time')->nullable();
            $table->dateTime('last_collect')->nullable();
            $table->dateTime('next_collect')->nullable();
            $table->integer('ruong_slot',false,true)->default(0);
            $table->timestamps();
            $table->bigInteger('time_online')->nullable();
            $table->dateTime('last_online')->nullable();
            $table->json('others')->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('players_charater');
    }
}
