@props(['color' => null, 'type' => null])

<span
    {{ $attributes->class(['bg-' . $color => $color, 'bullet-' . $type => $type])->merge(['class' => 'bullet']) }}></span>
