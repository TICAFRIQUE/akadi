# Intégration du paiement Wave

## Vue d'ensemble

Cette intégration permet aux clients de payer leurs commandes via Wave, en plus du paiement en espèces à la livraison. Les webhooks Wave sont implémentés pour confirmer automatiquement les paiements.

## Configuration

### 1. Variables d'environnement

Les variables suivantes ont été ajoutées au fichier `.env` :

```env
WAVE_API_KEY=wave_ci_prod_iN4D1TBk42ANhiprAZjCiK5CVbQrzeaAganp9n2ZqJEuymf1ADUyCvNjZrckat9AwJx3NE0Zbh1_tqsBvUfORr3Ceegk8jYrWg
WAVE_API_URL=https://api.wave.com/v1/checkout/sessions
WAVE_SUCCESS_URL="${APP_URL}/payment/wave/success"
WAVE_ERROR_URL="${APP_URL}/payment/wave/error"
WAVE_WEBHOOK_URL="${APP_URL}/webhook/wave"
```

### 2. Migration de la base de données

Exécutez la migration pour ajouter les champs Wave à la table orders :

```bash
php artisan migrate
```

Nouveaux champs ajoutés :
- `wave_session_id` : ID de session Wave
- `wave_payment_id` : ID de paiement Wave
- `payment_status` : Statut du paiement (pending, completed, failed, cancelled)
- `payment_completed_at` : Date de complétion du paiement

### 3. Configuration du webhook dans le dashboard Wave

**IMPORTANT** : Vous devez configurer l'URL du webhook dans votre dashboard Wave :

1. Connectez-vous à votre compte Wave Business : https://business.wave.com
2. Allez dans **Paramètres** > **Développeurs** > **Webhooks**
3. Ajoutez l'URL du webhook : `https://votre-domaine.com/webhook/wave`
4. Sélectionnez les événements à surveiller :
   - `checkout.session.completed` (paiement réussi)
   - `checkout.session.failed` (paiement échoué)
   - `checkout.session.cancelled` (paiement annulé)

⚠️ **Note** : L'URL du webhook doit être accessible publiquement. En développement local, utilisez ngrok ou un service similaire.

### 3. Base de données

Exécutez la migration pour ajouter les champs Wave à la table orders :

```bash
php artisan migrate
```

Les champs suivants sont ajoutés :
- `wave_session_id` : ID de la session de paiement Wave
- `wave_payment_id` : ID du paiement Wave
- `payment_status` : Statut du paiement (pending, completed, failed, cancelled)
- `payment_completed_at` : Date de complétion du paiement

Ensuite, initialisez les méthodes de paiement :

```bash
php artisan db:seed --class=PaymentMethodSeeder
```

### 4. Configuration du webhook Wave

**IMPORTANT** : Pour que Wave puisse notifier votre application du statut des paiements, vous devez configurer l'URL du webhook dans votre compte Wave.

#### En développement (local)

1. Utilisez un tunnel comme **ngrok** pour exposer votre application locale :
   ```bash
   ngrok http 80
   ```

2. Notez l'URL fournie (ex: `https://abc123.ngrok.io`)

3. Mettez à jour votre `.env` :
   ```env
   APP_URL=https://abc123.ngrok.io
   WAVE_WEBHOOK_URL="${APP_URL}/webhook/wave"
   ```

4. Dans le dashboard Wave, configurez l'URL du webhook :
   ```
   https://abc123.ngrok.io/webhook/wave
   ```

#### En production

1. Assurez-vous que `APP_URL` dans `.env` correspond à votre domaine :
   ```env
   APP_URL=https://votre-domaine.com
   ```

2. L'URL du webhook sera automatiquement :
   ```
   https://votre-domaine.com/webhook/wave
   ```

3. Configurez cette URL dans votre compte Wave

#### Vérification du webhook

Wave enverra des notifications POST à votre webhook avec le format :
```json
{
  "id": "session_id",
  "status": "complete|failed|cancelled",
  "wave_payment_id": "payment_id",
  ...
}
```

Les webhooks sont loggés dans `storage/logs/laravel.log` pour faciliter le débogage.

## Architecture

### Services

**`App\Services\WavePaymentService`**
- Gère toutes les interactions avec l'API Wave
- Méthodes principales :
  - `createCheckoutSession($amount, $currency, $orderId)` : Crée une session de paiement
  - `checkPaymentStatus($sessionId)` : Vérifie le statut d'un paiement

