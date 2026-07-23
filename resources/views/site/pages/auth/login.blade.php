@extends('site.layouts.app')
@section('title', 'Se connecter')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-user"></i></span>
            Connexion
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Connexion</li>
        </ul>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2 class="auth-title">Bon retour !</h2>
                <p class="auth-subtitle">Entrez votre numéro pour accéder à votre compte</p>
            </div>

            @include('admin.components.validationMessage')

            <form action="{{ route('login') }}" method="POST" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label class="auth-label">Numéro de téléphone</label>
                    <div class="auth-phone-input">
                        <span class="auth-prefix">🇨🇮 +225</span>
                        <input
                            type="tel"
                            name="phone"
                            placeholder="0707070707"
                            maxlength="10"
                            inputmode="numeric"
                            pattern="[0-9]{10}"
                            value="{{ old('phone') }}"
                            autocomplete="tel"
                            required
                        >
                    </div>
                    <span class="auth-hint">Ex : 0707070707 — 10 chiffres</span>
                </div>

                <input type="hidden" name="url_previous" value="{{ url()->previous() }}">

                <button type="submit" class="th-btn auth-submit w-100" id="submit-btn">
                    <span class="btn-text">Se connecter</span>
                    <span class="btn-loading d-none">
                        <span class="auth-spin"></span>Connexion en cours...
                    </span>
                </button>

                <p class="auth-footer-link">
                    Pas encore de compte ?
                    <a href="{{ route('register-form') }}">Créer un compte</a>
                </p>

            </form>
        </div>
    </div>
</section>

<style>
.auth-section {
    padding: 60px 0 80px;
    background: #f7f7f7;
    min-height: 60vh;
    display: flex;
    align-items: center;
}
.auth-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0,0,0,.08);
    padding: 44px 48px;
    max-width: 460px;
    margin: 0 auto;
}
.auth-header {
    text-align: center;
    margin-bottom: 32px;
}
.auth-icon {
    font-size: 3rem;
    color: var(--theme-color, #e74c3c);
    margin-bottom: 12px;
}
.auth-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 6px;
}
.auth-subtitle {
    color: #777;
    font-size: .93rem;
    margin: 0;
}
.auth-field {
    margin-bottom: 24px;
}
.auth-label {
    display: block;
    font-weight: 600;
    font-size: .88rem;
    color: #333;
    margin-bottom: 8px;
}
.auth-phone-input {
    display: flex;
    align-items: center;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color .2s;
}
.auth-phone-input:focus-within {
    border-color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 3px rgba(231,76,60,.1);
}
.auth-prefix {
    padding: 13px 16px;
    background: #f5f5f5;
    border-right: 1.5px solid #ddd;
    font-size: .92rem;
    color: #444;
    white-space: nowrap;
    font-weight: 600;
    user-select: none;
}
.auth-phone-input input {
    flex: 1;
    border: none;
    outline: none;
    padding: 13px 14px;
    font-size: .95rem;
    color: #1a1a1a;
    background: transparent;
    letter-spacing: .5px;
}
.auth-phone-input input::placeholder {
    color: #bbb;
}
.auth-hint {
    display: block;
    font-size: .78rem;
    color: #999;
    margin-top: 5px;
}
.auth-submit {
    margin-top: 8px;
    padding: 14px;
    font-size: 1rem;
    font-weight: 700;
    border-radius: 8px;
    letter-spacing: .3px;
    transition: opacity .2s;
}
.auth-submit:disabled {
    opacity: .75;
    cursor: not-allowed;
}
.btn-loading {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.auth-spin {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: auth-rotate .7s linear infinite;
    flex-shrink: 0;
}
@keyframes auth-rotate {
    to { transform: rotate(360deg); }
}
.auth-footer-link {
    text-align: center;
    margin-top: 20px;
    font-size: .88rem;
    color: #777;
}
.auth-footer-link a {
    font-weight: 700;
    color: var(--theme-color, #e74c3c);
    text-decoration: none;
}
.auth-footer-link a:hover {
    text-decoration: underline;
}
@media (max-width: 576px) {
    .auth-card {
        padding: 32px 22px;
    }
}
</style>

<script>
document.querySelector('form').addEventListener('submit', function () {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
});
</script>

@endsection
