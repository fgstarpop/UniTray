<?php

namespace App\Http\Livewire\Tuluyen;

use Livewire\Component;

class Gift extends Component
{
	protected $listeners = ['giftcode'];
	public function giftcode($gift_code){
		dd($gift_code);
	}


    public function render()
    {
        return <<<'blade'
            <div>
                {{-- Care about people's approval and you will be their prisoner. --}}
            </div>
        blade;
    }
}
