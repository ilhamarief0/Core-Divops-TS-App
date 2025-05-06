@props(['type' => null, 'color' => 'danger', 'text' => 'New'])

<span {{ $attributes->merge(['class' => 'badge badge-' . $color . ($type ? ' badge-' . $type : null)]) }}>
    {{ $text }}
</span>