### Contrôleurs

**`App\Http\Controllers\site\PaymentController`**
- Gère le processus de paiement côté client
- Routes principales :
  - `GET /paiement/selection` : Affiche les moyens de paiement
  - `POST /paiement/traiter` : Traite le paiement selon la méthode choisie
  - `GET /payment/wave/success` : Callback de succès Wave (redirection client)
  - `GET /payment/wave/error` : Callback d'erreur Wave (redirection client)
  - `POST /webhook/wave` : Webhook pour recevoir les notifications Wave (backend)
  - `GET /commande/succes/{orderId}` : Page de confirmation de commande

### Flux de paiement

1. **Page panier** → Client ajoute des produits au panier
2. **Page caisse** (`/finaliser-ma-commande`) → Client saisit les informations de livraison
3. **Sauvegarde des informations** → Les infos sont stockées en session
4. **Page de sélection de paiement** (`/paiement/selection`) → Client choisit le moyen de paiement
5. **Traitement du paiement** :
   - **Espèces** : La commande est créée directement
   - **Wave** : Redirection vers la page de paiement Wave
6. **Page de succès** → Confirmation de la commande

### Vues

- `resources/views/site/pages/payment-selection.blade.php` : Sélection du moyen de paiement
- `resources/views/site/pages/order-success.blade.php` : Page de confirmation de commande

## Webhooks Wave

### Fonctionnement

Les webhooks permettent à Wave de notifier votre application du statut d'un paiement de manière asynchrone. C'est la méthode recommandée pour confirmer les paiements.

### Endpoint webhook

**URL** : `POST /webhook/wave` avec `payment_status = 'pending'`
   - Une session de paiement Wave est initialisée via l'API
   - L'ID de session Wave est stocké dans la commande
   - Le client est redirigé vers la page de paiement Wave
   - Le client effectue le paiement sur Wave
   - **Wave envoie un webhook à notre serveur** pour confirmer le paiement
   - Le webhook met à jour le statut de la commande
   - Wave redirige le client vers l'URL de succès ou d'erreur
5. La page de succès affiche les détails de la commande

### Flux webhook

```
Client → Wave → Paiement effectué
                    ↓
Wave → Webhook → Serveur (confirmation automatique)
                    ↓
              Statut mis à jour
                    ↓
         Notifications envoyées
```'appeler)
- Tous les événements sont loggés dans `storage/logs/laravel.log`

### Traitement des événements

Le webhook traite les événements suivants :

1. **`complete`/`completed`** :
   - Marque le paiement comme complété
   - Change le statut de la commande en "Confirmée"
   - Enregistre la date de complétion
   - Déclenche l'envoi de notifications (email, WhatsApp)

2. **`failed`** :
   - Marque le paiement comme échoué
   - Annule la commande
   - Enregistre la raison d'annulation

3. **`cancelled`** :
   - Marque le paiement comme annulé
   - Annule la commande

### Payload exemple

```json
{
  "id": "checkout_session_xxxxx",
  "status": "completed",
  "wave_payment_id": "payment_xxxxx",
  "amount": 5000,
  "currency": "XOF"
}
```

### Logs et debugging

Tous les webhooks reçus sont loggés avec :
- Headers complets de la requête
- Payload complet
- Actions effectuées
- Erreurs éventuelles

Consultez `storage/logs/laravel.log` pour le debugging.

## Utilisation

### Fonctionnement du paiement Wave

1. Le client clique sur "Confirmer la commande" dans la page caisse
2. Les informations de livraison sont enregistrées en session
3. Le client est redirigé vers la page de sélection de paiement
4. Si Wave est sélectionné : avec `payment_status = 'pending'`
   - L'ID de session Wave est stocké dans `wave_session_id`
   - Une session de paiement Wave est initialisée via l'API
   - Le client est redirigé vers la page de paiement Wave
   - Le client effectue le paiement sur Wave
   - **Wave envoie une notification au webhook** avec le statut du paiement
   - Le webhook met à jour la commande (`payment_status = 'completed'`)
   - Wave redirige le client vers l'URL de succès ou d'erreur
5. La page de succès affiche les détails de la commande

### Différence entre callback et webhook

