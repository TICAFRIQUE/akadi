@extends('site.layouts.app')

@section('title', 'Mes commandes')

<style>
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .orders-grid {
            grid-template-columns: 1fr;
        }
    }

    .order-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
    }

    .order-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .order-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f3f7 100%);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #e9ecef;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 500px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-header > div:last-child {
            align-self: flex-start;
        }
    }

    .order-number {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .order-number strong {
        font-size: 18px;
        color: #dc3545;
    }

    .order-date {
        font-size: 13px;
        color: #999;
        margin-top: 5px;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
    }

    .status-badge.attente {
        background-color: #e7f3ff;
        color: #0066cc;
    }

    .status-badge.precommande {
        background-color: #f3e7f9;
        color: #9333ea;
    }

    .status-badge.en_attente_acompte {
        background-color: #fff7ed;
        color: #ea580c;
    }

    .status-badge.confirmée {
        background-color: #e6ffed;
        color: #00b33c;
    }

    .status-badge.en_cuisine {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .status-badge.cuisine_terminee {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .status-badge.en_livraison {
        background-color: #e2e8f0;
        color: #475569;
    }

    .status-badge.livrée {
        background-color: #e6ffed;
        color: #00b33c;
    }

    .status-badge.annulée {
        background-color: #ffebee;
        color: #dc3545;
    }

    .order-body {
        padding: 20px;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    @media (max-width: 500px) {
        .product-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-price {
            text-align: left !important;
            width: 100%;
        }
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info {
        flex: 1;
    }

    .product-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .product-details {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #666;
    }

    .product-details span {
        display: flex;
        align-items: center;
    }

    .product-details strong {
        color: #333;
        margin-right: 5px;
    }

    .product-price {
        text-align: right;
        font-weight: 600;
        color: #333;
        min-width: 100px;
    }

    .order-footer {
        background: #fafbfc;
        padding: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: auto;
    }

    .order-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .order-actions a {
        padding: 10px 18px !important;
        font-size: 14px !important;
    }

    .th-btn.btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .th-btn.btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .order-summary {
        display: flex;
        gap: 30px;
        flex: 1;
    }

    @media (max-width: 500px) {
        .order-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-summary {
            width: 100%;
            gap: 15px;
        }

        .order-footer a {
            width: 100%;
            text-align: center;
        }
    }

    .summary-item {
        text-align: center;
    }

    .summary-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 700;
        color: #333;
    }

    .summary-value.delivery {
        color: #0066cc;
        font-size: 16px;
    }

    .summary-value.total {
        color: #dc3545;
        font-size: 20px;
    }

    .no-orders {
        text-align: center;
        padding: 60px 20px;
    }

    .no-orders-icon {
        font-size: 60px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-orders p {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }

    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #f0f0f0;
    }

    .orders-count {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
</style>

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Mes commandes</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Mes commandes</li>
                </ul>
            </div>
        </div>
    </div>

    @if (count($orders) > 0)
        <div class="py-5">
            @include('admin.components.validationMessage')
            <div class="container">
                <div class="orders-header">
                    <h2 style="margin: 0; font-size: 28px; color: #333;">Historique de mes commandes</h2>
                    <span class="orders-count">{{ count($orders) }} commande(s)</span>
                </div>

                <div class="orders-grid">
                    @foreach ($orders as $item)
                        <div class="order-card">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div>
                                <div class="order-number">Commande N°<strong>{{ $item['code'] }}</strong></div>
                                <div class="order-date">{{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY') }}</div>
                            </div>
                            <div class="status-badge {{ strtolower($item['status']) }}">
                                {{ $item->status_label }}
                            </div>
                        </div>

                        <!-- Order Body - Products -->
                        <div class="order-body">
                            @foreach ($item['products'] as $product)
                                <div class="product-item">
                                    <div class="product-image">
                                        <a href="{{ route('detail-produit', $product['slug']) }}">
                                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product['title'] }}">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="product-title">
                                            <a href="{{ route('detail-produit', $product['slug']) }}" style="color: #333; text-decoration: none;">
                                                {{ $product['title'] }}
                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <span><strong>Qté:</strong> {{ $product['pivot']['quantity'] }}</span>
                                            <span><strong>P.U:</strong> {{ number_format($product['pivot']['unit_price']) }} FCFA</span>
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        {{ number_format($product['pivot']['unit_price'] * $product['pivot']['quantity']) }} FCFA
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Footer - Summary -->
                        <div class="order-footer">
                            <div class="order-summary">
                                <div class="summary-item">
                                    <div class="summary-label">Livraison</div>
                                    <div class="summary-value delivery">{{ number_format($item['delivery_price']) }} FCFA</div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-label">Total TTC</div>
                                    <div class="summary-value total">{{ number_format($item['total']) }} FCFA</div>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="{{ route('suivi-commande', $item['code']) }}" class="th-btn btn-secondary rounded-2">
                                    <i class="fas fa-tracking"></i> Suivre
                                </a>
                                <a href="{{ route('detail-produit', $item['products'][0]['slug'] ?? '#') }}" class="th-btn rounded-2">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </div>
                        </div>

                        @include('site.sections.raison_annulation_cmd')
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="py-5">
            <div class="container">
                <div class="no-orders">
                    <div class="no-orders-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p>Vous n'avez aucune commande pour le moment</p>
                    <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">
                        <i class="fas fa-shopping-cart"></i> Commencer vos achats
                    </a>
                </div>
            </div>
        </div>
    @endif

@endsection
