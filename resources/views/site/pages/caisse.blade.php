@extends('site.layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Caisse</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Resumé de ma commande</li>
                </ul>
            </div>
        </div>
    </div>


    @include('admin.components.validationMessage')

    <!-- ========== Start panier ========== -->
    <div class="th-cart-wrapper mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message">Panier <span
                                class="quantityProduct">({{ count((array) session('cart')) }})</span></div>
                    </div>
                    @php $sousTotal = 0 @endphp

                    @if (session('cart'))
                        <form action="#" class="woocommerce-cart-form">
                            <table class="cart_table">
                                <thead>
                                    <tr>
                                        <th class="cart-col-image">Produit</th>
                                        <th class="cart-col-price">Prix</th>
                                        <th class="cart-col-quantity">Quantité</th>
                                        <th class="cart-col-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach (session('cart') as $id => $details)
                                        @php $sousTotal += $details['price'] * $details['quantity'] @endphp

                                        <tr class="cart_item" id="row_{{ $id }}">
                                            <td data-title="Product">
                                                <a class="cart-productimage"
                                                    href="{{ route('detail-produit', $details['slug']) }}"><img
                                                        width="91" height="91" src="{{ $details['image'] }} "
                                                        alt="Image">
                                                    <br> <span>{{ $details['title'] }}</span>
                                                </a>
                                            </td>
                                            <td data-title="Price">
                                                <span class="amount text-dark"><bdi>
                                                        {{ number_format($details['price'], 0) }}
                                                        <span></span>
                                                    </bdi></span>
                                            </td>
                                            <td data-title="Quantity">
                                                <span> {{ $details['quantity'] }} </span>
                                            </td>
                                            <td data-title="Total">
                                                @php
                                                    $total = $details['price'] * $details['quantity'];
                                                @endphp
                                                <span class="amount"><bdi><span id="totalPriceQty{{ $id }}">
                                                            {{ number_format($total) }}
                                                        </span>FCFA</bdi></span>
                                            </td>

                                        </tr>
                                    @endforeach


                                    <tr>
                                        <td colspan="6" class="actions">
                                            {{-- <button type="submit" class="th-btn rounded-2">Update cart</button> --}}
                                            <a href="{{ route('liste-produit') }}" class="th-btn rounded-2 w-100">Continuer
                                                les achats</a>
                                            {{-- <div class="th-cart-coupon">
                                                <input type="text" class="form-control" placeholder="Coupon Code...">
                                                <button type="submit" class="th-btn rounded-2">Appliquer</button>
                                            </div> --}}

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    @else
                        <p>Vous n'avez pas de produits dans votre panier</p>
                        <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">Commandez maintenant</a>

                    @endif
                </div>

                @if (session('cart'))
                    <div class="col-md-4">
                        <div class="row">
                            <div class="">
                                <h2 class="h4 summary-title">Resumé du panier</h2>

                                <div class="mb-3">
                                    <h6>Informations du client</h6>
                                    <span>Nom : {{ Auth::user()->name }} </span>
                                    <br> <span>Telephone : {{ Auth::user()->phone }} </span>
                                    <br><span class="{{ Auth::user()->email ?? 'd-none ' }}">Email :
                                        {{ Auth::user()->email }} </span>
                                </div>
                                <table class="cart_totals" cellspacing="0">
                                    <tbody>

                                        <tr>
                                            <td>Sous total</td>
                                            <td data-title="Total">
                                                <span data-subTotal="{{ $sousTotal }}" class="amount sousTotal">
                                                    <h6 class="text-danger">{{ number_format($sousTotal) }} FCFA</h6>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Livraison
                                                <span class=" text-danger delivery_name"></span>
                                            </td>
                                            <td data-title="Total">
                                                <span class="amount delivery_price">
                                                    <h6 class="text-danger delivery_price">0 FCFA</h6>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="shipping">
                                            {{-- <th>Lieu de livraison</th> --}}
                                            <td colspan="2" data-title="Shipping and Handling">
                                                <form action="#" method="post">
                                                    <a href="#" class="shipping-calculator-button">Choisir un lieu de
                                                        livraison</a>
                                                    <div class="shipping-calculator-form">
                                                        <select class="form-control delivery">
                                                            <option disabled selected value> Choisir un lieu de
                                                                livraison</option>
                                                            @foreach ($delivery as $item)
                                                                <option value="{{ $item['id'] }}">
                                                                    {{ $item['zone'] }} ({{ $item['tarif'] }}) FCFA
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order-total">

                                            <td>
                                                <h6>TOTAL TTC</h6>
                                            </td>
                                            <td data-title="Total">
                                                <strong><span class="amount">
                                                        <h6 class="text-danger total_order"> </h6>
                                                    </span>

                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="wc-proceed-to-checkout mb-30">
                                    @auth
                                        <a href="#" class="th-btn rounded-2 confirmOrder">Confirmer la commande</a>
                                    @endauth


                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>



        </div>
    </div>
    <!-- ========== End panier ========== -->
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        //Recuperer les information du lieu de livraison
        $('.delivery').change(function(e) {
            e.preventDefault();
            var deliveryId = $('.delivery option:selected').val();
            var subTotal = $('.sousTotal').attr('data-subTotal');

            $.ajax({
                type: "GET",
                url: "/refresh-shipping/" + deliveryId,
                data: {
                    sub_total: subTotal
                },
                dataType: "json",
                success: function(response) {
                    $('.delivery_price').html(response.delivery_price + 'FCFA');
                    $('.delivery_name').html('(' + response.delivery_name + ')');
                    $('.total_order').html(response.total_price + 'FCFA');
                }
            });


        });


        //Enregistrer les informations de la commande
        $('.confirmOrder').click(function(e) {
            e.preventDefault()
            var deliveryId = $('.delivery').val();
            //si le lieu de livraison n'est pas choisi , on affiche un message d'alerte
            if (!deliveryId) {
                Swal.fire({
                    title: 'Choisir son Lieu de livraison',
                    text: "Veuillez choisir un lieu de livraison pour recevoir votre commande",
                    icon: 'warning',
                    width: '500px',
                    confirmButtonColor: '#212529',
                    confirmButtonText: 'D\'accord'
                })
            } else {
                //send data to back
                var subTotal = $('.sousTotal').attr('data-subTotal');
                // var total_order = $('.total_order').html();

                var data = {
                    subTotal,
                    deliveryId
                }

                $.ajax({
                    type: "GET",
                    url: "save-order",
                    data: {
                        data
                    },
                    dataType: "json",
                    success: function(response) {

                        if (response.status === 200) {
                            let timerInterval
                            Swal.fire({
                                title: 'Enregistrement de la commande',
                                html: 'Veuillez patienter dans <b></b> Secondes.',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Commande enregistré avec success',
                                        width: '100%',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });

                                    window.location.href = "{{ route('user-order') }}";
                                }
                            })

                        }
                    }
                });


            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>


@endsection
