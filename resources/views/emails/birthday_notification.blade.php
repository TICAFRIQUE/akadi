<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Anniversaire Client</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f85d05 0%, #ff7b2d 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px 25px;
            text-align: center;
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .message {
            font-size: 18px;
            color: #333;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .highlight {
            color: #f85d05;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #f85d05 0%, #ff7b2d 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 10px;
            transition: transform 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f8f9fa;
            color: #666;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎂 Notification Anniversaire</h1>
        </div>
        
        <div class="content">
            <div class="icon">🎉</div>
            
            <div class="message">
                Bonjour <span class="highlight">AKADI</span>,<br><br>
                
                <!-- ICI on utilise la variable $message -->
                <strong>{{ $message }}</strong><br><br>
                
                Veuillez consulter les notifications sur le dashboard.
            </div>
            
            <a href="{{ url('/dashboard') }}" class="btn">
                📊 Voir le Dashboard
            </a>
        </div>
        
        <div class="footer">
            <p><strong>AKADI Restaurant</strong></p>
            <p>Notification automatique du système</p>
        </div>
    </div>
</body>
</html>