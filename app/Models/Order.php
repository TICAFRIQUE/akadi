<?php

namespace App\Models;

use App\Observers\AchatObserver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;

    // ─── Statuts ────────────────────────────────────────────────────────────────
    const STATUS_ATTENTE           = 'attente';
    const STATUS_PRECOMMANDE       = 'precommande';
    const STATUS_ATTENTE_ACOMPTE   = 'en_attente_acompte';
    const STATUS_CONFIRMEE         = 'confirmée';
    const STATUS_EN_CUISINE        = 'en_cuisine';
    const STATUS_CUISINE_TERMINEE  = 'cuisine_terminee';
    const STATUS_LIVRAISON         = 'en_livraison';
    const STATUS_LIVREE            = 'livrée';
    const STATUS_ANNULEE           = 'annulée';

    // ─── Sources ─────────────────────────────────────────────────────────────────
    const SOURCE_WEB        = 'web';
    const SOURCE_BACKOFFICE = 'backoffice';
    const SOURCE_WHATSAPP   = 'whatsapp';
    const SOURCE_APPEL      = 'appel';
    const SOURCE_AUTRE      = 'autre';

    public static array $statuts = [
        self::STATUS_ATTENTE          => ['label' => 'En attente',          'color' => 'warning'],
        self::STATUS_PRECOMMANDE       => ['label' => 'Précommande',          'color' => 'purple'],
        self::STATUS_ATTENTE_ACOMPTE  => ['label' => 'Attente acompte',     'color' => 'orange'],
        self::STATUS_CONFIRMEE        => ['label' => 'Confirmée',           'color' => 'success'],
        self::STATUS_EN_CUISINE       => ['label' => 'En cuisine',          'color' => 'info'],
        self::STATUS_CUISINE_TERMINEE => ['label' => 'Cuisine terminée',    'color' => 'primary'],
        self::STATUS_LIVRAISON        => ['label' => 'En livraison',        'color' => 'secondary'],
        self::STATUS_LIVREE           => ['label' => 'Livrée',              'color' => 'success'],
        self::STATUS_ANNULEE          => ['label' => 'Annulée',             'color' => 'danger'],
    ];

    public static array $sources = [
        self::SOURCE_WEB        => ['label' => 'Site Web',   'icon' => 'fa-globe',        'color' => 'primary'],
        self::SOURCE_BACKOFFICE => ['label' => 'Backoffice', 'icon' => 'fa-desktop',      'color' => 'secondary'],
        self::SOURCE_WHATSAPP   => ['label' => 'WhatsApp',   'icon' => 'fa-whatsapp',     'color' => 'success'],
        self::SOURCE_APPEL      => ['label' => 'Appel',      'icon' => 'fa-phone',        'color' => 'info'],
        self::SOURCE_AUTRE      => ['label' => 'Autre',      'icon' => 'fa-question',     'color' => 'warning'],
    ];

    protected $fillable = [
        'code',
        'quantity_product',
        'subtotal',
        'delivery_price',
        'delivery_name',
        'address',
        'address_yango',
        'discount',
        'type_discount',       // percent ou fixed
        'total',
        'delivery_planned',
        'delivery_date',
        'status', //[attente, confirmée, en_cuisine, cuisine_terminee, en_livraison, livrée, annulée]
        'raison_annulation_cmd',
        'payment method',        // ancien champ conservé
        'payment_method_id',     // nouveau FK
        'wave_session_id',       // ID de session Wave
        'wave_payment_id',       // ID de paiement Wave
        'payment_status',        // pending, completed, failed, cancelled
        'payment_completed_at',  // Date de complétion du paiement
        'mode_livraison', //[livraison, retrait_sur_place, domicile]
        'available_product',
        'user_id',
        'date_order',
        'note',
        'type_order',
        'source',                // web, backoffice, whatsapp, appel, autre
        'acompte',               // montant versé d'avance
        'solde_restant',         // total - acompte
        'caisse_id',             // caisse utilisée
        'created_by',            // agent qui a créé
        'client_phone',          // client sans compte
        'client_name',           // client sans compte
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id   = IdGenerator::generate(['table' => 'orders', 'length' => 10, 'prefix' => mt_rand()]);
            $model->code = IdGenerator::generate(['table' => 'orders', 'field' => 'code', 'length' => 10, 'prefix' => mt_rand()]);
        });

        // Recalcul automatique du solde restant
        self::saving(function ($model) {
            $model->solde_restant = max(0, (float)$model->total - (float)$model->acompte);
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Agent backoffice qui a créé la commande */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'unit_price', 'discount', 'type_discount', 'prix_apres_remise', 'total', 'options', 'available'])
            ->withTimestamps();
    }

    // ─── Accessors ───────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::$statuts[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statuts[$this->status]['color'] ?? 'secondary';
    }

    public function getSourceLabelAttribute(): string
    {
        return self::$sources[$this->source]['label'] ?? ucfirst($this->source ?? '');
    }

    public function getSourceIconAttribute(): string
    {
        return self::$sources[$this->source]['icon'] ?? 'fa-question';
    }

    /** Nom du client : priorité à l'utilisateur lié, sinon client_name */
    public function getNomClientAttribute(): string
    {
        return $this->user?->name ?? $this->client_name ?? 'Client anonyme';
    }

    /** Téléphone du client */
    public function getTelClientAttribute(): string
    {
        return $this->user?->phone ?? $this->client_phone ?? '';
    }





}
