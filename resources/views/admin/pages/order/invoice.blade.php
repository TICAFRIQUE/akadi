@extends('admin.layouts.app')

@section('title', 'order')
@section('sub-title', 'Facture')


@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        body {
            font-family: 'Montserrat', sans-serif
        }

        .card {
            border: none
        }

        .totals tr td {
            font-size: 13px
        }

        .product-qty span {
            font-size: 12px;
            color: rgb(56, 56, 56)
        }
    </style>



    <div class="container mt-5 mb-5">

        <div class="row d-flex justify-content-center">

            <div class="col-md-8">
                <div class="py-3 d-flex justify-content-between">
                    <a class="btn btn-link  py-3" onclick="history.go(-1)" href="#"><i
                            data-feather="arrow-left"></i>Retour</a>
                    <a class="btn btn-secondary text-dark" style="margin-bottom: 10px" href="" id="print"> <i
                            data-feather="printer"></i>Imprimer</a>

                </div>

                {{-- <a class="btn btn-dark text-white" style="margin-bottom: 10px" href="#" id="download"> <i
                        data-feather="download"></i>Télecharger</a> --}}

                <div class="card" id="div_print">

                    <div class="text-center logo p-2 px-5">
                        <img src="{{ asset('site/assets/img/custom/logo.png') }}" width="25%">
                    </div>
                    <div class="invoice p-5" style="box-shadow: none">

                        <h5>Votre commande est confirmée !</h5>

                        <span class="font-weight-bold d-block mt-4">Salut, {{ $orders['user']['name'] }} </span>
                        {{-- <span>You order has been confirmed and will be shipped in next two days!</span> --}}

                        <div class="payment border-top mt-3 mb-3 border-bottom table-responsive">

                            <table class="table table-borderless">

                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="py-2">

                                                <span class="d-block text-muted">Date commande</span>
                                                <span>{{ \Carbon\Carbon::parse($orders['created_at'])->isoFormat('dddd D MMMM YYYY') }}</span>

                                            </div>
                                        </td>

                                        <td>
                                            <div class="py-2">

                                                <span class="d-block text-muted">No commande</span>
                                                <span class="order_num">{{ $orders['code'] }} </span>

                                            </div>
                                        </td>

                                        <td>
                                            <div class="py-2">

                                                <span class="d-block text-muted">Payment</span>
                                                <span>{{ $orders['payment method'] }} </span>

                                            </div>
                                        </td>

                                        <td>
                                            <div class="py-2">

                                                <span class="d-block text-muted">Adresse de livraison</span>
                                                <span>{{ $orders['delivery_name'] }}</span>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <div class="product border-bottom table-responsive">

                            <table class="table table-borderless">

                                <tbody>
                                    @php
                                        //pu * qte
                                        $price = 0;
                                        $subTotal = 0;
                                    @endphp
                                    @foreach ($orders['products'] as $item)
                                        @php
                                            
                                             $price =  $item['pivot']['quantity'] * $item['pivot']['unit_price'] ;
                                              $subTotal +=$price
                                        @endphp
                                            
                                        <tr>
                                            <td width="20%">

                                                <img src="{{ $item->getFirstMediaUrl('product_image') }}" width="50">

                                            </td>

                                            <td width="60%">
                                                <span class="font-weight-bold">{{ $item['title'] }} </span>
                                                <div class="product-qty">
                                                    <span class="d-block">Quantity: {{ $item['pivot']['quantity'] }}</span>
                                                    <span
                                                        class="fst-italic">{{ $item['pivot']['options'] && is_numeric($item['pivot']['options'][0]) ? 'Pointure: ' : ($item['pivot']['options'] && ctype_alpha($item['pivot']['options'][0]) ? 'Taille:' : '') }}
                                                        {{ $item['pivot']['options'] }}</span>

                                                </div>
                                            </td>
                                            <td width="20%">
                                                <div class="text-right">
                                                    <span
                                                        class="font-weight-bold">{{ number_format($item['pivot']['unit_price']) }}
                                                        FCFA</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>




                        <div class="row d-flex justify-content-end">

                            <div class="col-md-5">

                                <table class="table table-borderless">

                                    <tbody class="totals">

                                        <tr>
                                            <td>
                                                <div class="text-left">

                                                    <span class="text-muted">Sous total</span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-right">
                                                    <span>{{ number_format($subTotal) }} </span>
                                                </div>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <div class="text-left">

                                                    <span class="text-muted">Frais de livraison</span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-right">
                                                    <span>{{ $orders['delivery_price'] }}</span>
                                                </div>
                                            </td>
                                        </tr>


                                        {{-- <tr>
                                            <td>
                                                <div class="text-left">

                                                    <span class="text-muted">Tax Fee</span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-right">
                                                    <span>$7.65</span>
                                                </div>
                                            </td>
                                        </tr> --}}


                                        {{-- <tr>
                                            <td>
                                                <div class="text-left">

                                                    <span class="text-muted">Remise</span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-right">
                                                    <span class="text-success">00</span>
                                                </div>
                                            </td>
                                        </tr> --}}


                                        <tr class="border-top border-bottom">
                                            <td>
                                                <div class="text-left">

                                                    <span class="font-weight-bold">Total</span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-right">
                                                    @php
                                                        $delivery_price = str_replace("," , "" ,$orders['delivery_price']);
                                                        $delivery_price =  str_replace("FCFA" , "" ,$delivery_price);
                                                        $total = $delivery_price + $subTotal ;
                                                    @endphp
                                                    <span class="font-weight-bold">{{number_format($total)}} FCFA </span>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>



                        </div>


                        <p class="font-weight-bold mb-0">Merci d'avoir fait vos achats chez nous !</p>
                        {{-- <span>Nike Team</span> --}}


                    </div>

                </div>

            </div>

        </div>

    </div>


@section('script')
    <script src="{{ asset('admin/assets/js/jQuery.print.js') }}"></script>
@endsection
<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    $('#print').click(function(e) {
        var numOrder = $('.order_num').html();

        e.preventDefault();
        $("#div_print").print({
            globalStyles: true,
            mediaPrint: true,
            stylesheet: null,
            format: "a6",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: 'Facture #' + numOrder,
            doctype: '<!doctype html>'
        });

    });


    // $('#download').click(function() {
    //     var numOrder = $('.order_num').html();
    //         var pdf = new jsPDF();
    //         var specialElementHandlers = {
    //             '#editor': function(element, renderer) {
    //                 return true;
    //             }
    //         };
    //         pdf.fromHTML($('#div_print').html(), 15, 15, {
    //             // 'width': 500,
    //             // 'elementHandlers': specialElementHandlers
    //         });
    //         pdf.save(numOrder + '.pdf');
    //     });
</script>
@endsection
