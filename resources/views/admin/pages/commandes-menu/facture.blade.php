<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture — {{ $reservation->client_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #1a1a1a; background: #fff; }

        .page { padding: 40px 48px; }

        /* ── Header ── */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 3px solid #f85d05; padding-bottom: 20px; }
        .brand-name { font-size: 28px; font-weight: 800; color: #f85d05; letter-spacing: -1px; }
        .brand-sub { font-size: 11px; color: #666; margin-top: 2px; }
        .facture-label { text-align: right; }
        .facture-label .titre { font-size: 22px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 2px; }
        .facture-label .ref { font-size: 11px; color: #888; margin-top: 4px; }

        /* ── Info bloc ── */
        .info-row { display: flex; gap: 0; margin-bottom: 28px; }
        .info-bloc { flex: 1; }
        .info-bloc:last-child { text-align: right; }
        .info-bloc .label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 4px; }
        .info-bloc .value { font-size: 13px; font-weight: 600; color: #1a1a1a; line-height: 1.5; }
        .info-bloc .value.big { font-size: 15px; }

        /* ── Semaine badge ── */
        .semaine-bar { background: #fff8f5; border-left: 4px solid #f85d05; padding: 10px 16px; margin-bottom: 24px; border-radius: 2px; }
        .semaine-bar .s-titre { font-weight: 700; font-size: 14px; color: #f85d05; }
        .semaine-bar .s-periode { font-size: 11px; color: #666; margin-top: 2px; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #f85d05; color: #fff; }
        thead th { padding: 9px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700; text-align: left; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td { padding: 8px 12px; border-bottom: 1px solid #f0f0f0; font-size: 12px; vertical-align: middle; }
        tbody td.right { text-align: right; }
        .plat-nom { font-weight: 600; }
        .badge-livree { background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; }

        /* ── Totaux ── */
        .totaux { width: 280px; margin-left: auto; margin-bottom: 28px; }
        .totaux table { margin-bottom: 0; }
        .totaux td { padding: 6px 10px; font-size: 13px; }
        .totaux td:last-child { text-align: right; font-weight: 600; }
        .totaux tr.total-final td { background: #f85d05; color: #fff; font-size: 15px; font-weight: 800; border-radius: 0; }
        .totaux tr.total-final td:first-child { border-radius: 4px 0 0 4px; }
        .totaux tr.total-final td:last-child { border-radius: 0 4px 4px 0; }

        /* ── Paiement / livraison ── */
        .meta-row { display: flex; gap: 20px; margin-bottom: 32px; }
        .meta-card { flex: 1; border: 1px solid #e8e8e8; border-radius: 6px; padding: 12px 16px; }
        .meta-card .m-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 4px; }
        .meta-card .m-value { font-size: 13px; font-weight: 600; }
        .meta-card .m-sub { font-size: 11px; color: #666; margin-top: 2px; }

        /* ── Footer ── */
        .footer { border-top: 1px solid #e8e8e8; padding-top: 16px; text-align: center; color: #999; font-size: 10px; line-height: 1.6; }
        .footer strong { color: #f85d05; }

        /* ── Stamp ── */
        .stamp { position: absolute; bottom: 120px; right: 60px; border: 3px solid #22c55e; color: #15803d; border-radius: 6px; padding: 8px 16px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; opacity: .85; transform: rotate(-8deg); }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="brand-name">Akadi</div>
            <div class="brand-sub">Restaurant — Livraison de repas</div>
        </div>
        <div class="facture-label">
            <div class="titre">Facture</div>
            <div class="ref">N° {{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="ref">Émise le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
        </div>
    </div>

    {{-- Client / Semaine --}}
    <div class="info-row">
        <div class="info-bloc">
            <div class="label">Facturé à</div>
            <div class="value big">{{ $reservation->client_name }}</div>
            <div class="value">{{ $reservation->client_phone }}</div>
        </div>
        <div class="info-bloc" style="text-align:right;">
            <div class="label">Date de réservation</div>
            <div class="value">{{ $reservation->created_at->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
            <div class="label mt-2" style="margin-top:8px;">Statut</div>
            <div class="value">
                @if($reservation->statut === 'payee') Payée & Livrée
                @elseif($reservation->statut === 'annulee') Annulée
                @else En attente @endif
            </div>
        </div>
    </div>

    {{-- Semaine --}}
    <div class="semaine-bar">
        <div class="s-titre">{{ $reservation->menuSemaine->titre_affiche }}</div>
        <div class="s-periode">
            Semaine du {{ $reservation->menuSemaine->date_debut->format('d/m/Y') }}
            au {{ $reservation->menuSemaine->date_fin->format('d/m/Y') }}
            &nbsp;·&nbsp; {{ $reservation->nombre_jours }} jour(s) commandé(s)
        </div>
    </div>

    {{-- Table des jours/plats --}}
    @php
        $joursGroupes = $reservation->items->groupBy(fn($item) => $item->date->format('Y-m-d'));
        $ordersParDate = $reservation->orders->keyBy(fn($o) => \Carbon\Carbon::parse($o->date_order)->format('Y-m-d'));
    @endphp

    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>Plat(s)</th>
                <th style="text-align:right;">Prix unit.</th>
                <th style="text-align:right;">Qté</th>
                <th style="text-align:right;">Sous-total</th>
                <th style="text-align:center;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($joursGroupes as $date => $lignes)
                @php
                    $order = $ordersParDate->get($date);
                    $labelJour = \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMM');
                @endphp
                @foreach($lignes as $ligne)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $lignes->count() }}" style="vertical-align:top;padding-top:10px;color:#666;font-size:11px;white-space:nowrap;">
                                {{ ucfirst($labelJour) }}
                            </td>
                        @endif
                        <td class="plat-nom">{{ $ligne->menuProduit->nom }}</td>
                        <td class="right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="right">{{ $ligne->quantity }}</td>
                        <td class="right">{{ number_format($ligne->prix_unitaire * $ligne->quantity, 0, ',', ' ') }} F</td>
                        @if($loop->first)
                            <td rowspan="{{ $lignes->count() }}" style="text-align:center;vertical-align:middle;">
                                @if($order && $order->status === \App\Models\Order::STATUS_LIVREE)
                                    <span class="badge-livree">Livré</span>
                                @else
                                    <span style="color:#999;font-size:10px;">—</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    {{-- Totaux --}}
    <div class="totaux">
        <table>
            <tr>
                <td>{{ $reservation->nombre_jours }} jours × {{ number_format($reservation->prix_unitaire_applique, 0, ',', ' ') }} F/j</td>
                <td>{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total-final">
                <td>Total</td>
                <td>{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    {{-- Paiement & Livraison --}}
    <div class="meta-row">
        <div class="meta-card">
            <div class="m-label">Moyen de paiement</div>
            <div class="m-value">{{ $reservation->paymentMethod->name ?? 'Non renseigné' }}</div>
        </div>
        <div class="meta-card">
            <div class="m-label">Mode de livraison</div>
            <div class="m-value">{{ $reservation->mode_livraison === 'yango' ? 'Yango (livraison)' : 'Sur place' }}</div>
            @if($reservation->address_yango)
                <div class="m-sub">{{ $reservation->address_yango }}</div>
            @endif
        </div>
    </div>

    {{-- Stamp livré --}}
    <div class="stamp">Livré ✓</div>

    {{-- Footer --}}
    <div class="footer">
        <strong>Akadi Restaurant</strong> — Merci de votre confiance.<br>
        Pour toute question, contactez-nous par WhatsApp ou sur notre site.<br>
        Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.
    </div>

</div>
</body>
</html>
