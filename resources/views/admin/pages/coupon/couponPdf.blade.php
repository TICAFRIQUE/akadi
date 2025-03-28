<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: 33.33%;
            /* 3 coupons par ligne */
            padding: 10px;
            vertical-align: top;
        }

        .coupon {
            width: 100%;
            border: 2px dashed #ff0000;
            padding: 10px;
            text-align: center;
            background-color: #fff;
            box-sizing: border-box;
        }

        .logo img {
            width: 50px;
            margin-bottom: 5px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            color: #ff0000;
        }

        .discount {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }

        .details,
        .code,
        .expiry {
            font-size: 12px;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 10px;
            font-size: 12px;
            color: orange;
            font-weight: bold;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <table>
        @php $count = 0; @endphp
        @foreach (range(1, $quantite) as $i)
            @if ($count % 3 == 0)
                <tr>
            @endif

            <td>
                <div class="coupon">
                    <!-- Logo -->
                    <div class="logo">
                        <img src="https://akadi.ci/site/assets/img/custom/logo.png" alt="Akadi Logo">
                    </div>

                    <div class="title"> COUPON SPÉCIAL {{ $coupon->nom }} </div>
                    <div class="discount">{{ $coupon->valeur_remise }} {{ $coupon->type_remise == 'pourcentage' ? '%' : 'FCFA' }} de réduction</div>
                    {{-- <div class="details">Sur : {{ $coupon->nom }}</div> --}}
                    <div class="code"><strong>CODE :</strong> {{ $coupon->code }}</div>
                    <div class="expiry">Expire le :
                        {{ \Carbon\Carbon::parse($coupon->date_expiration)->format('d/m/Y') }}</div>

                    <!-- QR Code -->
                    {{-- <div class="qr-code">
                        <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{ urlencode('https://www.akadi.ci') }}"
                            alt="QR Code">
                    </div> --}}

                    <!-- Commande -->
                    <div class="footer">Commandez sur <a href="https://www.akadi.ci"
                            style="color: orange; text-decoration: none;">www.akadi.ci</a></div>
                </div>
            </td>

            @php $count++; @endphp
            @if ($count % 3 == 0)
                </tr>
            @endif
        @endforeach
    </table>

</body>

</html>
