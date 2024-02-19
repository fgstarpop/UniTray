<?php

/**
 * User Agent details. user browser,OS,device,country
 * @link    https://www.html-code-generator.com/php/get-user-details
 */

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\TuLuyen\Model_charater;

class TuLuyenTraits extends TuLuyenCfg
{
	protected $p_class;
	protected $p_gioitinh;
	private $players;
	protected $user;

	protected $players_details;

	function __construct($user = null)
	{
		$this->user = $user;
		parent::__construct();
	}
	public function get_details()
	{

		$players = $this->players ? $this->players : $this->get_player();
		if (!$players) {

			return false;
		}

		$mp_name = $this->get_mp_name($players->class);
		$players = $this->check_level_up($players);
		$formula = $this->get_formula($players->class);
		$players_base = $this->get_playerbase($players->class);
		$array = [];
		$check = [];
		foreach ($formula as $type => $f) {
			foreach ($f as $key => $stat) {
				// dd($stat,$type);
				$array[$key] = round((($players[$type] + $players["sum_$type"]) / $stat['stat_point']) * $stat['value'], 4);

				switch ($key) {
					case 'max_hp':
						if ($players->hp+$players->sum_hp >= $players->max_hp + $players->sum_max_hp || $players->hp <= 0) {
							$array['hp'] = $array['max_hp'];
						}
						break;
					case 'max_mp':
						if ($players->mp+$players->sum_mp >= $players->max_mp + $players->sum_max_mp || $players->mp <= 0) {
							$array['mp'] = $array['max_mp'];
						}
						break;
					case 'hp_regen':
						$array['hp_regen'] += $players_base['hp_regen'];
						break;
					case 'mp_regen':
						$array['mp_regen'] += $players_base['mp_regen'];
						break;
				}
				if ($array[$key] == $players[$key]) {
					$check[$key] = true;
				} elseif ($array[$key] != $players[$key]) {
					$check[$key] = false;
				}
			}
		}
		// dd($array);

		$check_ok = collect($check)->search(false);
		// if ($players->point < 500) {
		//     $players->increment('point', 500);
		// }
		if ($check_ok) {
			$players->update($array);
		}

		$array2 = [
			// 'uuid' => $players->uuid,
			'class' => $players->class,
			'class_name' => $this->get_class_name($players->class),
			'img' => $this->get_img($players->class, $players->gioi_tinh),
			'lv' => $players->level,
			'lv_name' => $this->get_lv_name($players->level, $players->class),
			'exp' => $players->exp,
			'max_exp' => $players->max_exp,
			'percent_exp' => round(($players->exp / $players->max_exp) * 100),
			'hp' => $players->hp + $players->sum_hp,
			'max_hp' => $players->max_hp + $players->sum_max_hp,
			'percent_hp' => $players->max_hp + $players->sum_max_hp == 0 ? 0 : round((($players->hp + $players->sum_hp) / ($players->max_hp + $players->sum_max_hp)) * 100),
			'hp_regen' => $players->hp_regen + $players->sum_hp_regen,
			'mp' => $players->mp + $players->sum_mp,
			'max_mp' => $players->max_mp + $players->sum_max_mp,
			'percent_mp' => $players->max_mp + $players->sum_max_mp == 0 ? 0 : round((($players->mp + $players->sum_mp) / ($players->max_mp + $players->sum_max_mp)) * 100),
			'mp_regen' => $players->mp_regen + $players->sum_mp_regen,
			'mp_name' => $mp_name,
			'dodge' => $players->dodge + $players->sum_dodge,
			'crit' => $players->crit + $players->sum_crit,
			'crit_dmg' => $players->crit_dmg + $players->sum_crit_dmg,

			'atk' => $players->atk + $players->sum_atk,
			'def' => $players->def + $players->sum_def,
			'atk_speed' => $players->atk_speed + $players->sum_atk_speed,
			'can_co' => $players->can_co + $players->sum_can_co,
			'luk' => $players->luk + $players->sum_luk,
			'str' => $players->str + $players->sum_str,
			'agi' => $players->agi + $players->sum_agi,
			'vit' => $players->vit + $players->sum_vit,
			'ene' => $players->ene + $players->sum_ene,
			'point' => $players->point,
			'linh_thach' => $players->linh_thach,
			'chien_bao' => $players->chien_bao,
			'last_online' => $players->time_last_online,
			'is_gift' => $players->is_collect,
			'is_ban' => $players->is_banned,
			'is_lv_up' => $players->is_level_up,
			// 'reset' => $players->reset,
			// 'relife' => $players->relife,
		];

		return $array2;
	}






