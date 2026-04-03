@if ($paginator->hasPages())

{{-- ===== STYLES PAGINATION ORANGE ===== --}}
<style>
    /* --- Variables --- */
    :root {
        --pg-orange:        #F97316;
        --pg-orange-dark:   #EA580C;
        --pg-orange-light:  #FED7AA;
        --pg-orange-xlight: #FFF7ED;
        --pg-white:         #FFFFFF;
        --pg-muted:         #9CA3AF;
        --pg-radius:        10px;
        --pg-transition:    0.18s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* --- Wrapper --- */
    .pg-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.5rem 0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    /* --- Info texte --- */
    .pg-info {
        font-size: 0.85rem;
        color: var(--pg-muted);
        letter-spacing: 0.01em;
    }
    .pg-info strong {
        color: var(--pg-orange-dark);
        font-weight: 700;
    }

    /* --- Liste pagination --- */
    .pg-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* --- Item de base --- */
    .pg-list .page-item .page-link,
    .pg-list .page-item span.page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border-radius: var(--pg-radius);
        border: 1.5px solid #E5E7EB;
        background: var(--pg-white);
        color: #374151;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: background var(--pg-transition),
                    color var(--pg-transition),
                    border-color var(--pg-transition),
                    box-shadow var(--pg-transition),
                    transform var(--pg-transition);
        cursor: pointer;
        user-select: none;
    }

    /* --- Hover --- */
    .pg-list .page-item:not(.disabled):not(.active) .page-link:hover {
        background: var(--pg-orange-xlight);
        border-color: var(--pg-orange-light);
        color: var(--pg-orange-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.18);
    }

    /* --- Page active --- */
    .pg-list .page-item.active .page-link,
    .pg-list .page-item.active span.page-link {
        background: var(--pg-orange);
        border-color: var(--pg-orange);
        color: var(--pg-white);
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.45);
        transform: translateY(-1px);
    }

    /* --- Désactivé --- */
    .pg-list .page-item.disabled .page-link,
    .pg-list .page-item.disabled span.page-link {
        background: #F9FAFB;
        border-color: #E5E7EB;
        color: #D1D5DB;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* --- Prev / Next (chevrons) --- */
    .pg-list .page-item.pg-prev .page-link,
    .pg-list .page-item.pg-next .page-link {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--pg-orange);
        border-color: var(--pg-orange-light);
        background: var(--pg-orange-xlight);
    }
    .pg-list .page-item.pg-prev:not(.disabled) .page-link:hover,
    .pg-list .page-item.pg-next:not(.disabled) .page-link:hover {
        background: var(--pg-orange);
        border-color: var(--pg-orange);
        color: var(--pg-white);
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.4);
    }
    .pg-list .page-item.pg-prev.disabled .page-link,
    .pg-list .page-item.pg-next.disabled .page-link {
        background: #F9FAFB;
        border-color: #E5E7EB;
        color: #D1D5DB;
    }

    /* --- Séparateur "..." --- */
    .pg-list .page-item.pg-dots span.page-link {
        border-color: transparent;
        background: transparent;
        color: var(--pg-muted);
        font-size: 1rem;
        letter-spacing: 2px;
    }

    /* --- Mobile : prev/next uniquement --- */
    .pg-mobile {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .pg-mobile .page-link {
        padding: 0 20px !important;
        font-weight: 600;
    }
    .pg-mobile .page-link:not([class*="disabled"]) {
        background: var(--pg-orange) !important;
        border-color: var(--pg-orange) !important;
        color: var(--pg-white) !important;
    }
</style>

<nav class="pg-nav" aria-label="Pagination">

    {{-- ===== MOBILE (xs) : prev / next texte uniquement ===== --}}
    <div class="pg-mobile d-flex d-sm-none">
        <ul class="pg-list">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&#8592; @lang('pagination.previous')</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&#8592; @lang('pagination.previous')</a>
                </li>
            @endif
        </ul>
        <ul class="pg-list">
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next') &#8594;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">@lang('pagination.next') &#8594;</span>
                </li>
            @endif
        </ul>
    </div>

    {{-- ===== DESKTOP (sm+) : info + pagination complète ===== --}}
    <div class="d-none d-sm-flex align-items-center justify-content-between w-100 flex-wrap gap-2">

        {{-- Info résultats --}}
        <p class="pg-info mb-0">
            Affichage de
            <strong>{{ $paginator->firstItem() }}</strong>
            à
            <strong>{{ $paginator->lastItem() }}</strong>
            sur
            <strong>{{ $paginator->total() }}</strong>
            résultats
        </p>

        {{-- Numéros de page --}}
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

            {{-- Pages --}}
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
    </div>

</nav>
@endif