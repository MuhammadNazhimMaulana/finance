@if ($paginator->hasPages())
    <div class="paginating-container pagination-solid">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="prev"><a>{{ __('Sebelumnya') }}</a></li>
            @else
                <li class="prev"><a href="{{ $paginator->previousPageUrl() }}">{{ __('Sebelumnya') }}</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><a>{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><a href="javascript:void(0);">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="next"><a href="{{ $paginator->nextPageUrl() }}">{{ __('Selanjutnya') }}</a></li>
            @else
                <li class="next"><a>{{ __('Selanjutnya') }}</a></li>
            @endif
        </ul>
    </div>
@endif
