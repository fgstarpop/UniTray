<div class="page-banner-area bg-5">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>{{ $name ?? null }}</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">{{ __('Trang chủ') }}</a></li>
                        <li>{{ $name ?? null }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
