<?php

namespace App\Http\Livewire\Tuluyen;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\TuLuyenTraits;

class Createcharater extends Component
{
    // public $players;
    public $p_class;
    public $p_gioitinh;

    public $user_id;
    // public function mount()
    // {
    //     if (!auth()->guard('web')->check()) {
    //         flash()->error('Bạn cần đăng nhập để sử dụng tính năng này');
    //         return redirect()->route('home');
    //     }
    //     $user = auth()->guard('web')->user();
    //     $check_player = DB::table('players_charater')->where('user_id', '=', $user->id)->first();
    //     if ($check_player) {
    //         flash()->error('Bạn đã tạo nhân vật rồi');
    //         return redirect()->route('tuluyen.index');
    //     }
    // }


    public function savecharater()
    {
        // dd($this->user_id);



        $this->validate([
            'p_class' => 'required',
            'p_gioitinh' => 'required'
        ]);
        $tuluyen = new TuLuyenTraits(auth()->guard('web')->user());
        $check = $tuluyen->create_charater( $this->p_class,$this->p_gioitinh);
        if ($check) {
           $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Tạo nhân vật thành công',
                'text' => '',
            ]);
            return redirect()->route('tuluyen.index');
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Tạo nhân vật thất bại',
                'text' => '',
            ]);
            return redirect()->back();
        }

    }
    public function backurl()
    {
        return redirect()->route('home');
    }
    public function render()
    {


        $player_class = config('tuluyen.class');

        return view('livewire.tuluyen.createcharater', [
            'players' =>  $player_class

        ]);
    }
}
