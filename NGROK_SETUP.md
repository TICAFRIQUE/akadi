# Configuration Ngrok pour Wave Payment (Localhost)

## Pourquoi Ngrok ?
Wave API **exige des URLs HTTPS** publiquement accessibles. Sur localhost HTTP, Wave rejette toutes les demandes.

## Installation et Configuration

### 1. Installer Ngrok
```bash
# Télécharger depuis https://ngrok.com/download
# Ou avec chocolatey :
choco install ngrok

# S'inscrire sur https://ngrok.com pour obtenir un authtoken
ngrok config add-authtoken VOTRE_TOKEN
```

### 2. Lancer Ngrok
```bash
# Dans un terminal séparé
ngrok http 8000
```

Vous obtiendrez une URL comme : `https://abc123.ngrok.io`

### 3. Mettre à jour .env
```env
# NE PAS changer APP_URL (gardez localhost pour l'interface)
APP_URL=http://localhost:8000

# Mais configurez les URLs Wave avec votre URL ngrok :
WAVE_SUCCESS_URL=https://abc123.ngrok.io/payment/wave/success
WAVE_ERROR_URL=https://abc123.ngrok.io/payment/wave/error
WAVE_WEBHOOK_URL=https://abc123.ngrok.io/webhook/wave
```

### 4. Redémarrer l'application
```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

### 5. Tester le paiement
- Ouvrez `http://localhost:8000` dans votre navigateur
- Ajoutez des produits au panier
- Remplissez les informations de livraison
- Sélectionnez Wave comme moyen de paiement
- Vous serez redirigé vers Wave avec l'URL ngrok
- Après paiement, Wave appellera le webhook via ngrok
- La commande sera créée automatiquement

## Vérification rapide

### Tester si ngrok fonctionne
```bash
# Dans un autre terminal, testez l'accès au webhook
curl https://abc123.ngrok.io/webhook/wave
```

### Voir les logs Wave en temps réel
```bash
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

### Vérifier les transactions en base
```bash
php artisan tinker
>>> \App\Models\WaveTransaction::all()
```

## Alternative : Tester en production

Si vous ne voulez pas utiliser ngrok, déployez sur un serveur avec HTTPS :

```env
APP_URL=https://votre-domaine.com
WAVE_SUCCESS_URL=https://votre-domaine.com/payment/wave/success
WAVE_ERROR_URL=https://votre-domaine.com/payment/wave/error
WAVE_WEBHOOK_URL=https://votre-domaine.com/webhook/wave
```

## Dépannage

### Le webhook n'est pas appelé
- Vérifiez que ngrok est toujours actif
- Vérifiez les logs ngrok : `http://localhost:4040`
- Assurez-vous que `WAVE_WEBHOOK_URL` utilise l'URL ngrok

### La commande ne se crée pas
```bash
# Vérifiez les logs
Get-Content storage/logs/laravel.log -Tail 100 | Select-String "Wave"

# Vérifiez si la transaction existe
php artisan tinker
>>> \App\Models\WaveTransaction::latest()->first()
```

### Erreur "Transaction not found"
La transaction WaveTransaction n'a pas été créée lors de l'initialisation.
Vérifiez que `processWavePayment()` s'exécute sans erreur.

## Notes importantes

- **L'URL ngrok change à chaque redémarrage** (version gratuite)
- Mettez à jour `.env` chaque fois que vous relancez ngrok
- **NE JAMAIS commiter les URLs ngrok dans Git**
- En production, utilisez toujours votre vrai domaine HTTPS
