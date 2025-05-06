@props(['title' => null, 'tabs', 'contents', 'active' => 1])

<x-molecules.card>
    <x-slot:header class="card-header-stretch">
        @if ($title)
            <x-slot:title class="d-flex align-items-center">
                <h3 class="m-0 text-gray-800 fw-bold">{{ $title }}</h3>
            </x-slot:title>
        @endif
        
        <x-slot:toolbar class="m-0">
            <ul class="border-0 nav nav-tabs nav-line-tabs nav-stretch fs-6 fw-bold" role="tablist">
                @foreach ($tabs as $tab)
                    <li class="nav-item" role="presentation">
                        <a id="kt_tab_{{ $loop->iteration }}" @class([
                            'nav-link',
                            'justify-content-center',
                            'text-active-gray-800',
                            'active' => $loop->iteration == $active,
                        ]) data-bs-toggle="tab"
                            role="tab" href="#kt_tab_content_{{ $loop->iteration }}">{{ $tab }}</a>
                    </li>
                @endforeach
            </ul>
        </x-slot:toolbar>
    </x-slot:header>
    <x-slot:body>
        <div class="tab-content">
            @foreach ($contents as $content)
                <div id="kt_tab_content_{{ $loop->iteration }}" @class([
                    'card-body',
                    'p-0',
                    'tab-pane',
                    'fade',
                    'show',
                    'active' => $loop->iteration == $active,
                ]) role="tabpanel"
                    aria-labelledby="kt_tab_{{ $loop->iteration }}">
                    {{ $content }}
                </div>
            @endforeach
        </div>
    </x-slot:body>
</x-molecules.card>