	public function create_charater($class, $gioitinh)
	{




		$check_player = $this->user->get_charaters()->first();
		if ($check_player) {
			$this->error = 12;
			return false;
		} else {
			$playerbase = $this->get_playerbase($class);
			if (!$playerbase) {
				return false;
			}
			if ($this->get_gioi_tinh($gioitinh) == false) {
				return false;
			}

			$exp = $this->cfg_exp_re[1];
			$time_now = Carbon::now();
			$formula = $this->get_formula($class);
			if (!$formula) {
				return false;
			}
			$insert = [
				'user_id' => $this->user->id,
				'class' => $class,
				'gioi_tinh' => $gioitinh,
				'exp' => 0,
				'level' => 1,
				'max_exp' => $exp,
				'str' => 0,
				'agi' => 0,
				'vit' => 0,
				'ene' => 0,
				'atk' => 0,
				'can_co' => $playerbase['can_co'],
				'def' => 0,
				'atk_speed' => 0,
				'crit' => 0,
				'crit_dmg' => 0,
				'dodge' => 0,
				'luk' => $playerbase['luk'],
				'max_hp' => 0,
				'max_mp' => 0,
				'time_next_collect' => $time_now->copy()->addMinutes(rand(1, 2)),
				'is_online' => true,
				'time_last_online' => $time_now,
			];

			$insert['hp'] = $insert['max_hp'];
			$insert['mp'] = $insert['max_mp'];
			$insert['total_point'] = $playerbase['str'] + $playerbase['agi'] + $playerbase['vit'] + $playerbase['ene'];
			$insert['point'] = $insert['total_point'];
			$insert['hp_regen'] = $playerbase['hp_regen'];
			$insert['mp_regen'] = $playerbase['mp_regen'];
			$player = Model_charater::create($insert);
			if (!$player) {
				return false;
			}
			$this->players = $player;
			return $player;
		}
	}






	public function add_stat($type, $s_point)
	{

		$players = $this->players ? $this->players : $this->get_player();
		if (!$players) {
			false;
		}
		if ($players->point < $s_point) {
			false;
		}
		$array = [];
		$players[$type] += $s_point;
		$array[$type] = $players[$type];

		$formula = $this->get_formula($players->class);

		// if()

		foreach ($formula[$type] as $key => $f) {

			$array[$key] = round(((($players[$type] + $players["sump_$type"]) / $f['stat_point']) * $f['value']), 4);

			$player_base = $this->get_playerbase($players->class);
			switch ($key) {
				case 'max_hp':
					if ($players->hp >= $players->max_hp || $players->hp <= 0) {
						$array['hp'] = $array['max_hp'];
					}
					break;
				case 'max_mp':
					if ($players->mp >= $players->max_mp || $players->mp <= 0) {
						$array['mp'] = $array['max_mp'];
					}
					break;
				case 'hp_regen':
					$array['hp_regen'] += $player_base['hp_regen'];
					break;
				case 'mp_regen':
					$array['mp_regen'] += $player_base['mp_regen'];
					break;
			}
		}

		$players->point -= $s_point;
		$array['point'] = $players->point;
		$check =  $this->players->update($array);
		if ($check) {
			return true;
		} else {
			$this->error = 13;
			return false;
		}
	}

	public function get_player()


	{

		$charater = $this->user->get_charaters()->first();
		// dd($charater);
		if (!$charater) {
			$this->error = 11;
			return false;
		}
		$this->players = $charater;
		$this->p_class = $charater->class;
		$this->p_gioitinh = $charater->gioi_tinh;
		$this->players = $charater;
		return $charater;
	}

