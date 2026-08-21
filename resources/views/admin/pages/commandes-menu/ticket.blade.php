<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #111;
            background: #fff;
            width: 100%;
        }
        .ticket { padding: 10px 12px; }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .sep    { border: none; border-top: 1px dashed #888; margin: 8px 0; }
        .sep-solid { border: none; border-top: 1px solid #111; margin: 6px 0; }

        /* En-tête */
        .brand { font-size: 20px; font-weight: 900; color: #f85d05; letter-spacing: 2px; text-align: center; margin-bottom: 2px; }
        .brand-sub { font-size: 9px; text-align: center; color: #555; margin-bottom: 6px; }

        /* Infos */
        .row-info { display: flex; justify-content: space-between; margin: 2px 0; font-size: 10px; }
        .row-info .lbl { color: #555; }

        /* Articles */
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 3px 0; vertical-align: top; font-size: 10.5px; }
        table td.qty  { width: 20px; }
        table td.prix { width: 60px; text-align: right; white-space: nowrap; }
        .plat-nom { font-weight: bold; }

        /* Total */
        .total-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; font-weight: 900; margin-top: 6px; }
        .total-row .lbl { font-size: 11px; }

        /* Pied */
        .footer { text-align: center; font-size: 9px; color: #777; margin-top: 10px; line-height: 1.5; }
        .footer .merci { font-size: 12px; font-weight: bold; color: #f85d05; margin-bottom: 4px; }

        /* Badge livré */
        .badge-livre { text-align: center; border: 2px solid #16a34a; color: #16a34a; border-radius: 4px; padding: 3px 6px; font-size: 11px; font-weight: 900; display: inline-block; margin: 4px auto; letter-spacing: 1px; }
    </style>
</head>
<body>
<div class="ticket">

    {{-- En-tête --}}
    <div class="brand">AKADI</div>
    <div class="brand-sub">Restaurant · Livraison de repas</div>

    <hr class="sep-solid">

    {{-- Infos commande --}}
    @php
        $res = $order->menuSemaineReservation;
        $date = \Carbon\Carbon::parse($order->date_order);
    @endphp

    <div class="row-info">
        <span class="lbl">Date :</span>
        <span class="bold">{{ $date->locale('fr')->isoFormat('dddd D MMM YYYY') }}</span>
    </div>
    <div class="row-info">
        <span class="lbl">N° cmd :</span>
        <span>{{ $order->code ?? $order->id }}</span>
    </div>
    <div class="row-info">
        <span class="lbl">Client :</span>
        <span class="bold">{{ $res->client_name }}</span>
    </div>
    <div class="row-info">
        <span class="lbl">Tél :</span>
        <span>{{ $res->client_phone }}</span>
    </div>
    @if($res->mode_livraison === 'yango')
    <div class="row-info">
        <span class="lbl">Livraison :</span>
        <span>Yango</span>
    </div>
    @if($res->address_yango)
    <div class="row-info">
        <span class="lbl">Adresse :</span>
        <span style="max-width:120px;text-align:right;">{{ $res->address_yango }}</span>
    </div>
    @endif
    @else
    <div class="row-info">
        <span class="lbl">Mode :</span>
        <span>Sur place</span>
    </div>
    @endif

    <hr class="sep">

    {{-- Semaine --}}
    <div style="font-size:9px;color:#555;text-align:center;margin-bottom:4px;">
        {{ $res->menuSemaine->titre_affiche }}
    </div>

    <hr class="sep">

    {{-- Plats --}}
    <table>
        <tbody>
            @foreach($order->menuProduits as $plat)
            <tr>
                <td class="qty">{{ $plat->pivot->quantity }}×</td>
                <td>
                    <div class="plat-nom">{{ $plat->nom }}</div>
                </td>
                <td class="prix">{{ number_format($plat->pivot->prix_apres_remise ?? $plat->pivot->unit_price ?? $res->prix_unitaire_applique, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="sep-solid">

    {{-- Total --}}
    <div class="total-row">
        <span class="lbl">TOTAL</span>
        <span>{{ number_format($res->prix_unitaire_applique, 0, ',', ' ') }} FCFA</span>
    </div>

    <div class="row-info" style="margin-top:4px;">
        <span class="lbl">Paiement :</span>
        <span>{{ $res->paymentMethod->name ?? '—' }}</span>
    </div>

    <hr class="sep">

    {{-- Statut livré --}}
    <div class="center" style="margin: 6px 0;">
        <span class="badge-livre">✓ LIVRÉ</span>
    </div>
    <div class="center" style="font-size:9px;color:#555;">
        {{ now()->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}
    </div>

    <hr class="sep">

    {{-- Pied --}}
    <div class="footer">
        <div class="merci">Merci !</div>
        Votre repas Akadi du {{ $date->locale('fr')->isoFormat('dddd') }}<br>
        a bien été livré. Bonne dégustation.
    </div>

</div>
</body>
</html>
