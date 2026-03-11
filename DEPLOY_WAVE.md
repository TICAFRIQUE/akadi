# Déploiement Wave Payment sur le serveur

## Fichiers modifiés à uploader

1. **app/Http/Controllers/site/PaymentController.php** (✅ MODIFIÉ - webhook amélioré)

## Sur le serveur de production

### Étape 1 : Uploader les fichiers
Utilisez FTP, SFTP, ou Git pour transférer :
- `app/Http/Controllers/site/PaymentController.php`

### Étape 2 : Vérifier le .env (déjà fait ✅)
```bash
cat .env | grep WAVE
```

Vous devez voir :
```
WAVE_SUCCESS_URL=https://akadi.ci/payment/wave/success
WAVE_ERROR_URL=https://akadi.ci/payment/wave/error
WAVE_WEBHOOK_URL=https://akadi.ci/webhook/wave
```

### Étape 3 : Effacer les caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Étape 4 : Vérifier les permissions des logs
```bash
chmod -R 775 storage/logs/
chown -R www-data:www-data storage/logs/
```

### Étape 5 : Tester un paiement Wave
1. Allez sur https://akadi.ci
2. Ajoutez un produit au panier
3. Passez à la caisse
4. Sélectionnez Wave comme moyen de paiement
5. Effectuez le paiement sur Wave

### Étape 6 : Consulter les logs en temps réel
```bash
tail -f storage/logs/laravel.log | grep -A 10 "Wave Webhook"
```

Ou voir les derniers logs :
```bash
tail -100 storage/logs/laravel.log | grep -A 5 "Wave Webhook"
```

## Ce qui va se passer maintenant

### ✅ Améliorations apportées au webhook :

1. **Logging détaillé** : Le webhook logue maintenant TOUT ce que Wave envoie
2. **Formats multiples** : Accepte différentes structures de données Wave
3. **Pas d'erreur 400** : Retourne toujours 200 (même si données manquantes)
4. **Mise à jour correcte** : Met à jour `payment_status`, `acompte`, `solde_restant`

### 📊 Dans les logs, vous verrez :

```
[YYYY-MM-DD HH:MM:SS] local.INFO: Wave Webhook Received
{
  "method": "POST",
  "raw_body": "...",
  "parsed_payload": {...}
}

[YYYY-MM-DD HH:MM:SS] local.INFO: Wave Webhook Processing
{
  "session_id": "cos-xxxxx",
  "status": "completed"
}

[YYYY-MM-DD HH:MM:SS] local.INFO: Wave Payment Completed - Order Updated
{
  "order_id": 123456,
  "order_code": "ABC123",
  "session_id": "cos-xxxxx",
  "amount_paid": 5000
}
```

## Vérifier qu'une commande a été mise à jour

```bash
# Sur le serveur
mysql -u username -p database_name -e "SELECT id, code, payment_status, acompte, status FROM orders WHERE wave_session_id IS NOT NULL ORDER BY created_at DESC LIMIT 5;"
```

## En cas de problème

### Le webhook ne reçoit toujours rien
Vérifiez que l'URL est accessible :
```bash
curl -X POST https://akadi.ci/webhook/wave \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

Vous devriez voir : `{"status":"received","message":"Webhook received but could not process"}`

### La commande n'est pas mise à jour
Regardez les logs pour voir le format exact envoyé par Wave :
```bash
grep "Wave Webhook Received" storage/logs/laravel.log | tail -1
```

## Checklist finale

- [ ] PaymentController.php uploadé sur le serveur
- [ ] Caches effacés avec artisan
- [ ] Test de paiement effectué
- [ ] Logs consultés (tail -f storage/logs/laravel.log)
- [ ] Commande mise à jour avec payment_status = 'completed'

**Une fois ces étapes faites, testez un nouveau paiement et envoyez-moi les logs !**
