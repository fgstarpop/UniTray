<?php

declare(strict_types=1);

namespace App\Domain\Type\Models;

use App\Domain\Admin\Models\Admin;
use App\Domain\Model;
use App\Support\Traits\MenuItemTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Domain\Type\Models\Type.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $created_by
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Type\Models\Type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type extends Model implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    const ACTIVE = 1;

    public static function boot()
    {
        parent::boot();
//        static::creating(function ($model) {
//            $model->user_id = currentAdmin()->id;
//        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function url()
    {
        return route('type.show', $this->slug);
    }

//    public function user()
//    {
//        return $this->belongsTo(Admin::class, 'user_id', 'id');
//    }
}
