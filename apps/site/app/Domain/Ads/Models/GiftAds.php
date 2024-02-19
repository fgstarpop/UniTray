<?php

namespace App\Domain\Ads\Models;

use App\Domain\Model;

class GiftAds extends Model
{
    public $guarded = [];

    protected $table = 'gift_ads';
    protected $connection = 'dbuser';
    public function giftAdsUsers()
    {
        $this->hasOne(GiftAdsUsers::class, 'gift_ads_id');
    }
}
