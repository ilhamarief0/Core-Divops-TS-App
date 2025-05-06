@php
    $segments = str_replace('-', ' ', Request::segments());

    if (count($segments) > 2) {
        unset($segments[2]);
    }

    $segmentCount = count($segments);
@endphp

<ul class="pt-1 my-0 breadcrumb breadcrumb-dot fw-semibold fs-7">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
    </li>

    @foreach ($segments as $index => $segment)
        @php
            $segment = encryptCheck($segment) ? 'Detail' : $segment;
            $label = $segment !== 'dashboard' ? $segment : 'Home';
            $segment = "{$segment}/lists";

            if ($segment == 'rule details/lists') {
                $segment = 'rules';
                $label = $segment;
            }
        @endphp

        <li class="breadcrumb-item text-capitalize @if ($index === $segmentCount - 1) text-muted @endif">
            @if ($loop->last)
                {{ $label }}
            @else
                <a href="/{{ $segment }}">{{ $label }}</a>
            @endif
        </li>
    @endforeach
</ul>
