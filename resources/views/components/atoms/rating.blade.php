@props(['checked' => 5, 'total' => 5])

<div class="rating">
    @foreach (range(1, $total) as $rating)
        <div {{ $attributes->class(['checked' => $rating <= $checked])->merge(['class' => 'rating-label']) }}>
            <x-atoms.icon class="fs-1" icon="star" />
        </div>
    @endforeach
</div>
