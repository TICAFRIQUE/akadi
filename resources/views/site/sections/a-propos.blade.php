<style>
/* ── About section ── */
.ak-about-section {
    padding: 80px 0;
    /* Gradient + pattern assiette/étoile — 0 requête HTTP */
    background-color: #fff;
    background-image:
        linear-gradient(135deg, rgba(255,255,255,0) 60%, rgba(255,245,245,.9) 100%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Ccircle cx='60' cy='60' r='18' fill='none' stroke='%23f85d05' stroke-width='1.5' opacity='.045'/%3E%3Ccircle cx='60' cy='60' r='10' fill='none' stroke='%23f85d05' stroke-width='1' opacity='.03'/%3E%3Ccircle cx='0' cy='0' r='12' fill='none' stroke='%23eb0029' stroke-width='1' opacity='.035'/%3E%3Ccircle cx='120' cy='120' r='12' fill='none' stroke='%23eb0029' stroke-width='1' opacity='.035'/%3E%3Ccircle cx='120' cy='0' r='8' fill='none' stroke='%23f85d05' stroke-width='1' opacity='.025'/%3E%3Ccircle cx='0' cy='120' r='8' fill='none' stroke='%23f85d05' stroke-width='1' opacity='.025'/%3E%3C/svg%3E");
    background-repeat: repeat;
    overflow: hidden;
    position: relative;
}
.ak-about-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--ak-red,#eb0029), var(--ak-orange,#f85d05));
}

/* ── Image bloc ── */
.ak-about-imgs {
    position: relative;
    padding: 20px 20px 20px 0;
}
.ak-about-img-main {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 16px 48px rgba(0,0,0,.14);
    position: relative;
}
.ak-about-img-main img { width: 100%; height: 420px; object-fit: cover; display: block; }
.ak-about-img-logo {
    position: absolute;
    bottom: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    padding: 10px;
}
.ak-about-img-logo img { width: 100%; height: auto; object-fit: contain; }

/* ── Accent badge ── */
.ak-about-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(235,0,41,.07);
    color: var(--ak-red, #eb0029);
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid rgba(235,0,41,.15);
    margin-bottom: 16px;
}
.ak-about-badge i { font-size: .7rem; }

/* ── Texte ── */
.ak-about-title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.25;
    margin-bottom: 20px;
}
.ak-about-title .accent { color: var(--ak-red, #eb0029); }
.ak-about-text {
    font-size: .92rem;
    color: #666;
    line-height: 1.85;
    margin-bottom: 28px;
}

/* ── Features ── */
.ak-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 32px;
}
.ak-feature-item {
    background: #fff;
    border: 1.5px solid #f0f0f0;
    border-radius: 12px;
    padding: 14px 12px;
    text-align: center;
    transition: border-color .2s;
}
.ak-feature-item:hover { border-color: var(--ak-red, #eb0029); }
.ak-feature-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--ak-red,#eb0029), var(--ak-orange,#f85d05));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-size: .9rem;
    color: #fff;
}
.ak-feature-label {
    font-size: .75rem;
    font-weight: 700;
    color: #333;
    line-height: 1.2;
}

/* ── CTA ── */
.ak-about-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 28px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
}
.ak-about-cta:hover { background: #d44d00; color: #fff; text-decoration: none; transform: translateY(-2px); }
.ak-about-cta i { font-size: .75rem; }

@media (max-width: 991px) {
    .ak-about-imgs { margin-bottom: 40px; }
    .ak-about-img-main img { height: 300px; }
    .ak-features { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 480px) {
    .ak-features { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .ak-feature-label { font-size: .68rem; }
}
</style>

<section class="ak-about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6">
                <div class="ak-about-imgs">
                    <div class="ak-about-img-main">
                        <img src="{{ asset('site/assets/img/custom/about-img.jpg') }}" alt="Akadi Restaurant">
                    </div>
                    <div class="ak-about-img-logo">
                        <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" alt="Logo Akadi">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="ak-about-badge">
                    <i class="fas fa-fire"></i> À propos de nous
                </div>
                <h2 class="ak-about-title">
                    Le goût du partage,<br>livré <span class="accent">chez vous</span>
                </h2>
                <p class="ak-about-text">
                    AKADI Restaurant est un restaurant en ligne spécialisé dans le poulet fumé braisé et les saveurs
                    locales revisitées. Notre mission est de créer des moments de plaisir à travers des repas savoureux,
                    préparés avec soin et passion — de la commande à la livraison.
                </p>

                <div class="ak-features">
                    <div class="ak-feature-item">
                        <div class="ak-feature-icon"><i class="fas fa-star"></i></div>
                        <div class="ak-feature-label">Qualité</div>
                    </div>
                    <div class="ak-feature-item">
                        <div class="ak-feature-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="ak-feature-label">Hygiène</div>
                    </div>
                    <div class="ak-feature-item">
                        <div class="ak-feature-icon"><i class="fas fa-motorcycle"></i></div>
                        <div class="ak-feature-label">Livraison</div>
                    </div>
                </div>

                <a href="{{ route('liste-produit') }}" class="ak-about-cta">
                    Découvrir nos plats <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
