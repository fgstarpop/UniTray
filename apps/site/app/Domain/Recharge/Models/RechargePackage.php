<?php

declare(strict_types=1);

namespace App\Domain\Recharge\Models;

use App\Domain\Model;
use Cknow\Money\MoneyCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Domain\Recharge\Models\RechargePackage.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $body
 * @property int $created_by
 * @property string $group
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargePackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RechargePackage extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $connection = 'dbuser';
    public static function boot()
    {
        parent::boot();
//        static::creating(function ($model) {
//            $model->user_id = currentAdmin()->id;
//        });
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
    }
}
