@props(['title' => null, 'header' => null, 'toolbar' => null, 'body', 'footer' => null])

<div {{ $attributes->merge(['class' => 'card shadow-sm']) }}>
    @if ($title || $toolbar)
        <div {{ $header->attributes->class(['card-header']) }}>
            <div {{ $title->attributes->class(['card-title']) }}>{{ $title }}</div>
            @if ($toolbar)
                <div {{ $toolbar->attributes->class(['card-toolbar']) }}> {{ $toolbar }}</div>
            @endif
        </div>
    @endif

    <div {{ $body->attributes->class(['card-body']) }}>{{ $body }}</div>

    @if ($footer)
        <div {{ $footer->attributes->class(['card-footer']) }}>
            {{ $footer }}
        </div>
    @endif
</div>
