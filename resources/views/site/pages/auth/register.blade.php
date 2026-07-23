@extends('site.layouts.app')
@section('title', 'Créer un compte')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-user-plus"></i></span>
            Créer un compte
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Inscription</li>
        </ul>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="auth-title">Créer un compte</h2>
                <p class="auth-subtitle">Rejoignez Akadi et commandez en quelques clics</p>
            </div>

            @include('admin.components.validationMessage')

            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf
                <input type="hidden" name="form_time" value="{{ time() }}">
                <input type="hidden" name="role" value="client">
                <input type="hidden" name="url_previous" value="{{ url()->previous() }}">

                {{-- Honeypot --}}
                <div style="display:none !important" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>

                {{-- Nom --}}
                <div class="auth-field">
                    <label class="auth-label">Nom et prénoms <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="name"
                        class="auth-input"
                        placeholder="Ex : Alex Kouamelan"
                        minlength="3"
                        maxlength="100"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                {{-- Téléphone --}}
                <div class="auth-field">
                    <label class="auth-label">Numéro de téléphone <span class="text-danger">*</span></label>
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
                    <span class="auth-hint">Ce numéro vous servira à vous connecter — 10 chiffres</span>
                </div>

                {{-- Email --}}
                <div class="auth-field">
                    <label class="auth-label">
                        Email
                        <span class="auth-optional">(Facultatif — recommandé)</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="auth-input"
                        placeholder="alex@gmail.com"
                        value="{{ old('email') }}"
                    >
                </div>

                {{-- Date anniversaire --}}
                <div class="auth-field">
                    <label class="auth-label">
                        Date d'anniversaire
                        <span class="auth-optional">— une surprise vous attend le Jour-J 🎁</span>
                    </label>
                    <div class="auth-birthday">
                        <select name="jour" class="auth-select">
                            <option value="" disabled selected>Jour</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ old('jour') == $i ? 'selected' : '' }}>
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        <select name="mois" class="auth-select">
                            <option value="" disabled selected>Mois</option>
                            @foreach (['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $k => $m)
                                <option value="{{ $k + 1 }}" {{ old('mois') == $k + 1 ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="th-btn auth-submit w-100" id="submit-btn">
                    <span class="btn-text">Créer mon compte</span>
                    <span class="btn-loading d-none">
                        <span class="auth-spin"></span>Création en cours...
                    </span>
                </button>

                <p class="auth-footer-link">
                    Déjà un compte ?
                    <a href="{{ route('login-form') }}">Se connecter</a>
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
    max-width: 500px;
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
    margin-bottom: 20px;
}
.auth-label {
    display: block;
    font-weight: 600;
    font-size: .88rem;
    color: #333;
    margin-bottom: 7px;
}
.auth-optional {
    font-weight: 400;
    color: #999;
    font-size: .8rem;
}
.auth-input {
    width: 100%;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    padding: 13px 14px;
    font-size: .95rem;
    color: #1a1a1a;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    background: #fff;
}
.auth-input:focus {
    border-color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 3px rgba(231,76,60,.1);
}
.auth-phone-input {
    display: flex;
    align-items: center;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
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
.auth-phone-input input::placeholder { color: #bbb; }
.auth-hint {
    display: block;
    font-size: .78rem;
    color: #999;
    margin-top: 5px;
}
.auth-birthday {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.auth-select {
    width: 100%;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    padding: 12px 14px;
    font-size: .9rem;
    color: #444;
    background: #fff;
    outline: none;
    cursor: pointer;
    transition: border-color .2s;
}
.auth-select:focus {
    border-color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 3px rgba(231,76,60,.1);
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
.auth-footer-link a:hover { text-decoration: underline; }
@media (max-width: 576px) {
    .auth-card { padding: 32px 22px; }
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
