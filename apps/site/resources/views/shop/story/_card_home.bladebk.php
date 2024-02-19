@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
        <div class="cap bookthumb">
            <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <span class="position-relative d-inline-block position-relative">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 184px !important;" class="lazyload" alt="{{ $story->name }}">
                <span class="count-chapter btn btn-danger position-absolute" style="left: 0; bottom: 0">{{ $story->count_chapters }}</span>
            </span>
                <b>{{ ucfirst( $story->name) }} </b>
            </a>
        </div>
    </div>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <div class="cap bookthumb">
        <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <span class="position-relative d-inline-block position-relative">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 184px !important;" class="lazyload" alt="{{ $story->name }}">
                <span class="count-chapter btn btn-danger position-absolute" style="left: 0; bottom: 0">{{ $story->count_chapters }}</span>
            </span>
            <b>{{ ucfirst( $story->name) }} </b>
        </a>
    </div>
@endif
