{{-- <div style="font-family:Arial,sans-serif;text-align:center;padding:20px;">
    <img src="{{ $logo }}" alt="Akadi" width="80" style="margin-bottom:16px">
    <h2>Bienvenue, {{ $userName }} !</h2>
    <p>Votre compte a été créé avec succès.</p>
    <p>Merci pour votre confiance.</p>
    <a href="https://akadi.ci"
       style="display:inline-block;margin-top:16px;padding:10px 24px;background:#e63946;color:#fff;text-decoration:none;border-radius:6px;">
        Visiter Akadi.ci
    </a>
</div> --}}


<div style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background-color:#f5f5f5;padding:40px 20px;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:20px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.05),0 8px 10px -6px rgba(0,0,0,0.02);overflow:hidden;">
        
        <!-- bandeau supérieur orange -->
        <div style="height:6px;background:linear-gradient(90deg,#FF8C00,#FFA500);"></div>

        <!-- contenu principal -->
        <div style="padding:32px 28px 40px 28px;text-align:center;">
            
            <!-- logo -->
            <img src="{{ $logo }}" alt="Akadi" width="90" style="margin-bottom:24px;display:inline-block;">

            <!-- icône de bienvenue -->
            <div style="font-size:48px;margin-bottom:12px;">🎉</div>

            <!-- titre personnalisé -->
            <h1 style="color:#E65100;font-size:26px;font-weight:700;margin:0 0 8px 0;letter-spacing:-0.3px;">
                Bonjour, {{ $userName }} !
            </h1>

            <!-- message principal -->
            <div style="background:#FFF8F0;border-radius:16px;padding:24px 20px;margin:24px 0;text-align:center;">
                <p style="color:#4b5563;font-size:16px;line-height:1.6;margin:0 0 16px 0;">
                    ✨ Votre compte a été créé avec succès ✨
                </p>
                <p style="color:#6b7280;font-size:15px;line-height:1.6;margin:0;">
                    Merci pour votre confiance. Nous sommes ravis de vous compter parmi nous !
                </p>
            </div>

            <!-- bouton de visite -->
            <a href="https://akadi.ci"
               style="display:inline-block;margin:8px 0 16px 0;padding:14px 32px;background:#FF8C00;color:#ffffff;text-decoration:none;border-radius:60px;font-weight:600;font-size:16px;box-shadow:0 2px 5px rgba(255,140,0,0.3);transition:background 0.3s ease;">
                🌍 Découvrir Akadi.ci
            </a>

            <!-- message bonus -->
            <p style="font-size:13px;color:#9ca3af;margin-top:24px;">
                Votre aventure commence ici 🧡
            </p>
        </div>

        <!-- footer -->
        <div style="background:#FEF9F0;border-top:1px solid #FFE0B5;padding:20px 28px;text-align:center;">
            <p style="margin:0 0 6px 0;font-size:12px;color:#e67e22;">
                🧡 Akadi – L'excellence à portée de clic
            </p>
            <p style="margin:0;font-size:12px;color:#b9770e;">
                Une question ? <a href="#" style="color:#FF8C00;text-decoration:none;font-weight:500;">Contactez-nous</a>
            </p>
        </div>
    </div>
</div>