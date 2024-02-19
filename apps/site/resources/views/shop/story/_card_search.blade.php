@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-3" style="margin-bottom: 50px">
        <div class="cap bookthumb">
            <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <div style="display:flex">
                <div class="position-relative d-inline-block position-relative">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 110px !important; width:82px !important; border-radius: 5px" class="lazyload" alt="{{ $story->name }}">
                <!-- <span class="count-chapter btn btn-danger position-absolute" style="left: 0; bottom: 0">{{ $story->count_chapters }}</span> -->

                </div>
                <div style="margin-left: 13px">
                    <span style="display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; -webkit-line-clamp: 2; font-size: 14.5px; font-weight: 650; height: 40px">
                        {{ ucfirst( $story->name) }} 
                    </span>
                    <div style="margin-top: 14px; font-size: 14px ; font-weight: 500">
                        @foreach($story['categories'] as $cate)
                            <span style="color: #0C9A00; margin-right: 3px"># {{ $cate->name}}</span>
                        @endforeach
                            <span style="color: #0C9A00; margin-right: 3px"># {{ $story->from}}</span>
                    </div>
                    <div style="margin-top: 10px; display: flex;">
                        <div style="display: flex; align-items: center; color: #878787">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                            <span style="margin-left: 5px; font-size: 14px ; font-weight: 500">
                            <!-- {{ $story->view }} -->
                            @if ($story->view > 999 && $story->view <= 999999)
                        {{ round(($story->view / 1000) ,2) }}K
                        @elseif ($story->view > 1000000 && $story->view <= 99999999)
                        {{ round(($story->view / 1000000),2) }}M
                    @else
                        {{ $story->view }}
                    @endif
                </span>
                        </div>
                        <div style="display: flex; align-items: center; color: #878787; margin-left: 10px">
                            <svg width="11" height="13" viewBox="0 0 11 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.66667 7.16667C7.66667 6.89052 7.44281 6.66667 7.16667 6.66667H3.16667C2.89052 6.66667 2.66667 6.89052 2.66667 7.16667C2.66667 7.44281 2.89052 7.66667 3.16667 7.66667H7.16667C7.44281 7.66667 7.66667 7.44281 7.66667 7.16667Z" fill="#878787"/>
                                <path d="M7.66667 9.83333C7.66667 9.55719 7.44281 9.33333 7.16667 9.33333H3.16667C2.89052 9.33333 2.66667 9.55719 2.66667 9.83333C2.66667 10.1095 2.89052 10.3333 3.16667 10.3333H7.16667C7.44281 10.3333 7.66667 10.1095 7.66667 9.83333Z" fill="#878787"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.83333 0C0.820811 0 0 0.820811 0 1.83333V11.1667C0 12.1792 0.820811 13 1.83333 13H8.5C9.51252 13 10.3333 12.1792 10.3333 11.1667V3.81177C10.3333 3.55792 10.2505 3.311 10.0975 3.10847L8.09899 0.463361C7.87849 0.171532 7.5339 0 7.16814 0H1.83333ZM1 1.83333C1 1.3731 1.3731 1 1.83333 1H6.66667V3.93136C6.66667 4.20751 6.89052 4.43136 7.16667 4.43136H9.33333V11.1667C9.33333 11.6269 8.96024 12 8.5 12H1.83333C1.3731 12 1 11.6269 1 11.1667V1.83333Z" fill="#878787"/>
                            </svg>

                            <span style="margin-left: 5px; font-size: 14px ; font-weight: 500">{{ $story->count_chapters }}</span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <!-- <div class="cap bookthumb">
        <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <div style="display: flex">
                <div class="position-relative d-inline-block position-relative">
                    <img style="width: 75px; height: 95px" data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 184px !important;" class="lazyload" alt="{{ $story->name }}">
                </div>
                <div>{{ ucfirst( $story->name) }} </div>
            </div>
        </a>
    </div> -->

    <div class="cap bookthumb" style="margin-top: 20px">
            <a href="{{ route('story.show', $story->id) }}" class="box-card-story">
            <div style="display:flex">
                <div class="position-relative d-inline-block position-relative">
                <img data-src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}" style="height: 110px !important; width:82px !important; border-radius: 5px" class="lazyload" alt="{{ $story->name }}">
                <!-- <span class="count-chapter btn btn-danger position-absolute" style="left: 0; bottom: 0">{{ $story->count_chapters }}</span> -->

                </div>
                <div style="margin-left: 13px">
                    <span style="display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; -webkit-line-clamp: 2; font-size: 14.5px; font-weight: 650; height: 40px">
                        {{ ucfirst( $story->name) }} 
                    </span>
                    <div style="margin-top: 14px; font-size: 14px ; font-weight: 500">
                        @foreach($story['categories'] as $cate)
                            <span style="color: #0C9A00; margin-right: 3px"># {{ $cate->name}}</span>
                        @endforeach
                            <span style="color: #0C9A00; margin-right: 3px"># {{ $story->from}}</span>
                    </div>
                    <div style="margin-top: 10px; display: flex;">
                        <div style="display: flex; align-items: center; color: #878787">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                            <span style="margin-left: 5px; font-size: 14px ; font-weight: 500">
                            <!-- {{ $story->view }} -->
                            @if ($story->view > 999 && $story->view <= 999999)
                        {{ round(($story->view / 1000) ,2) }}K
                        @elseif ($story->view > 1000000 && $story->view <= 99999999)
                        {{ round(($story->view / 1000000),2) }}M
                    @else
                        {{ $story->view }}
                    @endif
                </span>
                        </div>
                        <div style="display: flex; align-items: center; color: #878787; margin-left: 10px">
                            <svg width="11" height="13" viewBox="0 0 11 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.66667 7.16667C7.66667 6.89052 7.44281 6.66667 7.16667 6.66667H3.16667C2.89052 6.66667 2.66667 6.89052 2.66667 7.16667C2.66667 7.44281 2.89052 7.66667 3.16667 7.66667H7.16667C7.44281 7.66667 7.66667 7.44281 7.66667 7.16667Z" fill="#878787"/>
                                <path d="M7.66667 9.83333C7.66667 9.55719 7.44281 9.33333 7.16667 9.33333H3.16667C2.89052 9.33333 2.66667 9.55719 2.66667 9.83333C2.66667 10.1095 2.89052 10.3333 3.16667 10.3333H7.16667C7.44281 10.3333 7.66667 10.1095 7.66667 9.83333Z" fill="#878787"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.83333 0C0.820811 0 0 0.820811 0 1.83333V11.1667C0 12.1792 0.820811 13 1.83333 13H8.5C9.51252 13 10.3333 12.1792 10.3333 11.1667V3.81177C10.3333 3.55792 10.2505 3.311 10.0975 3.10847L8.09899 0.463361C7.87849 0.171532 7.5339 0 7.16814 0H1.83333ZM1 1.83333C1 1.3731 1.3731 1 1.83333 1H6.66667V3.93136C6.66667 4.20751 6.89052 4.43136 7.16667 4.43136H9.33333V11.1667C9.33333 11.6269 8.96024 12 8.5 12H1.83333C1.3731 12 1 11.6269 1 11.1667V1.83333Z" fill="#878787"/>
                            </svg>

                            <span style="margin-left: 5px; font-size: 14px ; font-weight: 500">{{ $story->count_chapters }}</span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
@endif

<!-- <div class="book_detail">
                    <span> <i class="fa fa-eye" aria-hidden="true"></i>  
                    @if ($story->view > 999 && $story->view <= 999999)
                        {{ intval($story->view / 1000) }}K
                        @elseif ($story->view > 1000000 && $story->view <= 99999999)
                        {{ round(($story->view / 1000000),2) }}M
                    @else
                        {{ $story->view }}
                    @endif</span>
                    <span><i class="fa fa-book" aria-hidden="true"></i>  {{ $story->count_chapters }}</span>
                </div> -->