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
                    <a href="#" data-toggle="dropdown" class="btn btn-dark dropdown-toggle">Changer le statut de la commande</a>
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

            <a class="btn btn-link py-3" href="{{ route('order.invoice', $orders['id']) }}"><i
                    data-feather="file-text"></i>Aperçu de la facture</a>

        </div>
        <ul class="list-group list-group-flush rounded-0">

            <div class="fst-italic p-2 d-flex  justify-content-between">
                <div>
                    <span class="text-dark fw-bold">Commande: #{{ $orders['code'] }} <span
                            class="badge {{ $orders['status'] == 'attente' ? 'badge-primary' : ($orders['status'] == 'livrée' ? 'badge-success' : ($orders['status'] == 'confirmée' ? 'badge-info' : ($orders['status'] == 'annulée' ? 'badge-danger' : ''))) }} text-white p-1 px-3">{{ $orders['status'] }}
                           </span> </span><br>
                    <span>Commandé le: {{ $orders['created_at']->format('d-m-Y') }} </span><br>
                    <span>Nbre articles: {{ $orders['quantity_product'] }} </span><br>
                    <span class="text-dark fw-bold">Total: {{ $orders['total'] }} </span><br>
                    <span>Méthode de paiement: {{ $orders['payement_method'] }} </span><br>

                </div>

                <div class="fst-italic p-2">
                    <h6 class="p-2" style="background-color: #e1e6ea">Client</h6>

                    <span>Client: {{ $orders['user']['name'] }} </span><br>
                    <span>Email: {{ $orders['user']['email'] }} </span><br>
                    <span>Téléphone: {{ $orders['user']['phone'] }} </span><br>

                </div>

            </div>
            <h6 class="p-2" style="background-color: #e1e6ea">Articles commandés</h6>
            @foreach ($orders['products'] as $item)
                <li class="list-group-item py-3">
                    <div class="d-flex flex-row align-items-start align-items-stretch gap-3">
                        <div class="product-img">
                            <a href="#">
                                <img src="{{ $item->getFirstMediaUrl('product_image') }}" class="rounded-3" width="100"
                                    alt=""></a>
                        </div>
                        <div class="product-info ml-3 ">
                            <h6 class="mb-1 text-dark">{{ $item['title'] }}</h6>
                            <div class="mt-1">
                                {{-- <span
                                        class="fst-italic">{{ $item['pivot']['options'] && is_numeric($item['pivot']['options'][0]) ? 'Pointure: ' : ($item['pivot']['options'] && ctype_alpha($item['pivot']['options'][0]) ? 'Taille:' : '') }}
                                        {{ $item['pivot']['options'] }}</span><br> --}}
                                <span class="fst-italic">Qté :{{ $item['pivot']['quantity'] }} </span><br>
                                <span class="fst-italic">Pu :{{ number_format($item['pivot']['unit_price']) }} FCFA
                                </span>

                                {{-- <br><h5 class="text-dark fw-bold">{{$item['pivot']['available']==null ? 'En attente de verification' : $item['pivot']['available'] }} </h5> --}}

                            </div>
                        </div>
                    </div>
                    {{-- start change state of available --}}

                    {{-- @if ($orders['status'] == 'attente' || $orders['status'] == 'annulée')
                            
                        <form class=""action="{{route('vendor-available', $orders['id'])}}" method="post">
                            @csrf
                           <div class="row form-group">
                            <div class="col-8">
                                <select class="form-control" name="state" id="" required>
                                    <option disabled value selected>Choisir une option</option>
                                    <option value="disponible">Disponible</option>
                                    <option value="pas disponible">Pas disponible</option>
                                    <option value="reserve">Reservation</option>
                                </select>
                                <input value="{{$item['id']}}" name="product_id" type="text" hidden>
                               
                            </div>
                            <div class="col-4">
                                <button class="btn btn-dark" type="submit">Valider</button>
                            </div>
                            
                            </div> 
                        </form>
                        @endif --}}

                    {{-- end change state of available --}}

                </li>
            @endforeach

            <h6 class="p-2" style="background-color: #e1e6ea">Adresse de Livraison</h6>

            <div class="fst-italic p-2">
                <span class="text-dark">#Livraison à domicile</span><br>
                <span class="">Lieu de livraison: <b>{{ $orders['delivery_name'] }}</b> </span><br>
                <span>Tarif livraison: <b>{{ $orders['delivery_price'] }}</b> </span><br>
                <span>Client: <b>{{ $orders['user']['name'] }}</b> </span><br>

            </div>


            <h6 class="p-2" style="background-color: #e1e6ea">Expédition</h6>

            <div class="fst-italic p-2">
                <span class="">Livraison prevue le :
                    <b>{{ \Carbon\Carbon::parse($orders['delivery_planned'])->isoFormat('dddd D MMMM YYYY') }}</b>
                </span><br>
                <span class="">Date de livraison :
                    <b>{{ $orders['delivery_date'] !== null ? \Carbon\Carbon::parse($orders['delivery_date'])->isoFormat('dddd D MMMM YYYY') : 'En attende livraison' }}</b>
                </span><br>

            </div>



    </div>
    <!--end to page content-->

    {{-- @endauth --}}
    <!--end to page content-->

@endsection
