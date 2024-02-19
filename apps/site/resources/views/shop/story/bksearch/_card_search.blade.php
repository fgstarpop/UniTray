<div class="col-lg-2 col-md-2 col-sm-2 col-4">
    <div class="booksearch">
        <a class="nb cap" href="{{ route('story.show', $story) }}">
            <span class="d-block position-relative">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" class="lazyload">
                @if((new \Jenssegers\Agent\Agent())->isMobile())
                    <span class="count-chapter btn btn-danger position-absolute">{{ $story->count_chapters }}</span>
                @endif
            </span>
            <div>
                <b class="searchbooktitle">
                    {{ $story->name }}
                </b>
                {{--                <span class="searchbookauthor"> {!! $story->description !!}</span>--}}
                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                    <div class="info">
                        <span>{{ $story->chapters_view }} <i class="far fa-eye"></i></span>
                        <span>{{ $story->whishlist_count }} <i class="far fa-thumbs-up"></i></span>
                    </div>
                    @if($story->from)
                        <span class="searchtag" style="background:#2cb15b;">{{ $story->from }}</span>
                    @endif
                    @foreach(\App\Domain\Story\Models\Story::STATUS as $key => $status)
                        @if($story->status == $key)
                            <span class="searchtag" style="background:#0da0f0;">{{ $status }}</span>
                        @endif
                    @endforeach
                @endif
            </div>
        </a>
    </div>
</div>
