@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="pagination__element pagination__element_text"
               disabled="true">
                Назад
            </a>
            <a class="pagination__element pagination__element_symbol"
               disabled="true">
                &#60;
            </a>
        @else
            <a class="pagination__element pagination__element_text"
               href="{{ $paginator->previousPageUrl() }}"
               rel="prev">
                Назад
            </a>
            <a class="pagination__element pagination__element_symbol">
                &#60;
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
            <a class="pagination__element pagination__element_text"
               href="{{ $paginator->nextPageUrl() }}"
               rel="next">
                Вперед
            </a>
            <a class="pagination__element pagination__element_symbol">
                &#62;
            </a>
        @else
            <a class="pagination__element pagination__element_text"
                disabled="true">
                Вперед
            </a>
            <a class="pagination__element pagination__element_symbol"
               disabled="true">
                &#62;
            </a>
        @endif
    </div>
@endif
