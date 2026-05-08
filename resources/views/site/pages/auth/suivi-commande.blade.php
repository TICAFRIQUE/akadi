@extends('site.layouts.app')

@section('title', 'Suivi de commande')

<style>
    .tracking-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        margin-bottom: 40px;
        border-radius: 15px;
    }

    .tracking-header {
        text-align: center;
    }

    .tracking-number {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #ffd700;
    }

    .tracking-date {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .tracking-status-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 25px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .timeline {
        position: relative;
        padding: 30px 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #667eea, #764ba2, #e9ecef);
    }

    .timeline-item {
        margin-bottom: 40px;
        margin-left: 80px;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -68px;
        top: 5px;
        width: 20px;
        height: 20px;
        background: white;
        border: 3px solid #667eea;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .timeline-item.completed::before {
        background: #667eea;
        width: 24px;
        height: 24px;
        left: -70px;
        top: 3px;
    }

    .timeline-item.completed::after {
        content: '✓';
        position: absolute;
        left: -66px;
        top: 5px;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .timeline-item.active::before {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 0 0 8px rgba(102, 126, 234, 0.1);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 8px rgba(102, 126, 234, 0.1);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(102, 126, 234, 0.05);
        }
        100% {
            box-shadow: 0 0 0 8px rgba(102, 126, 234, 0.1);
        }
    }

    .timeline-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .timeline-item.active .timeline-content {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
    }

    .timeline-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .timeline-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
    }

    .timeline-date {
        font-size: 13px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline-date i {
        color: #667eea;
    }

    .order-summary-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .summary-box {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f3f7 100%);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .summary-label {
        font-size: 13px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }

    .summary-value.status {
        font-size: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .products-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .products-section h3 {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    .product-card-tracking {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 15px;
        align-items: center;
    }

    .product-card-tracking:last-child {
        margin-bottom: 0;
    }

    .product-image-tracking {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-image-tracking img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details-tracking {
        flex: 1;
    }

    .product-title-tracking {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .product-meta {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #666;
    }

    .contact-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        margin-top: 30px;
    }

    .contact-section h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .contact-section p {
        font-size: 15px;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .contact-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .contact-btn {
        background: white;
        color: #667eea;
        padding: 10px 25px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .timeline::before {
            left: 10px;
        }

        .timeline-item {
            margin-left: 50px;
        }

        .timeline-item::before {
            left: -38px;
        }

        .timeline-item.completed::before {
            left: -40px;
        }

        .timeline-item.completed::after {
            left: -36px;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .tracking-container {
            padding: 30px 20px;
        }

        .tracking-number {
            font-size: 28px;
        }
    }
</style>

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Suivi de commande</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="{{ route('user-order') }}">Mes commandes</a></li>
                    <li>Suivi de commande</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <!-- Tracking Header -->
            <div class="tracking-container">
                <div class="tracking-header">

                  <!--ajouter un input pour mettre le code de la commande-->

                    <div class="tracking-number">N°{{ $order['code'] }}</div>
                    <div class="tracking-date">
                        Commandée le {{ \Carbon\Carbon::parse($order['created_at'])->isoFormat('dddd D MMMM YYYY à HH:mm') }}
                    </div>
                    <div class="tracking-status-badge">
                        {{ $order->status_label }}
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary-card">
                <h3 style="font-size: 20px; font-weight: 700; color: #333; margin-bottom: 20px;">Résumé de la commande</h3>
                <div class="summary-grid">
                    <div class="summary-box">
                        <div class="summary-label">Montant</div>
                        <div class="summary-value">{{ number_format($order['total']) }} FCFA</div>
                    </div>
                    <div class="summary-box">
                        <div class="summary-label">Livraison</div>
                        <div class="summary-value">{{ number_format($order['delivery_price']) }} FCFA</div>
                    </div>
                    <div class="summary-box">
                        <div class="summary-label">Statut</div>
                        <div class="summary-value status">{{ $order->status_label }}</div>
                    </div>
                    <div class="summary-box">
                        <div class="summary-label">Articles</div>
                        <div class="summary-value">{{ count($order['products']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline">
                <!-- Étape 1: Commande reçue -->
                <div class="timeline-item completed">
                    <div class="timeline-content">
                        <div class="timeline-title">Commande reçue</div>
                        <div class="timeline-description">Votre commande a été enregistrée avec succès</div>
                        <div class="timeline-date">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::parse($order['created_at'])->isoFormat('D MMMM YYYY à HH:mm') }}
                        </div>
                    </div>
                </div>

                <!-- Étape 2: Confirmation -->
                <div class="timeline-item {{ in_array($order['status'], ['confirmée', 'en_cuisine', 'cuisine_terminee', 'en_livraison', 'livrée']) ? 'completed' : '' }} {{ in_array($order['status'], ['confirmée', 'en_attente_acompte', 'precommande']) ? 'active' : '' }}">
                    <div class="timeline-content">
                        <div class="timeline-title">Commande confirmée</div>
                        <div class="timeline-description">Votre commande a été confirmée et est en attente</div>
                        <div class="timeline-date">
                            <i class="fas fa-check-circle"></i>
                            {{ in_array($order['status'], ['confirmée', 'en_cuisine', 'cuisine_terminee', 'en_livraison', 'livrée']) ? 'Confirmé' : 'En attente' }}
                        </div>
                    </div>
                </div>

                <!-- Étape 3: En cuisine -->
                <div class="timeline-item {{ in_array($order['status'], ['en_cuisine', 'cuisine_terminee', 'en_livraison', 'livrée']) ? 'completed' : '' }} {{ $order['status'] === 'en_cuisine' ? 'active' : '' }}">
                    <div class="timeline-content">
                        <div class="timeline-title">En préparation</div>
                        <div class="timeline-description">Notre équipe prépare votre commande avec soin</div>
                        <div class="timeline-date">
                            <i class="fas fa-box"></i>
                            {{ in_array($order['status'], ['en_cuisine', 'cuisine_terminee', 'en_livraison', 'livrée']) ? 'En cours' : 'En attente' }}
                        </div>
                    </div>
                </div>

                <!-- Étape 4: Cuisine terminée -->
                <div class="timeline-item {{ in_array($order['status'], ['cuisine_terminee', 'en_livraison', 'livrée']) ? 'completed' : '' }} {{ $order['status'] === 'cuisine_terminee' ? 'active' : '' }}">
                    <div class="timeline-content">
                        <div class="timeline-title">Prête pour livraison</div>
                        <div class="timeline-description">Votre commande est emballée et prête à être livrée</div>
                        <div class="timeline-date">
                            <i class="fas fa-truck"></i>
                            {{ in_array($order['status'], ['cuisine_terminee', 'en_livraison', 'livrée']) ? 'Prête' : 'En attente' }}
                        </div>
                    </div>
                </div>

                <!-- Étape 5: En livraison -->
                <div class="timeline-item {{ in_array($order['status'], ['en_livraison', 'livrée']) ? 'completed' : '' }} {{ $order['status'] === 'en_livraison' ? 'active' : '' }}">
                    <div class="timeline-content">
                        <div class="timeline-title">En cours de livraison</div>
                        <div class="timeline-description">Votre commande est en route vers vous</div>
                        <div class="timeline-date">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ in_array($order['status'], ['en_livraison', 'livrée']) ? 'En livraison' : 'En attente' }}
                        </div>
                    </div>
                </div>

                <!-- Étape 6: Livrée -->
                <div class="timeline-item {{ $order['status'] === 'livrée' ? 'completed active' : '' }}">
                    <div class="timeline-content">
                        <div class="timeline-title">Commande livrée</div>
                        <div class="timeline-description">Merci pour votre commande ! Nous espérons que vous êtes satisfait</div>
                        <div class="timeline-date">
                            <i class="fas fa-smile"></i>
                            {{ $order['status'] === 'livrée' ? 'Livraison terminée' : 'En attente' }}
                        </div>
                    </div>
                </div>

                @if($order['status'] === 'annulée')
                    <!-- Étape: Annulée -->
                    <div class="timeline-item" style="margin-top: 30px;">
                        <div class="timeline-content" style="background-color: #ffebee; border-color: #dc3545;">
                            <div class="timeline-title" style="color: #dc3545;">Commande annulée</div>
                            <div class="timeline-description">{{ $order['raison_annulation_cmd'] ?? 'Votre commande a été annulée' }}</div>
                            <div class="timeline-date">
                                <i class="fas fa-times-circle"></i>
                                Annulée
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Products Section -->
            <div class="products-section">
                <h3>Articles commandés</h3>
                @foreach ($order['products'] as $product)
                    <div class="product-card-tracking">
                        <div class="product-image-tracking">
                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product['title'] }}">
                        </div>
                        <div class="product-details-tracking">
                            <div class="product-title-tracking">{{ $product['title'] }}</div>
                            <div class="product-meta">
                                <span><strong>Quantité:</strong> {{ $product['pivot']['quantity'] }}</span>
                                <span><strong>Prix unitaire:</strong> {{ number_format($product['pivot']['unit_price']) }} FCFA</span>
                                <span><strong>Total:</strong> {{ number_format($product['pivot']['unit_price'] * $product['pivot']['quantity']) }} FCFA</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h3>Besoin d'aide ?</h3>
                <p class="text-white">Vous avez des questions sur votre commande ? Notre équipe est disponible pour vous aider</p>
                <div class="contact-buttons">
                    <a href="tel:+2250758838338" class="contact-btn">
                        <i class="fas fa-phone"></i> Appeler
                    </a>
                    {{-- <a href="javascript:void(0)" class="contact-btn">
                        <i class="fas fa-envelope"></i> Email
                    </a> --}}
                    <a href="{{ route('user-order') }}" class="contact-btn">
                        <i class="fas fa-arrow-left"></i> Retour aux commandes
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
