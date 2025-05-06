@props(['color' => null, 'size' => null, 'type' => null])

<div
    {{ $attributes->class([
            'separator-' . $type => $type,
            'border-' . $size => $size,
            'border-' . $color => $color,
        ])->merge(['class' => 'separator']) }}>
</div>
