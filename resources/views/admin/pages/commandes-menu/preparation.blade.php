<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fiche préparation — {{ $menuSemaine->titre_affiche }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; }

        .page { padding: 32px 40px; }

        /* ── Header ── */
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 3px solid #f85d05; margin-bottom: 20px; }
        .brand  { font-size: 22px; font-weight: 900; color: #f85d05; }
        .brand-sub { font-size: 9px; color: #888; margin-top: 2px; }
        .doc-info { text-align: right; }
        .doc-info .titre { font-size: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }
        .doc-info .meta  { font-size: 9px; color: #888; margin-top: 3px; }

        /* ── Semaine ── */
        .semaine-bar { background: #fff8f5; border-left: 4px solid #f85d05; padding: 8px 14px; margin-bottom: 24px; }
        .semaine-bar .s-titre  { font-weight: 700; font-size: 13px; color: #f85d05; }
        .semaine-bar .s-meta   { font-size: 10px; color: #666; margin-top: 2px; }

        /* ── Jour bloc ── */
        .jour-bloc { margin-bottom: 20px; page-break-inside: avoid; }
        .jour-header {
            background: #1a1a1a;
            color: #fff;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 4px 4px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .jour-header .nb-plats { font-size: 10px; background: rgba(248,93,5,.8); padding: 2px 8px; border-radius: 20px; }

        .jour-table { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; border-top: none; }
        .jour-table thead tr { background: #f9fafb; }
        .jour-table th { padding: 6px 12px; font-size: 10px; text-transform: uppercase; letter-spacing: .4px; color: #555; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .jour-table th.right { text-align: right; }
        .jour-table td { padding: 8px 12px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        .jour-table td.right { text-align: right; }
        .jour-table tbody tr:last-child td { border-bottom: none; }

        .plat-nom { font-weight: 700; font-size: 12px; }
        .qty-badge {
            font-size: 16px;
            font-weight: 900;
            color: #f85d05;
        }

        /* ── Résumé global ── */
        .resume-section { margin-top: 28px; page-break-inside: avoid; }
        .resume-title { font-size: 14px; font-weight: 800; border-bottom: 2px solid #f85d05; padding-bottom: 6px; margin-bottom: 12px; color: #f85d05; text-transform: uppercase; letter-spacing: 1px; }

        .resume-table { width: 100%; border-collapse: collapse; }
        .resume-table thead tr { background: #f85d05; color: #fff; }
        .resume-table th { padding: 7px 12px; font-size: 10px; text-transform: uppercase; font-weight: 700; text-align: left; }
        .resume-table th.right { text-align: right; }
        .resume-table tbody tr:nth-child(even) { background: #fafafa; }
        .resume-table td { padding: 7px 12px; border-bottom: 1px solid #eee; font-size: 11px; }
        .resume-table td.right { text-align: right; font-weight: 700; font-size: 13px; color: #f85d05; }

        /* ── Footer ── */
        .footer { margin-top: 28px; border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 9px; color: #aaa; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div>
            <div class="brand">Akadi</div>
            <div class="brand-sub">Restaurant — Fiche interne cuisine</div>
        </div>
        <div class="doc-info">
            <div class="titre">Fiche de préparation</div>
            <div class="meta">Généré le {{ now()->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}</div>
        </div>
    </div>

    <div class="semaine-bar">
        <div class="s-titre">{{ $menuSemaine->titre_affiche }}</div>
        <div class="s-meta">
            Semaine du {{ $menuSemaine->date_debut->format('d/m/Y') }}
            au {{ $menuSemaine->date_fin->format('d/m/Y') }}
            &nbsp;·&nbsp;
            Basé sur les réservations payées uniquement
        </div>
    </div>

    {{-- ── Par jour ── --}}
    @forelse($parJour as $dateStr => $plats)
        @php
            $labelJour = \Carbon\Carbon::parse($dateStr)->locale('fr')->isoFormat('dddd D MMMM YYYY');
            $totalPortions = $plats->sum('quantite');
        @endphp
        <div class="jour-bloc">
            <div class="jour-header">
                <span>{{ ucfirst($labelJour) }}</span>
                <span class="nb-plats">{{ $plats->count() }} plat{{ $plats->count() > 1 ? 's' : '' }}</span>
            </div>
            <table class="jour-table">
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th class="right">Qté à préparer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plats->sortByDesc('quantite') as $ligne)
                    <tr>
                        <td class="plat-nom">{{ $ligne['plat']->nom ?? '—' }}</td>
                        <td class="right">
                            <span class="qty-badge">{{ $ligne['quantite'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align:center;color:#888;padding:30px 0;">Aucune réservation payée pour ce menu.</p>
    @endforelse

    {{-- ── Résumé global ── --}}
    @if($totalParPlat->isNotEmpty())
    <div class="resume-section">
        <div class="resume-title">Total général — tous jours confondus</div>
        <table class="resume-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plat</th>
                    <th class="right">Total portions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalParPlat as $i => $ligne)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>{{ $ligne['plat']->nom ?? '—' }}</td>
                    <td class="right">{{ $ligne['quantite'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Document interne — Cuisine Akadi · {{ now()->format('d/m/Y') }} · Ne pas diffuser
    </div>

</div>
</body>
</html>
