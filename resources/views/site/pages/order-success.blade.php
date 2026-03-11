@extends('site.layouts.app')

@section('title', 'Commande réussie')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Commande réussie</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Confirmation</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="space-top space-extra-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Message de succès -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                            </div>
                            <h2 class="text-success mb-3">Commande confirmée !</h2>
                            <p class="lead mb-4">Merci pour votre commande. Nous avons bien reçu votre demande.</p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Numéro de commande: <strong>#{{ $order->id }}</strong>
                            </div>
                            
                            @if($order->payment_status === 'completed')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Paiement confirmé avec succès
                                </div>
                            @elseif($order->payment_status === 'pending')
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    Paiement en cours de vérification
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Détails de la commande -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Détails de la commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Date de commande:</strong></p>
                                    <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Livraison prévue:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($order->delivery_planned)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Zone de livraison:</strong></p>
                                    <p>{{ $order->delivery_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Moyen de paiement:</strong></p>
                                    <p>{{ $order->paymentMethod->nom ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produits commandés -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Produits commandés</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Prix unitaire</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->products as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($product->media->isNotEmpty())
                                                            <img src="{{ $product->media[0]->getUrl() }}" 
                                                                 alt="{{ $product->title }}" 
                                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                                                 class="me-2">
                                                        @endif
                                                        <span>{{ $product->title }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $product->pivot->quantity }}</td>
                                                <td class="text-end">{{ number_format($product->pivot->unit_price, 0, '', ' ') }} FCFA</td>
                                                <td class="text-end fw-bold">{{ number_format($product->pivot->total, 0, '', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                            <td class="text-end">{{ number_format($order->subtotal, 0, '', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Livraison:</strong></td>
                                            <td class="text-end">{{ number_format($order->delivery_price, 0, '', ' ') }} FCFA</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                            <td class="text-end"><h5 class="mb-0">{{ number_format($order->total, 0, '', ' ') }} FCFA</h5></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-center">
                        <a href="{{ route('user-order') }}" class="btn btn-primary me-2">
                            <i class="fas fa-list"></i> Voir mes commandes
                        </a>
                        <a href="{{ route('page-acceuil') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Afficher une notification si nécessaire
                console.log('{{ session("success") }}');
            });
        </script>
    @endif
@endsection
