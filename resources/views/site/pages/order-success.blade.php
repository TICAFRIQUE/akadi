@extends('site.layouts.app')

@section('title', 'Commande réussie')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Confirmation</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Commande</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="space-top space-extra-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Message de succès avec animation -->
                    <div class="success-header text-center mb-5">
                        <div class="success-icon-wrapper mb-4">
                            <div class="success-icon-circle">
                                <i class="fas fa-check success-icon"></i>
                            </div>
                            <div class="success-confetti">
                                <div class="confetti"></div>
                                <div class="confetti"></div>
                                <div class="confetti"></div>
                                <div class="confetti"></div>
                                <div class="confetti"></div>
                            </div>
                        </div>
                        <h2 class="success-title">Commande confirmée !</h2>
                        <p class="success-subtitle">Merci pour votre commande. Nous la traitons avec soin.</p>
                        
                        <div class="order-number-card">
                            <span class="order-label">Numéro de commande</span>
                            <span class="order-number">#{{ $order->id }}</span>
                        </div>
                        
                        @if($order->payment_status === 'completed')
                            <div class="status-badge status-completed">
                                <i class="fas fa-check-circle"></i>
                                <span>Paiement confirmé avec succès</span>
                            </div>
                        @elseif($order->payment_status === 'pending')
                            <div class="status-badge status-pending">
                                <i class="fas fa-clock"></i>
                                <span>Paiement en cours de vérification</span>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline de la commande -->
                    <div class="order-timeline mb-5">
                        <div class="timeline-item active">
                            <div class="timeline-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Commande passée</h6>
                                <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="timeline-item {{ $order->payment_status === 'completed' ? 'active' : '' }}">
                            <div class="timeline-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Paiement</h6>
                                <small>{{ $order->payment_status === 'completed' ? 'Confirmé' : 'En attente' }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Préparation</h6>
                                <small>Bientôt</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Livraison</h6>
                                <small>
                                    @php
                                        try {
                                            // Vérifier si c'est une vraie date
                                            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $order->delivery_planned)) {
                                                echo \Carbon\Carbon::parse($order->delivery_planned)->format('d/m/Y');
                                            } else {
                                                echo $order->delivery_planned;
                                            }
                                        } catch (\Exception $e) {
                                            echo $order->delivery_planned ?? 'À définir';
                                        }
                                    @endphp
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Détails de la commande -->
                        <div class="col-lg-7 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-info-circle"></i>
                                    <h5>Détails de la commande</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-alt"></i>
                                            Date de commande
                                        </div>
                                        <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-truck"></i>
                                            Livraison prévue
                                        </div>
                                        <div class="info-value">
                                            @php
                                                try {
                                                    // Vérifier si c'est une vraie date
                                                    if (preg_match('/^\d{4}-\d{2}-\d{2}/', $order->delivery_planned)) {
                                                        echo \Carbon\Carbon::parse($order->delivery_planned)->format('d/m/Y');
                                                    } else {
                                                        echo $order->delivery_planned;
                                                    }
                                                } catch (\Exception $e) {
                                                    echo $order->delivery_planned ?? 'À définir';
                                                }
                                            @endphp
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Zone de livraison
                                        </div>
                                        <div class="info-value">{{ $order->delivery_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-wallet"></i>
                                            Moyen de paiement
                                        </div>
                                        <div class="info-value">{{ $order->paymentMethod->nom ?? 'N/A' }}</div>
                                    </div>
                                    @if($order->note)
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-sticky-note"></i>
                                                Note
                                            </div>
                                            <div class="info-value">{{ $order->note }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Résumé financier -->
                        <div class="col-lg-5 mb-4">
                            <div class="info-card summary-card">
                                <div class="info-card-header">
                                    <i class="fas fa-receipt"></i>
                                    <h5>Résumé financier</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="summary-row">
                                        <span>Sous-total</span>
                                        <span>{{ number_format($order->subtotal, 0, '', ' ') }} FCFA</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Livraison</span>
                                        <span>{{ number_format($order->delivery_price, 0, '', ' ') }} FCFA</span>
                                    </div>
                                    @if($order->discount > 0)
                                        <div class="summary-row discount-row">
                                            <span>Réduction</span>
                                            <span>- {{ number_format($order->discount, 0, '', ' ') }} FCFA</span>
                                        </div>
                                    @endif
                                    <div class="summary-divider"></div>
                                    <div class="summary-row total-row">
                                        <span>Total</span>
                                        <span>{{ number_format($order->total, 0, '', ' ') }} FCFA</span>
                                    </div>
                                    @if($order->acompte > 0)
                                        <div class="summary-row paid-row">
                                            <span><i class="fas fa-check-circle"></i> Payé</span>
                                            <span>{{ number_format($order->acompte, 0, '', ' ') }} FCFA</span>
                                        </div>
                                    @endif
                                    @if($order->solde_restant > 0)
                                        <div class="summary-row remaining-row">
                                            <span>Restant à payer</span>
                                            <span>{{ number_format($order->solde_restant, 0, '', ' ') }} FCFA</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produits commandés -->
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <i class="fas fa-shopping-bag"></i>
                            <h5>Produits commandés ({{ $order->products->count() }})</h5>
                        </div>
                        <div class="products-list">
                            @foreach ($order->products as $product)
                                <div class="product-item">
                                    <div class="product-image">
                                        @if($product->media->isNotEmpty())
                                            <img src="{{ $product->media[0]->getUrl() }}" 
                                                 alt="{{ $product->title }}">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <h6 class="product-name">{{ $product->title }}</h6>
                                        <div class="product-meta">
                                            <span class="product-quantity">Qté: {{ $product->pivot->quantity }}</span>
                                            <span class="product-price">{{ number_format($product->pivot->unit_price, 0, '', ' ') }} FCFA</span>
                                        </div>
                                    </div>
                                    <div class="product-total">
                                        {{ number_format($product->pivot->total, 0, '', ' ') }} FCFA
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="action-buttons text-center">
                        <a href="{{ route('user-order') }}" class="btn btn-primary-custom">
                            <i class="fas fa-list"></i> Voir mes commandes
                        </a>
                        <a href="{{ route('page-acceuil') }}" class="btn btn-secondary-custom">
                            <i class="fas fa-home"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #eb0029;
            --secondary-color: #ff9d2d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --dark-color: #2c3e50;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }

        /* Header de succès */
        .success-header {
            position: relative;
        }

        .success-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .success-icon-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(235, 0, 41, 0.3);
            animation: scaleIn 0.5s ease-out;
        }

        .success-icon {
            font-size: 60px;
            color: white;
            animation: checkmark 0.8s ease-out 0.3s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Confetti animation */
        .success-confetti {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--secondary-color);
            animation: confetti-fall 3s ease-out;
        }

        .confetti:nth-child(1) { left: 10%; animation-delay: 0s; background: var(--primary-color); }
        .confetti:nth-child(2) { left: 30%; animation-delay: 0.2s; background: var(--secondary-color); }
        .confetti:nth-child(3) { left: 50%; animation-delay: 0.4s; background: var(--success-color); }
        .confetti:nth-child(4) { left: 70%; animation-delay: 0.6s; background: var(--primary-color); }
        .confetti:nth-child(5) { left: 90%; animation-delay: 0.8s; background: var(--secondary-color); }

        @keyframes confetti-fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(400px) rotate(720deg);
                opacity: 0;
            }
        }

        .success-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .success-subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .order-number-card {
            display: inline-block;
            background: linear-gradient(135deg, rgba(235, 0, 41, 0.1), rgba(255, 157, 45, 0.1));
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 15px 40px;
            margin-bottom: 20px;
        }

        .order-label {
            display: block;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .order-number {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 2px solid var(--success-color);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
            border: 2px solid var(--warning-color);
        }

        /* Timeline */
        .order-timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 30px 0;
            margin: 50px 0;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--border-color);
            transform: translateY(-50%);
        }

        .timeline-item {
            position: relative;
            flex: 1;
            text-align: center;
            z-index: 1;
        }

        .timeline-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            background: white;
            border: 4px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .timeline-item.active .timeline-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
        }

        .timeline-icon i {
            font-size: 24px;
            color: #999;
        }

        .timeline-item.active .timeline-icon i {
            color: white;
        }

        .timeline-content h6 {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .timeline-content small {
            color: #999;
        }

        .timeline-item.active .timeline-content h6 {
            color: var(--primary-color);
        }

        /* Info cards */
        .info-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .info-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .info-card-header i {
            font-size: 24px;
        }

        .info-card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .info-card-body {
            padding: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-weight: 500;
        }

        .info-label i {
            color: var(--primary-color);
        }

        .info-value {
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Summary card */
        .summary-card .info-card-body {
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 1rem;
        }

        .summary-divider {
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 15px 0;
        }

        .total-row {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .paid-row {
            color: var(--success-color);
            font-weight: 600;
        }

        .remaining-row {
            color: var(--warning-color);
            font-weight: 600;
        }

        .discount-row {
            color: var(--success-color);
        }

        /* Products list */
        .products-list {
            padding: 20px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: var(--light-bg);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .product-item:hover {
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .product-image {
            width: 80px;
            height: 80px;
            min-width: 80px;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            color: #999;
        }

        .product-placeholder i {
            font-size: 32px;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .product-meta {
            display: flex;
            gap: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .product-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .btn-primary-custom {
            padding: 15px 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(235, 0, 41, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 0, 41, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            padding: 15px 40px;
            background: white;
            color: var(--dark-color);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary-custom:hover {
            background: var(--light-bg);
            border-color: var(--dark-color);
            color: var(--dark-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .success-title {
                font-size: 1.8rem;
            }

            .order-timeline {
                flex-direction: column;
                align-items: center;
            }

            .order-timeline::before {
                width: 4px;
                height: 100%;
                left: 30px;
                top: 0;
            }

            .timeline-item {
                text-align: left;
                padding-left: 80px;
                margin-bottom: 30px;
            }

            .timeline-icon {
                position: absolute;
                left: 0;
            }

            .product-item {
                flex-direction: column;
                text-align: center;
            }

            .product-total {
                width: 100%;
                text-align: center;
                padding-top: 10px;
                border-top: 1px solid var(--border-color);
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('{{ session("success") }}');
            });
        </script>
    @endif
@endsection
