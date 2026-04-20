<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Facture-#{{ $orders['code'] }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 2mm 3mm;
        }

        body {
            width: 74mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt;
            color: #000;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }
        .logo-text {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .header-sub {
            font-size: 7pt;
            margin-top: 1mm;
        }

        .section-title {
            font-weight: bold;
            font-size: 7.5pt;
            border-bottom: 1px solid #000;
            padding-bottom: 0.5mm;
            margin-bottom: 1mm;
            margin-top: 2mm;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            line-height: 1.5;
        }
        .info-table td {
            padding: 0.2mm 0;
            vertical-align: top;
        }
        .info-table td.label {
            width: 22mm;
            color: #333;
        }
        .info-table td.value {
            font-weight: bold;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-top: 1.5mm;
        }
        .items-table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .items-table thead td {
            font-weight: bold;
            padding: 0.8mm 0.5mm;
        }
        .items-table tbody tr {
            border-bottom: 1px dotted #999;
        }
        .items-table tbody td {
            padding: 0.7mm 0.5mm;
            vertical-align: top;
        }
        .items-table td.col-produit { width: 34mm; }
        .items-table td.col-qty     { width: 8mm;  text-align: right; }
        .items-table td.col-pu      { width: 15mm; text-align: right; }
        .items-table td.col-total   { width: 15mm; text-align: right; }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 2mm;
            border-top: 1px dashed #000;
        }
        .totals-table td {
            padding: 0.4mm 0;
        }
        .totals-table td.tot-label { text-align: left; }
        .totals-table td.tot-value { text-align: right; font-weight: bold; }
        .totals-table tr.grand td {
            border-top: 1px solid #000;
            padding-top: 1mm;
            font-size: 9.5pt;
        }
        .totals-table tr.solde td {
            color: #000;
            font-size: 8.5pt;
        }

        .note-box {
            font-size: 7pt;
            margin-top: 2mm;
            border-top: 1px dotted #999;
            padding-top: 1mm;
            line-height: 1.4;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            font-size: 7pt;
            line-height: 1.6;
        }
        .footer .bonne-deg {
            font-size: 7pt;
            font-style: italic;
            margin-top: 1mm;
        }

        .cut-line {
            text-align: center;
            font-size: 7.5pt;
            margin: 3mm 0;
        }

        .copy-label {
            text-align: center;
            font-size: 6.5pt;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 2mm;
            border: 1px solid #000;
            padding: 0.8mm;
        }
    </style>
</head>
<body>

@for ($i = 0; $i < 2; $i++)

    <div class="copy-label">{{ $i === 0 ? 'COPIE CLIENT' : 'COPIE AKADI' }}</div>

    {{-- EN-TÊTE --}}
    <div class="header">
        <div class="logo-text">AKADI.CI</div>
        <div class="header-sub">
            07 58 83 83 38 &nbsp;|&nbsp; www.akadi.ci
        </div>
    </div>

    {{-- COMMANDE --}}
    <div class="section-title">COMMANDE</div>
    <table class="info-table">
        <tr>
            <td class="label">N° Cmd</td>
            <td class="value">#{{ $orders['code'] }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $orders['created_at']->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    {{-- CLIENT --}}
    <div class="section-title">CLIENT</div>
    <table class="info-table">
        <tr>
            <td class="label">Nom</td>
            <td class="value">{{ $orders['user']['name'] ?? $orders['client_name'] ?? 'Anonyme' }}</td>
        </tr>
        <tr>
            <td class="label">Téléphone</td>
            <td class="value">{{ $orders['user']['phone'] ?? $orders['client_phone'] ?? '-' }}</td>
        </tr>
    </table>

    {{-- ARTICLES --}}
    <table class="items-table">
        <thead>
            <tr>
                <td class="col-produit">Produit</td>
                <td class="col-qty">Qté</td>
                <td class="col-pu">P.U</td>
                <td class="col-total">Total</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders['products'] as $item)
                @php $total = $item['pivot']['quantity'] * $item['pivot']['unit_price']; @endphp
                <tr>
                    <td class="col-produit">{{ $item['title'] }}</td>
                    <td class="col-qty">{{ $item['pivot']['quantity'] }}</td>
                    <td class="col-pu">{{ number_format($item['pivot']['unit_price']) }}</td>
                    <td class="col-total">{{ number_format($total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <table class="totals-table">
        <tr>
            <td class="tot-label">Sous-total</td>
            <td class="tot-value">{{ number_format($orders['subtotal']) }} F</td>
        </tr>
        <tr>
            <td class="tot-label">Livraison</td>
            <td class="tot-value">{{ number_format($orders['delivery_price']) }} F</td>
        </tr>
        <tr class="grand">
            <td class="tot-label">TOTAL</td>
            <td class="tot-value">{{ number_format($orders['total']) }} FCFA</td>
        </tr>
        @if(!empty($orders['acompte']) && $orders['acompte'] > 0)
        <tr>
            <td class="tot-label">Acompte versé</td>
            <td class="tot-value">{{ number_format($orders['acompte']) }} F</td>
        </tr>
        @endif
        @if(!empty($orders['solde_restant']) && $orders['solde_restant'] > 0)
        <tr class="solde">
            <td class="tot-label">Reste à payer</td>
            <td class="tot-value">{{ number_format($orders['solde_restant']) }} F</td>
        </tr>
        @endif
    </table>

    {{-- NOTE (si présente) --}}
    {{-- @if(!empty($orders['note']))
    <div class="note-box">
        <b>Note :</b> {{ $orders['note'] }}
    </div>
    @endif --}}

    {{-- PIED --}}
    <div class="footer">
        <div>Imprimé le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
        <div class="bonne-deg">Bonne dégustation !</div>
    </div>

    @if ($i === 0)
        <div class="cut-line">- - - - - - - ✂ - - - - - - -</div>
    @endif

@endfor

</body>
</html>