<div>
    @if(!empty($has_player) && $has_player == 1)
    <button class="btn notification position-relative {{ (new \Jenssegers\Agent\Agent())->isMobile() ? 'mx-1' : '' }}" data-bs-toggle="modal" data-bs-target="#chienbao"
    wire:poll.15000ms="online" >
        @if((new \Jenssegers\Agent\Agent())->isDesktop())
            <i class="fad fa-flag" style="color: gray; font-size: 17px;"  wire:click="read_chien_bao"></i>
        @endif
        @if((new \Jenssegers\Agent\Agent())->isMobile())
            <i class="far fa-flag-alt text-white" style="color: gray; font-size: 17px;"  wire:click="read_chien_bao"></i>
        @endif
        @if ($chien_bao > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notisss">{{ $chien_bao }}</span>
        @endif

    </button>
    @endif
    @if(currentUser()->user_vip == 0)
        <div id="earn-gifts-block" style="top: calc(100vh - 110px);left: 15px;z-index: 999;"
         class="block-click-for-gifts position-fixed">
            <button id="btn-earn-gifts" class="hidden" onclick="clickEarnGifts()">View ADS</button>
        </div>
    @endif
    <div class="earn-poll hidden" wire:poll.60000ms="showPopupEarn" wire:init="showPopupEarn"></div>
    <button class="hidden" id="btn-popup-earn-gift" data-bs-toggle="modal" data-bs-target="#earn-gift"></button>
</div>
