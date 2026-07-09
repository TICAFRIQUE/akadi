{{-- ================= FILTRE TOOLBAR ================= --}}

@if ($canFilter ?? false)

@php
    $currentStatus = request('status', 'all');
    $currentSource = request('source', '');
@endphp

<div class="filter-bar">

    <form action="{{ route('order.index') }}" method="GET" id="filterForm">

        <div class="filter-toolbar">

            {{-- STATUT --}}
            <div class="filter-group filter-grow">

                <label>Statut</label>

                <select name="status" class="form-control form-control-sm">

                    <option value="all">Tous les statuts</option>

                    @foreach ($statuts as $key => $item)
                        <option value="{{ $key }}" {{ $currentStatus == $key ? 'selected' : '' }}>

                            {{ $item['label'] ?? $key }}

                        </option>
                    @endforeach

                </select>

            </div>

            {{-- PROVENANCE --}}
            <div class="filter-group filter-grow">

                <label>Provenance</label>

                <select name="source" class="form-control form-control-sm">

                    <option value="">Toutes les sources</option>

                    @foreach ($sources as $srcKey => $src)
                        <option value="{{ $srcKey }}" {{ $currentSource == $srcKey ? 'selected' : '' }}>

                            {{ $src['label'] }}

                        </option>
                    @endforeach

                </select>

            </div>

            {{-- PÉRIODE — masquée si la période est bridée par permission --}}
            @if (!($periodMinDate ?? false))
            <div class="filter-group">

                <label>Période</label>

                <select name="all_dates" id="periodeSelect" class="form-control form-control-sm">

                    <option value="0" {{ !$allDates ? 'selected' : '' }}>Mois en cours</option>

                    <option value="1" {{ $allDates ? 'selected' : '' }}>Toutes</option>

                </select>

            </div>
            @else
                <input type="hidden" name="all_dates" value="0">
            @endif

            {{-- DATE DEBUT --}}
            <div class="filter-group date-range-group">

                <label>Du {{ ($periodMinDate ?? false) ? '(min : ' . \Carbon\Carbon::parse($periodMinDate)->format('d/m/Y') . ')' : '' }}</label>

                <input type="date" name="date_debut"
                    value="{{ $dateDebut ?: ($periodMinDate ?? now()->startOfMonth()->format('Y-m-d')) }}"
                    min="{{ $periodMinDate ?? '' }}"
                    class="form-control form-control-sm">

            </div>

            {{-- DATE FIN --}}
            <div class="filter-group date-range-group">

                <label>Au</label>

                <input type="date" name="date_fin"
                    value="{{ $dateFin ?: now()->format('Y-m-d') }}"
                    class="form-control form-control-sm">

            </div>

            {{-- ACTIONS --}}
            <div class="filter-actions">

                <button type="submit" class="btn btn-primary btn-sm">

                    <i class="fa fa-search mr-1"></i>
                    Filtrer

                </button>

                <a href="{{ route('order.index') }}" class="btn btn-outline-secondary btn-sm">

                    <i class="fa fa-undo"></i>

                </a>

            </div>

        </div>

    </form>

</div>

@endif

{{-- ================= STYLE ================= --}}

<style>
    .filter-toolbar {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 170px;
    }

    .filter-grow {
        flex: 1;
        min-width: 220px;
    }

    .filter-group label {
        font-size: .75rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0;
    }

    .filter-group .form-control {
        height: 38px;
        border-radius: 8px;
    }

    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 6px;
    }

    .filter-actions .btn {
        height: 38px;
        border-radius: 8px;
        min-width: 44px;
    }

    @media(max-width:992px) {

        .filter-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group,
        .filter-grow {
            width: 100%;
            min-width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }
    }
</style>

{{-- ================= SCRIPT ================= --}}

{{-- <script>

    function toggleDateFields() {

        const periode = $('#periodeSelect').val();

        if (periode == '1') {

            $('.date-range-group').hide();

        } else {

            $('.date-range-group').show();
        }
    }

    toggleDateFields();

    $('#periodeSelect').on('change', function () {

        toggleDateFields();

    });

</script> --}}


{{-- <script>
    // Sauvegarde des dernières dates valides
    let savedDateDebut = $('input[name="date_debut"]').val();
    let savedDateFin = $('input[name="date_fin"]').val();

    function toggleDateFields() {

        const periode = $('#periodeSelect').val();

        if (periode == '1') {

            // Sauvegarder avant masquage
            savedDateDebut = $('input[name="date_debut"]').val();
            savedDateFin = $('input[name="date_fin"]').val();

            $('.date-range-group').hide();

        } else {

            $('.date-range-group').show();

            // Restaurer les dates
            $('input[name="date_debut"]').val(savedDateDebut);
            $('input[name="date_fin"]').val(savedDateFin);
        }
    }

    toggleDateFields();

    $('#periodeSelect').on('change', function() {

        toggleDateFields();

    });
</script> --}}


<script>

$(document).ready(function () {

    function toggleDateFields() {

        const periode = $('#periodeSelect').val();

        if (periode == '1') {

            $('.date-range-group').hide();

        } else {

            $('.date-range-group').show();
        }
    }

    toggleDateFields();

    $('#periodeSelect').on('change', function () {
        toggleDateFields();
    });

});

</script>