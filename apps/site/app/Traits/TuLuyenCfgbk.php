<?php

/**
 * User Agent details. user browser,OS,device,country
 * @link    https://www.html-code-generator.com/php/get-user-details
 */

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\TuLuyen\Model_charater;

use Illuminate\Support\Facades\Cache;
use function Symfony\Component\String\b;

class TuLuyenCfg
{
    protected $cfg;
    protected $cfg_class;
    protected $cfg_setting;
    protected $cfg_item;
    protected $cfg_gioitinh;
    protected $cfg_formula;
    protected $cfg_exp_re;
    protected $p_class;
    protected $p_gioitinh;
    protected $cfg_name_item_type;
    protected $cfg_rate_item_type;
    protected $cfg_rate_item_rare;
    protected $cfg_item_list;
    public $error;

    public function __construct()
    {
        $this->cfg = (currentUser() && currentUser()->user_vip) == 1 ? config('tuluyenvip') : config('tuluyen');
        $this->cfg_class = $this->cfg['class'];
        $this->cfg_setting = $this->cfg['setting'];
        $this->cfg_item = $this->cfg['item'];
        $this->cfg_gioitinh = $this->cfg['gioitinh'];
        $this->cfg_formula = $this->cfg['formula'];
        $this->cfg_exp_re = $this->cfg['exp_re'];
        $this->cfg_name_item_type = $this->cfg_setting['item_type'];
        $this->cfg_rate_item_type = $this->cfg_setting['item_rate'];
        $this->cfg_rate_item_rare = $this->cfg_setting['item_rare'];
        $this->cfg_item_list = $this->cfg_item['item_list'];
    }

    public function get_cfg()
    {
        return $this->cfg;
    }

    public function get_cfg_class()
    {
        return $this->cfg_class;
    }

    public function get_cfg_setting()
    {
        return $this->cfg_setting;
    }

    public function get_cfg_item()
    {
        return $this->cfg_item;
    }

    public function get_cfg_gioitinh()
    {
        return $this->cfg_gioitinh;
    }

    public function get_cfg_formula()
    {
        return $this->cfg_formula;
    }

    public function get_cfg_exp_re()
    {
        return $this->cfg_exp_re;
    }

    public function get_item_name($type, $id, $rare)
    {

        $config_item = $this->cfg['item']['item_list'];
        // dd($config_item);
        if (!Arr::exists($config_item, $type)) {
            return $this->error = 1;
        }
        if (!Arr::exists($config_item[$type], $id)) {
            return $this->error = 2;
        }
        $name2 = $this->cfg['setting']['rare'][$type][$rare]['name'];
        $item = $config_item[$type][$id];
        $name = Str::title("$name2 $item[name]");
        return $name;
    }

    public function get_item_color($type,  $rare)
    {



        $name2 = $this->cfg['setting']['rare'][$type][$rare]['color'];

        return $name2;
    }

    public function get_item_info($type, $id)
    {
        $config_item = $this->cfg['item']['item_list'];
        if (!Arr::exists($config_item, $type)) {
            return $this->error = 1;
        }
        if (!Arr::exists($config_item[$type], $id)) {
            return $this->error = 2;
        }
        $item = $config_item[$type][$id]['gioi_thieu'];
        return $item;
    }
    public function get_gioi_tinh($id)
    {
        if (!Arr::exists($this->cfg_gioitinh, $id)) {
            $this->error = 2;
            return false;
        }
        return $this->cfg_gioitinh[$id];
    }

    public function get_exp_re($lv)
    {
        if (!Arr::exists($this->cfg_exp_re, $lv)) {
            $this->error = 7;
            return false;
        }
        return $this->cfg_exp_re[$lv];
    }
    public function get_class($id)
    {

        if (!Arr::exists($this->cfg_class, $id)) {
            $this->error = 1;
            return false;
        }
        return $this->cfg_class[$id];
    }


    public function get_mp_name($class)
    {
        if (!$this->get_class($class)) {
            return false;
        }
        $name_lv = $this->cfg_class[$class];

        return $name_lv['name_mp'];
    }

    public function get_all_item_list()
    {
        if (!Arr::exists($this->cfg_item, 'item_list')) {
            $this->error = 14;
            return false;
        }
        foreach ($this->cfg_item['item_list'] as $type => $value) {

            $item_list[$type] = $value;
        }
        return $item_list;
    }
    public function get_is_type_random()
    {
        if (!Arr::exists($this->cfg_setting, 'is_type_random')) {
            $this->error = 15;
            return false;
        }
        return $this->cfg_item['is_type_random'];
    }
    public function get_lv_name($lv, $class)
    {
        if (!$this->get_class($class)) {
            return false;
        }
        $lv_1 = floor($lv / 10);
        // dd($lv_1);
        if ($lv % 10 <= 3) {
            $name_lv2 = "Sơ Kỳ";
        } elseif ($lv % 10 <= 6) {
            $name_lv2 = "Trung Kỳ";
        } else {
            $name_lv2 = "Hậu Kỳ";
        }
        if (!Arr::exists($this->cfg_class[$class], 'name_lv')) {
            $this->error = 4;
            return false;
        }

        // if (!Arr::exists($this->cfg_class[$class]['name_lv'], $lv_1)) {
        //     $this->error = 5;
        //     return false;
        // }
        // dd($lv,$lv_1);
        $name_lv = $this->cfg_class[$class]['name_lv'][$lv_1];

        return "$name_lv $name_lv2";
    }



