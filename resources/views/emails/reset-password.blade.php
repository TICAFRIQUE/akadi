{{-- <div style="font-family:Arial,sans-serif;text-align:center;padding:20px;">
    <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" alt="Akadi" width="80" style="margin-bottom:16px">
    <h1 style="color:#220072;">Réinitialisation de mot de passe</h1>
    <p>Vous pouvez réinitialiser votre mot de passe à partir du lien ci-dessous.</p>
    <a href="{{ $url }}"
       style="display:inline-block;margin-top:16px;padding:10px 50px;background:#232323;color:#fff;text-decoration:none;border-radius:3px;">
        Cliquez pour réinitialiser
    </a>
    <p style="margin-top:20px;font-size:12px;color:#999;">
        Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.
    </p>
</div> --}}

<div style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background-color:#f5f5f5;padding:40px 20px;margin:0;">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:20px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.05),0 8px 10px -6px rgba(0,0,0,0.02);overflow:hidden;">
        
        <!-- bandeau supérieur décoratif (orange) -->
        <div style="height:6px;background:linear-gradient(90deg,#FF8C00,#FFA500);"></div>

        <!-- contenu principal -->
        <div style="padding:32px 28px 40px 28px;text-align:center;">
            
            <!-- logo -->
            <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" alt="Akadi" width="90" style="margin-bottom:24px;display:inline-block;">

            <!-- titre (orange foncé) -->
            <h1 style="color:#E65100;font-size:26px;font-weight:700;margin:0 0 12px 0;letter-spacing:-0.3px;">
                Réinitialisation du mot de passe
            </h1>

            <!-- sous-titre -->
            <p style="color:#4b5563;font-size:16px;line-height:1.5;margin-bottom:32px;border-left:3px solid #FFB347;padding-left:16px;text-align:left;">
                Nous avons reçu une demande de réinitialisation pour votre compte AKADI.
            </p>

            <!-- lien de réinitialisation (bouton orange) -->
            <a href="{{ $url }}"
               style="display:inline-block;margin:8px 0 24px 0;padding:14px 32px;background:#FF8C00;color:#ffffff;text-decoration:none;border-radius:60px;font-weight:600;font-size:16px;box-shadow:0 2px 5px rgba(255,140,0,0.3);transition:background 0.3s ease;border:none;">
                🔑 Réinitialiser mon mot de passe
            </a>

            <!-- note de sécurité (avec accents orange) -->
            <div style="background:#FFF8F0;border-radius:16px;padding:20px 16px;margin-top:24px;text-align:left;border-left:4px solid #FF8C00;">
                <p style="font-size:13px;color:#6b7280;margin:0 0 8px 0;">
                    ⚠️ Ce lien expire automatiquement dans <strong style="color:#E65100;">60 minutes</strong> pour votre sécurité.
                </p>
                <p style="font-size:13px;color:#6b7280;margin:0;">
                    Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email. Aucune action ne sera requise.
                </p>
            </div>
        </div>

        <!-- footer avec accents orange -->
        <div style="background:#FEF9F0;border-top:1px solid #FFE0B5;padding:20px 28px;text-align:center;">
            <p style="margin:0 0 6px 0;font-size:12px;color:#e67e22;">
                🧡 Akadi – Sécurité des comptes
            </p>
            <p style="margin:0;font-size:12px;color:#b9770e;">
                Besoin d'aide ? <a href="#" style="color:#FF8C00;text-decoration:none;font-weight:500;">Contacter le support</a>
            </p>
        </div>
    </div>
</div>