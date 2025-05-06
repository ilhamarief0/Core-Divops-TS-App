@props(['start' => 1, 'end', 'active' => 1])

<ul class="pagination">
    @foreach (range($start, $end) as $item)
        @if ($loop->first)
            <li @class(['page-item', 'previous', 'disabled' => $active == $start])>
                <a href="#" class="page-link">
                    <i class="previous"></i>
                </a>
            </li>
        @endif

        <li class="page-item ">
            <a href="#" @class(['page-link', 'active' => $item == $active])>{{ $item }}</a>
        </li>

        @if ($loop->last)
            <li @class(['page-item', 'next', 'disabled' => $active == $end])>
                <a href="#" class="page-link">
                    <i class="next"></i>
                </a>
            </li>
        @endif
    @endforeach
</ul>
