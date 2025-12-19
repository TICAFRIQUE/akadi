<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header avec logo -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center;">
                            <img src="{{ $imagePath }}" alt="Akadi logo" width="100" style="display: block; margin: 0 auto;">
                        </td>
                    </tr>
                    
                    <!-- Corps du message -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333333; font-size: 24px; margin: 0 0 20px 0; text-align: center;">
                                Bonjour {{ $clientName }} 👋
                            </h2>
                            
                            @if($status == 'confirmée')
                                <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                    <p style="margin: 0; color: #155724; font-size: 16px;">
                                        ✅ Votre commande <strong>#{{ $orderCode }}</strong> a été <strong>confirmée avec succès</strong> !
                                    </p>
                                </div>
                                <p style="color: #666666; font-size: 15px; line-height: 1.6; text-align: center;">
                                    Votre colis sera préparé avec soin et vous serez livré dans les plus brefs délais.
                                </p>
                            @elseif($status == 'annulée')
                                <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                    <p style="margin: 0; color: #721c24; font-size: 16px;">
                                        ❌ Votre commande <strong>#{{ $orderCode }}</strong> a été <strong>annulée</strong>.
                                    </p>
                                </div>
                                @if(isset($raison) && $raison)
                                    <div style="background-color: #fff3cd; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                        <p style="margin: 0; color: #856404; font-size: 14px;">
                                            <strong>Raison :</strong> {{ $raison }}
                                        </p>
                                    </div>
                                @endif
                            @elseif($status == 'livrée')
                                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                    <p style="margin: 0; color: #0c5460; font-size: 16px;">
                                        🎉 Votre commande <strong>#{{ $orderCode }}</strong> a été <strong>livrée avec succès</strong> !
                                    </p>
                                </div>
                                <p style="color: #666666; font-size: 15px; line-height: 1.6; text-align: center;">
                                    Nous espérons que vous êtes satisfait(e) de votre achat.<br>
                                    Merci d'avoir choisi <strong>AKADI</strong> ! 💜
                                </p>
                            @elseif($status == 'en cours')
                                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                    <p style="margin: 0; color: #856404; font-size: 16px;">
                                        ⏳ Votre commande <strong>#{{ $orderCode }}</strong> est <strong>en cours de traitement</strong>.
                                    </p>
                                </div>
                                <p style="color: #666666; font-size: 15px; line-height: 1.6; text-align: center;">
                                    Votre colis est en préparation. Vous serez notifié(e) dès sa livraison.
                                </p>
                            @else
                                <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                                    <p style="margin: 0; color: #004085; font-size: 16px;">
                                        📦 Le statut de votre commande <strong>#{{ $orderCode }}</strong> : <strong>{{ $status }}</strong>
                                    </p>
                                </div>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="color: #666666; font-size: 14px; margin: 0 0 10px 0;">
                                Une question ? Besoin d'aide ?
                            </p>
                            <p style="color: #666666; font-size: 14px; margin: 0 0 20px 0;">
                                📞 <strong>07 58 83 83 38</strong> | 📧 <strong>info@akadi.ci</strong>
                            </p>
                            <p style="color: #999999; font-size: 13px; margin: 0;">
                                Merci pour votre confiance 💚<br>
                                <a href="http://Akadi.ci" target="_blank" style="color: #667eea; text-decoration: none; font-weight: bold;">www.akadi.ci</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
