<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Caisse;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    /**
     * Interface POS – formulaire de création de commande backoffice
     */
    public function create()
    {
        if (!canChangeOrderStatus(\App\Models\Order::STATUS_CONFIRMEE)) {
            abort(403, 'Vous devez avoir la permission p-confirmation pour créer une commande.');
        }

        // if (!session('caisse_id')) {
        //     return redirect()->route('caisse.selection')->with('error', 'Veuillez d\'abord sélectionner une caisse.');
        // }

        $products       = Product::with('media')->where('disponibilite', '!=', 'indisponible')->orWhereNull('disponibilite')->orderBy('title')->get();
        $paymentMethods = PaymentMethod::actif()->get();
        $deliveries     = Delivery::orderBy('zone')->get();
        $sources        = Order::$sources;
        $statuts        = Order::$statuts;
        $caisse_id      = session('caisse_id');
        $caisse         = $caisse_id ? Caisse::find($caisse_id) : null;

        return view('admin.pages.pos.create', compact(
            'products',
            'paymentMethods',
            'deliveries',
            'sources',
            'statuts',
            'caisse'
        ));
    }

    /**
     * Recherche d'un client par téléphone ou nom (AJAX)
     */
    public function searchClient(Request $request)
    {
        $q = $request->input('q', '');
        $users = User::where('phone', 'like', "%$q%")
            ->orWhere('name', 'like', "%$q%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email']);

        return response()->json($users);
    }

    /**
     * Enregistrer une commande backoffice
     */
    public function store(Request $request)
    {
        // if (!session('caisse_id')) {
        //     return redirect()->route('caisse.selection')->with('error', 'Veuillez d\'abord sélectionner une caisse.');
        // }

        $status           = $request->input('status', Order::STATUS_ATTENTE);
        $acompteOptionnel = in_array($status, [
            Order::STATUS_ATTENTE,
            Order::STATUS_PRECOMMANDE,
            Order::STATUS_ATTENTE_ACOMPTE,
            Order::STATUS_ANNULEE,
        ]);
        $isLivree = $status === Order::STATUS_LIVREE;

        // ── Règles de base (toujours) ────────────────────────────────────────────
        $rules = [
            'products'                      => 'required|array|min:1',
            'products.*.product_id'         => 'required|exists:products,id',
            'products.*.quantity'           => 'required|integer|min:1',
            'products.*.unit_price'         => 'required|numeric|min:0',
            'products.*.discount'           => 'nullable|numeric|min:0',
            'products.*.type_discount'      => 'nullable|in:percent,fixed',
            'type_discount'                 => 'nullable|in:percent,fixed',
            'source'                        => 'required|in:web,backoffice,whatsapp,appel,autre',
            'status'                        => 'required|string',
        ];

        // ── Téléphone client toujours obligatoire ────────────────────────────────
        if (!$request->filled('user_id')) {
            $rules['client_phone'] = 'required|string|min:8';
        }

        // ── Acompte selon le statut ───────────────────────────────────────────────
        if ($acompteOptionnel) {
            // precommande / attente / attente_acompte / annulée → acompte facultatif
            $rules['acompte'] = 'nullable|numeric|min:0';
        } elseif ($isLivree) {
            // livrée → acompte obligatoire (égalité au total vérifiée après calcul)
            $rules['acompte']           = 'required|numeric|min:0';
            $rules['payment_method_id'] = 'required|exists:payment_methods,id';
        } else {
            // confirmé / en_cuisine / cuisine_terminée / en_livraison → acompte > 0
            $rules['acompte']           = 'required|numeric|min:1';
            $rules['payment_method_id'] = 'required|exists:payment_methods,id';
        }

        $messages = [
            'client_phone.required'      => 'Le téléphone du client est obligatoire.',
            'client_phone.min'           => 'Le numéro de téléphone est invalide.',
            'acompte.required'           => 'L\'acompte est obligatoire pour le statut « ' . $status . ' ».',
            'acompte.min'                => 'L\'acompte doit être supérieur à 0 pour le statut « ' . $status . ' ».',
            'payment_method_id.required' => 'Le moyen de paiement est obligatoire pour ce statut.',
            'products.required'          => 'Le panier est vide.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // ── Client ────────────────────────────────────────────────────────────
            $userId      = null;
            $clientName  = null;
            $clientPhone = null;

            if ($request->filled('user_id')) {
                $userId = $request->user_id;
            } elseif ($request->filled('client_phone')) {
                $user = User::firstOrCreate(
                    ['phone' => $request->client_phone],
                    [
                        'name'     => $request->client_name ?? 'Client anonyme',
                        'password' => Hash::make('password'),
                        'role'     => 'client',
                    ]
                );
                $userId = $user->id;

                //assignment du role client si pas déjà assigné
                if (!$user->hasRole('client')) {
                    $user->assignRole('client');
                }
            } else {
                $clientName  = $request->client_name;
                $clientPhone = $request->client_phone;
            }

            // ── Calcul des totaux ────────────────────────────────────────────────
            $subtotal         = 0;
            $quantityProduct  = 0;
            $productsData     = [];

            foreach ($request->products as $item) {
                $product      = Product::findOrFail($item['product_id']);
                $qty          = (int) $item['quantity'];
                $unitPrice    = (float) $item['unit_price'];
                $discountVal  = (float) ($item['discount'] ?? 0);
                $typeDiscount = $item['type_discount'] ?? 'percent';
                if ($typeDiscount === 'percent') {
                    $prixApres = $unitPrice * (1 - min($discountVal, 100) / 100);
                } else {
                    $prixApres = $unitPrice - min($discountVal, $unitPrice);
                }
                $prixApres    = max(0, $prixApres);
                $lineTotal    = $prixApres * $qty;

                if ($product->stock !== null && $qty > $product->stock) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Stock insuffisant pour « {$product->title} » (stock: {$product->stock})")
                        ->withInput();
                }

                $subtotal        += $lineTotal;
                $quantityProduct += $qty;

                $productsData[] = [
                    'product'           => $product,
                    'quantity'          => $qty,
                    'unit_price'        => $unitPrice,
                    'discount'          => $discountVal,
                    'type_discount'     => $typeDiscount,
                    'prix_apres_remise' => $prixApres,
                    'total'             => $lineTotal,
                    'options'           => $item['options'] ?? null,
                ];
            }

            $globalDiscountVal  = (float) ($request->discount ?? 0);
            $globalDiscountType = $request->input('type_discount', 'fixed');
            if ($globalDiscountType === 'percent') {
                $globalDiscountAmount = $subtotal * min($globalDiscountVal, 100) / 100;
            } else {
                $globalDiscountAmount = $globalDiscountVal;
            }
            $deliveryPrice  = (float) ($request->delivery_price ?? 0);
            $total          = max(0, $subtotal - $globalDiscountAmount + $deliveryPrice);
            $acompte        = (float) ($request->acompte ?? 0);
            $soldeRestant   = max(0, $total - $acompte);

            // Vérification spécifique : livrée → acompte doit être égal au total
            if ($isLivree && round($acompte, 2) !== round($total, 2)) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Pour une commande livrée, l'acompte (" . number_format($acompte, 0, ',', '\u{202F}') . " FCFA) doit être égal au total (" . number_format($total, 0, ',', '\u{202F}') . " FCFA).")
                    ->withInput();
            }

            // Status : conserver le statut choisi ; forcer attente_acompte seulement si
            // le statut n'est pas un statut "sans acompte obligatoire" et que l'acompte = 0
            $finalStatus = $status;
            if (!$acompteOptionnel && !$isLivree && $acompte <= 0) {
                $finalStatus = Order::STATUS_ATTENTE_ACOMPTE;
            }

            // ── Création de la commande ──────────────────────────────────────────
            $order = Order::create([
                'quantity_product'  => $quantityProduct,
                'subtotal'          => $subtotal,
                'delivery_price'    => $deliveryPrice,
                'delivery_name'     => $request->delivery_name,
                'address'           => $request->address,
                'mode_livraison'    => $request->mode_livraison ?? 'sur_place',
                'discount'          => $globalDiscountVal,
                'type_discount'     => $globalDiscountType,
                'total'             => $total,
                'acompte'           => $acompte,
                'solde_restant'     => $soldeRestant,
                'status'            => $finalStatus,
                'source'            => $request->source,
                'payment_method_id' => $request->payment_method_id,
                'caisse_id'         => session('caisse_id'),
                'user_id'           => $userId,
                'client_name'       => $clientName,
                'client_phone'      => $clientPhone,
                'created_by'        => Auth::id(),
                'note'              => $request->note,
                'type_order'        => $request->type_order ?? 'normal',
                'date_order'        => now()->format('Y-m-d'),
                'delivery_planned'  => $request->delivery_planned,
                'available_product' => 'yes',
            ]);

            // ── Pivot produits ───────────────────────────────────────────────────
            foreach ($productsData as $d) {
                $order->products()->attach($d['product']->id, [
                    'quantity'          => $d['quantity'],
                    'unit_price'        => $d['unit_price'],
                    'discount'          => $d['discount'],
                    'type_discount'     => $d['type_discount'],
                    'prix_apres_remise' => $d['prix_apres_remise'],
                    'total'             => $d['total'],
                    'options'           => $d['options'],
                    'available'         => 'yes',
                ]);

                // Décrémenter le stock si défini
                if ($d['product']->stock !== null) {
                    $d['product']->decrement('stock', $d['quantity']);
                }
            }

            DB::commit();

            return redirect()->route('order.show', $order->id)
                ->with('success', 'Commande créée avec succès. Code : ' . $order->code);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Modifier une commande (vue d'édition POS)
     */
    public function edit($id)
    {
        if (!canChangeOrderStatus(\App\Models\Order::STATUS_CONFIRMEE)) {
            abort(403, 'Vous devez avoir la permission p-confirmation pour modifier une commande.');
        }
        // if (!session('caisse_id')) {
        //     return redirect()->route('caisse.selection')->with('error', 'Veuillez d\'abord sélectionner une caisse.');
        // }

        $order          = Order::with(['products', 'user', 'paymentMethod', 'caisse'])->findOrFail($id);
        $products       = Product::with('media')->orderBy('title')->get();
        $paymentMethods = PaymentMethod::actif()->get();
        $deliveries     = Delivery::orderBy('zone')->get();
        $sources        = Order::$sources;
        $statuts        = Order::$statuts;

        return view('admin.pages.pos.edit', compact(
            'order',
            'products',
            'paymentMethods',
            'deliveries',
            'sources',
            'statuts'
        ));
    }

    /**
     * Mettre à jour une commande existante
     */
    public function update(Request $request, $id)
    {
        // if (!session('caisse_id')) {
        //     return redirect()->route('caisse.selection')->with('error', 'Veuillez d\'abord sélectionner une caisse.');
        // }

        $order = Order::findOrFail($id);

        // ── Validation statut / acompte ──────────────────────────────────────────
        $status           = $request->input('status', $order->status);
        $acompteOptionnel = in_array($status, [
            Order::STATUS_ATTENTE,
            Order::STATUS_PRECOMMANDE,
            Order::STATUS_ATTENTE_ACOMPTE,
            Order::STATUS_ANNULEE,
        ]);
        $isLivree = $status === Order::STATUS_LIVREE;

        $rules = [
            'products'                      => 'required|array|min:1',
            'products.*.product_id'         => 'required|exists:products,id',
            'products.*.quantity'           => 'required|integer|min:1',
            'products.*.unit_price'         => 'required|numeric|min:0',
            'products.*.discount'           => 'nullable|numeric|min:0',
            'products.*.type_discount'      => 'nullable|in:percent,fixed',
            'type_discount'                 => 'nullable|in:percent,fixed',
            'status'                        => 'required|string',
        ];

        if ($acompteOptionnel) {
            $rules['acompte'] = 'nullable|numeric|min:0';
        } elseif ($isLivree) {
            $rules['acompte']           = 'required|numeric|min:0';
            $rules['payment_method_id'] = 'required|exists:payment_methods,id';
        } else {
            $rules['acompte']           = 'required|numeric|min:1';
            $rules['payment_method_id'] = 'required|exists:payment_methods,id';
        }

        $request->validate($rules, [
            'acompte.required'           => "L'acompte est obligatoire pour le statut « {$status} ».",
            'acompte.min'                => "L'acompte doit être supérieur à 0 pour le statut « {$status} ».",
            'payment_method_id.required' => 'Le moyen de paiement est obligatoire pour ce statut.',
            'products.required'          => 'Le panier est vide.',
        ]);

        DB::beginTransaction();
        try {
            // ── Recalcul ─────────────────────────────────────────────────────────
            $subtotal        = 0;
            $quantityProduct = 0;
            $productsData    = [];

            foreach ($request->products as $item) {
                $product      = Product::findOrFail($item['product_id']);
                $qty          = (int) $item['quantity'];
                $unitPrice    = (float) $item['unit_price'];
                $discountVal  = (float) ($item['discount'] ?? 0);
                $typeDiscount = $item['type_discount'] ?? 'percent';
                if ($typeDiscount === 'percent') {
                    $prixApres = max(0, $unitPrice * (1 - min($discountVal, 100) / 100));
                } else {
                    $prixApres = max(0, $unitPrice - min($discountVal, $unitPrice));
                }
                $lineTotal    = $prixApres * $qty;

                // Vérifier stock (en tenant compte de l'ancienne quantité)
                $oldQty = $order->products->find($product->id)?->pivot->quantity ?? 0;
                $diff   = $qty - $oldQty;
                if ($product->stock !== null && $diff > 0 && $diff > $product->stock) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Stock insuffisant pour « {$product->title} »")
                        ->withInput();
                }

                $subtotal        += $lineTotal;
                $quantityProduct += $qty;
                $productsData[]   = compact('product', 'qty', 'unitPrice', 'discountVal', 'typeDiscount', 'prixApres', 'lineTotal', 'item', 'oldQty', 'diff');
            }

            $globalDiscountVal  = (float) ($request->discount ?? 0);
            $globalDiscountType = $request->input('type_discount', 'fixed');
            if ($globalDiscountType === 'percent') {
                $globalDiscountAmount = $subtotal * min($globalDiscountVal, 100) / 100;
            } else {
                $globalDiscountAmount = $globalDiscountVal;
            }
            $deliveryPrice  = (float) ($request->delivery_price ?? 0);
            $total          = max(0, $subtotal - $globalDiscountAmount + $deliveryPrice);
            $acompte        = (float) ($request->acompte ?? $order->acompte);
            $soldeRestant   = max(0, $total - $acompte);

            // Vérification spécifique : livrée → acompte doit être égal au total
            if ($isLivree && round($acompte, 2) !== round($total, 2)) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Pour une commande livrée, l'acompte (" . number_format($acompte, 0, ',', '\u{202F}') . " FCFA) doit être égal au total (" . number_format($total, 0, ',', '\u{202F}') . " FCFA).")
                    ->withInput();
            }

            $order->update([
                'quantity_product'  => $quantityProduct,
                'subtotal'          => $subtotal,
                'delivery_price'    => $deliveryPrice,
                'delivery_name'     => $request->delivery_name,
                'address'           => $request->address,
                'mode_livraison'    => $request->mode_livraison,
                'discount'          => $globalDiscountVal,
                'type_discount'     => $globalDiscountType,
                'total'             => $total,
                'acompte'           => $acompte,
                'solde_restant'     => $soldeRestant,
                'status'            => $status,
                'source'            => $request->source ?? $order->source,
                'payment_method_id' => $request->payment_method_id ?? $order->payment_method_id,
                'note'              => $request->note,
                'delivery_planned'  => $request->delivery_planned,
            ]);

            // ── Resync pivots ─────────────────────────────────────────────────────
            $syncData = [];
            foreach ($productsData as $d) {
                $syncData[$d['product']->id] = [
                    'quantity'          => $d['qty'],
                    'unit_price'        => $d['unitPrice'],
                    'discount'          => $d['discountVal'],
                    'type_discount'     => $d['typeDiscount'],
                    'prix_apres_remise' => $d['prixApres'],
                    'total'             => $d['lineTotal'],
                    'options'           => $d['item']['options'] ?? null,
                    'available'         => 'yes',
                ];

                // Ajuster le stock
                if ($d['product']->stock !== null && $d['diff'] !== 0) {
                    $d['product']->decrement('stock', $d['diff']);
                }
            }
            $order->products()->sync($syncData);

            DB::commit();

            return redirect()->route('order.show', $order->id)
                ->with('success', 'Commande mise à jour.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Enregistrer un acompte supplémentaire sur une commande (AJAX)
     */
    public function addAcompte(Request $request, $id)
    {
        $request->validate(['montant' => 'required|numeric|min:0.01']);
        $order = Order::findOrFail($id);

        $newAcompte = $order->acompte + (float) $request->montant;
        if ($newAcompte > $order->total) {
            return response()->json(['error' => 'L\'acompte ne peut pas dépasser le total.'], 422);
        }

        $order->update([
            'acompte'       => $newAcompte,
            'solde_restant' => max(0, $order->total - $newAcompte),
        ]);

        // Passer à confirmée si acompte suffisant et statut attente acompte
        if ($order->status === Order::STATUS_ATTENTE_ACOMPTE && $newAcompte > 0) {
            $order->update(['status' => Order::STATUS_CONFIRMEE]);
        }

        return response()->json([
            'success'       => true,
            'acompte'       => $newAcompte,
            'solde_restant' => $order->solde_restant,
            'status'        => $order->status,
        ]);
    }
}
