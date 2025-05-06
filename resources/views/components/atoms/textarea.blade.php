@props(['rows' => 3])

<textarea {{ $attributes->merge(['class' => 'form-control form-control-solid']) }} rows="{{ $rows }}"></textarea>
 