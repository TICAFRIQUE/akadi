@if ($annonce)
@php
    // Style "message flash" : toujours sur une seule ligne. Le HTML riche (gras, div,
    // styles collés depuis Word...) ne se prête pas à un défilement propre — on affiche
    // donc le texte brut ici, et on fait défiler seulement s'il dépasse ce seuil.
    $texteAnnonce = trim(strip_tags($annonce->texte ?? ''));
    $seuilDefilement = 70;
    $estLong = mb_strlen($texteAnnonce) > $seuilDefilement;
    $dureeDefilement = $estLong ? max(10, (int) round(mb_strlen($texteAnnonce) / 8)) : 0;
@endphp
<style>
.ak-announce-bar {
    background: linear-gradient(90deg, var(--ak-dark,#1a0000) 0%, #3d0010 50%, var(--ak-dark,#1a0000) 100%);
    border-bottom: 2px solid var(--ak-red, #eb0029);
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    animation: ak-announce-slide-in .5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}
@keyframes ak-announce-slide-in {
    from { transform: translateY(-100%); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}
.ak-announce-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ak-orange, #f85d05);
    font-size: .85rem;
    flex-shrink: 0;
}
.ak-announce-text-wrap {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    white-space: nowrap;
}
.ak-announce-text {
    color: #fff;
    font-size: .85rem;
    font-weight: 500;
    white-space: nowrap;
}
.ak-announce-text-wrap.is-static {
    text-align: center;
    text-overflow: ellipsis;
}
.ak-announce-text-wrap.is-static .ak-announce-text { overflow: hidden; text-overflow: ellipsis; display: block; }
.ak-announce-text-wrap.is-scrolling .ak-announce-text {
    display: inline-block;
    padding-left: 100%;
    animation-name: ak-announce-marquee;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}
.ak-announce-text-wrap.is-scrolling:hover .ak-announce-text { animation-play-state: paused; }
@keyframes ak-announce-marquee {
    from { transform: translateX(0); }
    to   { transform: translateX(-100%); }
}
.ak-announce-close {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: #fff;
    font-size: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .2s;
    padding: 0;
    line-height: 1;
}
.ak-announce-close:hover { background: var(--ak-red, #eb0029); border-color: var(--ak-red, #eb0029); }

@media (max-width: 576px) {
    .ak-announce-bar { padding: 8px 12px; }
    .ak-announce-text { font-size: .76rem; }
}

@media (prefers-reduced-motion: reduce) {
    .ak-announce-bar { animation: none; }
    .ak-announce-text-wrap.is-scrolling .ak-announce-text { animation: none; padding-left: 0; }
}
</style>

<div id="bande-annonce" class="ak-announce-bar" data-annonce-id="{{ $annonce->id }}">
    <div class="ak-announce-icon"><i class="fas fa-bullhorn"></i></div>

    <div class="ak-announce-text-wrap {{ $estLong ? 'is-scrolling' : 'is-static' }}">
        <span class="ak-announce-text"
            @if ($estLong) style="animation-duration: {{ $dureeDefilement }}s;" @endif>
            {{ $texteAnnonce }}
        </span>
    </div>

    <button type="button" class="ak-announce-close" aria-label="Fermer l'annonce">&times;</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var bar = document.getElementById('bande-annonce');
    if (!bar) return;

    // Une annonce fermée reste masquée pour ce visiteur (par id d'annonce, donc
    // une nouvelle annonce publiée réapparaît même si la précédente a été fermée).
    var storageKey = 'ak_annonce_fermee_' + bar.dataset.annonceId;
    try {
        if (localStorage.getItem(storageKey)) {
            bar.style.display = 'none';
            return;
        }
    } catch (e) {}

    var closeBtn = bar.querySelector('.ak-announce-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            bar.style.transition = 'opacity .3s';
            bar.style.opacity = '0';
            setTimeout(function () { bar.style.display = 'none'; }, 300);
            try { localStorage.setItem(storageKey, '1'); } catch (e) {}
        });
    }
});
</script>
@endif
