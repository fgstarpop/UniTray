@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-6">
        <div class="cap bookthumb">
            <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <span class="position-relative d-inline-block position-relative" style="height: 184px">
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
            <span class="position-relative d-inline-block position-relative" style="height: 184px">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 184px !important;" class="lazyload" alt="{{ $story->name }}">
                <div class="book_detail">
                    <span> <i class="fa fa-eye" aria-hidden="true"></i>
                    @if ($story->view > 999 && $story->view <= 999999)
                        {{ intval($story->view / 1000) }}K
                        @elseif ($story->view > 1000000 && $story->view <= 99999999)
                        {{ round(($story->view / 1000000),2) }}M
                    @else
                        {{ $story->view }}
                    @endif</span>
                    <span><i class="fa fa-book" aria-hidden="true"></i>  {{ $story->count_chapters }}</span>
                </div>
            </span>
            <b>{{ ucfirst( $story->name) }} </b>
        </a>
    </div>
@endif
