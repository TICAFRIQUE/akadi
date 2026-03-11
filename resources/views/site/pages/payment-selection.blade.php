@extends('site.layouts.app')

@section('title', 'Sélection du moyen de paiement')

@section('content')
    <style>
        :root {
            --primary-color: #eb0029;
            --secondary-color: #ff9d2d;
            --success-color: #28a745;
            --dark-color: #2c3e50;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }

        /* En-tête */
        .payment-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(235, 0, 41, 0.3);
        }

        .payment-icon i {
            font-size: 36px;
            color: white;
        }

        .payment-title {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Card principale */
        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        /* Méthodes de paiement */
        .payment-method-card {
            position: relative;
            transition: all 0.3s ease;
        }

        .payment-method-radio {
            position: absolute;
            opacity: 0;
        }

        .payment-method-label {
            display: block;
            padding: 0;
            margin: 0;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .payment-method-label:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 4px 15px rgba(255, 157, 45, 0.2);
            transform: translateY(-2px);
        }

        .payment-method-radio:checked+.payment-method-label {
            border-color: var(--primary-color);
            background: linear-gradient(to right, rgba(235, 0, 41, 0.05), rgba(255, 157, 45, 0.05));
            box-shadow: 0 4px 20px rgba(235, 0, 41, 0.3);
        }

        .payment-method-content {
            display: flex;
            align-items: center;
            padding: 20px;
            gap: 20px;
        }

        .payment-method-icon {
            width: 60px;
            height: 60px;
            min-width: 60px;
            background: linear-gradient(135deg, var(--light-bg), white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .payment-method-radio:checked+.payment-method-label .payment-method-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
        }

        .payment-method-icon i {
            font-size: 28px;
            color: var(--dark-color);
            transition: all 0.3s ease;
        }

        .payment-method-radio:checked+.payment-method-label .payment-method-icon i {
            color: white;
        }

        .payment-method-info {
            flex: 1;
        }

        .payment-method-name {
            margin: 0 0 5px 0;
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .payment-method-desc {
            margin: 0;
        }

        .badge-secure,
        .badge-cash {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-secure {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .badge-cash {
            background: rgba(255, 157, 45, 0.1);
            color: var(--secondary-color);
        }

        .payment-method-check {
            width: 30px;
            height: 30px;
            min-width: 30px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .payment-method-check i {
            font-size: 30px;
            color: var(--success-color);
        }

        .payment-method-radio:checked+.payment-method-label .payment-method-check {
            opacity: 1;
        }

        /* Résumé de commande */
        .order-summary-card {
            background: var(--light-bg);
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .order-summary-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }

        .order-summary-header i {
            font-size: 20px;
        }

        .order-summary-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .order-summary-body {
            padding: 20px;
            background: white;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .summary-divider {
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 15px 0;
        }

        .total-row {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .total-amount {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Boutons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .btn-back {
            padding: 12px 30px;
            border: 2px solid var(--border-color);
            background: white;
            color: var(--dark-color);
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--light-bg);
            border-color: var(--dark-color);
            color: var(--dark-color);
        }

        .btn-pay {
            flex: 1;
            padding: 15px 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(235, 0, 41, 0.3);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 0, 41, 0.4);
        }

        .security-info {
            padding: 15px;
            background: rgba(40, 167, 69, 0.05);
            border-radius: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-method-content {
                padding: 15px;
                gap: 15px;
            }

            .payment-method-icon {
                width: 50px;
                height: 50px;
                min-width: 50px;
            }

            .payment-method-icon i {
                font-size: 24px;
            }

            .payment-method-name {
                font-size: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-back,
            .btn-pay {
                width: 100%;
                justify-content: center;
            }
        }
    </style>


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
                <div class="col-lg-9">
                    <!-- En-tête avec icône -->
                    <div class="text-center mb-5">
                        <div class="payment-icon mb-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h2 class="payment-title">Choisissez votre moyen de paiement</h2>
                        <p class="text-muted">Sélectionnez la méthode de paiement qui vous convient</p>
                    </div>

                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                                @csrf

                                <div class="payment-methods mb-4">
                                    @foreach ($paymentMethods as $index => $method)
                                        <div class="payment-method-card mb-3" data-method="{{ $method->code }}">
                                            <input type="radio" class="payment-method-radio" name="payment_method_id"
                                                id="payment_{{ $method->id }}" value="{{ $method->id }}"
                                                {{ $loop->first ? 'checked' : '' }} required>
                                            <label for="payment_{{ $method->id }}" class="payment-method-label">
                                                <div class="payment-method-content">
                                                    <div class="payment-method-icon">
                                                        @if ($method->code === 'wave')
                                                            <img src="{{ asset('admin/assets/img/wave.png') }}" alt="Wave" style="width: 48px; height: 48px; object-fit: contain;">
                                                        @elseif ($method->icone)
                                                            <i class="{{ $method->icone }}"></i>
                                                        @else
                                                            <i class="fas fa-wallet"></i>
                                                        @endif
                                                    </div>
                                                    <div class="payment-method-info">
                                                        <h5 class="payment-method-name">{{ $method->nom }}</h5>
                                                        <p class="payment-method-desc">
                                                            @if ($method->code === 'wave')
                                                                <span class="badge-secure"><i class="fas fa-shield-alt"></i>
                                                                    Paiement sécurisé</span>
                                                                <small class="d-block text-muted mt-1">Mobile Money
                                                                    Wave</small>
                                                            @elseif($method->code === 'cash' || $method->code === 'espece')
                                                                <span class="badge-cash"><i
                                                                        class="fas fa-hand-holding-usd"></i> À la
                                                                    livraison</span>
                                                                <small class="d-block text-muted mt-1">Payez en espèces lors
                                                                    de la réception</small>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="payment-method-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Résumé de la commande -->
                                <div class="order-summary-card">
                                    <div class="order-summary-header">
                                        <i class="fas fa-shopping-bag"></i>
                                        <h5>Résumé de la commande</h5>
                                    </div>
                                    <div class="order-summary-body">
                                        <div class="summary-row">
                                            <span>Sous-total:</span>
                                            <span class="fw-bold">{{ number_format($subtotal, 0, '', ' ') }} FCFA</span>
                                        </div>
                                        <div class="summary-row">
                                            <span>Livraison:</span>
                                            <span class="fw-bold">{{ number_format($deliveryPrice, 0, '', ' ') }}
                                                FCFA</span>
                                        </div>
                                        <div class="summary-divider"></div>
                                        <div class="summary-row total-row">
                                            <span>Total à payer:</span>
                                            <span class="total-amount">{{ number_format($total, 0, '', ' ') }} FCFA</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="action-buttons mt-4">
                                    <a href="{{ route('checkout') }}" class="btn btn-back">
                                        <i class="fas fa-arrow-left"></i> Retour
                                    </a>
                                    <button type="submit" class="btn btn-pay">
                                        <span class="btn-text">Confirmer le paiement</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info sécurité -->
                    <div class="security-info text-center mt-4">
                        <i class="fas fa-lock text-success me-2"></i>
                        <small class="text-muted">Vos informations de paiement sont sécurisées et cryptées</small>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation lors de la sélection
            const radios = document.querySelectorAll('.payment-method-radio');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Ajouter une petite animation
                    const label = this.nextElementSibling;
                    label.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        label.style.transform = 'scale(1)';
                    }, 200);
                });
            });
        });
    </script>
@endsection
