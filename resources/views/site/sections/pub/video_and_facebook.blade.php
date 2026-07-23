<style>
/* ── Video section ── */
.ak-video-section {
    padding: 32px 0;
    background: #1c0a00;
    position: relative;
    overflow: hidden;
    border-top: 3px solid rgba(235,0,41,.25);
    border-bottom: 3px solid rgba(235,0,41,.25);
}
.ak-video-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 70% 40%, rgba(235,0,41,.18) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%,  "rgba(248,93,5,$($args[0].Groups[1].Value))"  0%, transparent 50%);
    pointer-events: none;
}
.ak-video-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--ak-orange, #f85d05);
    margin-bottom: 12px;
}
.ak-video-label::before, .ak-video-label::after {
    content: '';
    display: block;
    width: 24px;
    height: 2px;
    background: var(--ak-orange, #f85d05);
    border-radius: 2px;
}
.ak-video-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: 8px;
    line-height: 1.25;
}
.ak-video-title .accent { color: var(--ak-red, #eb0029); }
.ak-video-subtitle { font-size: .9rem; color: rgba(255,255,255,.55); margin-bottom: 36px; }

.ak-video-frame {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0,0,0,.5);
    aspect-ratio: 16/7;
    background: #000;
}
.ak-video-frame iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.ak-video-cta {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-top: 28px;
    padding: 12px 26px;
    background: var(--ak-red, #eb0029);
    color: #fff;
    font-size: .85rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
}
.ak-video-cta:hover { background: #c4001f; color: #fff; text-decoration: none; transform: translateY(-2px); }
.ak-video-cta i { font-size: .8rem; }
</style>

<section class="ak-video-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="ak-video-label mx-0">Notre cuisine</div>
                <h2 class="ak-video-title">
                    Regardez-nous<br>cuisiner <span class="accent">avec passion</span>
                </h2>
                <p class="ak-video-subtitle">
                    Découvrez les coulisses d'Akadi — de la préparation à la livraison, tout est fait avec soin.
                </p>
                <a href="https://www.youtube.com/@akadi2113" target="_blank" class="ak-video-cta">
                    <i class="fab fa-youtube"></i> Voir notre chaîne
                </a>
            </div>
            <div class="col-lg-8">
                <div class="ak-video-frame">
                    <iframe src="https://www.youtube.com/embed/ULSIGpRSHhM"
                        title="Akadi Restaurant"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
