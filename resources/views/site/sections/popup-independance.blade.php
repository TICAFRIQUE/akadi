@php
    $anneesIndependance = now()->year - 1960;
    $isPreviewIndep = request()->boolean('preview_indep');
@endphp

<style>
/* ── Popup fête de l'indépendance ── */
.ak-indep-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .6);
    backdrop-filter: blur(4px);
    z-index: 1600;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
}
.ak-indep-backdrop.show { opacity: 1; pointer-events: all; }

.ak-indep-popup {
    background: #fff;
    border-radius: 22px;
    overflow: hidden;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 32px 80px rgba(0,0,0,.35);
    transform: translateY(24px) scale(.97);
    transition: transform .35s cubic-bezier(.34,1.56,.64,1);
    position: relative;
}
.ak-indep-backdrop.show .ak-indep-popup { transform: translateY(0) scale(1); }

/* ── Bandeau tricolore (orange / blanc / vert) ── */
.ak-indep-header {
    background: linear-gradient(90deg, #ff8200 0% 33.33%, #ffffff 33.33% 66.66%, #009a44 66.66% 100%);
    padding: 34px 24px 26px;
    text-align: center;
    position: relative;
}
.ak-indep-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.12);
}
.ak-indep-header > * { position: relative; z-index: 1; }

.ak-indep-close {
    position: absolute;
    top: 12px; right: 12px;
    width: 30px; height: 30px;
    border-radius: 50%;
    background: rgba(255,255,255,.3);
    border: none;
    color: #1a1a1a;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .2s;
    line-height: 1;
    z-index: 2;
}
.ak-indep-close:hover { background: rgba(255,255,255,.5); }

.ak-indep-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.92);
    color: #1a1a1a;
    font-size: .75rem;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.15);
}

.ak-indep-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    text-shadow: 0 2px 8px rgba(0,0,0,.35);
    line-height: 1.3;
    margin: 0;
}

/* ── Corps ── */
.ak-indep-body { padding: 24px 26px 28px; text-align: center; }
.ak-indep-desc {
    font-size: .9rem;
    color: #555;
    line-height: 1.65;
    margin-bottom: 22px;
}
.ak-indep-desc strong { color: #1a1a1a; }

.ak-indep-actions { display: flex; flex-direction: column; gap: 10px; }
.ak-indep-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px;
    background: #ff8200;
    color: #fff;
    font-size: .9rem;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
}
.ak-indep-cta:hover { background: #d96e00; color: #fff; text-decoration: none; transform: translateY(-1px); }

.ak-indep-skip {
    text-align: center;
    margin-top: 4px;
}
.ak-indep-skip button {
    background: none;
    border: none;
    font-size: .78rem;
    color: #aaa;
    cursor: pointer;
    text-decoration: underline;
    transition: color .15s;
}
.ak-indep-skip button:hover { color: #777; }
</style>

<div class="ak-indep-backdrop" id="ak-indep-popup">
    <div class="ak-indep-popup">
        <div class="ak-indep-header">
            <button class="ak-indep-close" id="ak-indep-close" aria-label="Fermer">×</button>
            <span class="ak-indep-badge">🎉 {{ $anneesIndependance }} ans d'Indépendance</span>
            <h2 class="ak-indep-title">Joyeuse Fête de<br>l'Indépendance 🇨🇮</h2>
        </div>

        <div class="ak-indep-body">
            <p class="ak-indep-desc">
                Toute l'équipe <strong>Akadi</strong> vous souhaite une excellente fête !<br>
                Bonne Indépendance à toute la Côte d'Ivoire 🧡🤍💚
            </p>

            <div class="ak-indep-actions">
                <a href="{{ route('liste-produit') }}" class="ak-indep-cta">
                    <i class="fas fa-utensils"></i> Découvrir nos plats
                </a>
            </div>

            <div class="ak-indep-skip">
                <button id="ak-indep-skip">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var popup     = document.getElementById('ak-indep-popup');
    var close     = document.getElementById('ak-indep-close');
    var skip      = document.getElementById('ak-indep-skip');
    var isPreview = @json($isPreviewIndep);

    function dismiss() {
        popup.classList.remove('show');
        setTimeout(function () { popup.style.display = 'none'; }, 350);
        try { sessionStorage.removeItem('ak_indep_popup_dismissed'); } catch(e){}
        if (!isPreview) {
            try { sessionStorage.setItem('ak_indep_popup_dismissed', '1'); } catch(e){}
        }
    }

    if (close) close.addEventListener('click', dismiss);
    if (skip)  skip.addEventListener('click', dismiss);
    popup.addEventListener('click', function(e) {
        if (e.target === popup) dismiss();
    });

    // En mode aperçu (?preview_indep=1), on ignore le "déjà fermé" pour pouvoir retester.
    if (!isPreview) {
        try { if (sessionStorage.getItem('ak_indep_popup_dismissed')) return; } catch(e){}
    }

    setTimeout(function () { popup.classList.add('show'); }, 900);
})();
</script>
