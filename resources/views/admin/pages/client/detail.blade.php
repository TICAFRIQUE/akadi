@extends('admin.layouts.app')
@section('title', 'clients')
@section('sub-title', 'Détail client')

@section('content')
    <section class="section">
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                    <div class="card-body">
                        <div>
                            <a href="{{ route('client.list') }}" class="btn btn-secondary text-white"
                                style="background-color:rgb(61, 61, 61)">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                        <div class="author-box-center">
                            <img alt="image" src="{{ asset('site/assets/img/custom/avatar.png') }}"
                                class="rounded-circle author-box-picture">
                            <div class="clearfix"></div>
                            <div class="author-box-name">
                                <a href="#">{{ $user['name'] }}</a>
                            </div>
                            <div class="author-box-job badge badge-primary text-white">
                                client — {{ ucfirst($user['type_client'] ?? 'prospect') }}
                            </div>
                            <div>Inscrit le {{ $user['created_at'] }}</div>
                        </div>
                        <div class="py-4">
                            @php
                                $Y = date('Y');
                                $nex_date = $user['date_anniversaire'] . '-' . $Y;
                                $date = \Carbon\Carbon::parse($nex_date)->locale('fr_FR');
                                $date = $date->day . ' ' . $date->monthName;
                            @endphp
                            <p class="clearfix">
                                <span class="float-left">Date Anniversaire</span>
                                <span class="float-right text-muted">{{ $date }}</span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">Contact</span>
                                <span class="float-right text-muted">{{ $user['phone'] }}</span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">Email</span>
                                <span class="float-right text-muted">{{ $user['email'] }}</span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">Localisation</span>
                                <span class="float-right text-muted">{{ $user['localisation'] ?? '—' }}</span>
                            </p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('client.edit', $user['id']) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit mr-1"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="padding-20">
                        <h3>Liste des commandes</h3>
                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="about" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Commandes Passées</strong><br>
                                        <p class="text-muted">{{ $user['orders_count'] }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Commandes Reçues</strong><br>
                                        <p class="text-muted">{{ $orders_livre }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Commandes Annulées</strong><br>
                                        <p class="text-muted">{{ $orders_annule }}</p>
                                    </div>
                                </div>

                                <div class="section-title">Commande(s) {{ $user['orders_count'] }}</div>
                                <ul>
                                    @foreach ($user['orders'] as $item)
                                        <li>
                                            <a href="{{ route('order.show', $item['id']) }}">
                                                # {{ $item['code'] }}
                                                <span class="badge text-white p-1 px-3
                                                    {{ $item['status'] == 'attente'      ? 'badge-primary'  :
                                                      ($item['status'] == 'livrée'       ? 'badge-success'  :
                                                      ($item['status'] == 'confirmée'    ? 'badge-info'     :
                                                      ($item['status'] == 'annulée'      ? 'badge-danger'   :
                                                      ($item['status'] == 'precommande'  ? 'badge-dark'     : ''))))}}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
