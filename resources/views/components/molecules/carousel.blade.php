@props(['id' => 'kt_carousel_3_carousel', 'items', 'title' => null, 'color' => 'primary', 'interval' => 8000])

<div id="{{ $id }}" class="carousel carousel-custom slide" data-bs-ride="carousel"
    data-bs-interval="{{ $interval }}">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <span class="fs-4 fw-bold pe-2">{{ $title }}</span>
        <ol
            class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-{{ $color }}">
            @foreach ($items as $key => $item)
                <li data-bs-target="#{{ $id }}" data-bs-slide-to="{{ $key }}"
                    @class(['ms-1', 'active' => !$key])>
                </li>
            @endforeach
        </ol>
    </div>
    <div class="carousel-inner pt-8">
        @foreach ($items as $key => $item)
            <div @class(['carousel-item', 'active' => !$key])>
                {{ $item }}
            </div>
        @endforeach
    </div>
</div>
