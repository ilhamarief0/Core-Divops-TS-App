@props(['icon', 'path' => 0, 'size' => 2])

<i {{ $attributes->merge(['class' => 'fs-' . $size . ' ki-duotone ki-' . $icon]) }}>
    @for ($i = 1; $i <= $path; $i++)
        <span class="path{{ $i }}"></span>
    @endfor
</i>
