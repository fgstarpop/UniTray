<?php

namespace App\Http\Livewire\Tuluyen;

use Carbon\Carbon;
use Livewire\Component;

use Illuminate\Support\Str;
use App\Traits\TuLuyenItems;
use App\Traits\TuLuyenTraits;
use App\TuLuyen\players_charater;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Domain\Admin\Models\WalletTransaction;

class Index extends Component
{
	public $players_user;
	public $players;
	public $add_mul_point;
	public $add_mul_type;
	public $item_list;
	public $gold;
	public $total_gold = 0;
	public $total_tuvi = 0;
	public $i_lt;
	protected $rules = [
		'add_mul_point' => 'required|integer|min:1',
		'add_mul_type' => 'required|string|in:str,agi,ene,vit|max:3',
		'i_lt' => 'required|integer|min:1'


	];
	protected $validationAttributes = [

		'add_mul_point' => 'số điểm',
		'add_mul_type' => 'chỉ số',
		'i_lt' => 'linh thạch'

	];

	protected $listeners = [
		'set_player' => 'set_player', 'dotpha', 'sudung_vatphamnn', 'sudung_vatpham', 'xoa_vatpham'
	];

	//used item for player control
	public function sudung_vatpham($id_item)
	{
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$item = new TuLuyenItems($players);

		$used = $item->use_item($id_item);


		if (!$item) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Sử dụng vật phẩm thất bại",
			]);
		}

		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		return $this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'title' => "Sử dụng vật phẩm thành công",
			'text' => $used,
		]);
	}
	public function sudung_vatphamn($id_item)
	{
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$item = new TuLuyenItems($players);

		$used = $item->use_item($id_item, true);


		if (!$item) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Sử dụng vật phẩm thất bại",
			]);
		}

		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		return $this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'title' => "Sử dụng vật phẩm thành công",
			'text' => $used,
		]);
	}
	public function sudung_vatphamnn($id_item, $count)
	{
		$validata = Validator::make(
			['count' => $count],
			[
				'count' => 'required|integer|min:1',
			],
			[
				'count.required' => 'Số lượng không được để trống',
				'count.integer' => 'Số lượng phải là số',
				'count.min' => 'Số lượng phải lớn hơn 0',
			],
			[
				'count' => 'số lượng',
			]


		);

		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$item = new TuLuyenItems($players);
		$getitem = $players->get_items()->where('id', $id_item)->first();

		if (!$getitem) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				'text' => "Không tìm thấy vật phẩm",
			]);
		}
		if ($count > $getitem->stack) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				'text' => "Số lượng vật phẩm không đủ",
			]);
		}

		$used = $item->use_itemn($getitem, $count);


		if (!$used) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Sử dụng vật phẩm thất bại",
			]);
		}

		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		return $this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'title' => "Sử dụng vật phẩm thành công",
			'text' => $used,
		]);
	}
	public function resetpoint()
	{
		//reset point player
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		if ($players->linh_thach < 50) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Bạn không đủ 50 linh thạch để tẩy điểm",
			]);
		}
		$players->str = 0;
		$players->agi = 0;
		$players->ene = 0;
		$players->vit = 0;
		$players->point = $players->total_point;
		$players->linh_thach = $players->linh_thach - 10;
		$players->save();
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		$this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'text' => "Đã tẩy điểm thành công",
		]);
	}
	public function tangcancot()
	{
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$can_cot_old = $players->can_co + $players->sum_can_co;
		if ($players->linh_thach < 10) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Bạn không đủ 10 linh thạch để tăng căn cốt",
			]);
		}
		if ($players->can_co + $players->sum_can_co >= 100) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Căn cốt đã đạt 100%",
			]);
		}
		$can_cot = $tuluyen->random_weight(
			[
				3 => 80,
				4 => 8,
				5 => 1,
				6 => 1
			]
		);
		$con_cot_add = $players->can_co + $players->sum_can_co + $can_cot > 100 ? (100 - ($players->sum_can_co)) : $players->can_co + $can_cot;
		$players->can_co = $con_cot_add;
		$players->linh_thach = $players->linh_thach - 10;
		$players->save();
		$can_cot_new = $players->can_co + $players->sum_can_co;
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		$this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'text' => "Bạn đã tăng căn cốt từ $can_cot_old lên $can_cot_new",
		]);
	}

	public function xoa_vatpham($id)
	{
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$items = new TuLuyenItems($players);

		$item = $items->get_item($id);
		if ($item->player_id != $players->id) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Vật phẩm không tồn tại",
			]);
		}
		$item->delete();
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		$this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'text' => "Đã xóa vật phẩm",
		]);
	}
	public function tangkhivan()
	{
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);
		$players = $tuluyen->get_player();
		$khivan_old = $players->luk + $players->sum_luk;
		if ($players->linh_thach < 10) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Bạn không đủ 10 linh thạch để tăng khí vận",
			]);
		}
		if ($players->luk + $players->sum_luk >= 100) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Khí vận đã đạt 100%",
			]);
		}
		$khi_van = $tuluyen->random_weight(
			[
				5 => 80,
				6 => 16,
				7 => 1,
				8 => 1,
				9 => 1,
				10 => 1
			]
		);
		$khi_van_add = $players->luk + $players->sum_luk + $khi_van > 100 ? (100 - ($players->sum_luk)) : $players->luk + $khi_van;
		$players->luk = $khi_van_add;
		$players->linh_thach = $players->linh_thach - 10;
		$players->save();
		$khivan_new = $players->luk + $players->sum_luk;
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		$this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			// 'text' => 'Đã thêm '.$point.' điểm thất bại',
			'text' => "Bạn đã tăng khí vận từ $khivan_old lên $khivan_new",
		]);
	}

	public function dotpha()
	{
		$tuluyen = new TuLuyenTraits(auth()->guard('web')->user());
		$players = $tuluyen->get_player();
		if ($players->linh_thach < 50) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Bạn không đủ 50 linh thạch để đột phá",
			]);
		}
		$players->linh_thach = $players->linh_thach - 50;
		$players->save();

		$player = $tuluyen->upgrade_lv();
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
		if (!$player) {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Bạn bị tâm ma quấy phá nên đột phá thất tại, tổn thất một lượng lớn tu vi. Căn cốt giảm 1 điểm",
			]);
		} else {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'success',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'text' => "Đột phá thành công, bạn đã lên cấp độ " . $this->players['lv_name'],
			]);
		}
		$player = $tuluyen->upgrade_lv();
		$this->players = $tuluyen->get_details();
		$this->item_list = $tuluyen->get_players_item();
	}
	public function doilinhthach()
	{
		$valida = Validator::make(

			[
				'i_lt' =>  $this->i_lt,
			],
			['i_lt' => 'required|integer|min:1'],
			[
				'i_lt.required' => 'Bạn chưa nhập số linh thạch',
				'i_lt.integer' => 'Số linh thạch phải là số nguyên',
				'i_lt.min' => 'Số linh thạch phải lớn hơn 0',
			],
			['i_lt' => 'linh thạch']

		)->validate();
		$user = auth()->guard('web')->user();
		$tuluyen = new TuLuyenTraits($user);

		$gold = $user->get_gold->gold;
		$total = $this->i_lt * 50;
		if ($total > $gold) {
			return $this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				'text' => "Bạn không đủ vàng để đổi linh thạch",
			]);
		}

		try {
			DB::transaction(function () use ($user, $tuluyen,$total) {
				$user->get_gold->gold -= $total;
				$user->get_gold->save();

				$nowtime = Carbon::now();

				WalletTransaction::create([
					'transaction_id'    => Str::uuid(),
					'user_id'           => $user->id,
					'change_type'       => 1,
					'transaction_type'  => 7,
					'created_at'        => $nowtime,
					'gold'              => $total,
					'yuan'              => 0,
					'gold_balance'      => $user->get_gold->gold,
					'yuan_balance'      => $user->get_gold->silver,
				]);
				$tuluyen->add_linh_thach($this->i_lt);

			});
		} catch (\Exception $e) {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				'title' => 'Đổi linh thạch thất bại',
				'text' => "Quá trình đổi thất bại, sẽ không trừ vàng của bạn.",
			]);
		}



		$this->players = $tuluyen->get_details();
		$this->gold = $user->get_gold->gold;
		if($total >= 1000){
			if($user->user_vip == 1){
                $tuvi = (floor ($total / 1000)*50) + (((floor ($total / 1000)*50)*10)/100);
            }else{
                $tuvi = floor ($total / 1000)*50;
            }
			$exp = $tuluyen->add_tuvi($tuvi);
		}else{
			$exp = 0;
		}
		$this->dispatchBrowserEvent('swal:modal', [
			'icon' => 'success',
			'title' => 'Đổi linh thạch thành công',
			'text' => "Bạn đổi $total vàng thành $this->i_lt linh thạch. Bạn được nhận được $exp tu vi. Tu vi nhận được dựa trên căn cốt.",
		]);


		$this->i_lt = 0;
		$this->total_gold = 0;
	}
	public function set_player($player, $item)
	{
		// dd($player);
		$this->players = $player;
		$this->item_list = $item;
	}

	public function updated($propertyName)

	{
        $user = auth()->guard('web')->user();
		$this->validateOnly($propertyName);
		if ($propertyName == 'i_lt') {
			if($user->user_vip == 1){
                $tuvi = ($this->i_lt * 50 >= 1000 ? floor(($this->i_lt *50)/ 1000) * 50 : 0) + (($this->i_lt * 50 >= 1000 ? floor(($this->i_lt *50)/ 1000) * 50 : 0)*10)/100;
            }else{
                $tuvi = $this->i_lt * 50 >= 1000 ? floor(($this->i_lt *50)/ 1000) * 50 : 0;
            }
			$this->total_tuvi = $tuvi;
			$this->total_gold = $this->i_lt * 50;
		}
	}
	public function addpoint($point, $type)
	{


		$tuluyen = new TuLuyenTraits(auth()->guard('web')->user());
		$this->players = $tuluyen->get_details();

		if ($this->players['point'] < $point) {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Không đủ điểm cộng',
				'title' => 'Không đủ điểm cộng',
			]);
			return;
		}
		$tuluyen->add_stat($type, $point);
		$this->players = $tuluyen->get_details();


		if (!$this->players) {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'error',
				// 'text' => 'Đã thêm '.$point.' điểm thất bại',
				'title' => "Đã thêm $point điểm thất bại",
			]);
		} else {
			$this->dispatchBrowserEvent('swal:modal', [
				'icon' => 'success',
				// 'text' => 'Đã thêm '.$point.' điểm thành công',
				'title' => "Đã thêm $point điểm thành công",
			]);
		}
		$this->item_list = $tuluyen->get_players_item();
	}

	public function addpointmul()
	{
		// $this->validate();
		$this->addpoint($this->add_mul_point, $this->add_mul_type);
	}


	public function render()
	{

		return view('livewire.tuluyen.index');
	}
}
