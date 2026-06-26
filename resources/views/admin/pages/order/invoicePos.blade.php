<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Ticket - {{ $orders->code }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            background: #d1d5db;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 30px 16px 60px;
            font-family: 'Courier New', 'Lucida Console', monospace;
            color: #000;
        }

        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 9px 22px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        .btn-print {
            background: #111;
            color: #fff;
        }

        .btn-close {
            background: #fff;
            color: #111;
            border: 1px solid #aaa;
        }

        .ticket {
            background: #fff;
            width: 302px;
            padding: 12px 10px 14px;
            font-size: 11px;
            line-height: 1.4;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
            position: relative;
        }

        .ticket::before {
            content: '';
            display: block;
            position: absolute;
            top: -8px;
            left: 0;
            right: 0;
            height: 8px;
            background: radial-gradient(circle at 6px -2px, #d1d5db 6px, white 6px) top left / 12px 8px repeat-x;
        }

        .ticket::after {
            content: '';
            display: block;
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 8px;
            background: radial-gradient(circle at 6px 10px, #d1d5db 6px, white 6px) bottom left / 12px 8px repeat-x;
        }

        .ticket * {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        /* Un seul séparateur simple */
        .sep {
            border-top: 1px dashed #999;
            margin: 6px 0;
        }

        .shop-name {
            font-size: 18px;
            letter-spacing: 4px;
            margin-bottom: 2px;
        }

        .shop-sub {
            font-size: 9px;
            line-height: 1.5;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .row .lbl {
            color: #666;
            font-size: 9px;
        }

        .row .val {
            text-align: right;
        }

        /* Articles */
        .items-head {
            display: flex;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 3px;
            font-size: 9px;
        }

        .h-name {
            width: 50%;
        }

        .h-qty {
            width: 12%;
            text-align: center;
        }

        .h-pu {
            width: 20%;
            text-align: right;
        }

        .h-tot {
            width: 18%;
            text-align: right;
        }

        .item-line {
            display: flex;
            margin-bottom: 3px;
            font-size: 10px;
        }

        .i-name {
            width: 50%;
            word-break: break-word;
        }

        .i-qty {
            width: 12%;
            text-align: center;
        }

        .i-pu {
            width: 20%;
            text-align: right;
        }

        .i-tot {
            width: 18%;
            text-align: right;
        }

        /* Totaux */
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 2px;
        }

        .total-row.grand {
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 9px;
            line-height: 1.6;
        }

        .footer .deg {
            font-size: 10px;
            font-style: italic;
            margin-bottom: 2px;
        }

        @media print {
            body {
                background: none;
                display: block;
                padding: 0;
            }

            .action-bar {
                display: none;
            }

            .ticket {
                width: 100%;
                box-shadow: none;
                padding: 3mm 2mm;
            }

            .ticket::before,
            .ticket::after {
                display: none;
            }
        }

        @media print {

            #qrcode img,
            #qrcode canvas {
                display: block !important;
            }
        }
    </style>
</head>

<body>

    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">&#128438; Imprimer</button>
        <button class="btn btn-close" onclick="window.close()">&#10005; Fermer</button>
    </div>

    <div class="ticket">

        {{-- En-tête --}}
        <div class="center">
            <div class="shop-name"><img src="{{ asset('site/assets/img/custom/AKADI.png') }}" width="60px"
                    alt="logo akadi"></div>
            <div class="shop-sub">07 58 83 83 38 &nbsp;|&nbsp; www.akadi.ci</div>
        </div>

        <div class="sep"></div>

        {{-- Commande --}}
        <div class="row">
            <span class="lbl">N° CMD</span>
            <span class="val">#{{ $orders->code }}</span>
        </div>
        <div class="row">
            <span class="lbl">DATE</span>
            <span class="val">{{ $orders->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row">
            <span class="lbl">CLIENT</span>
            <span class="val">{{ $orders->nom_client }}</span>
        </div>
        @if ($orders->tel_client)
            <div class="row">
                <span class="lbl">TEL</span>
                <span class="val">{{ $orders->tel_client }}</span>
            </div>
        @endif

        <div class="sep"></div>

        {{-- Articles --}}
        <div class="items-head">
            <span class="h-name">ARTICLE</span>
            <span class="h-qty">QTE</span>
            <span class="h-pu">P.U</span>
            <span class="h-tot">TOTAL</span>
        </div>

        @foreach ($orders->products as $item)
            @php
                $hasLineDiscount = ($item->pivot->discount ?? 0) > 0;
                $pu              = $item->pivot->prix_apres_remise ?? $item->pivot->unit_price;
                $lineTotal       = $item->pivot->total ?? ($item->pivot->quantity * $pu);
            @endphp
            <div class="item-line">
                <span class="i-name">
                    {{ $item->title }}
                    @if ($hasLineDiscount)
                        <br><span style="font-size:8px;color:#666;font-weight:normal">
                            remise {{ $item->pivot->type_discount === 'percent' ? $item->pivot->discount . '%' : format_price($item->pivot->discount) . ' F' }}
                        </span>
                    @endif
                </span>
                <span class="i-qty">{{ $item->pivot->quantity }}</span>
                <span class="i-pu">{{ format_price($pu) }}</span>
                <span class="i-tot">{{ format_price($lineTotal) }}</span>
            </div>
        @endforeach

        <div class="sep"></div>

        {{-- Totaux --}}
        <div class="total-row">
            <span>SOUS-TOTAL</span>
            <span>{{ format_price($orders->subtotal) }} F</span>
        </div>
        @if (($orders->discount ?? 0) > 0)
            @php
                $discountAmount = $orders->type_discount === 'percent'
                    ? round($orders->subtotal * $orders->discount / 100)
                    : $orders->discount;
            @endphp
            <div class="total-row">
                <span>REMISE{{ $orders->type_discount === 'percent' ? ' (' . $orders->discount . '%)' : '' }}</span>
                <span>- {{ format_price($discountAmount) }} F</span>
            </div>
        @endif
        <div class="total-row">
            <span>LIVRAISON</span>
            <span>{{ format_price($orders->delivery_price) }} F</span>
        </div>
        <div class="total-row grand">
            <span>TOTAL</span>
            <span>{{ format_price($orders->total) }} FCFA</span>
        </div>
        @if (($orders->acompte ?? 0) > 0)
            <div class="total-row" style="margin-top:3px">
                <span>ACOMPTE</span>
                <span>{{ format_price($orders->acompte) }} F</span>
            </div>
        @endif
        @if (($orders->solde_restant ?? 0) > 0)
            <div class="total-row" style="font-size:11px">
                <span>RESTE A PAYER</span>
                <span>{{ format_price($orders->solde_restant) }} F</span>
            </div>
        @endif

        @if (!empty($orders->note))
            <div class="sep"></div>
            <div class="row">
                <span class="lbl">NOTE</span>
                <span class="val">{{ $orders->note }}</span>
            </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <div class="sep"></div>
            <div class="deg">Bonne dégustation !</div>

            {{-- QR Code --}}
            <div id="qrcode" style="display:flex;justify-content:center;margin:6px 0 4px;"></div>
            <div style="font-size:8px;margin-bottom:3px;">Scannez pour visiter notre site</div>

            <div>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
        </div>

    </div>

    <script>
        window.onload = function() {
            new QRCode(document.getElementById("qrcode"), {
                text: "https://www.akadi.ci",
                width: 80,
                height: 80,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });

            // Petit délai pour laisser le QR code se générer avant l'impression
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>

</body>

</html>
