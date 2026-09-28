@extends('site.layouts.app')
@section('title', 'Top promo')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-tag"></i></span>
            {{ $promo->discount }}% de réduction
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Top promo</li>
        </ul>
    </div>
</div>

<style>
.promo-detail-section { padding: 60px 0 80px; }
.promo-detail-image-wrap { position: relative; margin-bottom: 28px; border-radius: 14px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,.12); }
.promo-detail-image-wrap img { width: 100%; display: block; }
.promo-detail-discount-badge {
    position: absolute; top: 20px; right: 20px; width: 96px; height: 96px;
    background: linear-gradient(135deg, var(--ak-red,#eb0029), var(--ak-orange,#f85d05));
    border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; box-shadow: 0 8px 24px rgba(235,0,41,.4); line-height: 1;
}
.promo-detail-discount-badge .pct { font-size: 1.7rem; }
.promo-detail-discount-badge .pct-off { font-size: .72rem; opacity: .85; }
.promo-detail-status {
    display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px;
    font-weight: 700; font-size: .9rem; margin-bottom: 20px;
}
.promo-detail-status.en-cours { background: rgba(235,0,41,.08); color: var(--ak-red,#eb0029); }
.promo-detail-status.bientot { background: rgba(248,93,5,.08); color: var(--ak-orange,#f85d05); }
.promo-detail-status.termine { background: #f2f2f2; color: #888; }
.promo-detail-text { font-size: 1rem; color: #444; line-height: 1.85; margin-bottom: 28px; }
.promo-detail-cta {
    display: inline-flex; align-items: center; gap: 8px; padding: 14px 30px; background: var(--ak-red, #eb0029);
    color: #fff; font-size: .92rem; font-weight: 700; border-radius: 8px; text-decoration: none; transition: all .2s;
}
.promo-detail-cta:hover { background: #c4001f; color: #fff; text-decoration: none; transform: translateY(-2px); }
.promo-detail-back { display: inline-flex; align-items: center; gap: 6px; color: #777; font-size: .9rem; margin-top: 28px; text-decoration: none; }
.promo-detail-back:hover { color: var(--ak-red,#eb0029); }
</style>

<section class="promo-detail-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="promo-detail-image-wrap">
                    <img src="{{ $promo->getFirstMediaUrl('publicite_image') }}" alt="{{ $promo->discount }}% de réduction">
                    <div class="promo-detail-discount-badge">
                        <span class="pct">{{ $promo->discount }}</span>
                        <span class="pct-off">% Off</span>
                    </div>
                </div>

                @if ($promo->status_pub == 'en_cours')
                    <div class="promo-detail-status en-cours">
                        <i class="fas fa-fire"></i>
                        Se termine dans <span id="Promo-Detail-Timer">--</span>
                    </div>
                @elseif ($promo->status_pub == 'bientot')
                    <div class="promo-detail-status bientot">
                        <i class="fas fa-clock"></i>
                        Commence dans <span id="Promo-Detail-Timer-Debut">--</span>
                    </div>
                @else
                    <div class="promo-detail-status termine">
                        <i class="fas fa-info-circle"></i>
                        Cette promotion n'est plus disponible
                    </div>
                @endif

                <div class="promo-detail-text">{!! $promo->texte !!}</div>

                @if ($promo->status_pub == 'en_cours' && $promo->url)
                    <a href="{{ $promo->url }}" class="promo-detail-cta">
                        {{ $promo->button_name ?: 'Profiter de l\'offre' }} <i class="fas fa-arrow-right"></i>
                    </a>
                @endif

                <div>
                    <a href="{{ route('page-acceuil') }}" class="promo-detail-back">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@if ($promo->status_pub == 'en_cours' || $promo->status_pub == 'bientot')
<script>
(function () {
    function formatCompte(left) {
        var d = Math.floor(left / 86400);
        var h = Math.floor((left % 86400) / 3600);
        var m = Math.floor((left % 3600) / 60);
        var s = left % 60;
        return d + ' j : ' + h + ' h : ' + m + ' m : ' + s + ' s';
    }

    @if ($promo->status_pub == 'en_cours')
        var timerEl = document.getElementById('Promo-Detail-Timer');
        if (timerEl) {
            var endTime = new Date(@json($promo->date_fin_pub)).getTime();
            (function countDown() {
                var left = Math.floor((endTime - Date.now()) / 1000);
                if (left <= 0) { timerEl.textContent = 'Terminé'; return; }
                timerEl.textContent = formatCompte(left);
                setTimeout(countDown, 1000);
            })();
        }
    @else
        var timerDebutEl = document.getElementById('Promo-Detail-Timer-Debut');
        if (timerDebutEl) {
            var startTime = new Date(@json($promo->date_debut_pub)).getTime();
            (function countDown() {
                var left = Math.floor((startTime - Date.now()) / 1000);
                if (left <= 0) { location.reload(); return; }
                timerDebutEl.textContent = formatCompte(left);
                setTimeout(countDown, 1000);
            })();
        }
    @endif
})();
</script>
@endif

@endsection
