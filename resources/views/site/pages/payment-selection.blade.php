@extends('site.layouts.app')
@section('title', 'Paiement')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-credit-card"></i></span>
            Paiement
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('panier') }}">Panier</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Paiement</li>
        </ul>
    </div>
</div>

<section class="pay-section">
    <div class="container">

        @include('admin.components.validationMessage')

        {{-- Stepper --}}
        <div class="pay-stepper">
            <div class="pay-step done">
                <div class="pay-step-dot"><i class="fas fa-check"></i></div>
                <span>Panier</span>
            </div>
            <div class="pay-step-line done"></div>
            <div class="pay-step active">
                <div class="pay-step-dot"><i class="fas fa-credit-card"></i></div>
                <span>Paiement</span>
            </div>
            <div class="pay-step-line"></div>
            <div class="pay-step">
                <div class="pay-step-dot"><i class="fas fa-check-circle"></i></div>
                <span>Confirmation</span>
            </div>
        </div>

        <div class="pay-grid">

            {{-- ══════ COLONNE GAUCHE — Méthodes ══════ --}}
            <div class="pay-left">
                <div class="pay-block">
                    <div class="pay-block-header">
                        <i class="fas fa-wallet"></i>
                        <h2>Moyen de paiement</h2>
                    </div>

                    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="pay-methods">
                            @foreach ($paymentMethods as $method)
                                <label class="pay-method {{ $loop->first ? 'is-selected' : '' }}" for="payment_{{ $method->id }}">
                                    <input
                                        type="radio"
                                        class="pay-method-radio"
                                        name="payment_method_id"
                                        id="payment_{{ $method->id }}"
                                        value="{{ $method->id }}"
                                        {{ $loop->first ? 'checked' : '' }}
                                        required
                                    >
                                    <div class="pay-method-icon">
                                        @if ($method->code === 'wave')
                                            <img src="{{ asset('admin/assets/img/wave.png') }}" alt="Wave">
                                        @elseif ($method->icone)
                                            <i class="{{ $method->icone }}"></i>
                                        @else
                                            <i class="fas fa-wallet"></i>
                                        @endif
                                    </div>
                                    <div class="pay-method-body">
                                        <span class="pay-method-name">{{ $method->nom }}</span>
                                        @if ($method->code === 'wave')
                                            <span class="pay-method-tag tag-secure"><i class="fas fa-shield-alt"></i> Sécurisé</span>
                                            <small class="pay-method-hint">Mobile Money Wave</small>
                                        @elseif (in_array($method->code, ['cash','espece']))
                                            <span class="pay-method-tag tag-cash"><i class="fas fa-hand-holding-usd"></i> À la livraison</span>
                                            <small class="pay-method-hint">Payez en espèces à la réception</small>
                                        @else
                                            <small class="pay-method-hint">{{ $method->description ?? '' }}</small>
                                        @endif
                                    </div>
                                    <div class="pay-method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- CTA --}}
                        <div class="pay-actions">
                            <a href="{{ route('panier') }}" class="pay-btn-back">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="pay-btn-submit" id="pay-submit">
                                <span class="pay-btn-text"><i class="fas fa-lock"></i> Confirmer le paiement</span>
                                <span class="pay-btn-loading" style="display:none">
                                    <span class="pay-spin"></span> Traitement…
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="pay-security">
                    <i class="fas fa-shield-alt"></i>
                    <span>Paiement 100% sécurisé — vos données sont protégées</span>
                </div>
            </div>

            {{-- ══════ COLONNE DROITE — Résumé ══════ --}}
            <div class="pay-right">
                <div class="pay-block">
                    <div class="pay-block-header">
                        <i class="fas fa-receipt"></i>
                        <h2>Récapitulatif</h2>
                    </div>

                    <div class="pay-summary">
                        <div class="pay-summary-row">
                            <span>Sous-total</span>
                            <span class="fw-bold">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="pay-summary-row">
                            <span>Livraison</span>
                            <span class="fw-bold">
                                @if ($deliveryPrice == 0)
                                    <span class="free-tag">Gratuit</span>
                                @else
                                    {{ number_format($deliveryPrice, 0, ',', ' ') }} FCFA
                                @endif
                            </span>
                        </div>
                        @if (isset($discount) && $discount > 0)
                        <div class="pay-summary-row">
                            <span>Remise</span>
                            <span class="discount-tag">–{{ number_format($discount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        <div class="pay-summary-divider"></div>
                        <div class="pay-summary-row pay-summary-total">
                            <span>Total à payer</span>
                            <span class="pay-total-amount">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    {{-- Infos livraison --}}
                    @if (Session::has('delivery_info'))
                    @php $info = Session::get('delivery_info'); @endphp
                    <div class="pay-delivery-info">
                        <div class="pay-delivery-row">
                            <i class="fas fa-truck"></i>
                            <span>{{ $info['mode'] ?? '—' }}</span>
                        </div>
                        @if (!empty($info['name']))
                        <div class="pay-delivery-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $info['name'] }}</span>
                        </div>
                        @endif
                        @if (!empty($info['address']))
                        <div class="pay-delivery-row">
                            <i class="fas fa-home"></i>
                            <span>{{ $info['address'] }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════ STYLES ══════════════ --}}
<style>
/* ── Layout ── */
.pay-section {
    padding: 48px 0 80px;
    background: #f4f5f7;
    min-height: 70vh;
}
.pay-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) {
    .pay-grid { grid-template-columns: 1fr; }
    .pay-right { order: -1; }
}

/* ── Stepper ── */
.pay-stepper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-bottom: 36px;
}
.pay-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.pay-step-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    color: #bbb;
    transition: all .3s;
}
.pay-step span {
    font-size: .75rem;
    font-weight: 600;
    color: #bbb;
    white-space: nowrap;
}
.pay-step.done .pay-step-dot {
    background: var(--theme-color, #e74c3c);
    border-color: var(--theme-color, #e74c3c);
    color: #fff;
}
.pay-step.done span { color: var(--theme-color, #e74c3c); }
.pay-step.active .pay-step-dot {
    background: #fff;
    border-color: var(--theme-color, #e74c3c);
    color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 4px rgba(231,76,60,.12);
}
.pay-step.active span { color: #1a1a1a; font-weight: 700; }
.pay-step-line {
    height: 2px;
    width: 60px;
    background: #ddd;
    flex-shrink: 0;
    margin: 0 4px;
    margin-bottom: 24px;
    transition: background .3s;
}
.pay-step-line.done { background: var(--theme-color, #e74c3c); }

/* ── Block ── */
.pay-block {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.pay-block-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f0f0f0;
}
.pay-block-header i {
    color: var(--theme-color, #e74c3c);
    font-size: 1.1rem;
}
.pay-block-header h2 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

/* ── Méthodes de paiement ── */
.pay-methods { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
.pay-method {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all .25s;
    background: #fff;
    user-select: none;
}
.pay-method:hover {
    border-color: #bbb;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.pay-method.is-selected {
    border-color: var(--theme-color, #e74c3c);
    background: #fff8f8;
    box-shadow: 0 2px 16px rgba(231,76,60,.12);
}
.pay-method-radio { display: none; }
.pay-method-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 10px;
    background: #f5f5f5;
    border: 1.5px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .25s;
    overflow: hidden;
}
.is-selected .pay-method-icon {
    background: linear-gradient(135deg, var(--theme-color, #e74c3c), #ff8a65);
    border-color: transparent;
}
.pay-method-icon img { width: 36px; height: 36px; object-fit: contain; }
.pay-method-icon i { font-size: 1.4rem; color: #555; }
.is-selected .pay-method-icon i { color: #fff; }
.pay-method-body { flex: 1; display: flex; flex-direction: column; gap: 3px; }
.pay-method-name {
    font-weight: 700;
    font-size: .95rem;
    color: #1a1a1a;
}
.pay-method-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .75rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    width: fit-content;
}
.tag-secure { background: rgba(40,167,69,.1); color: #28a745; }
.tag-cash { background:  "rgba(248,93,5,$($args[0].Groups[1].Value))" ; color: #f59e0b; }
.pay-method-hint { font-size: .78rem; color: #999; }
.pay-method-check {
    font-size: 1.4rem;
    color: var(--theme-color, #e74c3c);
    opacity: 0;
    transition: opacity .25s;
}
.is-selected .pay-method-check { opacity: 1; }

/* ── Actions ── */
.pay-actions {
    display: flex;
    gap: 12px;
    align-items: stretch;
}
.pay-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .88rem;
    font-weight: 600;
    color: #555;
    text-decoration: none;
    padding: 13px 18px;
    border: 1.5px solid #ddd;
    border-radius: 10px;
    transition: all .2s;
    white-space: nowrap;
}
.pay-btn-back:hover { border-color: #aaa; color: #1a1a1a; text-decoration: none; }
.pay-btn-submit {
    flex: 1;
    padding: 14px 20px;
    border: none;
    border-radius: 10px;
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .2s;
    letter-spacing: .2px;
}
.pay-btn-submit:hover { filter: brightness(.92); transform: translateY(-1px); }
.pay-btn-submit:disabled { opacity: .7; cursor: not-allowed; transform: none; filter: none; }
.pay-btn-loading {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.pay-spin {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: pay-spin .7s linear infinite;
    flex-shrink: 0;
}
@keyframes pay-spin { to { transform: rotate(360deg); } }
@media (max-width: 576px) {
    .pay-actions { flex-direction: column; }
    .pay-btn-back { justify-content: center; }
}

/* ── Sécurité ── */
.pay-security {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: .8rem;
    color: #888;
    padding: 10px;
}
.pay-security i { color: #28a745; }

/* ── Résumé ── */
.pay-summary { display: flex; flex-direction: column; }
.pay-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    font-size: .9rem;
    color: #555;
    border-bottom: 1px solid #f5f5f5;
}
.pay-summary-row:last-child { border-bottom: none; }
.pay-summary-divider {
    height: 2px;
    background: linear-gradient(to right, var(--theme-color, #e74c3c), #ff8a65);
    border-radius: 2px;
    margin: 4px 0;
}
.pay-summary-total {
    font-weight: 700;
    font-size: 1rem;
    color: #1a1a1a;
    padding-top: 12px;
    border-bottom: none;
}
.pay-total-amount {
    color: var(--theme-color, #e74c3c);
    font-size: 1.15rem;
    font-weight: 700;
}
.free-tag {
    background: #d4edda;
    color: #28a745;
    font-size: .75rem;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
}
.discount-tag { color: #28a745; font-weight: 700; }

/* ── Infos livraison ── */
.pay-delivery-info {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.pay-delivery-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: .83rem;
    color: #666;
}
.pay-delivery-row i {
    color: var(--theme-color, #e74c3c);
    font-size: .85rem;
    margin-top: 2px;
    flex-shrink: 0;
    width: 14px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle selected state on method cards
    document.querySelectorAll('.pay-method-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.pay-method').forEach(function (m) {
                m.classList.remove('is-selected');
            });
            this.closest('.pay-method').classList.add('is-selected');
        });
    });

    // Spinner on submit
    document.getElementById('payment-form').addEventListener('submit', function () {
        var btn = document.getElementById('pay-submit');
        btn.disabled = true;
        btn.querySelector('.pay-btn-text').style.display = 'none';
        btn.querySelector('.pay-btn-loading').style.display = 'inline-flex';
    });
});
</script>

@endsection
