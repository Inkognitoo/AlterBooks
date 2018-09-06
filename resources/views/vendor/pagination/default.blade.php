@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="pagination__element"
               disabled="true">
                Первая
            </a>
        @else
            <a class="pagination__element"
               href="{{ $paginator->previousPageUrl() }}"
               rel="prev">
                Первая
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="pagination__element pagination__element_dots">
                    {{ $element }}
                </a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="pagination__element pagination__element_current">
                            {{ $page }}
                        </a>
                    @else
                        <a class="pagination__element"
                           href="{{ $url }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="pagination__element"
               href="{{ $paginator->nextPageUrl() }}"
               rel="next">
                Последняя
            </a>
        @else
            <li class="pagination__element"
                disabled="true">
                Последняя
            </li>
        @endif
    </div>
@endif
