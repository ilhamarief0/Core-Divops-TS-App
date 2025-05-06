@props([
    'type' => 'image',
    'image' => 'assets/media/avatars/blank.png',
    'label',
    'color' => 'primary',
    'size' => 50,
    'circle' => false,
])

<div class="symbol symbol-{{ $size }}px {{ $circle ? 'symbol-circle' : null }}">
    <div {{ $attributes->class(['bg-' . $color => $type == 'label', 'text-inverse-' . $color => $type == 'label'])->merge(['class' => 'symbol-label fs-2 fw-semibold']) }}
        @style(["background-image: url('" . $image . "')" => $type == 'image'])>
        {{ $type == 'label' ? $label : null }}
    </div>
</div>
