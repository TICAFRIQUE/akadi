@extends('site.layouts.app')

@section('title', 'Paiement en cours')

@section('content')
    <div class="breadcumb-wrapper" data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Paiement en cours</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Paiement</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="space-top space-extra-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <!-- Icône de chargement -->
                            <div class="mb-4">
                                <div class="spinner-wrapper">
                                    <div class="spinner-border text-primary" role="status" style="width: 80px; height: 80px;">
                                        <span class="sr-only">Chargement...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Message principal -->
                            <h2 class="text-primary mb-3">Vérification en cours...</h2>
                            <p class="lead mb-4">
                                {{ $message ?? 'Nous vérifions votre paiement. Veuillez patienter quelques instants.' }}
                            </p>

                            <!-- Informations supplémentaires -->
                            <div class="alert alert-info mx-auto" style="max-width: 500px;">
                                <i class="fas fa-info-circle me-2"></i>
                                Cette page se mettra à jour automatiquement une fois votre paiement confirmé.
                            </div>

                            <!-- Actions -->
                            <div class="mt-4">
                                <a href="{{ route('user-order') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-list"></i> Voir mes commandes
                                </a>
                                <a href="{{ route('page-acceuil') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Retour à l'accueil
                                </a>
                            </div>

                            <!-- Message d'attente -->
                            <div class="mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Si la page ne se met pas à jour dans 2 minutes, veuillez vérifier vos commandes.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .spinner-wrapper {
            display: inline-block;
            position: relative;
        }

        .spinner-border {
            border-width: 6px;
        }

        /* Animation de pulsation pour l'icône */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .spinner-wrapper {
            animation: pulse 2s ease-in-out infinite;
        }
    </style>

    <script>
        @if(isset($order_id))
        // Vérifier périodiquement si le paiement a été confirmé
        let checkCount = 0;
        const maxChecks = 24; // 2 minutes (5 secondes × 24)
        
        const checkInterval = setInterval(function() {
            checkCount++;
            
            // Faire une requête AJAX pour vérifier le statut
            fetch('{{ route("wave.check-status") }}?order_id={{ $order_id }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.completed) {
                    clearInterval(checkInterval);
                    window.location.href = '{{ url("/order/success") }}/' + data.order_id;
                } else if (data.status === 'failed' || data.status === 'cancelled') {
                    clearInterval(checkInterval);
                    window.location.href = '{{ route("payment.select") }}';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification:', error);
            });
            
            // Arrêter après maxChecks tentatives
            if (checkCount >= maxChecks) {
                clearInterval(checkInterval);
                window.location.href = "{{ route('user-order') }}";
            }
        }, 5000);
        @else
        // Rafraîchir la page après 5 secondes pour vérifier si le webhook a été traité
        setTimeout(function() {
            window.location.reload();
        }, 5000);

        // Arrêter le rafraîchissement automatique après 2 minutes
        setTimeout(function() {
            window.location.href = "{{ route('user-order') }}";
        }, 120000);
        @endif
    </script>
@endsection
