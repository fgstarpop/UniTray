<?php

namespace App\Domain\Post\Models;

use App\Domain\Admin\Models\Admin;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Domain\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $casts = [
        'related_posts' => 'array',
    ];

    protected $guarded = [];

    /**
     * @inheritDoc
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
        $this
            ->addMediaCollection('file')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
    }

    public function url()
    {
        return route('post.show', $this->slug);
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
