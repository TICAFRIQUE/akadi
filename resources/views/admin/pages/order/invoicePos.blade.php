<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Recu #{{ $orders->code }}</title>
    <style>
        /* ── Reset ── */
        * { margin: 0; padding: 0; }

        /* ── Corps général (aperçu navigateur) ── */
        body {
            background: #e5e5e5;
            display: flex;
            justify-content: center;
            padding: 20px;
            font-family: 'Courier New', Courier, monospace;
        }

        .receipt-wrap {
            background: #fff;
            width: 302px; /* 80mm ≈ 302px à 96dpi */
            padding: 10px 8px;
        }

        /* ── En-tête ── */
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .logo-text {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 3px;
        }
        .header-sub {
            font-size: 10px;
            margin-top: 3px;
        }

        /* ── Étiquette copie ── */
        .copy-label {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1px;
            border: 1px solid #000;
            padding: 2px 0;
            margin-bottom: 6px;
        }

        /* ── Titres de section ── */
        .section-title {
            font-weight: bold;
            font-size: 9px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 3px;
            margin-top: 6px;
        }

        /* ── Tableau infos (2 colonnes) ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            line-height: 1.6;
        }
        .info-table td { padding: 0; vertical-align: top; }
        .info-table td.label { width: 80px; }
        .info-table td.value { font-weight: bold; text-align: right; }

        /* ── Tableau articles ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 5px;
        }
        .items-table thead tr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .items-table thead td { font-weight: bold; padding: 2px 0; }
        .items-table tbody tr { border-bottom: 1px dotted #aaa; }
        .items-table tbody td { padding: 2px 0; vertical-align: top; }
        .items-table td.col-produit { width: 120px; }
        .items-table td.col-qty     { width: 28px; text-align: right; }
        .items-table td.col-pu      { width: 56px; text-align: right; }
        .items-table td.col-total   { width: 62px; text-align: right; }

        /* ── Totaux ── */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 5px;
            border-top: 1px dashed #000;
            padding-top: 3px;
        }
        .totals-table td { padding: 1px 0; }
        .totals-table td.tot-label { text-align: left; }
        .totals-table td.tot-value { text-align: right; font-weight: bold; }
        .totals-table tr.grand td {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-size: 11px;
        }

        /* ── Note ── */
        .note-box {
            font-size: 8px;
            margin-top: 5px;
            border-top: 1px dotted #aaa;
            padding-top: 3px;
            line-height: 1.4;
        }

        /* ── Pied ── */
        .footer {
            text-align: center;
            margin-top: 8px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 8px;
            line-height: 1.6;
        }
        .footer .bonne-deg { font-style: italic; }

        /* ── Séparateur de coupe ── */
        .cut-line {
            text-align: center;
            font-size: 9px;
            margin: 8px 0;
            color: #555;
        }

        /* ── Bouton impression (masqué à l'impression) ── */
        .btn-print {
            display: block;
            margin: 15px auto 0;
            padding: 8px 24px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            letter-spacing: 1px;
        }

        /* ════════════════════════════
           STYLES D'IMPRESSION
        ════════════════════════════ */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                background: none;
                display: block;
                padding: 0;
            }

            .receipt-wrap {
                width: 100%;
                padding: 4mm 3mm;
            }

            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt-wrap">

    @for ($i = 0; $i < 2; $i++)

    <div class="copy-label">{{ $i === 0 ? 'COPIE CLIENT' : 'COPIE MARCHAND' }}</div>

    {{-- EN-TÊTE --}}
    <div class="header">
        <div class="logo-text">AKADI.CI</div>
        <div class="header-sub">07 58 83 83 38 | www.akadi.ci</div>
    </div>

    {{-- COMMANDE --}}
    <div class="section-title">COMMANDE</div>
    <table class="info-table">
        <tr>
            <td class="label">N° Cmd</td>
            <td class="value">#{{ $orders->code }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $orders->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    {{-- CLIENT --}}
    <div class="section-title">CLIENT</div>
    <table class="info-table">
        <tr>
            <td class="label">Nom</td>
            <td class="value">{{ $orders->nom_client }}</td>
        </tr>
        <tr>
            <td class="label">Tel</td>
            <td class="value">{{ $orders->tel_client ?: '-' }}</td>
        </tr>
    </table>

    {{-- ARTICLES --}}
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
            @foreach ($orders->products as $item)
                @php $total = $item->pivot->quantity * $item->pivot->unit_price; @endphp
                <tr>
                    <td class="col-produit">{{ $item->title }}</td>
                    <td class="col-qty">{{ $item->pivot->quantity }}</td>
                    <td class="col-pu">{{ number_format($item->pivot->unit_price) }}</td>
                    <td class="col-total">{{ number_format($total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <table class="totals-table">
        <tr>
            <td class="tot-label">Sous-total</td>
            <td class="tot-value">{{ number_format($orders->subtotal) }} F</td>
        </tr>
        <tr>
            <td class="tot-label">Livraison</td>
            <td class="tot-value">{{ number_format($orders->delivery_price) }} F</td>
        </tr>
        <tr class="grand">
            <td class="tot-label">TOTAL</td>
            <td class="tot-value">{{ number_format($orders->total) }} FCFA</td>
        </tr>
        @if(($orders->acompte ?? 0) > 0)
        <tr>
            <td class="tot-label">Acompte</td>
            <td class="tot-value">{{ number_format($orders->acompte) }} F</td>
        </tr>
        @endif
        @if(($orders->solde_restant ?? 0) > 0)
        <tr>
            <td class="tot-label">Reste a payer</td>
            <td class="tot-value">{{ number_format($orders->solde_restant) }} F</td>
        </tr>
        @endif
    </table>

    {{-- NOTE --}}
    @if(!empty($orders->note))
    <div class="note-box">Note : {{ $orders->note }}</div>
    @endif

    {{-- PIED --}}
    <div class="footer">
        <div>Imprime le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
        <div class="bonne-deg">Bonne degustation !</div>
    </div>

    {{-- SÉPARATEUR --}}
    @if($i === 0)
    <div class="cut-line">- - - - - - - - - - - - - - - - - -</div>
    @endif

    @endfor

</div>

{{-- Bouton impression visible dans le navigateur --}}
<button class="btn-print" onclick="window.print()">Imprimer</button>

<script>
    // Auto-impression à l'ouverture de la page
    window.addEventListener('load', function () {
        window.print();
    });
</script>

</body>
</html>