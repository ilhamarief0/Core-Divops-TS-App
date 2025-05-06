@props(['head', 'body' => null])

<table {{ $attributes->merge(['class' => 'table align-middle table-row-dashed']) }}>
    <thead>
        {{ $head }}
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        {{ $body }}
    </tbody>
</table>
