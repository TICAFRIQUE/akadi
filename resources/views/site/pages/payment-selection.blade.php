@extends('site.layouts.app')

@section('title', 'Sélection du moyen de paiement')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Paiement</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li><a href="{{ route('panier') }}">Panier</a></li>
                    <li>Paiement</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="th-checkout-wrapper space-top space-extra-bottom">
        <div class="container">
            @include('admin.components.validationMessage')

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Choisissez votre moyen de paiement</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                                @csrf
                                
                                <div class="payment-methods mb-4">
                                    @foreach ($paymentMethods as $method)
                                        <div class="payment-method-card mb-3">
                                            <input type="radio" 
                                                   class="payment-method-radio" 
                                                   name="payment_method_id" 
                                                   id="payment_{{ $method->id }}" 
                                                   value="{{ $method->id }}"
                                                   {{ $loop->first ? 'checked' : '' }}
                                                   required>
                                            <label for="payment_{{ $method->id }}" class="payment-method-label">
                                                <div class="d-flex align-items-center">
                                                    @if($method->icone)
                                                        <i class="{{ $method->icone }} fa-2x me-3"></i>
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-0">{{ $method->nom }}</h5>
                                                        @if($method->code === 'wave')
                                                            <small class="text-muted">Paiement sécurisé avec Wave</small>
                                                        @elseif($method->code === 'cash' || $method->code === 'espece')
                                                            <small class="text-muted">Paiement à la livraison</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Résumé de la commande -->
                                <div class="order-summary p-4 bg-light rounded">
                                    <h5 class="mb-3">Résumé de la commande</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Sous-total:</span>
                                        <span class="fw-bold">{{ number_format($subtotal, 0, '', ' ') }} FCFA</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Livraison:</span>
                                        <span class="fw-bold">{{ number_format($deliveryPrice, 0, '', ' ') }} FCFA</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="h5">Total:</span>
                                        <span class="h5 text-primary">{{ number_format($total, 0, '', ' ') }} FCFA</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('checkout') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Confirmer le paiement <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .payment-method-card {
            position: relative;
        }

        .payment-method-radio {
            position: absolute;
            opacity: 0;
        }

        .payment-method-label {
            display: block;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-label:hover {
            border-color: var(--theme-color);
            background-color: #f8f9fa;
        }

        .payment-method-radio:checked + .payment-method-label {
            border-color: var(--theme-color);
            background-color: rgba(var(--theme-color-rgb), 0.1);
            box-shadow: 0 0 10px rgba(var(--theme-color-rgb), 0.2);
        }

        .order-summary {
            border: 1px solid #ddd;
        }
    </style>
@endsection
