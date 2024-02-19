<?php

namespace App\Domain\Banner\Models;

use App\Domain\Admin\Models\Admin;
use App\Domain\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];

    const SHOW = 1;
    const HIDE = 0;
    const BANNER_MODE = [
        self::SHOW => 'Hiển thị',
        self::HIDE => 'Ẩn',
    ];
    protected $connection = 'dbuser';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = currentAdmin()->id;
        });
        static::saved(function ($model) {
            Cache::forget('banners');
        });
        static::deleted(function ($model) {
            Cache::forget('banners');
        });
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('banner')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
