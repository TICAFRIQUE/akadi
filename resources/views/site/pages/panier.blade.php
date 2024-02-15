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
    <div class="th-cart-wrapper  space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message">Panier ({{ count((array) session('cart')) }})</div>
                    </div>
                    @php $total = 0 @endphp

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
                                        <tr class="cart_item">
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
                                                        {{ number_format($details['price'], 0) }} <span>FCFA</span>
                                                    </bdi></span>
                                            </td>
                                            <td data-title="Quantity">
                                                <div class="quantity">
                                                    <button class="quantity-minus qty-btn"><i
                                                            class="far fa-minus"></i></button>
                                                    <input type="number" class="qty-input" value="1" min="1"
                                                        max="99">
                                                    <button class="quantity-plus qty-btn"><i
                                                            class="far fa-plus"></i></button>
                                                </div>
                                            </td>
                                            <td data-title="Total">
                                                <span class="amount"><bdi><span>$</span>18</bdi></span>
                                            </td>
                                            <td data-title="Remove">
                                                <a href="#" class="remove"><i class="fal fa-trash-alt"></i></a>
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
                        <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">c'est partir</a>

                    @endif
                </div>

                @if (session('cart'))
                    <div class="col-md-4">
                        <div class="row">
                            <div class="">
                                <h2 class="h4 summary-title">TotaL du panier</h2>
                                <table class="cart_totals">
                                    <tbody>
                                        <tr>
                                            <td>Sous total</td>
                                            <td data-title="Cart Subtotal">
                                                <span class="amount"><bdi><span>$</span>47</bdi></span>
                                            </td>
                                        </tr>
                                        <tr class="shipping">
                                            <th>Lieu de livraison</th>
                                            <td data-title="Shipping and Handling">
                                                <form action="#" method="post">
                                                    <a href="#" class="shipping-calculator-button">Choisir un lieu</a>
                                                    <div class="shipping-calculator-form">
                                                        <p class="form-row">
                                                            <select class="form-select">
                                                                <option value="AR">Argentina</option>
                                                                <option value="AM">Armenia</option>
                                                                <option value="BD" selected="selected">Bangladesh
                                                                </option>
                                                            </select>
                                                        </p>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order-total">
                                            <td>Total</td>
                                            <td data-title="Total">
                                                <strong><span class="amount"><bdi><span>$</span>47</bdi></span></strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="wc-proceed-to-checkout mb-30">
                                    <a href="checkout.html" class="th-btn rounded-2">Valider la commande</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>



        </div>
    </div>
    <!-- ========== End panier ========== -->

@endsection
