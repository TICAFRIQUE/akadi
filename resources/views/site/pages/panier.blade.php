@extends('site.layouts.app')

@section('title', 'Panier')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Panier</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Panier</li>
                </ul>
            </div>
        </div>
    </div>



    <!-- ========== Start panier ========== -->
    <div class="th-cart-wrapper mt-3">
        <div class="container">
            <div class="row">
                @include('admin.components.validationMessage')

                <div class="col-md-9">
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
                                        <th class="cart-col-remove">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('cart') as $id => $details)
                                        @php $sousTotal += $details['price'] * $details['quantity'] @endphp

                                        <tr class="cart_item" id="row_{{ $id }}">
                                            <td data-title="produit">
                                                <a class="cart-productimage"
                                                    href="{{ route('detail-produit', $details['slug']) }}"><img
                                                        width="50" height="50" src="{{ $details['image'] }} "
                                                        alt="Image">
                                                    <br> <span>{{ $details['title'] }} * </span> <span class="text-dark"
                                                        id="qte{{ $id }}"> {{ $details['quantity'] }}</span>

                                                </a>
                                            </td>
                                            <td data-title="prix">
                                                <span class="amount text-dark"><bdi>
                                                        {{ number_format($details['price'], 0) }}
                                                        <span></span>
                                                    </bdi></span>
                                            </td>
                                            <td data-title="Qte">
                                                <div class="quantity">
                                                    <button
                                                        class="quantity-minus qty-btn qte-decrease_{{ $id }} text-dark"
                                                        onclick="decreaseValue({{ $id }})"><i
                                                            class="far fa-minus"></i></button>
                                                    <input type="number" id="{{ $id }}" id="qty-input"
                                                        class="qty-input{{ $id }}  qte-input" style="width: 30px"
                                                        value="{{ $details['quantity'] ?? 1 }}" min="1"
                                                        max="99" readonly>
                                                    <button
                                                        class="quantity-plus qty-btn qte-increase_{{ $id }} text-dark"
                                                        onclick="increaseValue({{ $id }})"><i
                                                            class="far fa-plus"></i></button>
                                                </div>
                                            </td>
                                            <td data-title="Total">
                                                @php
                                                    $total = $details['price'] * $details['quantity'];
                                                @endphp
                                                <span class="amount fw-bold text-dark"><bdi><span
                                                            id="totalPriceQty{{ $id }}">
                                                            {{ number_format($total) }}
                                                        </span>FCFA</bdi></span>
                                            </td>
                                            <td data-title="Action">
                                                <a href="#" data-id="{{ $id }}"
                                                    class="remove remove-from-cart"><i class="fal fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach



                                </tbody>
                            </table>
                        </form>
                    @else
                        <p>Vous n'avez pas de produits dans votre panier</p>
                        <a href="{{ route('liste-produit') }}" class="th-btn rounded-2 mb-4">Commandez maintenant</a>

                    @endif
                </div>

                @if (session('cart'))
                    <div class="col-md-3">
                        <div class="row">
                            <div class="">
                                <h5 class="">Total du panier</h5>
                                <div class="cart_totals">
                                    <h6 class="sousTotal p-3"> <b> {{ number_format($sousTotal) }}
                                            FCFA</b></h6>
                                </div>

                                <div>
                                    <a href="{{ route('liste-produit') }}" class="th-btn rounded-2 mb-3 w-100">Continuer
                                        les achats</a>

                                    <a href="{{ route('checkout') }}" class="th-btn rounded-2 mb-3 w-100">Poursuivre
                                        La commande </a>
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
        function increaseValue(id) {
            var value = parseInt(document.getElementById(id).value);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById(id).value = value;

            let IdProduct = id;
            if (value > 1) {
                $('.qte-decrease_' + id).attr("disabled", false);
            }

            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: IdProduct,
                    quantity: value
                },
                success: function(response) {
                    //on modifie la quantité

                    $('.qty-input' + IdProduct).val(response.cart[id].quantity);
                    $('#qte' + IdProduct).html(response.cart[id].quantity);

                    var totalPriceQty = response.cart[id].quantity * response.cart[id].price
                    var totalPriceQty = totalPriceQty.toLocaleString("en-US");
                    $('#totalPriceQty' + IdProduct).html(totalPriceQty);
                    $('.sousTotal').html(response.sousTotal +  ' FCFA');
                    $('.badge').html(response.totalQte);

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Quantité modifié avec succès',
                        animation: false,
                        position: 'top-right',
                        background: '#3da108e0',
                        iconColor: '#fff',
                        color: '#fff',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    // window.location.reload();
                }
            });
        }



        function decreaseValue(id) {
            var value = parseInt(document.getElementById(id).value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;

            let IdProduct = id;
            if (value === 1) {
                $('.qte-decrease_' + id).attr('disabled', true);
            }

            document.getElementById(id).value = value;
            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: IdProduct,
                    quantity: value
                },
                success: function(response) {

                    $('#qty-input' + IdProduct).val(response.cart[id].quantity);
                    $('#qte' + IdProduct).html(response.cart[id].quantity);

                    var totalPriceQty = response.cart[id].quantity * response.cart[id].price
                    var totalPriceQty = totalPriceQty.toLocaleString("en-US");
                    $('#totalPriceQty' + IdProduct).html(totalPriceQty);
                    $('.sousTotal').html( response.sousTotal + ' FCFA');
                    $('.badge').html(response.totalQte);


                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Panier mis à jour avec succès',
                        animation: false,
                        position: 'top',
                        background: '#3da108e0',
                        iconColor: '#fff',
                        color: '#fff',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });

                }
            });

        }


        //remove product from cart
        $(".remove-from-cart").click(function(e) {
            e.preventDefault();

            var IdProduct = $(this).attr('data-id');
            // console.log(productId);

            Swal.fire({
                title: 'Retirer du panier',
                text: "Voulez-vous vraiment supprimer ce produit du panier ?",
                // icon: 'warning',
                width: '350px',
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#212529',
                cancelButtonColor: '#212529',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('remove.from.cart') }}',
                        method: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: IdProduct
                        },
                        success: function(response) {
                            $('.sousTotal').html('<h6 class="text-danger">' +
                                response
                                .sousTotal + ' FCFA' + '</h6>');
                            $('.badge').html(response.totalQte);
                            $('.quantityProduct').html('(' + response.countCart + ')');


                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                title: 'Le produit a été retiré du panier',
                                animation: false,
                                position: 'top',
                                background: '#3da108e0',
                                iconColor: '#fff',
                                color: '#fff',
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                            });
                            $('#row_' + IdProduct).remove();
                            if (response.countCart == 0) {
                                window.location.href = "{{ route('panier') }}";
                            }
                        }
                    });


                }
            })


        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>


@endsection
