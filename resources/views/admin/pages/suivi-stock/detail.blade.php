@extends('admin.layouts.app')

@section('title', 'Détail des ventes')
@section('sub-title', $productBase->nom)

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Détail des ventes — {{ $productBase->nom }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">
                    <a href="{{ route('suivi-stock.index', ['date_debut' => $dateDebut, 'date_fin' => $dateFin]) }}">Suivi de stock</a>
                </div>
                <div class="breadcrumb-item">{{ $productBase->nom }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                Ventes du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                            </h4>
                            <div class="card-header-action">
                                <a href="{{ route('suivi-stock.index', ['date_debut' => $dateDebut, 'date_fin' => $dateFin]) }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour au suivi de stock
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Commande</th>
                                            <th>Stock avant</th>
                                            <th>Qté vendue</th>
                                            <th>Stock après</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mouvements as $m)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($m['date'])->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('order.show', $m['order_id']) }}" target="_blank">
                                                        {{ $m['commande_code'] }}
                                                    </a>
                                                </td>
                                                <td>{{ format_price($m['stock_avant']) }} {{ $productBase->unite }}</td>
                                                <td class="text-danger">-{{ format_price($m['quantite_vendue']) }} {{ $productBase->unite }}</td>
                                                <td><strong>{{ format_price($m['stock_apres']) }} {{ $productBase->unite }}</strong></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Aucune vente pour ce produit sur cette période</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
