<?php

declare(strict_types=1);

namespace App;

use App\Domain\Admin\Models\Wallet;
use DateTimeInterface;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetNewPasswordNotification;
use App\TuLuyen\Model_charater;
use App\TuLuyen\Model_chienBao;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    protected $connection = 'dbuser';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password', 'status', 'is_vip', 'avatar', 'username','settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // protected $with = ['get_charaters'];
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small-thumb')
            ->fit(Manipulations::FIT_FILL, 100, 100)
            ->background('white');

        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_FILL, 209, 209)
            ->background('white');

        $this->addMediaConversion('main')
            ->fit(Manipulations::FIT_FILL, 500, 800)
            ->background('white');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('story')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetNewPasswordNotification($token));
    }

    public function get_charaters()
    {
        return $this->hasOne(Model_charater::class,'user_id','id');
    }

    public function get_chienbao()
    {
        return $this->hasOne(Model_chienBao::class,'user_id','id');
    }
    public function get_gold()

    {
        return $this->hasOne(Wallet::class);
    }
    // protected function serializeDate(DateTimeInterface $date)


    // {
    //        return $date->format('Y-m-d H:i:s');

    // }

}
