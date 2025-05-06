@props([
    'items',
    'property' => 'name',
    'value' => null,
    'select2' => 'true',
    'ajax' => false,
    'ajaxParent' => null,
    'optionText' => 'item.name',
    'selected' => null,
    'placeholder' => 'Pilih...',
])

<select {{ $attributes->merge(['class' => 'form-select form-select-solid', 'id' => '']) }}
    data-kt-select2="{{ $select2 }}">
    <option value="" disabled selected>{{ $placeholder }}</option>

    @unless ($ajax)
        @foreach ($items as $item)
            @php
                $optionValue = data_get($item, $value ?? $property);
                $processedProperty = data_get($item, $property) ?? $item;
                $processedValue = ($value == 'key' ? $loop->index : $optionValue) ?? $item;
            @endphp


            <option value="{{ $processedValue }}" @selected($processedValue == $selected)>
                {{ ucfirst($processedProperty) }}</option>
        @endforeach
    @endunless
</select>


@if ($ajax)
    @push('scripts')
        <script>
            const {{ $attributes->get('id') }} = $("#{{ $attributes->get('id') }}");

            {{ $attributes->get('id') }}.select2({
                dropdownParent: $("{{ $ajaxParent }}"),
                placeholder: "Search...",
                minimumInputLength: 3,
                ajax: {
                    url: '{{ $ajax }}',
                    dataType: 'json',
                    delay: 700,
                    data: function(params) {
                        return {
                            search: params.term ? `${params.term}%` : '',
                            id: {{ $attributes->get('id') }}.data('id') ?? null,
                        }
                    },
                    processResults: function(data) {
                        let results = [];

                        $.each(data, function(index, item) {
                            results.push({
                                id: item.id,
                                text: `${ {!! $optionText !!} }`
                            });
                        });

                        return {
                            results: results
                        };
                    },
                    cache: true,
                }
            });
        </script>
    @endpush
@endif