    public function get_class_name($class)
    {
        if (!$this->get_class($class)) {

            return false;
        }
        if (!Arr::exists($this->cfg_class[$class], 'name_class')) {
            $this->error = 3;
            return 3;
        }
        return $this->cfg_class[$class]['name_class'];
    }


    public function get_formula($class)
    {
        if (!$this->get_class($class)) {
            return false;
        }
        if (!Arr::exists($this->cfg_formula, $class)) {
            $this->error = 10;
            return false;
        }
        return $this->cfg_formula[$class];
    }
    public function get_playerbase($class)

    {
        if (!$this->get_class($class)) {
            return false;
        }
        if (!Arr::exists($this->cfg_class[$class], 'base')) {
            $this->error = 6;
            return false;
        }
        return $this->cfg_class[$class]['base'];
    }

    public function check_setting($setting)
    {
        if (!Arr::exists($this->cfg_setting, $setting)) {
            return false;
        }
        return true;
    }


    public function get_img($class, $gioi_tinh)
    {
        if (!$this->get_class($class)) {
            return false;
        }
        if (!$this->get_gioi_tinh($gioi_tinh)) {
            return false;
        }

        if (!Arr::exists($this->cfg_class[$class], 'img')) {
            $this->error = 8;
            return false;
        }
        if (!Arr::exists($this->cfg_class[$class]['img'], $gioi_tinh)) {
            $this->error = 9;
            return false;
        }

        return $this->cfg_class[$class]['img'][$gioi_tinh];
    }


    public function random_rate($rare)
    {
        $random = mt_rand(1, 100);
        if ($random <= $rare) {
            return true;
        } else {
            return false;
        }
    }
    public function get_item_type_name($type)
    {

        return $this->cfg_name_item_type[$type];
    }
    public function get_item_rate_type()
    {
        return $this->cfg_rate_item_type;
    }
    public function get_item_rate_rare()
    {
        return $this->cfg_rate_item_rare;
    }
    public function get_item_random_with_type($type)
    {
        if (!Arr::exists($this->cfg_item['item_list'], $type)) {
            $this->error = 16;
            return false;
        }
        $item_list = $this->cfg_item['item_list'][$type];
        $return_array = array();
        foreach ($item_list as $key => $value) {
            // dd($key, $value);
            if ($value['is_random']) {

                $return_array[$key] = $value;
            }
        }
        return $return_array;
    }

    public function set_item_rate_in_type($list)
    {
        $array_list = array();
        foreach ($list as $key => $value) {
            $array_list[$key] = $value['rate'];
        }
        return $array_list;
    }
    public function random_weight($array)
    {
        // random item list with rate
        $total = 0;
        foreach ($array as $key => $value) {
            $total += $value;
        }
        $rand = mt_rand(1, $total);
        $i = 0;
        foreach ($array as $key => $value) {
            $i += $value;
            if ($rand <= $i) {
                return $key;
            }
        }
    }
    public function random_weight_count($array, $count = 1)
    {
        $array_return = [];
        for ($icount = 0; $icount < $count; $icount++) {
            $total = 0;
            foreach ($array as $key => $value) {
                $total += $value;
            }
            $rand = mt_rand(1, $total);
            $i = 0;
            foreach ($array as $key => $value) {
                $i += $value;
                if ($rand <= $i) {
                    $array_return[] = $key;
                    break;
                }
            }
            unset($array[$key]);
        }
        return $array_return;
    }
































    // debug error message
    public function check_error($int = null)
    {
        if (!$int) {
            $int = $this->error;
        } else {
            $this->error = $int;
        }
        if (is_int($int)) {
            switch ($int) {
                case 1:
                    return $this->write_error('Lỗi: Không tìm thấy hệ nhân vật');
                    break;
                case 2:
                    return $this->write_error('Lỗi: Không tìm thấy giới tính');
                    break;
                case 3:
                    return $this->write_error('Lỗi: Không có tên của class');
                    break;
                case 4:
                    return $this->write_error('Lỗi: Không có tên của level theo hệ');
                    break;
                case 5:
                    return $this->write_error('Lỗi: Không có tên của level theo cấp');
                    break;
                case 6:
                    return $this->write_error('Lỗi: Không có base của hệ');
                    break;
                case 7:
                    return $this->write_error('Lỗi: Không có exp_re của level');
                    break;
                case 8:
                    return $this->write_error('Lỗi: Không có img của hệ');
                    break;
                case 9:
                    return $this->write_error('Lỗi: Không có img của giới tính');
                    break;
                case 10:
                    return $this->write_error('Lỗi: Không có công thức của hệ');
                    break;
                case 11:
                    return $this->write_error('Lỗi: Không có nhân vật hoặc chưa tạo');
                    break;
                case 12:
                    return $this->write_error('Lỗi: Đã có nhân vật không được tạo nữa');
                    break;

                case 13:
                    return $this->write_error('Lỗi: + Điểm thất bại');
                    break;
                default:
                    return $this->write_error('Lỗi: Không xác định');
                    break;
            }
        }
        return false;
    }
    public function write_error($message)
    {
        $error = $this->error;
        $this->error = null;
        $this->error_message = $message;
        return ['error' => $error, 'message' => $message];
    }



    // public function
}
