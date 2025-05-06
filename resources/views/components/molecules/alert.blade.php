@props(['title' => 'Success', 'color' => 'success'])

@php
    $icon = match ($color) {
        'success' => 'check-circle',
        'danger' => 'cross-circle',
        'info' => 'information-2',
        'warning' => 'information-5',
        default => 'notification-bing',
    };
@endphp

<div
    {{ $attributes->merge(['class' => 'alert alert-dismissible bg-light-' . $color . ' d-flex flex-column flex-sm-row p-5 mb-10']) }}>
    <x-atoms.icon class="text-{{ $color }} me-4 mb-5 mb-sm-0" icon="{{ $icon }}" path="3"
        size="2hx" />
    <div class="d-flex flex-column pe-0 pe-sm-10">
        <h4 class="fw-semibold">{{ $title }}</h4>
        <span>{{ $slot }}</span>
    </div>

    <x-atoms.button class="top-0 m-2 position-absolute position-sm-relative m-sm-0 end-0 ms-sm-auto" color="icon"
        data-bs-dismiss="alert">
        <x-atoms.icon class="text-{{ $color }} me-4 mb-5 mb-sm-0" icon="cross" path="2" size="1" />
    </x-atoms.button>
</div>
