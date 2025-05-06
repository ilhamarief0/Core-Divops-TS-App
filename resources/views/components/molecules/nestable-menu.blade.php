@props(['menu'])
@if (Feature::someAreActive($menu->feature))
    @canany($menu->permission)
        @if ($menu->heading)
            <div class="pt-5 menu-item">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">{{ $menu->name }}</span>
                </div>
            </div>

            @if ($menu->children)
                @each('components.molecules.nestable-menu', $menu->children, 'menu')
            @endif
        @else
            @php
            if (!isset($menu->param)) {
                dd($menu);
            }
                $element = $menu->children ? 'span' : 'a ';
                $attribute = $menu->children ? null : 'href=' . route($menu->route[0], $menu->param);
                $active = request()->routeIs($menu->route);
                $notification = $menu->hasNotification ? countProcessedData($menu->id) : null;
            @endphp

            <div @if ($menu->children) data-kt-menu-trigger="click" @endif @class([
                'menu-item',
                'menu-accordion' => $menu->children,
                'here show' => $active,
            ])>
                <{{ $element . $attribute }} @class(['menu-link', 'active' => !$menu->children && $active])>
                    @if ($menu->icon)
                        <span class="menu-icon">
                            <x-atoms.icon class="fs-2" icon="{{ $menu->icon }}" path="{{ $menu->path }}" />
                        </span>
                    @else
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                    @endif

                    <span class="menu-title">{{ $menu->name }}</span>

                    @if ($notification)
                        <span class="menu-badge">
                            <span class="badge badge-success badge-square badge-sm">{{ $notification }}</span>
                        </span>
                    @endif

                    @if ($menu->children)
                        <span class="menu-arrow"></span>
                    @endif
                    </{{ $element }}>
                    @if ($menu->children)
                        <div class="menu-sub menu-sub-accordion">
                            @each('components.molecules.nestable-menu', $menu->children, 'menu')
                        </div>
                    @endif
            </div>
        @endif
    @endcanany

@endif
