@extends('shop.layouts.app')
@push('styles')
@endpush
@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    @if((new \Jenssegers\Agent\Agent())->isDesktop())
        <section class="container mt-4 content main-list-user px-5">
            <div class="top-info d-flex justify-content-between pt-5">
                <div class="left-info ps-5 d-flex">
                    <div class="text-center">
                        <div class="avatar mb-3">
                            <img class="{{ $users->user_vip == 1 ? 'border-vip' : '' }}" src="{{ $users->avatar ? pare_url_file($users->avatar, 'user') : 'frontend/images/no-user.webp' }}"
                                 style="height: auto;width: 80%;vertical-align: bottom; border-radius: 6px;">
                        </div>
                        <div class="birth-certificate">
                            <p>Chứng sinh</p>
                            <p>{{ $users->created_at ? formatDayMonthYear($users->created_at) : '' }}</p>
                        </div>
                    </div>
                    <div class="information" style="font-size:16px;">
                        <div style="font-size: 20px; font-weight: 500" class="d-flex align-items-center name-user">
                            <a href="{{ route('user.index', $users->id) }}"
                               class="@if($users->user_vip) text-danger font-weight-bold @endif">
                                {{ $users->name }}
                            </a>
                            @if ($users->user_vip == 1)
                                <div class="circle bg-success">
                                    <div class="checkmark"></div>
                                </div>
                            @endif

                        </div>
                                {{--                            <p style="font-size:16px;color:gray;">{{ $users->username }} </p>--}}
                            <div class="text-left" style="">
                                <p >Giới thiệu: {{ $users->bio }} </p>
                                @auth('web')
                                    @if (currentUser()->id == $users->id)
                                        <p  style="font-size:16px;">ID của bạn : {{ $users->id }} </p>
                                    @endif
                                @endauth
{{--                                    <p >Vàng hiện tại: {{ number_format($gold) }}</p>--}}
                                <p>
                                    Vinh dự cá nhân
                                    @if ($users->is_vip == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/mod.webp') }}" alt="">
                                    @endif
                                    @if ($users->user_vip == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/user_vip.webp') }}" alt="">
                                    @endif
                                    @if($users->top_dedication == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-cong-hien.webp') }}" alt="">
                                    @endif
                                    @if($users->gold == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-dai-gia.webp') }}" alt="">
                                    @endif
                                    @if($users->training == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-tu-vi.webp') }}" alt="">
                                    @endif
                                    @if($users->top_master == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-cao-thu.webp') }}" alt="">
                                    @endif
                                    @if($users->top_atk == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-atk.webp') }}" alt="">
                                    @endif
                                    @if($users->top_def == 1)
                                        <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-def.webp') }}" alt="">
                                    @endif
                                </p>
                                <p>Hạn vip:
                                @if($users->user_vip == 1)
                                    Hết hạn ngày {{ date('d/m/Y', strtotime($users->vip_expired_date)) }}
                                @else
                                    Chưa đăng ký VIP
                                @endif
                                </p>
                                {{--                                <p style="font-size:16px;color:gray;">--}}
                                {{--                                    @if (currentUser()->is_vip != \App\Enums\UserType::Normal)--}}
                                {{--                                        Người bật kiếm tiền--}}
                                {{--                                    @endif--}}
                                {{--                                </p>--}}

                            </div>
                    </div>
                </div>
                <div class="right-info">
                    <div class="edit-info">
                        @auth('web')
                            @if (currentUser()->id == $users->id)
                                <a href="{{ route('user.edit', $users->id) }}" class="border p-2 text-white bg-success border-0"
                                   style="margin-left: -35px;background: #fff; border-radius: 6px;">Đổi thông tin
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="story hidden">
                TIỂU SỬ
                <hr>
                <textarea id="tainfo"
                          style="resize:vertical;min-height:120px;width:90%;margin-left:5%;border-radius:8px;background-color:#f9f9f9"
                          readonly=""> {{ $users->bio }}</textarea>
            </div>
            @if (count($storyUser))
                <div class="container mt-3 hidden">
                    <div class="row">
                        <div class="home-section" style="box-shadow: unset;">
                            <div class="home-header mb-3">
                                <span class="fs-4">Truyện của {{ $users->name }}</span>
                                <a href="{{ route('book.nhung', $users->id) }}" class="fs-12 float-end">Xem thêm...</a>
                            </div>
                            <div class="row">
                                @foreach ($storyUser as $storyWritten)
                                    @include('shop.story._card_home', ['story' => $storyWritten])
                                @endforeach
                            </div>

                        </div>
                        <div style="display: flex;max-width:100%;justify-content: center;">

                            {!! $storyUser->onEachSide(0)->links() !!}
                        </div>
                    </div>
                </div>
            @endif
            @include('shop.layouts.comment', [
                'type' => 'App/User',
                'id' => $users->id,
                'author' => $users->id,
            ])
        </section>
    @endif
    @if((new \Jenssegers\Agent\Agent())->isMobile())
        <section class="container mt-4 content main-list-user px-4">
            <div class="top-info d-flex justify-content-between pt-5 flex-column">
                <div class="left-info  d-flex flex-column">
                    <div class="text-center d-flex flex-column align-items-center mb-3">
                        <div class="avatar mb-3">
                            <img class="{{ $users->user_vip == 1 ? 'border-vip' : '' }}" src="{{ $users->avatar ? pare_url_file($users->avatar, 'user') : 'frontend/images/no-user.webp' }}"
                                 style="height: auto;width: 80%;vertical-align: bottom; border-radius: 6px;">
                        </div>
                        <div style="font-size: 20px; font-weight: 500" class="d-flex align-items-center name-user">
                            <a href="{{ route('user.index', $users->id) }}"
                               class="@if($users->user_vip) text-danger font-weight-bold @endif">
                                {{ $users->name }}
                            </a>
                            @if ($users->user_vip == 1)
                                <div class="circle bg-success">
                                    <div class="checkmark"></div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="information" style="font-size:16px;">
                        {{--                            <p style="font-size:16px;color:gray;">{{ $users->username }} </p>--}}
                        <div class="text-left" style="">
                            <p >Giới thiệu: {{ $users->bio }}</p>
                            @auth('web')
                                @if (currentUser()->id == $users->id)
                                    <p  style="font-size:16px;">ID của bạn : {{ $users->id }} </p>
                                @endif
                            @endauth
{{--                                    <p >Vàng hiện tại: {{ number_format($gold) }}</p>--}}
                            <p>
                                Vinh dự cá nhân
                                @if ($users->is_vip == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/mod.webp') }}" alt="">
                                @endif
                                @if ($users->user_vip == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/user_vip.webp') }}" alt="">
                                @endif
                                @if($users->top_dedication == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-cong-hien.webp') }}" alt="">
                                @endif
                                @if($users->gold == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-dai-gia.webp') }}" alt="">
                                @endif
                                @if($users->training == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-tu-vi.webp') }}" alt="">
                                @endif
                                @if($users->top_master == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-cao-thu.webp') }}" alt="">
                                @endif
                                @if($users->top_atk == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-atk.webp') }}" alt="">
                                @endif
                                @if($users->top_def == 1)
                                    <img style="margin-left: 20px; max-width: 50px" src="{{ asset('frontend/images/top-def.webp') }}" alt="">
                                @endif
                            </p>                            <p>Hạn vip:
                                @if($users->user_vip == 1)
                                    Hết hạn ngày {{ date('d/m/Y', strtotime($users->vip_expired_date)) }}
                                @else
                                    Chưa đăng ký VIP
                                @endif
                            </p>                                 <div class="birth-certificate">
                                <p>Chứng sinh: {{ $users->created_at ? formatDayMonthYear($users->created_at) : '' }}</p>
                            </div>
                            {{--                                <p style="font-size:16px;color:gray;">--}}
                            {{--                                    @if (currentUser()->is_vip != \App\Enums\UserType::Normal)--}}
                            {{--                                        Người bật kiếm tiền--}}
                            {{--                                    @endif--}}
                            {{--                                </p>--}}

                        </div>
                    </div>
                </div>
                <div class="right-info d-flex justify-content-center">
                    <div class="edit-info">
                        @auth('web')
                            @if (currentUser()->id == $users->id)
                                <a href="{{ route('user.edit', $users->id) }}" class="border p-2 text-white bg-success border-0"
                                   style="margin-left: -35px;background: #fff; border-radius: 20px;">Đổi thông tin
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="story hidden">
                TIỂU SỬ
                <hr>
                <textarea id="tainfo"
                          style="resize:vertical;min-height:120px;width:90%;margin-left:5%;border-radius:8px;background-color:#f9f9f9"
                          readonly=""> {{ $users->bio }}</textarea>
            </div>
            @if (count($storyUser))
                <div class="container mt-3 hidden">
                    <div class="row">
                        <div class="home-section" style="box-shadow: unset;">
                            <div class="home-header mb-3">
                                <span class="fs-4">Truyện của {{ $users->name }}</span>
                                <a href="{{ route('book.nhung', $users->id) }}" class="fs-12 float-end">Xem thêm...</a>
                            </div>
                            <div class="row">
                                @foreach ($storyUser as $storyWritten)
                                    @include('shop.story._card_home', ['story' => $storyWritten])
                                @endforeach
                            </div>

                        </div>
                        <div style="display: flex;max-width:100%;justify-content: center;">

                            {!! $storyUser->onEachSide(0)->links() !!}
                        </div>
                    </div>
                </div>
            @endif
            @include('shop.layouts.comment', [
                'type' => 'App/User',
                'id' => $users->id,
                'author' => $users->id,
            ])
        </section>
    @endif
@endsection
