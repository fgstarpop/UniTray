<?php

namespace App\Domain\Ads\Models;

use App\Domain\Model;
use App\User;

class GiftAdsUsers extends Model
{
    public $guarded = [];

    protected $table = 'gift_ads_users';
    protected $connection = 'dbuser';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function giftAds()
    {
        return $this->belongsTo(GiftAds::class, 'gift_ads_id');
    }
}
