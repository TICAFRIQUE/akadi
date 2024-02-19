@extends('site.layouts.app')

@section('title', 'Mes commandes')


@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Mes commandes</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Liste de mes commandes</li>
                </ul>
            </div>
        </div>
    </div>

    @if (count($orders) > 0)
        <div class="my-3">
            @include('admin.components.validationMessage')
            <div class="container">
                <div class="tinv-wishlist woocommerce tinv-wishlist-clear">
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message">
                            <span> {{ count($orders) }} commandes </span>
                        </div>
                    </div>
                    <table class="bg-white  table table-responsive table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="" colspan=""># </th>
                                <th class="" colspan="">Produits</th>
                                <th class="" colspan="">Total TTC</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $item)
                                <tr class="wishlist_item">
                                    <td class="product-cb" colspan="">
                                        <span>N0: <b class="text-danger">{{ $item['code'] }}</b> </span><br>
                                        <span>
                                            {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY') }}
                                        </span><br>
                                        <span
                                            class="{{ $item['status'] == 'attente' ? 'bg-primary' : ($item['status'] == 'livrée' ? 'bg-success' : ($item['status'] == 'confirmée' ? 'bg-blue' : ($item['status'] == 'annulée' ? 'bg-danger' : ''))) }} text-white p-1 px-3">{{ $item['status'] }}
                                        </span>


                                    </td>

                                    <td class="" colspan="">
                                        <div class="row">
                                            @foreach ($item['products'] as $product)
                                                <div class="col-md-4">
                                                    <a href="{{ route('detail-produit', $product['slug']) }}"><img
                                                            src="{{ $product->getFirstMediaUrl('product_image') }}"
                                                            width="50px"
                                                            class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                            alt="image"></a>
                                                    <span>{{ $product['title'] }}</span>
                                                    <small class="fst-italic">Qté :{{ $product['pivot']['quantity'] }}
                                                    </small><br>
                                                    <small class="fst-italic">Pu
                                                        :{{ number_format($product['pivot']['unit_price']) }} FCFA
                                                    </small><br>
                                                    <small class="fst-italic">Total
                                                        :
                                                        {{ number_format($product['pivot']['unit_price'] * $product['pivot']['quantity']) }}
                                                        FCFA
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>


                                    <td class="">
                                        <span>Livraison : {{ number_format($item['delivery_price']) }} FCFA </span><br>
                                        <span> <b>Total : {{ number_format($item['total']) }} FCFA </b> </span>
                                    </td>

                                    <td class="{{ $item['status'] == 'annulée' ? 'd-none' : ($item['status'] =='livrée' ? 'd-none' : '')}} ">
                                        <a href="{{ route('cancel-order', $item['id']) }}" class="button rounded-2"
                                            value="58" title="">
                                            <i class="far fa-cancel"></i><span class="tinvwl-txt">Annuler</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p>Vous n'avez pas de produits dans votre panier</p>
        <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">Commandez maintenant</a>
    @endif



@endsection
