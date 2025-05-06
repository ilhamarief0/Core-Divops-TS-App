@props(['type' => 'button', 'color' => 'primary', 'indicator' => false])

<button type="{{ $type }}" {{ $attributes->class(['btn'])->merge(['class' => 'btn-' . $color]) }}>
    {{ $slot }}

    @if ($indicator || $type == 'submit')
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
            <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
        </span>
    @endif
</button>
