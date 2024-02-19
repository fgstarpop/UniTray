<?php

declare(strict_types=1);

namespace App\Domain\Chapter\Models;

use App\Domain\Admin\Models\Admin;
use App\Domain\Model;
use App\Domain\Story\Models\Story;
use App\Support\Traits\MenuItemTrait;
use App\Support\Traits\Taxonable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Domain\Chapter\Models\Chapter.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $created_by
 * @property string $group
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Chapter\Models\Chapter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Chapter extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $connection ='dbnovel';
    protected $fillable = ['story_id', 'name', 'description', 'content', 'is_vip', 'status', 'price', 'view', 'link_other', 'mod_id', 'user_id', 'embed_link', 'order', 'timer', 'host','idhost','idchap','is_cn'];

    const ACTIVE = 1;
    const INACTIVE = 0;
    const STATUS = [
        self::ACTIVE => 'Đã xuất bản',
        self::INACTIVE => 'Chưa xuất bản'
    ];

    public static function boot()
    {
        parent::boot();
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
