@props(['link' => '#', 'value' => null, 'description' => null])

<a href="{{ $link }}" class="card bg-body hoverable card-xl-stretch mb-xl-8">
    <div class="card-body">
        {{ $slot }}
        <div class="mt-5 mb-2 text-gray-900 fw-bold fs-2">{{ $value }}</div>
        <div class="text-gray-400 fw-semibold">{{ $description }}</div>
    </div>
</a>
