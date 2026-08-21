<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liste clients — {{ $menuSemaine->titre_affiche }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }

        .page { padding: 30px 36px; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 3px solid #f85d05; padding-bottom: 14px; }
        .brand { font-size: 22px; font-weight: 800; color: #f85d05; }
        .brand-sub { font-size: 9px; color: #888; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title .titre { font-size: 16px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 1px; }
        .doc-title .meta { font-size: 10px; color: #888; margin-top: 3px; }

        /* Semaine info */
        .semaine-bar { background: #fff8f5; border-left: 4px solid #f85d05; padding: 8px 14px; margin-bottom: 20px; }
        .semaine-bar .s-titre { font-weight: 700; font-size: 12px; color: #f85d05; }
        .semaine-bar .s-meta  { font-size: 10px; color: #666; margin-top: 2px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f85d05; color: #fff; }
        thead th { padding: 7px 10px; font-size: 10px; text-align: left; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td { padding: 6px 10px; border-bottom: 1px solid #eee; font-size: 10.5px; vertical-align: middle; }
        .nom { font-weight: 600; }
        .badge { padding: 2px 7px; border-radius: 20px; font-size: 9px; font-weight: 700; }
        .badge-yango { background: #dbeafe; color: #1d4ed8; }
        .badge-place { background: #f3f4f6; color: #6b7280; }

        /* Totaux */
        .resume { margin-top: 20px; display: flex; gap: 20px; }
        .resume-card { border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 14px; flex: 1; }
        .resume-card .rc-val { font-size: 16px; font-weight: 800; color: #f85d05; }
        .resume-card .rc-lbl { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: .5px; }

        /* Footer */
        .footer { margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 9px; color: #aaa; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div>
            <div class="brand">Akadi</div>
            <div class="brand-sub">Restaurant — Livraison de repas</div>
        </div>
        <div class="doc-title">
            <div class="titre">Liste des clients</div>
            <div class="meta">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    <div class="semaine-bar">
        <div class="s-titre">{{ $menuSemaine->titre_affiche }}</div>
        <div class="s-meta">
            Semaine du {{ $menuSemaine->date_debut->format('d/m/Y') }}
            au {{ $menuSemaine->date_fin->format('d/m/Y') }}
            &nbsp;·&nbsp; {{ $reservations->count() }} client(s) payé(s)
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Téléphone</th>
                <th>Jours</th>
                <th>Montant</th>
                <th>Mode livraison</th>
                <th>Adresse</th>
                <th>Paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="nom">{{ $r->client_name }}</td>
                <td>{{ $r->client_phone }}</td>
                <td style="text-align:center;">{{ $r->nombre_jours }}</td>
                <td style="text-align:right;font-weight:600;">{{ number_format($r->montant_total, 0, ',', ' ') }} F</td>
                <td>
                    @if($r->mode_livraison === 'yango')
                        <span class="badge badge-yango">Yango</span>
                    @else
                        <span class="badge badge-place">Sur place</span>
                    @endif
                </td>
                <td style="font-size:9px;color:#555;">{{ $r->address_yango ?? '—' }}</td>
                <td>{{ $r->paymentMethod->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $totalCA = $reservations->sum('montant_total');
        $nbYango  = $reservations->where('mode_livraison', 'yango')->count();
        $nbPlace  = $reservations->count() - $nbYango;
    @endphp
    <div class="resume">
        <div class="resume-card">
            <div class="rc-val">{{ $reservations->count() }}</div>
            <div class="rc-lbl">Clients payés</div>
        </div>
        <div class="resume-card">
            <div class="rc-val">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
            <div class="rc-lbl">CA total</div>
        </div>
        <div class="resume-card">
            <div class="rc-val">{{ $nbYango }}</div>
            <div class="rc-lbl">Livraison Yango</div>
        </div>
        <div class="resume-card">
            <div class="rc-val">{{ $nbPlace }}</div>
            <div class="rc-lbl">Sur place</div>
        </div>
    </div>

    <div class="footer">
        Document confidentiel — Akadi Restaurant · {{ now()->format('d/m/Y') }}
    </div>

</div>
</body>
</html>
