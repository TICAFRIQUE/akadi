# Configuration Wave Payment - Guide de Dépannage

## Problèmes courants et solutions

### 1. "Informations de livraison manquantes"

**Cause**: La session `delivery_info` est vide lors du clic sur "Payer".

**Solutions**:
1. Vérifiez que vous avez bien rempli toutes les informations dans la page de checkout
2. Vérifiez que `storeOrder()` est appelé avant d'aller à la page de sélection de paiement
3. Vérifiez les logs: `storage/logs/laravel.log` pour voir les détails

**Debug**:
```bash
# Voir les derniers logs
php artisan tail

# Ou sur Windows
Get-Content storage/logs/laravel.log -Tail 50
```

### 2. "Erreur lors de la création de la session de paiement Wave"

**Cause**: L'API Wave rejette la requête. Raisons possibles:
- URLs de callback invalides (localhost n'est pas accessible par Wave)
- API Key incorrecte
- Format de données invalide

**Solutions pour développement local**:

#### Option A: Utiliser ngrok (Recommandé)
```bash
# Installer ngrok
# Puis lancer:
ngrok http 8000

# Dans .env, remplacer:
WAVE_SUCCESS_URL=https://your-ngrok-url.ngrok.io/payment/wave/success
WAVE_ERROR_URL=https://your-ngrok-url.ngrok.io/payment/wave/error
WAVE_WEBHOOK_URL=https://your-ngrok-url.ngrok.io/webhook/wave

# Puis redémarrer
php artisan config:clear
php artisan cache:clear
```

#### Option B: Utiliser un domaine de test
Configurez un domaine de test accessible publiquement

#### Option C: Mode développement (paiement en espèces uniquement)
Désactivez temporairement Wave et utilisez seulement le paiement en espèces

### 3. Vérifier la configuration Wave

```bash
# Vérifier la config actuelle
php artisan tinker
>>> config('services.wave')

# Doit retourner:
# - api_key: Votre clé API Wave
# - api_url: https://api.wave.com/v1/checkout/sessions
# - success_url: URL publique accessible
# - error_url: URL publique accessible
```

### 4. Tester la configuration

```bash
# Créer une commande de test en espèces (ne nécessite pas Wave)
# Puis vérifier les logs pour voir si les données sont bien stockées
```

### 5. Logs détaillés

Les logs suivants sont maintenant disponibles:
- `Processing Wave Payment` - Début du processus
- `Wave Payment - Missing Delivery Info` - Session vide
- `Wave Payment API Error` - Erreur de l'API Wave
- `Wave Transaction Created` - Transaction créée avec succès

## Configuration Production

Dans `.env` en production:
```env
WAVE_API_KEY=wave_ci_prod_xxxxx
WAVE_API_URL=https://api.wave.com/v1/checkout/sessions
WAVE_SUCCESS_URL=https://votre-domaine.com/payment/wave/success
WAVE_ERROR_URL=https://votre-domaine.com/payment/wave/error
WAVE_WEBHOOK_URL=https://votre-domaine.com/webhook/wave
```

## Flux de paiement Wave

1. Utilisateur remplit le panier
2. `storeOrder()` → stocke en session: cart + delivery_info
3. Redirection vers `payment.select`
4. Utilisateur choisit Wave
5. `processWavePayment()` → crée WaveTransaction en DB
6. Redirection vers Wave
7. Utilisateur paie
8. Wave envoie webhook → crée Order en DB
9. Utilisateur revient → voit sa commande

## Support

Si le problème persiste:
1. Vérifiez `storage/logs/laravel.log`
2. Vérifiez la table `wave_transactions` en DB
3. Vérifiez que la table existe: `php artisan migrate`
4. Contactez le support Wave si l'API retourne des erreurs
