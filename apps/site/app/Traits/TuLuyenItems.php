<?php

/**
 * User Agent details. user browser,OS,device,country
 * @link    https://www.html-code-generator.com/php/get-user-details
 */

namespace App\Traits;

use App\User;
use Carbon\Carbon;
use App\TuLuyen\Model_item;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\TuLuyen\players_charater;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class TuLuyenItems extends TuLuyenCfg
{

	protected $players;
	protected $items;




	public $check_player;
	function __construct($user_id)
	{
		parent::__construct($user_id);
		// $this->model_item = new Model_item();
		// return 1;
		$this->players = $user_id;
		// $this->get_player($this->user_id);
		// $this->players_details  = $this->players;
		// dd($this->players);
		// if (!$this->players) {
		//     $this->check_player = false;

		// }else{
		// $this->players_details = $this->get_details();
		//     $this->check_player = true;
		// }
	}
	/*
    get toàn bộ vật phẩm cho rơi ngẫu nhiên
*/


	public function create_item($type, $item_id, $rare, $option = null, $count = 1)
	{
		// dd($type, $item_id, $rare, $option , $count );
		// dd(empty($option));
		$config_item = $this->cfg_item_list;
		// dd($config_item);
		if (!Arr::exists($config_item, $type)) {
			return $this->error = 1;
		}
		if (!Arr::exists($config_item[$type], $item_id)) {
			return $this->error = 2;
		}

		$item = $config_item[$type][$item_id];
		// $user = User::find($this->user_id);
		$charater = $this->players;
		if (!$charater) {
			$this->error = 3;
			return false;
		}

		$item_return='';
		// dd($item);
		for ($i = 0; $i < $count; $i++) {
			switch ($type) {
				case 15:
					$array_item =   [
						'player_id' => $charater->id ?? $charater->get_player()->id,
						'player_create' => $charater->id ?? $charater->get_player()->id,
						'item_id' => $item_id,
						// 'name' => $this->get_item_name($type,$item_id,$rare),
						// 'info' =>$item['gioi_thieu'],
						'type' => 15,
						'rare' => $rare,
						'type_action' => $item['type_action'],
						'type_buff' => $item['type_buff'],
						'is_stack' => $item['is_stack'],
					];
					return $array_item;
					break;

				case 8:
					//create đan dược
					$item_return = $this->create_danduoc($charater, $type, $item_id, $rare, $item, $option);
					break;
				default:
					break;
			}
		}
		return $item_return;
	}

	public function create_danduoc($charater, $type, $item_id, $rare, $item, $option)
	{
		$array_item =   [
			'player_id' => $charater->id ?? $charater->get_player()->id,
			'player_create' => $charater->id ?? $charater->get_player()->id,
			'item_id' => $item_id,
			// 'name' => $this->get_item_name($type,$item_id,$rare),
			// 'info' =>$item['gioi_thieu'],
			'type' => $item['type'],
			'rare' => $rare,
			'type_action' => $item['type_action'],
			'type_buff' => $item['type_buff'],
			'is_stack' => $item['is_stack'],
		];

		$array_option = [];
		if (!empty($item['op_value']) && !empty($option)) {

			foreach ($option as $rare_op) {
				if (!Arr::exists($item['op_value'], $rare_op)) {
					return $this->error = 3;
				}
				$value_op = $item['op_value'][$rare_op];
				$value = rand($value_op['min'][$rare], $value_op['max'][$rare]);
				$array_option[$rare_op] = ['value' => $value, 'add_value' => $value];
			}
		}

		if ($item['is_stack']) {
			$find_item = Model_item::where('player_id', $charater->id ?? $charater->get_player()->id)
				->where('type', $type)
				->where('item_id', $item_id)
				->where('rare', $rare)
				->where('stack', '<', $item['max_stack'])->orderBy('updated_at', 'asc')->get();

			if ($find_item->isNotEmpty()) {

				if ($find_item->count() > 1) {
					$sumstack =  $find_item->sum('stack') + 1;
					if ($sumstack >= $item['max_stack'])
						foreach ($find_item as $key => $value) {
							if ($sumstack >= $item['max_stack']) {
								$sumstack -= $item['max_stack'];
								$value->stack = $item['max_stack'];
								$value->save();
								$array_item['id'] = $value->id;
								$item_return = $value;
							} elseif ($sumstack < $item['max_stack'] && $sumstack > 0) {
								$value->stack = $sumstack;
								$sumstack -= $sumstack;
								$value->save();
								$item_return = $value;
								$array_item['id'] = $value->id;
							} else {
								$value->delete();
							}
						}
					else {
						foreach ($find_item as $key => $value) {
							if ($sumstack > 0) {

								$value->stack = $sumstack;

								$value->save();
								$sumstack -= $sumstack;
								$array_item['id'] = $value->id;
								$item_return = $value;
							} else {
								$value->delete();
							}
						}
					}
				} else {

					$find_item[0]->stack += 1;
					$find_item[0]->save();
					$array_item['id'] = $find_item[0]->id;
					$item_return = $find_item;
				}
			} else {

				$item_return = $this->model_creat($array_item, $array_option);
				$array_item['id'] = $item_return->id;
			}
		} else {

			$item_return = $this->model_creat($array_item, $array_option);
			$array_item['id'] = $item_return->id;
		}

		$array_item['name'] =  $this->get_item_name($type, $item_id, $rare);
		$array_item['info'] =  $item['gioi_thieu'];
		return $array_item;
	}

	protected function model_creat($item, $option = null)
	{
		$creat =  Model_item::create($item);
		if (!empty($option) && $creat) {
			$creat->get_options()->attach($option);
		}
		// dd($creat);
		return $creat;
	}
	public function create_item_random($count = 1)
	{


		$item_random = [];
		for ($i = 0; $i < $count; $i++) {
			$item_type =  $this->random_weight($this->get_item_rate_type());
			// dd($item_type);
			if ($item_type == 15) {

				return $this->create_item($item_type, 1, 1);
			}
			// dd($item_type);
			$item_list = $this->get_item_random_with_type($item_type);

			$list_rate_random = $this->set_item_rate_in_type($item_list);
			$id_item = $this->random_weight($list_rate_random);
			$item_details =  $item_list[$id_item];
			$random_rare = $this->random_weight($item_details['rare']);
			// $rare_name = $this->cfg_setting['rare'][$item_type][$random_rare];

			$list_option = [];
			if (!$item_details['is_random_op']) {
				$options = $this->random_weight($item_details['op_bs']);
				$list_option[] = $options;
			} else {
				dd("random option");
			}

			// dd($item_type, $id_item, $random_rare, $list_option);
			$item_random = $this->create_item($item_type, $id_item, $random_rare, $list_option);
		}
		return $item_random;
	}

    public function createItemRandomWithRare($rare, $count = 1)
    {
        $item_random = [];
        for ($i = 0; $i < $count; $i++) {
            $item_type =  $this->random_weight($this->get_item_rate_type());
            // dd($item_type);
            if ($item_type == 15) {

                return $this->create_item($item_type, 1, $rare);
            }
            // dd($item_type);
            $item_list = $this->get_item_random_with_type($item_type);

            $list_rate_random = $this->set_item_rate_in_type($item_list);
            $id_item = $this->random_weight($list_rate_random);
            $item_details =  $item_list[$id_item];
            $random_rare = $this->random_weight($item_details['rare']);
            // $rare_name = $this->cfg_setting['rare'][$item_type][$random_rare];

            $list_option = [];
            if (!$item_details['is_random_op']) {
                $options = $this->random_weight($item_details['op_bs']);
                $list_option[] = $options;
            } else {
                dd("random option");
            }
            // dd($item_type, $id_item, $random_rare, $list_option);
            $item_random = $this->create_item($item_type, $id_item, $rare, $list_option);
        }
        return $item_random;
    }

	//function get collect
	public function get_collect()
	{

		return $this->create_item_random();
	}

    public function get_collect_with_rare($rare, $count = 1)
    {
        return $this->createItemRandomWithRare($rare, $count);
    }

	public function use_item($item_id, $multiple = false)
	{
		$players = $this->players;
		$item = $this->players->get_items()->where('id', $item_id)->first();

		if (!$item) {
			$this->error = 18;
			return false;
		}
		$name = '';

		if ($item->type_action == 1) {

			foreach ($item->get_options as $key => $value) {
				// dd($value->stat);
				$array_temp = [];
				if ($value->is_mul) {
					$array_temp['value'] = $value->op->value * 100;
				} else {
					$array_temp['value'] = $value->op->value;
				}
				if ($multiple == true) {
					switch ($value->stat) {
						case 'exp':
							$value_add = ($item->stack * $value->op->value) + round((($item->stack * $value->op->value) * $players->can_co / 100));
							break;
						default:
							$value_add =  ($item->stack * $value->op->value);
							break;
					}
					// $value_add =  ($item->stack * $value->op->value);
				} else {
					switch ($value->stat) {
						case 'exp':
							$value_add = $value->op->value + round(($value->op->value * $players->can_co / 100));
							break;
						default:
							$value_add =  $value->op->value;
							break;
					}
					// $value_add = $value->op->value;
				}

				if ($value->is_mp) {

					$array_temp['name'] = sprintf($value->name, $value_add, $this->get_mp_name($players->class));
				} else {
					$array_temp['name'] = sprintf($value->name, $value_add);
				}
				if ($multiple) {
					$players[$value->stat] += $value_add;
				} else {
					$players[$value->stat] += $value_add;
				}

				$name .= $array_temp['name'];
			}
			$players->save();
			if ($multiple == true) {
				$item->delete();
				return $name;
			}
			if ($item->stack > 1) {
				$item->stack -= 1;
				$item->save();
				return $name;
			} else {
				$item->delete();
				return $name;
			}
		}


		return $name;
	}

	public function use_itemn($item_id, $count = 1)
	{
		$players = $this->players;
		$name = '';
		if ($item_id->type_action == 1) {

			foreach ($item_id->get_options as $key => $value) {
				// dd($value->stat);
				$array_temp = [];
				if ($value->is_mul) {
					$array_temp['value'] = $value->op->value * 100;
				} else {
					$array_temp['value'] = $value->op->value;
				}

				switch ($value->stat) {
					case 'exp':
						$value_add = ($count * $value->op->value) + round((($count * $value->op->value) * $players->can_co / 100));
						break;
					default:
						$value_add =  ($count * $value->op->value);
						break;
				}


				if ($value->is_mp) {

					$array_temp['name'] = sprintf($value->name, $value_add, $this->get_mp_name($players->class));
				} else {
					$array_temp['name'] = sprintf($value->name, $value_add);
				}

				$players[$value->stat] += $value_add;


				$name .= $array_temp['name'];
			}
			$players->save();

			if ($item_id->stack > $count) {
				$item_id->stack -= $count;
				$item_id->save();
				return $name;
			} else {
				$item_id->delete();
				return $name;
			}
		}


		return $name;
	}

	public function use_item_type($item)
	{


		if ($item->type_action == 1) {
			$item = $this->add_value_options($item);
			if ($item->stack > 1) {
				$item->stack -= 1;
				$item->save();
				return true;
			} else {
				$item->delete();
				return true;
			}
		}
	}

	public function add_value_options($item)
	{
		$players = $this->players;

		foreach ($item->get_options as $key => $value) {
			// dd($value->stat);
			$players[$value->stat] += $value->op->value;
		}
		$players->save();
		return $item;
	}
	public function get_item($id)
	{
		return $this->players->get_items()->where('id', $id)->first();
	}
}
