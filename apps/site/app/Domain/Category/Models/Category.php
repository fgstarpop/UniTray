<?php

declare(strict_types=1);

namespace App\Domain\Category\Models;

use App\Domain\Admin\Models\Admin;
use App\Domain\Model;
use App\Support\Traits\MenuItemTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Domain\Category\Models\Category.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $created_by
 * @property string $group
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Category\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model implements HasMedia
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
