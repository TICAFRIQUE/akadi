<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Facture-#{{ $orders['code'] }} </title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: 1px solid #ececec;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
            font-size: 12px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #000000;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 1px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 0px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 15px;
            line-height: 15px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: justify;

        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: justify;

        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #ffffff;
            font-weight: bold;

        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                {{-- https://akadi.ci/wp-content/uploads/2023/10/cropped-logo-site-ak.png --}}
                                <img src="https://akadi.ci/site/assets/img/custom/logo.png" width="50" />
                            </td>

                            <td>
                                N°Cmd #: {{ $orders['code'] }} <br />
                                Date Cmd: {{ $orders['created_at']->format('d-m-Y') }} <br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <span>SAV: <b>07 58 83 83 38</b> </span><br>
                                <span>Email: <b>info@akadi.ci</b> </span><br>
                                <span>Adresse: <b>Plateau Dokui</b> </span><br>

                            </td>

                            <td>
                                <span>Nom: <b>{{ $orders['user']['name'] }}</b> </span><br>
                                <span>Email: <b>{{ $orders['user']['email'] }}</b> </span><br>
                                <span>Téléphone: <b>{{ $orders['user']['phone'] }}</b> </span><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- <tr class="heading">
                <td>Payment Method</td>

                <td>Check #</td>
            </tr> --}}

            {{-- <tr class="details">
                <td>Check</td>

                <td>1000</td>
            </tr> --}}

            <tr class="heading">
                <td>Produit</td>
                <td>Qté</td>
                <td>Pu</td>
                <td>Total</td>
            </tr>
            @foreach ($orders['products'] as $item)
                <tr class="item">
                    <td> {{ $item['title'] }}</td>
                    <td>{{ $item['pivot']['quantity'] }} </td>
                    <td> {{ number_format($item['pivot']['unit_price']) }}</td>
                    @php
                        $total = $item['pivot']['quantity'] * $item['pivot']['unit_price'];
                    @endphp
                    <td> {{ number_format($total) }}</td>

                </tr>
            @endforeach
            <tr class="total" style="text-align:right;">
                <td colspan="4" style="padding-right:40px; padding-top:10px"> <b>Sous-total</b>:
                    {{ number_format($orders['subtotal']) }} FCFA</td>
            </tr>

            <tr class="total" style="text-align:right;">
                <td colspan="4" style="padding-right:40px;"> <b>Livraison</b>:
                    {{ number_format($orders['delivery_price']) }} FCFA</td>


            </tr>

            <tr class="total" style="text-align:right;">
                <td colspan="4" style="padding-right:40px;"> <b>TOTAL</b>:
                    {{ number_format($orders['total']) }} FCFA</td>
            </tr>
        </table>
    </div>
</body>

</html>
