@props(['title' => null])

<span {{ $attributes->merge(['class' => 'ms-1']) }} data-bs-toggle="tooltip" title="{{ $title }}">
    @if ($slot)
        {{ $slot }}
    @else
        <x-atoms.icon class="text-gray-500" icon="question-2" path="3" size="6" />
    @endif
    </i>
</span>