- **Callback (success/error URL)** : Redirection du navigateur du client après paiement
- **Webhook** : Notification serveur-à-serveur envoyée par Wave de manière asynchrone
- ⚠️ **Important** : Le webhook est la source de vérité pour confirmer le paiement, pas le callbackcès ou d'erreur
5. La page de succès affiche les détails de la commande

### Gestion des coupons

Les coupons de réduction sont automatiquement appliqués et enregistrés lors de la création de la commande, que le paiement soit en espèces ou via Wave.

##Réception des webhooks Wave (avec payload complet)
- Mise à jour du statut des commandes
- Erreurs de paiement
- Vérification du statut de paiement

Consultez `storage/logs/laravel.log` pour plus de détails.

### Exemple de log webhook :

```
[2026-03-11 11:30:00] local.INFO: Wave Webhook Received {"headers":{...},"payload":{"id":"session_123","status":"complete",...}}
[2026-03-11 11:30:01] local.INFO: Wave Payment Completed {"order_id":"1234567890","session_id":"session_123"}
```cation
- Les logs détaillés permettent de tracer les transactions

## Logs

### Test du webhook en local

1. **Installer ngrok** (si pas déjà fait) :
   ```bash
   # Télécharger depuis https://ngrok.com/download
   ngrok http 80
   ```

2. **Mettre à jour votre .env** avec l'URL ngrok :
   ```env
   APP_URL=https://abc123.ngrok.io
   ```

3. **Tester le webhook manuellement** avec curl :
   ```bash
   curl -X POST https://abc123.ngrok.io/webhook/wave \
     -H "Content-Type: application/json" \
     -d '{
       "id": "test_session_123",
       "status": "complete",
       "wave_payment_id": "payment_456"
     }'
   ``` (`storage/logs/laravel.log`)
2. Les variables d'environnement
3. La validité de la clé API Wave
4. Les URLs de callback et webhook sont accessibles publiquement
5. Le webhook est bien configuré dans le dashboard Wave
6. La route webhook est exclue de la protection CSRF

### Débogage courant

**Webhook non reçu** :
- Vérifiez que l'URL est publiquement accessible
- Consultez les logs Wave dans votre dashboard
- Testez manuellement avec curl

**Paiement réussi mais commande pas confirmée** :
- Vérifiez les logs pour voir si le webhook a été reçu
- Vérifiez que le `wave_session_id` est bien stocké dans la commande
- Testez le webhook manuellement

**Erreur CSRF sur le webhook** :
- Vérifiez que `webhook/wave` est dans `$except` dans `VerifyCsrfToken.php`
   tail -f storage/logs/laravel.log
   ```

### Test d'une commande complète

Pour tester l'intégration complète en mode développement :

1. Assurez-vous que ngrok est actif et que votre environnement local est accessible
2. Configurez l'URL du webhook dans votre compte Wave de test
3. Mettez à jour les URLs dans `.env` :
   ```env
   APP_URL=https://votre-url-ngrok.ngrok.io
   WAVE_SUCCESS_URL="${APP_URL}/payment/wave/success"
   WAVE_ERROR_URL="${APP_URL}/payment/wave/error"
   WAVE_WEBHOOK_URL="${APP_URL}/webhook/wave"
   ```
4. Videz le cache de configuration :
   ```bash
   php artisan config:clear
   ```
5. Passez une commande test avec Wave
6. Surveillez les logs pour voir les webhooks reçus
Pour tester l'intégration en mode développement :

1. Assurez-vous que votre environnement local est accessible (utilisez ngrok si nécessaire)
2. Mettez à jour les URLs de callback dans `.env` :
   ```env
   WAVE_SUCCESS_URL="https://votre-domaine-test.ngrok.io/payment/wave/success"
   WAVE_ERROR_URL="https://votre-domaine-test.ngrok.io/payment/wave/error"
   ```
3. Testez une commande complète avec Wave

## Support

Pour tout problème lié à l'intégration Wave, vérifiez :
1. Les logs de l'application
2. Les variables d'environnement
3. La validité de la clé API Wave
4. Les URLs de callback sont accessibles publiquement

## Notes importantes

⚠️ **Important** :
- Les URLs de callback (success_url et error_url) doivent être accessibles publiquement pour que Wave puisse rediriger les clients
- En production, assurez-vous que `APP_URL` dans `.env` correspond à votre domaine réel
- La clé API Wave fournie est une clé de production. Gérez-la avec soin et ne la partagez pas publiquement
