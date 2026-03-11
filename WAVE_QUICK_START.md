# Test Wave Payment - Guide Rapide

## Vous êtes OÙ ?

### Option 1 : Sur un serveur de production avec HTTPS (ex: https://akadi.com)

1. **Connectez-vous au serveur** (SSH, FTP, etc.)
2. **Modifiez le `.env` sur le serveur** :
   ```env
   APP_URL=https://votre-domaine.com
   
   WAVE_SUCCESS_URL=https://votre-domaine.com/payment/wave/success
   WAVE_ERROR_URL=https://votre-domaine.com/payment/wave/error
   WAVE_WEBHOOK_URL=https://votre-domaine.com/webhook/wave
   ```

3. **Redémarrez l'application** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Testez le paiement** sur `https://votre-domaine.com`

---

### Option 2 : En local sur votre PC (http://localhost:8000)

**Wave exige HTTPS**, donc localhost ne fonctionne PAS directement.

**Solution : Utilisez ngrok**

1. **Téléchargez ngrok** : https://ngrok.com/download

2. **Lancez ngrok** (dans un terminal séparé) :
   ```bash
   ngrok http 8000
   ```

3. **Copiez l'URL HTTPS** générée (ex: `https://abc123.ngrok-free.app`)

4. **Modifiez `.env` sur votre PC** :
   ```env
   # Gardez localhost pour l'interface
   APP_URL=http://localhost:8000
   
   # Mais utilisez ngrok pour Wave
   WAVE_SUCCESS_URL=https://abc123.ngrok-free.app/payment/wave/success
   WAVE_ERROR_URL=https://abc123.ngrok-free.app/payment/wave/error
   WAVE_WEBHOOK_URL=https://abc123.ngrok-free.app/webhook/wave
   ```

5. **Redémarrez** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

6. **Testez** sur `http://localhost:8000` (l'interface)
   - Wave recevra les callbacks via l'URL ngrok HTTPS

---

## Vérifier la configuration actuelle

### Sur votre PC (local)
```bash
php artisan config:show services | Select-String "wave"
```

### Sur le serveur (SSH)
```bash
php artisan config:show services | grep wave
```

Vous devez voir des URLs **HTTPS** pour `success_url`, `error_url` et `webhook_url`.

---

## Dépannage

### Le webhook ne reçoit pas la confirmation
1. **Vérifiez les logs Wave** :
   ```bash
   Get-Content storage/logs/laravel.log -Tail 100 | Select-String "Wave"
   ```

2. **Vérifiez que le webhook est accessible** :
   - Sur ngrok : http://localhost:4040 (interface web ngrok)
   - Sur production : testez avec `curl https://votre-domaine.com/webhook/wave`

### La commande ne se crée pas
```bash
# Vérifier les dernières commandes
php artisan tinker --execute="var_dump(App\Models\Order::latest()->take(1)->get(['id', 'created_at', 'payment_status']));"
```

### Erreur "URL scheme not permitted"
= Vous utilisez encore HTTP. Passez en HTTPS (ngrok ou production).

---

## Quel est VOTRE cas ?

- [ ] **Serveur de production avec HTTPS** → Modifiez .env SUR LE SERVEUR
- [ ] **Local sur PC** → Utilisez ngrok

**Faites un choix et suivez les étapes ci-dessus !**
