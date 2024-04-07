<?php

declare(strict_types=1);

namespace App\Domain\Story\Models;

use App\Domain\Category\Models\Category;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\Model;
use App\Domain\Type\Models\Type;
use App\Enums\StoryState;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Domain\Activity\Readed;
use App\Traits\FullTextSearch;

/**
 * App\Domain\Story\Models\Story.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $created_by
 * @property string $group
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Story\Models\Chapter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Story extends Model implements HasMedia
{
    use InteractsWithMedia;
    use FullTextSearch;
    //guard column
    protected $guarded = [];
    protected $connection = 'dbnovel';
    // protected $casts = [
    //     "chapters_json" => "array",
    // ];
    const ACTIVE = 1;
    const INACTIVE = 0;
    const CONTINUE = 3;
    const FINISH = 4;
    const STATUS = [
        self::ACTIVE => 'Đã xuất bản',
        self::INACTIVE => 'Chưa xuất bản',
        self::CONTINUE => 'Còn tiếp',
        self::FINISH => 'Hoàn thành',
    ];
    const COMPLETE_FREE_ACTIVE = 1;
    const COMPLETE_FREE_INACTIVE = 0;
    const ORIGINS = [
        "https://b.faloo.com" => "Faloo",
        "https://www.uukanshu.com" => "Uukanshu",
        "https://book.qidian.com" => "Qidian",
        "https://www.bxwxorg.com" => "Bxwxorg",
        "https://fanqienovel.com" => "fanqie",
        "https://www.xinyushuwu.org" => 'xinyushuwu',
        "https://www.xinyushuwu.net" => 'xinyushuwu',
        "https://trxs.cc" => 'trxs'
    ];
    const SOURCE_GIANGTHE = 'giangthe';
    const SOURCE_TRUYENDICH = 'truyendich';
    const SOURCE_TRUYENSACTINH = 'truyensactinh';
    const SOURCE_TRUYENVIPFALOO = 'truyenvipfaloo';
    const SOURCE = [
        self::SOURCE_GIANGTHE,
        self::SOURCE_TRUYENDICH,
        self::SOURCE_TRUYENSACTINH,
        self::SOURCE_TRUYENVIPFALOO,
    ];
    const SORT_DESC = 1;
    const SORT_VIEW = 2;
    const SORT_UPDATE = 3;
    const SORT_FOLLOW = 4;
    const SORT_LIKE = 5;
    const SORT_VIP_REQUEST = 6;
    const SORT_VIEW_DAY = 7;
    const SORT_VIEW_WEEK = 8;
    const SORT_DONATE = 9;
    const SORT_NOMINATION = 10;
    const SORT = [
        self::SORT_DESC => 'Truyện mới',
        self::SORT_UPDATE => 'Chương mới',
        self::SORT_VIEW => 'Tổng lượt đọc',
        self::SORT_VIEW_WEEK => 'Lượt đọc tuần',
        self::SORT_VIEW_DAY => 'Lượt đọc ngày',
        self::SORT_FOLLOW => 'Lượt theo dõi',
        self::SORT_LIKE => 'Lượt thích',
        self::SORT_VIP_REQUEST => 'Lượt yêu cầu vip',
        self::SORT_DONATE => 'Thưởng truyện',
        self::SORT_NOMINATION => 'Đề cử tuần',
    ];
    protected $searchable = [
        'name',
    ];
    public static function boot()
    {
        parent::boot();
    }

    public function url()
    {
        return route('type.show', $this->id);
    }

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
            ->useFallbackUrl('/frontend/images/default.png');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('id','asc');
    }

    public function lastest_chapter()
    {
        return $this->hasMany(Chapter::class)->orderBy('updated_at','desc')->take(1);
    }

    public function chapters_vip()
    {
        return $this->hasMany(Chapter::class)->where('is_vip', 1);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'story_categories', 'stories_id', 'categories_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'story_types', 'stories_id', 'types_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function mod()
    {
        return $this->belongsTo(User::class, 'mod_id');
    }
    public function readed()
    {
        return $this->belongsToMany(User::class, 'readed', 'stories_id', 'user_id');
    }
    public function readedAll()
    {
        return $this->belongsTo(Readed::class, 'id', 'stories_id');
    }
    public function follow()
    {
        return $this->belongsToMany(User::class, 'follows', 'stories_id', 'user_id');
    }
    public function whishlist()
    {
        return $this->belongsToMany(User::class, 'whishlist', 'stories_id', 'user_id');
    }

    public function scopeVisible($query): void
    {
        $query->whereStatus(StoryState::Active);
    }

}