	public function online()
	{

		$players = $this->players ? $this->players : $this->get_player();
		if (!$players) {
			return false;
		}
		$exp_base = array_key_exists('exp', $this->cfg_setting) ? $this->cfg_setting['exp'] :  1;
		$time_now = Carbon::now();
		$time_last = Carbon::parse($players->time_last_online);
		$time_collect = Carbon::parse($players->time_next_collect);
		$time_min_online = array_key_exists('min_online', $this->cfg_setting) ? $this->cfg_setting['min_online'] :  60;

		if ($time_last->addSeconds($time_min_online) <= $time_now) {
			$exp = $exp_base + round(($exp_base * ($players->can_co / 100)));
			$array = [
				'is_online' => true,
				'time_last_online' => $time_now,

				'time_online' => $this->players->time_online + $time_min_online,
			];
			if ($players->hp < ($players->max_hp + $players->sum_max_hp)) {
				$hp_max = $players->max_hp + $players->sum_max_hp;
				$hp_regen = $players->hp_regen + $players->sum_hp_regen;
				$hp = $players->hp + ( $hp_max*$hp_regen ) > $hp_max ? $hp_max : $players->hp + ( $hp_max*$hp_regen );
				$array['hp'] =$hp ;
			}
			if ($players->mp < ($players->max_mp + $players->sum_max_mp)) {
				$mp_max = $players->max_mp + $players->sum_max_mp;
				$mp_regen = $players->mp_regen + $players->sum_mp_regen;
				$mp = $players->mp + ( $mp_max*$mp_regen ) > $mp_max ? $mp_max : $players->mp + ( $mp_max*$mp_regen );
				$array['mp'] =	$mp;
			}
			$this->check_level_up();
			if (!$players->is_level_up) {
				$array['exp'] = $players->exp + $exp;
			}
			if ($time_collect <= $time_now && !$players->is_collect) {
				$array['is_collect'] = true;
			}
			$check = $players->update($array);
			if ($check) {
				return $players;
			} else {
				return false;
			}
		} else {
			return $players;
		}
	}
	public function check_level_up()
	{
		//player up level

		$players = $this->players ? $this->players : $this->get_player();
		if ($players->exp >= $players->max_exp && $players->level % 10 != 9) {
			$exp = $players->exp - $players->max_exp;
			$lv_re = $this->cfg_exp_re[$players->level + 1];
			$players_base = $this->get_playerbase($players->class);
			$players->increment('level', 1, [
				'exp' => $exp,
				'point' => $players->point + $players_base['lv_up_point'],
				'total_point' => $players->total_point + $players_base['lv_up_point'],
				'max_exp' => $lv_re,
			]);
			return $players;
		} elseif ($players->exp >= $players->max_exp && $players->level % 10 == 9 && $this->players->is_level_up == false) {
			$exp = $players->exp - $players->max_exp;
			$lv_re = $this->cfg_exp_re[$players->level + 1];
			$players_base = $this->get_playerbase($players->class);
			$players->update([
				'is_level_up' => true,
			]);
			return $players;
		}
		return $players;
	}




	public function update($array)
	{
		$players = $this->players ? $this->players : $this->get_player();
		if (!$players) {
			return false;
		}
		$check = $players->update($array);
		if ($check) {
			return $players;
		} else {
			return false;
		}
	}



	public function upgrade_lv()
	{

		$players = $this->players ? $this->players : $this->get_player();
		$this->check_level_up();
		if ($players->is_level_up) {
			$random = $this->players->can_co > 30 ? $this->players->can_co : 30;
			$random = $random > 70 ? 70 : $random;
			if ($this->random_rate($random)) {
				$exp = $players->exp - $players->max_exp;
				$lv_re = $this->cfg_exp_re[$players->level + 1];
				$players_base = $this->get_playerbase($players->class);
				$players->increment('level', 1, [
					'exp' => $exp,
					'point' => $players->point + $players_base['lv_up_point'],
					'total_point' => $players->total_point + $players_base['lv_up_point'],
					'max_exp' => $lv_re,
					'is_level_up' => false,
				]);
				return true;
			} else {
				$array_update = [];

				$array_update['can_co'] = $players->can_co > 1 ? $players->can_co - 1 : 1;
				$array_update['exp'] = $players->exp - round(($players->exp * 0.3));
				$array_update['is_level_up'] = false;
				$players->update($array_update);
				return false;
			}
		} else {
			return false;
		}
	}
	public function get_players_item()
	{
		$players = $this->players ? $this->players : $this->get_player();
		$players_item = $players;
		// dd($players_item);
		if (!$players_item) {
			return false;
		}
		// dd($players_item);
		$array = [];
		// foreach ($players_item as  $item) {
		foreach ($players_item->get_items()->orderBy('rare', 'desc')->orderBy('type')->orderby('item_id')->take(100)->get() as $key => $value) {
			$array[$value->id] = [
				'item_id' => $value->item_id,
				'item_type' => $value->type,
				'item_rare'      => $value->rare,
				'name' => $this->get_item_name($value->type, $value->item_id, $value->rare),
				'info' => $this->get_item_info($value->type, $value->item_id),
				'sl' => $value->stack,
				'color' => $this->get_item_color($value->type, $value->rare),
			];
			foreach ($value->get_options as $key => $option) {
				$array_temp = [];
				if ($option->is_mul) {
					$array_temp['value'] = $option->op->value * 100;
				} else {
					$array_temp['value'] = $option->op->value;
				}
				if ($option->is_mp) {
					$array_temp['name'] = sprintf($option->name, $array_temp['value'], $this->get_mp_name($players->class));
				} else {
					$array_temp['name'] = sprintf($option->name, $array_temp['value']);
				}
				$array[$value->id]['op'][] = $array_temp;
			}
		}
		// }

		return $array;
	}
	public function add_linh_thach($lt)
	{
		$players = $this->players ? $this->players : $this->get_player();
		$players->increment('linh_thach', $lt);
		return $players;
	}
	public function tuvi($value){
		$players = $this->players ? $this->players : $this->get_player();

		return $value + round(($value * ($players->can_co / 100)));

	}
	public function add_tuvi($value){
		$players = $this->players ? $this->players : $this->get_player();
		$exp = $this->tuvi($value);
		$players->increment('exp', $exp);

		return $exp;
	}
}
