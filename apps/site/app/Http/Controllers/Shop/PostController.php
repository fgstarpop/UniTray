<?php

namespace App\Http\Controllers\Shop;

use App\Domain\Post\Models\Post;
use App\Enums\PostState;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Spatie\SchemaOrg\Schema;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', PostState::Active)->latest()->paginate(10);
        return view('shop.post.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->status != PostState::Active) {
            abort(404);
        }
        $post->increment('view');
        return view('shop.post.show', compact('post'));
    }
}
