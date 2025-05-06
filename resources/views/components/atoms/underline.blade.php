@props(['color' => 'primary', 'size' => 3])

<div class="d-inline-block position-relative ms-2">
    {{ $slot }}
    <span
        class="d-inline-block position-absolute h-{{ $size }}px bottom-0 end-0 start-0 bg-{{ $color }} translate rounded"></span>
</div>
