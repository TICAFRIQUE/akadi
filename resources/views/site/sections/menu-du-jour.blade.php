@if ($menuJour && $menuJour->menuProduits->count() > 0)
<style>
.ak-mdj-section { padding: 56px 0; background: #fff; }
.ak-mdj-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 28px;
}
.ak-mdj-title { font-size: 1.5rem; font-weight: 800; color: #1a1a1a; margin: 0; }
.ak-mdj-title span { color: var(--ak-red,#eb0029); }
.ak-mdj-cta {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .85rem;
    font-weight: 700;
    color: var(--ak-orange,#f85d05);
    text-decoration: none;
    padding: 8px 16px;
    border: 1.5px solid var(--ak-orange,#f85d05);
    border-radius: 8px;
    transition: all .2s;
}
.ak-mdj-cta:hover { background: var(--ak-orange,#f85d05); color: #fff; text-decoration: none; }
.ak-mdj-card {
    background: #fafafa;
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 14px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
}
.ak-mdj-card:hover { transform: translateY(-5px); box-shadow: 0 10px 32px rgba(0,0,0,.12); }
.ak-mdj-card-icon {
    aspect-ratio: 4/2.2;
    background: linear-gradient(135deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.8rem;
}
.ak-mdj-card-body { flex: 1; padding: 14px 16px; display: flex; flex-direction: column; gap: 4px; }
.ak-mdj-card-name { font-size: .92rem; font-weight: 700; color: #1a1a1a; }
.ak-mdj-card-price { font-size: 1rem; font-weight: 800; color: var(--ak-red,#eb0029); margin-top: auto; padding-top: 6px; }
.ak-mdj-card-footer { padding: 10px 16px; border-top: 1px solid #f0f0f0; }
.ak-mdj-card-add {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .8rem;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all .2s;
}
.ak-mdj-card-add:hover { background: #d44d00; }
</style>

<section class="ak-mdj-section">
    <div class="container">
        <div class="ak-mdj-header">
            <h2 class="ak-mdj-title"><span>Menu</span> du jour</h2>
            <a href="{{ route('menu-du-jour') }}" class="ak-mdj-cta">
                Voir tout le menu <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row gy-4">
            @foreach ($menuJour->menuProduits->take(4) as $plat)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="ak-mdj-card">
                        <div class="ak-mdj-card-icon"><i class="fas fa-utensils"></i></div>
                        <div class="ak-mdj-card-body">
                            <div class="ak-mdj-card-name">{{ $plat->nom }}</div>
                            <div class="ak-mdj-card-price">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="ak-mdj-card-footer">
                            <button class="ak-mdj-card-add addCartMenu" data-id="{{ $plat->id }}">
                                <i class="fa fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
