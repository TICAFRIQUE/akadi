<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande - {{ $order->code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f85d05;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 120px;
            height: auto;
        }
        .header h1 {
            color: #f85d05;
            margin: 10px 0 0 0;
            font-size: 24px;
        }
        .alert {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-info h3 {
            color: #333;
            margin-top: 0;
            border-bottom: 2px solid #f85d05;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th,
        .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .products-table th {
            background-color: #f85d05;
            color: white;
            font-weight: bold;
        }
        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-section {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #f85d05;
            border-top: 2px solid #f85d05;
            padding-top: 10px;
            margin-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-attente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-precommande {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #f85d05;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
        }
        .customer-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header avec logo -->
        <div class="header">
            <img src="{{ $data['imagePath'] }}" alt="AKADI Logo">
            <h1>Nouvelle Commande Reçue</h1>
        </div>

        <!-- Alerte de nouvelle commande -->
        <div class="alert">
            🎉 Une nouvelle commande vient d'être passée sur votre site !
        </div>

        <!-- Informations de la commande -->
        <div class="order-info">
            <h3>📋 Détails de la Commande</h3>
            <div class="info-row">
                <span class="info-label">Numéro de commande :</span>
                <span class="info-value"><strong>{{ $order->code }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de commande :</span>
                <span class="info-value">{{ date('d/m/Y à H:i', strtotime($order->created_at)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status :</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Type de commande :</span>
                <span class="info-value">{{ $order->type_order == 'cmd_precommande' ? 'Pré-commande' : 'Commande normale' }}</span>
            </div>
            @if($order->delivery_planned)
            <div class="info-row">
                <span class="info-label">Livraison prévue :</span>
                <span class="info-value">{{ $order->delivery_planned }}</span>
            </div>
            @endif
        </div>

        <!-- Informations client -->
        <div class="customer-info">
            <h3>👤 Informations Client</h3>
            <div class="info-row">
                <span class="info-label">Nom :</span>
                <span class="info-value">{{ $order->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">{{ $order->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">{{ $order->user->phone }}</span>
            </div>
        </div>

        <!-- Informations de livraison -->
        <div class="order-info">
            <h3>🚚 Informations de Livraison</h3>
            <div class="info-row">
                <span class="info-label">Zone de livraison :</span>
                <span class="info-value">{{ $order->delivery_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresse :</span>
                <span class="info-value">{{ $order->address }}</span>
            </div>
            @if($order->address_yango)
            <div class="info-row">
                <span class="info-label">Adresse Yango :</span>
                <span class="info-value">{{ $order->address_yango }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Mode de livraison :</span>
                <span class="info-value">{{ $order->mode_livraison }}</span>
            </div>
            @if($order->note)
            <div class="info-row">
                <span class="info-label">Note :</span>
                <span class="info-value">{{ $order->note }}</span>
            </div>
            @endif
        </div>

        <!-- Produits commandés -->
        <div>
            <h3>🛍️ Produits Commandés</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{{ number_format($product->pivot->unit_price, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ number_format($product->pivot->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Récapitulatif financier -->
        <div class="total-section">
            <h3>💰 Récapitulatif Financier</h3>
            <div class="total-row">
                <span>Sous-total :</span>
                <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row">
                <span>Remise :</span>
                <span>-{{ number_format($order->discount, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="total-row">
                <span>Frais de livraison :</span>
                <span>{{ number_format($order->delivery_price, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="total-row total-final">
                <span>TOTAL :</span>
                <span>{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="total-row">
                <span>Mode de paiement :</span>
                <span>{{ $order->payment_method ?? 'Paiement à la livraison' }}</span>
            </div>
        </div>

        <!-- Bouton d'action -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('order.show', $order->id) }}" class="btn">
                Voir la commande dans l'admin
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>AKADI Restaurant</strong></p>
            <p>Email: info@akadi.ci | Téléphone: 0758838338</p>
            {{-- <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p> --}}
        </div>
    </div>
</body>
</html>