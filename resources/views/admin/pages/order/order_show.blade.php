@extends('admin.layouts.app')

@section('title', 'order')
@section('sub-title', 'Detail de la commande')

@section('content')
    <!--start to page content-->
    {{-- @auth --}}
    <div class="page-content p-0 ">
        @include('admin.components.validationMessage')

        <div class="py-3 d-flex justify-content-between">
            <a href="{{ route('order.index') }}" class="py-3" href="#"><i data-feather="arrow-left"></i>Retour</a>

            @if ($orders['status'] != 'livrée' && $orders['status'] != 'annulée')
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="btn btn-dark dropdown-toggle">Changer le statut de la
                        commande</a>
                    <div class="dropdown-menu">
                        <a href="/admin/order/changeState?cs=confirmée && id={{ $orders['id'] }}"
                            class="dropdown-item has-icon"><i class="fas fa-check-double"></i>
                            Confirmer</a>

                        <a href="/admin/order/changeState?cs=livrée && id={{ $orders['id'] }}"
                            class="dropdown-item has-icon"><i class="fas fa-shipping-fast"></i>
                            Livrée</a>
                        <a href="/admin/order/changeState?cs=attente && id={{ $orders['id'] }}"
                            class="dropdown-item has-icon"><i class="fas fa-arrow-down"></i>
                            Attente</a>

                        <a href="/admin/order/changeState?cs=annulée && id={{ $orders['id'] }}" role="button"
                            data-id="{{ $orders['id'] }}" class="dropdown-item has-icon text-danger delete"><i
                                data-feather="x-circle"></i> Annuler</a>

                    </div>
                </div>
            @endif

            <a class="btn btn-link py-3" href="{{ route('order.invoice', $orders['id']) }}" target="_blank"><i
                    data-feather="file-text"></i>Aperçu de la facture</a>

        </div>
        <ul class="list-group list-group-flush rounded-0">

            <div class="fst-italic p-2 d-flex  justify-content-between">
                <div>
                    <span class="text-dark fw-bold">Commande: <b>#{{ $orders['code'] }}</b> <span
                            class="badge {{ $orders['status'] == 'attente' ? 'badge-primary' : ($orders['status'] == 'livrée' ? 'badge-success' : ($orders['status'] == 'confirmée' ? 'badge-info' : ($orders['status'] == 'annulée' ? 'badge-danger' : ''))) }} text-white p-1 px-3">{{ $orders['status'] }}
                        </span> </span><br>
                    <span>Commandé le: <b>{{ $orders['created_at']->format('d-m-Y') }}</b> </span><br>
                    <span>Nombre d'articles: <b>{{ $orders['quantity_product'] }}</b> </span><br>
                    <span class="text-dark fw-bold">Total: <b>{{ number_format($orders['total']) }} FCFA</b> </span><br>
                    {{-- <span>Méthode de paiement: {{ $orders['payment_method'] }} </span><br> --}}

                </div>

                <div class="fst-italic p-2">
                    <h6 class="p-2" style="background-color: #e1e6ea">Client</h6>

                    <span>Client: <b>{{ $orders['user']['name'] }}</b> </span><br>
                    <span>Email: <b>{{ $orders['user']['email'] }}</b> </span><br>
                    <span>Téléphone: <b>{{ $orders['user']['phone'] }}</b> </span><br>

                </div>

            </div>
            <h6 class="p-2" style="background-color: #e1e6ea">Articles commandés</h6>
            <div class="row mb-3">
                @foreach ($orders['products'] as $item)
                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6">
                        <div class="product-img">
                            <a href="#">
                                <img src="{{ $item->getFirstMediaUrl('product_image') }}" class="rounded-3" width="70px"
                                    alt=""></a>
                        </div>
                        <div class="">
                            <h6 class="mb-1 text-dark">{{ $item['title'] }}</h6>
                            <div class="mt-1">
                                <span class="fst-italic">Qté :{{ $item['pivot']['quantity'] }} </span><br>
                                <span class="fst-italic">Pu :{{ number_format($item['pivot']['unit_price']) }} FCFA
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h6 class="p-2" style="background-color: #e1e6ea">Livraison</h6>

            <div class="fst-italic p-2">
                <span class="text-dark">Mode de livraison : <b>{{ $orders['mode_livraison'] }}</b></span><br>

                @if ($orders['address'])
                    <span class="">Lieu de livraison: <b>{{ $orders['delivery_name'] }}</b> ,
                        <b>{{ $orders['address'] }}</b></span><br>
                    <span>Tarif livraison: <b>{{ $orders['delivery_price'] }}</b> </span><br>
                @elseif ($orders['address_yango'])
                    <span class="">Adresse de destination: <b>{{ $orders['address_yango'] }}</b> ,
                        <br>
                        <span>Tarif livraison: <b>Les frais sont à la charge du client</b> </span><br>
                @endif

            </div>


            @if ($orders['raison_annulation_cmd'])
                <h6 class="p-2" style="background-color: #e1e6ea">Motif d'annulation </h6>

                <div class="fst-italic p-2">
                    <p> {{ $orders['raison_annulation_cmd'] }} </p>

                </div>
            @endif


             @if ($orders['note'])
                <h6 class="p-2" style="background-color: #e1e6ea">Commentaire du client</h6>

                <div class="fst-italic p-2">
                    <p> {{ $orders['note'] }} </p>

                </div>
            @endif


    </div>
    <!--end to page content-->

    {{-- @endauth --}}
    <!--end to page content-->

@endsection
