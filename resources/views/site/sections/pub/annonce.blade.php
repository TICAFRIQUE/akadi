<style>
.ak-announce-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: linear-gradient(90deg, var(--ak-dark,#1a0000) 0%, #3d0010 50%, var(--ak-dark,#1a0000) 100%);
    border-bottom: 2px solid var(--ak-red, #eb0029);
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.ak-announce-content {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: center;
    color: #fff;
    font-size: .85rem;
    font-weight: 500;
}
.ak-announce-content i { color: var(--ak-orange, #f85d05); font-size: 1rem; flex-shrink: 0; }
.ak-announce-close {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: #fff;
    font-size: .8rem;
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
</style>

@if ($annonce)
    <div id="bande-annonce" class="ak-announce-bar">
        <div class="ak-announce-content">
            <i class="fas fa-bullhorn"></i>
            <span>{!! $annonce['texte'] !!}</span>
        </div>
        <button class="ak-announce-close close" aria-label="Fermer">×</button>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    var bar = document.getElementById('bande-annonce');
    if (!bar) return;

    function blinker() {
        bar.style.transition = 'opacity .5s';
        bar.style.opacity = '0';
        setTimeout(function () { bar.style.opacity = '1'; }, 500);
    }
    setInterval(blinker, 10000);

    var closeBtn = bar.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            bar.style.transition = 'max-height .3s, padding .3s, opacity .3s';
            bar.style.opacity = '0';
            setTimeout(function () { bar.style.display = 'none'; }, 300);
        });
    }
});
</script>
