<style>
/* ── Popup register ── */
.ak-popup-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(10, 0, 0, .65);
    backdrop-filter: blur(4px);
    z-index: 1500;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
}
.ak-popup-backdrop.show { opacity: 1; pointer-events: all; }

.ak-popup {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 32px 80px rgba(0,0,0,.35);
    transform: translateY(24px) scale(.97);
    transition: transform .35s cubic-bezier(.34,1.56,.64,1);
    position: relative;
}
.ak-popup-backdrop.show .ak-popup { transform: translateY(0) scale(1); }

/* ── Header image zone ── */
.ak-popup-header {
    background: linear-gradient(135deg, #1a0000 0%, #3d0010 60%, #eb0029 100%);
    padding: 36px 28px 28px;
    text-align: center;
    position: relative;
}
.ak-popup-logo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 2px solid rgba(255,255,255,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    animation: ak-pulse 2s ease-in-out infinite;
}
.ak-popup-logo img { width: 52px; height: 52px; object-fit: contain; }
@keyframes ak-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,.2); }
    50%       { box-shadow: 0 0 0 12px rgba(255,255,255,0); }
}
.ak-popup-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin: 0;
}
.ak-popup-title span { color: #f85d05; }

/* ── Close ── */
.ak-popup-close {
    position: absolute;
    top: 12px; right: 12px;
    width: 30px; height: 30px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    border: none;
    color: #fff;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .2s;
    line-height: 1;
}
.ak-popup-close:hover { background: rgba(255,255,255,.3); }

/* ── Body ── */
.ak-popup-body { padding: 24px 28px 28px; }
.ak-popup-desc {
    font-size: .88rem;
    color: #666;
    line-height: 1.65;
    text-align: center;
    margin-bottom: 22px;
}
.ak-popup-desc strong { color: #1a1a1a; }

/* ── Perks ── */
.ak-popup-perks {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 22px;
}
.ak-popup-perk {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .83rem;
    color: #444;
}
.ak-popup-perk-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: rgba(235,0,41,.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #eb0029;
    font-size: .75rem;
    flex-shrink: 0;
}

/* ── CTAs ── */
.ak-popup-actions { display: flex; flex-direction: column; gap: 10px; }
.ak-popup-register {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .9rem;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
}
.ak-popup-register:hover { background: #d44d00; color: #fff; text-decoration: none; transform: translateY(-1px); }
.ak-popup-login {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    background: transparent;
    color: #555;
    font-size: .85rem;
    font-weight: 600;
    border-radius: 10px;
    border: 1.5px solid #e0e0e0;
    text-decoration: none;
    transition: all .2s;
}
.ak-popup-login:hover { border-color: #eb0029; color: #eb0029; text-decoration: none; }

.ak-popup-skip {
    text-align: center;
    margin-top: 14px;
}
.ak-popup-skip button {
    background: none;
    border: none;
    font-size: .78rem;
    color: #bbb;
    cursor: pointer;
    text-decoration: underline;
    transition: color .15s;
}
.ak-popup-skip button:hover { color: #888; }
</style>

{{-- ── Popup ── --}}
<div class="ak-popup-backdrop" id="ak-register-popup">
    <div class="ak-popup">
        {{-- Header --}}
        <div class="ak-popup-header">
            <button class="ak-popup-close" id="ak-popup-close" aria-label="Fermer">×</button>
            <div class="ak-popup-logo">
                <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" alt="Akadi">
            </div>
            <h2 class="ak-popup-title">
                Rejoins la famille <span>Akadi</span> 🍗
            </h2>
        </div>

        {{-- Body --}}
        <div class="ak-popup-body">
            <p class="ak-popup-desc">
                Crée ton compte gratuitement et profite d'une<br>
                <strong>expérience de commande simplifiée</strong>.
            </p>

            <div class="ak-popup-perks">
                <div class="ak-popup-perk">
                    <div class="ak-popup-perk-icon"><i class="fas fa-history"></i></div>
                    <span>Suivez l'historique de vos commandes</span>
                </div>
                <div class="ak-popup-perk">
                    <div class="ak-popup-perk-icon"><i class="fas fa-bolt"></i></div>
                    <span>Commandez encore plus vite</span>
                </div>
                <div class="ak-popup-perk">
                    <div class="ak-popup-perk-icon"><i class="fas fa-tag"></i></div>
                    <span>Accède aux offres exclusives</span>
                </div>
            </div>

            <div class="ak-popup-actions">
                <a href="{{ route('register-form') }}" class="ak-popup-register">
                    <i class="fas fa-user-plus"></i> Créer mon compte
                </a>
                <a href="{{ route('login-form') }}" class="ak-popup-login">
                    <i class="fas fa-sign-in-alt"></i> J'ai déjà un compte
                </a>
            </div>

            <div class="ak-popup-skip">
                <button id="ak-popup-skip">Continuer sans compte</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var popup  = document.getElementById('ak-register-popup');
    var close  = document.getElementById('ak-popup-close');
    var skip   = document.getElementById('ak-popup-skip');

    function dismiss() {
        popup.classList.remove('show');
        setTimeout(function () { popup.style.display = 'none'; }, 350);
        try { sessionStorage.setItem('ak_popup_dismissed', '1'); } catch(e){}
    }

    if (close) close.addEventListener('click', dismiss);
    if (skip)  skip.addEventListener('click', dismiss);
    popup.addEventListener('click', function(e) {
        if (e.target === popup) dismiss();
    });

    try { if (sessionStorage.getItem('ak_popup_dismissed')) return; } catch(e){}

    setTimeout(function () { popup.classList.add('show'); }, 1200);
})();
</script>
