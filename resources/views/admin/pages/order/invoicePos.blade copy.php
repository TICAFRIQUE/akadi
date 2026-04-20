<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Ticket - {{ $orders->code }}</title>
    <style>
        /* Reset complet pour l'impression */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Styles d'impression 80mm (XP-80) */
        @page {
            size: 80mm auto; /* Largeur fixe 80mm, hauteur auto */
            margin: 0mm;     /* Pas de marge pour occuper toute la largeur */
        }

        /* Corps de la page */
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            background: white;
            width: 80mm;      /* Largeur exacte de l'imprimante */
            margin: 0 auto;
            padding: 0mm;
            font-size: 12px;  /* Taille de base plus grande */
            line-height: 1.4;
            color: #000;
        }

        /* Conteneur principal du ticket */
        .ticket {
            width: 100%;
            padding: 2mm 3mm; /* Léger padding intérieur */
            background: white;
        }

        /* --- Styles généraux --- */
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        
        /* Séparateurs */
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .solid-line {
            border-top: 2px solid #000;
            margin: 6px 0;
        }
        .dots-line {
            border-top: 1px dotted #000;
            margin: 4px 0;
        }

        /* En-tête */
        .shop-name {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .shop-infos {
            font-size: 10px;
            margin-top: 2px;
        }
        .copy-type {
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #000;
            display: inline-block;
            padding: 2px 10px;
            margin: 6px 0;
            letter-spacing: 2px;
        }

        /* Blocs d'information */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }
        .info-label {
            font-weight: bold;
        }

        /* Tableau des articles - Version Flex pour meilleur contrôle */
        .item-header, .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }
        .item-header {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 6px;
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
        
        /* Totaux */
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 13px;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 6px;
            margin-top: 6px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 12px;
        }
        .thankyou {
            font-weight: bold;
            font-size: 13px;
            margin: 8px 0 4px;
        }
        
        /* Bouton d'impression (caché à l'impression) */
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
    
    @for ($copy = 0; $copy < 2; $copy++)
        
        <!-- Entête -->
        <div class="center">
            <div class="shop-name">AKADI.CI</div>
            <div class="shop-infos">07 58 83 83 38</div>
            <div class="shop-infos mb-1">www.akadi.ci</div>
            <div class="copy-type">{{ $copy === 0 ? 'COPIE CLIENT' : 'COPIE MARCHAND' }}</div>
        </div>

        <!-- Infos commande -->
        <div class="mt-2">
            <div class="info-row">
                <span class="info-label">COMMANDE N°</span>
                <span class="bold">#{{ $orders->code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">DATE</span>
                <span>{{ $orders->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Infos client -->
        <div>
            <div class="info-row">
                <span class="info-label">CLIENT</span>
                <span>{{ $orders->nom_client ?: 'CLIENT COMPTE' }}</span>
            </div>
            @if($orders->tel_client)
            <div class="info-row">
                <span class="info-label">TEL</span>
                <span>{{ $orders->tel_client }}</span>
            </div>
            @endif
        </div>

        <div class="dashed-line"></div>

        <!-- Liste des articles -->
        <div>
            <div class="item-header">
                <span class="item-name">ARTICLE</span>
                <span class="item-qty">Qté</span>
                <span class="item-price">Prix</span>
            </div>
            
            @foreach ($orders->products as $item)
            <div class="item-row">
                <span class="item-name">{{ $item->title }}</span>
                <span class="item-qty">{{ $item->pivot->quantity }}</span>
                <span class="item-price">{{ number_format($item->pivot->quantity * $item->pivot->unit_price) }}</span>
            </div>
            <!-- Optionnel: afficher le prix unitaire en petit -->
            <div class="info-row" style="font-size: 10px; margin-top: -2px; margin-bottom: 6px;">
                <span></span>
                <span></span>
                <span class="item-price">({{ number_format($item->pivot->unit_price) }} F x {{ $item->pivot->quantity }})</span>
            </div>
            @endforeach
        </div>

        <div class="solid-line"></div>

        <!-- Totaux -->
        <div>
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
                <span>- {{ number_format($orders->acompte) }} F</span>
            </div>
            @endif
            
            <div class="grand-total total-row">
                <span>TOTAL</span>
                <span>{{ number_format($orders->total) }} FCFA</span>
            </div>
            
            @if(($orders->solde_restant ?? 0) > 0)
            <div class="total-row" style="margin-top: 4px;">
                <span>RESTE À PAYER</span>
                <span class="bold">{{ number_format($orders->solde_restant) }} F</span>
            </div>
            @endif
        </div>

        <!-- Note -->
        @if(!empty($orders->note))
        <div class="dots-line"></div>
        <div class="info-row" style="font-size: 11px;">
            <span>📝 NOTE : {{ $orders->note }}</span>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Imprimé le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
            <div class="thankyou">MERCI & BONNE DÉGUSTATION !</div>
            <div>--- Akadi.ci, votre saveur au sommet ---</div>
        </div>

        <!-- Séparateur entre les deux copies -->
        @if($copy === 0)
        <div class="center dashed-line" style="margin: 8px 0; letter-spacing: 2px;">
            ✂️ DÉCOUPER ICI ✂️
        </div>
        @endif
        
    @endfor
</div>

<!-- Bouton d'impression visible uniquement à l'écran -->
<button class="print-btn" onclick="window.print();">🖨️ IMPRIMER LE TICKET</button>

<script>
    // Impression automatique dès le chargement
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>