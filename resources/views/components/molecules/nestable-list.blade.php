@props(['label', 'button', 'target' => null])

<div class="rounded d-flex justify-content-between align-items-center pe-7">
    <span class="text-gray-900 fs-6 w-375px min-w-200px">{{ $label }}</span>

    @if ($button)
        <button
            {{ $button->attributes->merge(['class' => 'btn btn-icon btn-active-success btn-light-success btn-sm me-1']) }}
            data-bs-toggle="modal" data-bs-target="#{{ $target }}">
            <x-atoms.icon class="fs-2" icon="pencil" path="2" />
        </button>
    @endif
</div>
