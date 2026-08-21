@extends('admin.layouts.app')

@push('css')
<style>
    html, body {
        height: 100%;
    }

    .login-page-wrap {
        position: fixed;
        inset: 0;
        z-index: 1050;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
        display: flex;
        background: #fff;
    }

    .login-card {
        width: 100%;
        height: 100%;
        min-height: 100vh;
        background: #fff;
        border-radius: 0;
        box-shadow: none;
        overflow: hidden;
        display: flex;
    }

    /* ===== Left block : illustration ===== */
    .login-showcase {
        position: relative;
        flex: 1 1 46%;
        background: linear-gradient(150deg, #f85d05 0%, #ff9d2d 100%);
        color: #fff;
        padding: 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .login-showcase::before,
    .login-showcase::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .login-showcase::before {
        width: 260px;
        height: 260px;
        top: -80px;
        right: -80px;
    }

    .login-showcase::after {
        width: 180px;
        height: 180px;
        bottom: -60px;
        left: -60px;
    }

    .login-showcase-brand {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 18px;
    }

    .login-showcase-brand img {
        width: 42px;
        height: 42px;
        object-fit: contain;
        background: #fff;
        border-radius: 10px;
        padding: 4px;
    }

    .login-showcase-art {
        position: relative;
        z-index: 2;
        margin: 20px 0;
        text-align: center;
    }

    .login-showcase-art .art-icon {
        width: 140px;
        height: 140px;
        margin: 0 auto 22px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 58px;
        color: #fff;
        backdrop-filter: blur(2px);
    }

    .login-showcase-art h2 {
        color: #fff;
        font-size: 38px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 14px;
    }

    .login-showcase-art p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 17px;
        max-width: 400px;
        margin: 0 auto;
    }

    .login-showcase-features {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .login-showcase-features li {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 17px;
        font-weight: 600;
    }

    .login-showcase-features li i,
    .login-showcase-features li svg {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    /* ===== Right block : form ===== */
    .login-form-block {
        flex: 1 1 54%;
        height: 100%;
        overflow-y: auto;
        padding: 40px;
        background: #f7f8fa;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-form-inner {
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        border: 1px solid rgba(20, 20, 20, 0.04);
        box-shadow: 0 20px 45px rgba(20, 20, 20, 0.08), 0 4px 12px rgba(20, 20, 20, 0.04);
        padding: 48px 44px;
    }

    .login-form-inner .login-title {
        font-size: 27px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 6px;
        text-align: center;
    }

    .login-form-inner .login-subtitle {
        color: #7a7f87;
        font-size: 15px;
        margin-bottom: 32px;
        text-align: center;
    }

    .login-form-inner label {
        font-weight: 600;
        font-size: 13.5px;
        color: #333;
    }

    .login-form-inner .form-control {
        height: 48px;
        font-size: 14.5px;
        border-radius: 10px;
        border: 1px solid #e3e3e3;
    }

    .login-form-inner .form-control:focus {
        border-color: #f85d05;
        box-shadow: 0 0 0 0.15rem rgba(248, 93, 5, 0.15);
    }

    .login-form-inner .input-group .btn-toggle-password {
        border: 1px solid #e3e3e3;
        border-left: none;
        background: #fff;
        border-radius: 0 10px 10px 0;
        color: #7a7f87;
        padding: 0 16px;
    }

    .login-form-inner .input-group .form-control {
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    .btn-login {
        height: 48px;
        font-size: 15px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(120deg, #f85d05 0%, #ff9d2d 100%);
        color: #fff;
        font-weight: 600;
        letter-spacing: .3px;
        transition: opacity .2s ease;
    }

    .btn-login:hover {
        opacity: .92;
        color: #fff;
    }

    @media (max-width: 991.98px) {
        .login-showcase {
            display: none;
        }

        .login-form-block {
            padding: 24px;
        }

        .login-form-inner {
            padding: 32px 26px;
            border-radius: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page-wrap">
    <div class="login-card">

        {{-- ===== Bloc gauche : illustration / présentation ===== --}}
        <div class="login-showcase">
            <div class="login-showcase-brand">
                <img src="{{ asset('site/assets/img/custom/logo.png') }}" alt="{{ config('app.name') }}">
                <span>{{ config('app.name') }}</span>
            </div>

            <div class="login-showcase-art">
                <div class="art-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2>Gérez vos ventes en toute simplicité</h2>
                <p>Suivez vos commandes, votre stock et vos performances depuis un seul tableau de bord.</p>
            </div>

            <ul class="login-showcase-features">
                <li><i class="fas fa-shopping-cart"></i> Suivi des commandes et livraisons en temps réel</li>
                <li><i class="fas fa-boxes"></i> Gestion du stock et des produits</li>
                <li><i class="fas fa-chart-pie"></i> Rapports et statistiques de vente</li>
            </ul>
        </div>

        {{-- ===== Bloc droit : formulaire de connexion ===== --}}
        <div class="login-form-block">
            <div class="login-form-inner">
                @include('admin.components.validationMessage')

                <div class="login-title">Connexion</div>
                <div class="login-subtitle">Accédez à votre espace d'administration</div>

                <form class="needs-validation" novalidate method="POST" action="{{ route('auth.login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control" name="email" required autofocus>
                        <div class="invalid-feedback">
                            Champs obligatoire
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="d-block">Mot de passe</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control" name="password" required>
                            <div class="input-group-append">
                                <button class="btn btn-toggle-password" type="button" id="togglePassword"
                                    aria-label="Afficher le mot de passe">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Champs obligatoire
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-login btn-block">
                            Connexion
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
        this.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
</script>
@endpush
