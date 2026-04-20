<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Facture-#{{ $orders['code'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            color: #000;
            background: #fff;
            width: 72mm;
            margin: 0 auto;
        }

        .receipt {
            width: 72mm;
            padding: 3mm 0;
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
            font-size: 8pt;
            margin-top: 1mm;
        }

        .section-title {
            font-weight: bold;
            font-size: 8pt;
            border-bottom: 1px solid #000;
            padding-bottom: 0.5mm;
            margin-bottom: 1mm;
            margin-top: 2mm;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            line-height: 1.6;
        }
        .info-table td {
            padding: 0.2mm 0;
            vertical-align: top;
        }
        .info-table td.label {
            width: 24mm;
        }
        .info-table td.value {
            font-weight: bold;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 2mm;
        }
        .items-table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .items-table thead td {
            font-weight: bold;
            padding: 1mm 0;
        }
        .items-table tbody tr {
            border-bottom: 1px dotted #999;
        }
        .items-table tbody td {
            padding: 0.8mm 0;
            vertical-align: top;
        }
        .items-table td.col-produit { width: 32mm; }
        .items-table td.col-qty     { width: 8mm;  text-align: right; }
        .items-table td.col-pu      { width: 15mm; text-align: right; }
        .items-table td.col-total   { width: 17mm; text-align: right; }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-top: 2mm;
            border-top: 1px dashed #000;
        }
        .totals-table td {
            padding: 0.5mm 0;
        }
        .totals-table td.tot-label { text-align: left; }
        .totals-table td.tot-value { text-align: right; font-weight: bold; }
        .totals-table tr.grand td {
            border-top: 1px solid #000;
            padding-top: 1.5mm;
            font-size: 10pt;
        }

        .note-box {
            font-size: 7.5pt;
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
            font-size: 7.5pt;
            line-height: 1.6;
        }
        .footer .bonne-deg {
            font-style: italic;
        }

        .cut-line {
            text-align: center;
            font-size: 8pt;
            margin: 3mm 0;
        }

        .copy-label {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1px;
            border: 1px solid #000;
            padding: 1mm 0;
            margin-bottom: 2mm;
        }
    </style>
</head>
<body>

@for ($i = 0; $i < 2; $i++)
<div class="receipt">

    <div class="copy-label">{{ $i === 0 ? 'COPIE CLIENT' : 'COPIE MARCHAND' }}</div>

    <div class="header">
        <div class="logo-text">AKADI.CI</div>
        <div class="header-sub">07 58 83 83 38 | www.akadi.ci</div>
    </div>

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

    <div class="section-title">CLIENT</div>
    <table class="info-table">
        <tr>
            <td class="label">Nom</td>
            <td class="value">{{ $orders['user']['name'] ?? $orders['client_name'] ?? 'Anonyme' }}</td>
        </tr>
        <tr>
            <td class="label">Tel</td>
            <td class="value">{{ $orders['user']['phone'] ?? $orders['client_phone'] ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <td class="col-produit">Produit</td>
                <td class="col-qty">Qte</td>
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
            <td class="tot-label">Acompte</td>
            <td class="tot-value">{{ number_format($orders['acompte']) }} F</td>
        </tr>
        @endif
        @if(!empty($orders['solde_restant']) && $orders['solde_restant'] > 0)
        <tr>
            <td class="tot-label">Reste a payer</td>
            <td class="tot-value">{{ number_format($orders['solde_restant']) }} F</td>
        </tr>
        @endif
    </table>

    @if(!empty($orders['note']))
    <div class="note-box">Note : {{ $orders['note'] }}</div>
    @endif

    <div class="footer">
        <div>Imprime le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
        <div class="bonne-deg">Bonne degustation !</div>
    </div>

</div>
    @if ($i === 0)
        <div class="cut-line">- - - - - - - - - - - - - - - - - -</div>
    @endif
@endfor

</body>
</html>