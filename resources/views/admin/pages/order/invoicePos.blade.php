<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Ticket - {{ $orders->code }}</title>
    <style>
        /* Reset complet */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Styles d'impression 80mm */
        @page {
            size: 80mm auto;
            margin: 0mm;
        }

        /* Corps */
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            background: white;
            width: 80mm;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
        }

        /* Conteneur avec marges internes */
        .ticket {
            width: 100%;
            margin: 0;
            padding: 8px 6px 8px 6px;  /* Haut, Droite, Bas, Gauche */
            background: white;
        }

        /* Tout en gras */
        .ticket, .ticket * {
            font-weight: bold;
        }

        /* Centrage */
        .center {
            text-align: center;
        }
        
        /* Alignement droite */
        .right {
            text-align: right;
        }

        /* Espacements */
        .mt-1 { margin-top: 2px; }
        .mb-1 { margin-bottom: 2px; }
        .mt-2 { margin-top: 4px; }
        
        /* Séparateurs */
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .solid-line {
            border-top: 1px solid #000;
            margin: 4px 0;
        }
        .double-line {
            border-top: 2px solid #000;
            margin: 4px 0;
        }

        /* En-tête */
        .shop-name {
            font-size: 16px;
            letter-spacing: 2px;
        }
        .shop-infos {
            font-size: 9px;
        }

        /* Lignes d'information */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 3px;
            font-size: 11px;
        }

        /* Lignes articles */
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }
        .item-name {
            width: 55%;
            word-break: break-word;
        }
        .item-qty {
            width: 15%;
            text-align: center;
        }
        .item-price {
            width: 30%;
            text-align: right;
        }
        
        /* Prix unitaire */
        .unit-price {
            font-size: 9px;
            margin-bottom: 4px;
            margin-top: -2px;
            text-align: right;
        }
        
        /* Totaux */
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .grand-total {
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 4px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 9px;
        }
        .thankyou {
            font-size: 11px;
            margin: 5px 0;
        }
        
        /* Bouton d'impression */
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #000;
            color: #fff;
            text-align: center;
            cursor: pointer;
            font-family: Arial, sans-serif;
            border: none;
            border-radius: 4px;
        }
        
        @media print {
            .print-btn {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="ticket">
    <!-- En-tête -->
    <div class="center">
        <div class="shop-name">AKADI.CI</div>
        <div class="shop-infos">07 58 83 83 38</div>
        <div class="shop-infos">www.akadi.ci</div>
    </div>

    <div class="solid-line"></div>

    <!-- Infos commande -->
    <div class="info-row">
        <span>N°</span>
        <span>#{{ $orders->code }}</span>
    </div>
    <div class="info-row">
        <span>DATE</span>
        <span>{{ $orders->created_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="dashed-line"></div>

    <!-- Infos client -->
    <div class="info-row">
        <span>CLIENT</span>
        <span>{{ $orders->nom_client ?: 'COMPTE' }}</span>
    </div>
    @if($orders->tel_client)
    <div class="info-row">
        <span>TEL</span>
        <span>{{ $orders->tel_client }}</span>
    </div>
    @endif

    <div class="dashed-line"></div>

    <!-- En-tête articles -->
    <div class="item-row" style="border-bottom: 1px solid #000; margin-bottom: 4px; padding-bottom: 2px;">
        <span class="item-name">ARTICLE</span>
        <span class="item-qty">QTÉ</span>
        <span class="item-price">TOTAL</span>
    </div>
    
    <!-- Liste des articles -->
    @foreach ($orders->products as $item)
    <div class="item-row">
        <span class="item-name">{{ $item->title }}</span>
        <span class="item-qty">{{ $item->pivot->quantity }}</span>
        <span class="item-price">{{ number_format($item->pivot->quantity * $item->pivot->unit_price) }}</span>
    </div>
    <div class="unit-price">
        ({{ number_format($item->pivot->unit_price) }} x {{ $item->pivot->quantity }})
    </div>
    @endforeach

    <div class="double-line"></div>

    <!-- Totaux -->
    <div class="total-row">
        <span>SOUS-TOTAL</span>
        <span>{{ number_format($orders->subtotal) }} F</span>
    </div>
    <div class="total-row">
        <span>LIVRAISON</span>
        <span>{{ number_format($orders->delivery_price) }} F</span>
    </div>
    
    @if(($orders->acompte ?? 0) > 0)
    <div class="total-row">
        <span>ACOMPTE</span>
        <span>-{{ number_format($orders->acompte) }} F</span>
    </div>
    @endif
    
    <div class="grand-total total-row">
        <span>TOTAL</span>
        <span>{{ number_format($orders->total) }} F</span>
    </div>
    
    @if(($orders->solde_restant ?? 0) > 0)
    <div class="total-row" style="margin-top: 4px; font-size: 12px;">
        <span>RESTE</span>
        <span>{{ number_format($orders->solde_restant) }} F</span>
    </div>
    @endif

    <!-- Note -->
    @if(!empty($orders->note))
    <div class="dashed-line"></div>
    <div class="info-row">
        <span>NOTE</span>
        <span>{{ $orders->note }}</span>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="solid-line"></div>
        <div class="thankyou">MERCI</div>
        <div>BONNE DEGUSTATION</div>
        <div class="mt-1">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
    </div>
</div>

<button class="print-btn" onclick="window.print();">IMPRIMER</button>

<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>