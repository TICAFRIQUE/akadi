@if ($paginator->hasPages())

{{-- ===== STYLES PAGINATION ===== --}}
<style>
    .pg-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.5rem 0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .pg-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pg-list .page-item .page-link,
    .pg-list .page-item span.page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        background: #fff;
        color: #374151;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.18s, color 0.18s, border-color 0.18s, box-shadow 0.18s, transform 0.18s;
        cursor: pointer;
        user-select: none;
    }

    .pg-list .page-item:not(.disabled):not(.active) .page-link:hover {
        background: #fff4ee;
        border-color: #fcd5b8;
        color: #d44d00;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(248,93,5,.18);
    }

    .pg-list .page-item.active .page-link,
    .pg-list .page-item.active span.page-link {
        background: #f85d05;
        border-color: #f85d05;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(248,93,5,.45);
        transform: translateY(-1px);
    }

    .pg-list .page-item.disabled .page-link,
    .pg-list .page-item.disabled span.page-link {
        background: #F9FAFB;
        border-color: #E5E7EB;
        color: #D1D5DB;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pg-list .page-item.pg-prev .page-link,
    .pg-list .page-item.pg-next .page-link {
        font-size: 1.1rem;
        font-weight: 700;
        color: #f85d05;
        border-color: #fcd5b8;
        background: #fff4ee;
    }
    .pg-list .page-item.pg-prev:not(.disabled) .page-link:hover,
    .pg-list .page-item.pg-next:not(.disabled) .page-link:hover {
        background: #f85d05;
        border-color: #f85d05;
        color: #fff;
        box-shadow: 0 4px 14px rgba(248,93,5,.4);
    }
    .pg-list .page-item.pg-prev.disabled .page-link,
    .pg-list .page-item.pg-next.disabled .page-link {
        background: #F9FAFB;
        border-color: #E5E7EB;
        color: #D1D5DB;
    }

    .pg-list .page-item.pg-dots span.page-link {
        border-color: transparent;
        background: transparent;
        color: #9CA3AF;
        font-size: 1rem;
        letter-spacing: 2px;
    }

    @media (max-width: 575px) {
        .pg-list .page-item .page-link,
        .pg-list .page-item span.page-link {
            min-width: 34px;
            height: 34px;
            font-size: 0.8rem;
        }
    }
</style>

<nav class="pg-nav" aria-label="Pagination">
    <ul class="pg-list">

        {{-- Précédent --}}
        @if ($paginator->onFirstPage())
            <li class="page-item pg-prev disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li class="page-item pg-prev">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Numéros de page --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item pg-dots disabled" aria-disabled="true">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Suivant --}}
        @if ($paginator->hasMorePages())
            <li class="page-item pg-next">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="page-item pg-next disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
        @endif

    </ul>
</nav>
@endif
