@props(['position'])

<li class="dd-item" data-id="{{ $position->id }}" data-name="{{ $position->name }}"
    data-supervisor="{{ $position->supervisor }}" data-leader="{{ $position->leader }}"
    data-position-class-id="{{ $position->position_class_id }}">
    <div class="dd-handle">
        <div class="rounded d-flex justify-content-between align-items-center pe-7">
            <span class="text-gray-900 fs-6 w-375px min-w-200px">{{ $position->name }}</span>

            <div class="d-flex">
                <x-atoms.button class="add-button btn-icon btn-light-primary btn-sm me-1" color="active-primary"
                    data-bs-target="#kt_modal_add_user" data-bs-toggle="modal" data-bs-target="#kt_modal_add_position"
                    data-id="{{ $position->id }}">
                    <x-atoms.icon class="fs-2" icon="plus" />
                </x-atoms.button>

                <x-atoms.button class="edit-button btn-icon btn-light-success btn-sm me-1" color="active-success"
                    data-bs-target="#kt_modal_add_user" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_position"
                    data-id="{{ $position->id }}">
                    <x-atoms.icon class="fs-2" icon="pencil" path="2" />
                </x-atoms.button>

                @unless ($position->leader == 1)
                    <x-atoms.button class="delete-button btn-icon btn-light-danger btn-sm me-1" color="active-danger"
                        data-id="{{ $position->id }}">
                        <x-atoms.icon class="fs-2" icon="trash" path="5" />
                    </x-atoms.button>
                @endunless
            </div>
        </div>
    </div>

    @if ($position->children)
        <ol class="dd-list">
            @each('components.molecules.nestable-position', $position->children, 'position')
        </ol>
    @endif
</li>
