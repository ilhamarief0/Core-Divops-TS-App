@props(['type' => 'checkbox', 'id' => null, 'checked' => false])

<div {{ $attributes->merge(['class' => 'form-check form-check-custom form-check-solid']) }}>
    <input {{ $input->attributes->merge(['class' => 'form-check-input']) }} type="{{ $type }}"
        @checked($checked) />

    @if ($type == 'radio')
        <label class="form-check-label" for="{{ $id }}">
            <div {{ $label->attributes->merge(['class' => 'fw-bold text-gray-800 text-capitalize']) }}>
                {{ $label }}
            </div>
        </label>
    @else
        {{ $slot }}
    @endif
</div>
