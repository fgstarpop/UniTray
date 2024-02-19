@if(!empty(currentUser()) && currentUser()->user_vip == 0)
    @php
        $currentTime = \Carbon\Carbon::now()->format('i');
        $canEarn = 0;
        if (($currentTime >= 0 && $currentTime <= 5) || ($currentTime >= 30 && $currentTime <= 35)) {
            if (\Carbon\Carbon::now()->format('i') < 30) {
                $currentTime = [
                    \Carbon\Carbon::now()->format('Y-m-d H:00:00'),
                    \Carbon\Carbon::now()->format('Y-m-d H:05:00')
                ];
            } else {
                $currentTime = [
                    \Carbon\Carbon::now()->format('Y-m-d H:30:00'),
                    \Carbon\Carbon::now()->format('Y-m-d H:35:00')
                ];
            }
            $giftAdsUser = \App\Domain\Ads\Models\GiftAdsUsers::where('user_id', currentUser()->id)
            ->whereBetween('created_at', $currentTime);
            if (!$giftAdsUser->exists()) {
                $canEarn = 1;
            }
        }
    @endphp
    <div class="ads-google-section position-relative">
        <div class="position-absolute {{ $canEarn ? '' : 'hidden'}}" id="earn-fade" style="width:100%;height:100%;top:0;left:0;z-index:999"></div>
        <!-- 1111 -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6402569697449690"
             data-ad-slot="9715784869"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
@elseif(empty(currentUser()))
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-6402569697449690"
         data-ad-slot="9715784869"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
@endif
