@props(['id', 'items', 'title' => 'title', 'body' => 'body'])

<div class="accordion accordion-icon-collapse" id="{{ $id }}">
    @foreach ($items as $key => $accordion)
        <div class="mb-0">
            <div @class([
                'py-4',
                'accordion-header',
                'd-flex',
                'align-items-center',
                'collapsed' => !$loop->first,
            ]) data-bs-toggle="collapse" data-bs-target="#{{ $id . '_item_' . $key }}">
                <span class="accordion-icon">
                    <x-atoms.icon class="accordion-icon-off fs-3" icon="plus-square" path="3" />
                    <x-atoms.icon class="accordion-icon-on fs-3" icon="minus-square" path="2" />
                </span>
                <h4 class="mb-0 text-gray-700 cursor-pointer fw-bold ms-4">
                    {{ $accordion->$title }}</h4>
            </div>

            <div id="{{ $id . '_item_' . $key }}" @class(['fs-6', 'ps-10', 'collapse', 'show' => $loop->first]) data-bs-parent="#{{ $id }}">
                <div class="mb-4 text-gray-600 fw-semibold fs-6">
                    {{ $accordion->$body }}
                </div>
            </div>

            @unless ($loop->last)
                <x-atoms.separator type="dashed" />
        @endif
    </div>
    @endforeach
    </div>
