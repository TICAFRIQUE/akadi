@extends('admin.layouts.app')

@section('content')
    <section class="section">
        @include('admin.components.validationMessage')

        <style>
            .info-card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .info-card:hover {
                transform: translateY(-5px);
            }

            .user-profile-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .welcome-card {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                color: white;
            }

            .info-item {
                padding: 15px;
                margin: 10px 0;
                background: #f8f9fa;
                border-radius: 10px;
                border-left: 4px solid #667eea;
            }

            .permission-alert {
                background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                border: none;
                color: #fff;
                font-weight: 500;
            }
        </style>

        <!-- Alert Permission -->
        <div class="row">
            <div class="col-12">
                <div class="alert permission-alert alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vous n'avez pas la permission d'accéder au dashboard complet.
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card info-card user-profile-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-circle mr-2"></i> Mon Profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <img src="{{ auth()->user()->avatar ?? asset('site/assets/img/custom/avatar.png') }}"
                                    alt="Avatar" class="rounded-circle"
                                    style="width: 120px; height: 120px; border: 4px solid white; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                                    <i class="fas fa-user mr-2"></i>
                                    <strong>Nom :</strong> {{ auth()->user()->name }}
                                </div>
                                <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <strong>Email :</strong> {{ auth()->user()->email }}
                                </div>
                                <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                                    <i class="fas fa-phone mr-2"></i>
                                    <strong>Téléphone :</strong> {{ auth()->user()->phone ?? 'Non renseigné' }}
                                </div>
                                <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    <strong>Rôle :</strong>
                                    @if (auth()->user()->roles->count() > 0)
                                        @foreach (auth()->user()->roles as $role)
                                            <span class="badge badge-light">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-light">Aucun rôle assigné</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card info-card welcome-card">
                    <div class="card-header">
                        <h4><i class="fas fa-calendar-check mr-2"></i> Informations</h4>
                    </div>
                    <div class="card-body">
                        <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                            <i class="fas fa-clock mr-2"></i>
                            <strong>Date du jour :</strong><br>
                            {{ \Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
                        </div>
                        <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            <strong>Heure :</strong><br>
                            <span id="current-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                        </div>
                        <div class="info-item" style="background: rgba(255,255,255,0.2); border-left-color: white;">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <strong>Compte créé le :</strong><br>
                            {{ \Carbon\Carbon::parse(auth()->user()->created_at)->isoFormat('D MMMM YYYY') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        

        <!-- Help Section -->
        <div class="row">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle fa-3x mb-3" style="color: #667eea;"></i>
                        <h5>Besoin de plus de permissions ?</h5>
                        <p class="text-muted">
                            Contactez votre administrateur système pour obtenir l'accès au dashboard complet et aux autres
                            fonctionnalités.
                        </p>
                        <a href="mailto:info@akadi.ci" class="btn btn-primary">
                            <i class="fas fa-envelope mr-2"></i> Contacter l'administrateur
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @push('js')
        <script>
            // Update current time every second
            setInterval(function() {
                var now = new Date();
                var hours = String(now.getHours()).padStart(2, '0');
                var minutes = String(now.getMinutes()).padStart(2, '0');
                var seconds = String(now.getSeconds()).padStart(2, '0');
                $('#current-time').text(hours + ':' + minutes + ':' + seconds);
            }, 1000);
        </script>
    @endpush
@endsection
