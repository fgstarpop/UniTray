<?php


declare(strict_types=1);

namespace App\Domain\Recharge\Models;

use App\Domain\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Domain\Recharge\Models\RechargePackage;
use App\User;
/**
 * App\Domain\Recharge\Models\RechargeTransaction.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property int $created_by
 * @property string $group
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Recharge\Models\RechargeTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RechargeTransaction extends Model implements HasMedia
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
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function package(){
        return $this->belongsTo(RechargePackage::class,'recharge_package_id');
    }
}
