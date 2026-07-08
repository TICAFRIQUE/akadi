<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\StockService;
use App\Services\TicAfriqueService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Envoyer un SMS au client via l'API TIC Afrique un fois la commande confirmée
     */
    public function sendSms($order)
    {
        $smsService = new TicAfriqueService();
        $smsService->sendOrderConfirmationSms($order);
    }
    //get all order 
    // public function getAllOrder(Request $request)
    // {
    //     // Par défaut : mois en cours
    //     $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
    //     $dateFin   = $request->input('date_fin',   now()->endOfMonth()->format('Y-m-d'));
    //     $status    = $request->input('status');
    //     $source    = $request->input('source');

    //     // Restriction selon permission (p-cuisine, p-livraison, p-confirmation)
    //     $allowedStatuses = orderStatusesAllowed();

    //     // Si tableau vide → aucun statut autorisé, retour accès refusé
    //     if (is_array($allowedStatuses) && count($allowedStatuses) === 0) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     $orders = Order::with(['user', 'paymentMethod', 'caisse', 'createdBy'])
    //         ->orderBy('created_at', 'DESC')
    //         ->when($allowedStatuses !== null, fn($q) => $q->whereIn('status', $allowedStatuses))
    //         ->when(request('d'), fn($q) => $q->where('date_order', Carbon::now()->format('Y-m-d')))
    //         ->when(request('s'), fn($q) => $q->whereStatus(request('s')))
    //         ->when($source, fn($q) => $q->where('source', $source))
    //         ->when(
    //             !request('d') && !request('s'),
    //             function ($q) use ($dateDebut, $dateFin, $status) {
    //                 $q->whereBetween('date_order', [$dateDebut, $dateFin]);
    //                 if ($status && $status !== 'all') {
    //                     $q->whereStatus($status);
    //                 }
    //             }
    //         )
    //         ->get();

    //     // Limiter le sélecteur de statuts aux statuts autorisés
    //     $statuts = $allowedStatuses !== null
    //         ? array_intersect_key(Order::$statuts, array_flip($allowedStatuses))
    //         : Order::$statuts;
    //     $sources = Order::$sources;

    //     return view('admin.pages.order.order', compact('orders', 'dateDebut', 'dateFin', 'statuts', 'sources'));
    // }

    // public function getAllOrder(Request $request)
    // {
    //     $status   = $request->input('status');
    //     $source   = $request->input('source');
    //     $allDates = $request->boolean('all_dates');

    //     // Dates seulement si pas "toutes les dates"
    //     $dateDebut = null;
    //     $dateFin   = null;

    //     if (!$allDates) {
    //         $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
    //         $dateFin   = $request->input('date_fin',   now()->endOfMonth()->format('Y-m-d'));
    //     }

    //     // Restriction selon permission (p-cuisine, p-livraison, p-confirmation)
    //     $allowedStatuses = orderStatusesAllowed();

    //     if (is_array($allowedStatuses) && count($allowedStatuses) === 0) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     $orders = Order::with(['user', 'paymentMethod', 'caisse', 'createdBy'])
    //         ->orderBy('created_at', 'DESC')
    //         ->when($allowedStatuses !== null, fn($q) => $q->whereIn('status', $allowedStatuses))
    //         ->when(request('d'), fn($q) => $q->where('date_order', Carbon::now()->format('Y-m-d')))
    //         ->when(request('s'), fn($q) => $q->whereStatus(request('s')))
    //         ->when($source, fn($q) => $q->where('source', $source))
    //         ->when(
    //             !request('d') && !request('s'),
    //             function ($q) use ($allDates, $dateDebut, $dateFin, $status) {
    //                 if (!$allDates) {
    //                     $q->whereBetween('date_order', [$dateDebut, $dateFin]);
    //                 }
    //                 if ($status && $status !== 'all') {
    //                     $q->whereStatus($status);
    //                 }
    //             }
    //         )
    //         ->get();

    //     // Limiter le sélecteur de statuts aux statuts autorisés
    //     $statuts = $allowedStatuses !== null
    //         ? array_intersect_key(Order::$statuts, array_flip($allowedStatuses))
    //         : Order::$statuts;
    //     $sources = Order::$sources;

    //     return view('admin.pages.order.order', compact('orders', 'dateDebut', 'dateFin', 'statuts', 'sources', 'allDates'));
    // }


    // liste des commandes avec stats et filtre ajax datatables
    // public function getAllOrder(Request $request)
    // {
    //     $status   = $request->input('status');
    //     $source   = $request->input('source');
    //     $allDates = $request->boolean('all_dates');

    //     $dateDebut = null;
    //     $dateFin   = null;

    //     if (!$allDates) {
    //         $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
    //         $dateFin   = $request->input('date_fin',   now()->endOfMonth()->format('Y-m-d'));
    //     }

    //     $allowedStatuses = orderStatusesAllowed();

    //     if (is_array($allowedStatuses) && count($allowedStatuses) === 0) {
    //         abort(403, 'Accès non autorisé.');
    //     }

    //     // ✅ Query de base réutilisable (sans get())
    //     $baseQuery = Order::with(['user', 'paymentMethod', 'caisse', 'createdBy'])
    //         ->when($allowedStatuses !== null, fn($q) => $q->whereIn('status', $allowedStatuses))
    //         ->when($source, fn($q) => $q->where('source', $source))
    //         ->when(!$allDates, fn($q) => $q->whereBetween('date_order', [$dateDebut, $dateFin]));

    //     // ✅ Stats : une seule requête groupée, pas de get()
    //     $statsRaw = (clone $baseQuery)
    //         ->selectRaw('status, COUNT(*) as total, SUM(total) as montant, SUM(solde_restant) as solde')
    //         ->groupBy('status')
    //         ->get()
    //         ->keyBy('status');

    //     $countMap = [
    //         'attente'            => $statsRaw->get('attente')?->total ?? 0,
    //         'en_attente_acompte' => $statsRaw->get('en_attente_acompte')?->total ?? 0,
    //         'precommande'        => $statsRaw->get('precommande')?->total ?? 0,
    //         'confirmée'          => $statsRaw->get('confirmée')?->total ?? 0,
    //         'en_cuisine'         => $statsRaw->get('en_cuisine')?->total ?? 0,
    //         'cuisine_terminee'   => $statsRaw->get('cuisine_terminee')?->total ?? 0,
    //         'en_livraison'       => $statsRaw->get('en_livraison')?->total ?? 0,
    //         'livrée'             => $statsRaw->get('livrée')?->total ?? 0,
    //         'annulée'            => $statsRaw->get('annulée')?->total ?? 0,
    //     ];

    //     $stats = [
    //         'countAttente'        => $countMap['attente'],
    //         'countAttenteAcompte' => $countMap['en_attente_acompte'],
    //         'countPrecommande'    => $countMap['precommande'],
    //         'countConfirmee'      => $countMap['confirmée'],
    //         'countCuisine'        => $countMap['en_cuisine'],
    //         'countCuisineTm'      => $countMap['cuisine_terminee'],
    //         'countLivraison'      => $countMap['en_livraison'],
    //         'countLivree'         => $countMap['livrée'],
    //         'countAnnulee'        => $countMap['annulée'],
    //         'total'               => $statsRaw->sum('total'),
    //         'montantTotal'        => $statsRaw->sum('montant'),
    //         'montantSolde'        => $statsRaw->sum('solde'),
    //     ];

    //     // ✅ Réponse Ajax DataTables Server-Side
    //     if ($request->ajax() && $request->has('draw')) {
    //         $query = (clone $baseQuery)
    //             ->orderBy('created_at', 'DESC')
    //             ->when($status && $status !== 'all', fn($q) => $q->whereStatus($status));

    //         return DataTables::of($query)
    //             ->addColumn('status_badge', function ($item) {
    //                 $statusColors = [
    //                     'attente'            => 'warning',
    //                     'en_attente_acompte' => 'warning',
    //                     'confirmée'          => 'success',
    //                     'en_cuisine'         => 'info',
    //                     'cuisine_terminee'   => 'primary',
    //                     'en_livraison'       => 'secondary',
    //                     'livrée'             => 'success',
    //                     'annulée'            => 'danger',
    //                     'precommande'        => 'dark',
    //                 ];
    //                 $statusLabels = [
    //                     'attente'            => 'En attente',
    //                     'en_attente_acompte' => 'Attente acompte',
    //                     'confirmée'          => 'Confirmée',
    //                     'en_cuisine'         => 'En cuisine',
    //                     'cuisine_terminee'   => 'Cuisine terminée',
    //                     'en_livraison'       => 'En livraison',
    //                     'livrée'             => 'Livrée',
    //                     'annulée'            => 'Annulée',
    //                     'precommande'        => 'Précommande',
    //                 ];
    //                 $sc = $statusColors[$item->status] ?? 'secondary';
    //                 $sl = $statusLabels[$item->status] ?? $item->status;
    //                 return "<span class='badge badge-{$sc} text-white p-1 px-2' style='white-space:nowrap;font-size:.75rem'>{$sl}</span>";
    //             })
    //             ->addColumn('source_badge', function ($item) {
    //                 $srcIcon  = Order::$sources[$item->source]['icon']  ?? 'fa-question';
    //                 $srcLabel = Order::$sources[$item->source]['label'] ?? ($item->source ?? '—');
    //                 return "<span class='badge-source'><i class='fab {$srcIcon} mr-1'></i>{$srcLabel}</span>";
    //             })
    //             ->addColumn('total_fmt', fn($item) => number_format($item->total ?? 0, 0, '', ' ') . ' FCFA')
    //             ->addColumn('acompte_fmt', fn($item) => number_format($item->acompte ?? 0, 0, '', ' '))
    //             ->addColumn('solde_fmt', function ($item) {
    //                 $cls = ($item->solde_restant ?? 0) > 0 ? 'text-danger' : 'text-muted';
    //                 return "<span class='{$cls}'>" . number_format($item->solde_restant ?? 0, 0, '', ' ') . "</span>";
    //             })
    //             ->addColumn('date_fmt', fn($item) => Carbon::parse($item->created_at)->format('d/m/Y H:i'))
    //             ->addColumn('actions', function ($item) {
    //                 return '
    //                 <div class="dropdown">
    //                     <a href="#" data-toggle="dropdown" class="btn btn-sm btn-warning dropdown-toggle">Options</a>
    //                     <div class="dropdown-menu dropdown-menu-right">
    //                         <a href="' . route('order.show', $item->id) . '" class="dropdown-item has-icon">
    //                             <i class="fas fa-eye"></i> Détail
    //                         </a>
    //                         <a href="' . route('pos.edit', $item->id) . '" class="dropdown-item has-icon">
    //                             <i class="fas fa-edit"></i> Modifier
    //                         </a>
    //                     </div>
    //                 </div>
    //             ';
    //             })
    //             ->rawColumns(['status_badge', 'source_badge', 'solde_fmt', 'actions'])
    //             ->make(true);
    //     }

    //     $statuts = $allowedStatuses !== null
    //         ? array_intersect_key(Order::$statuts, array_flip($allowedStatuses))
    //         : Order::$statuts;
    //     $sources = Order::$sources;

    //     return view('admin.pages.order.order', compact(
    //         'stats',
    //         'countMap',
    //         'dateDebut',
    //         'dateFin',
    //         'statuts',
    //         'sources',
    //         'allDates'
    //     ));
    // }


    public function getAllOrder(Request $request)
    {
        $status    = $request->input('status');
        $source    = $request->input('source');
        $dateDebut = $request->input('date_debut');
        $dateFin   = $request->input('date_fin');
        $allDates  = $request->boolean('all_dates');

        $allowedStatuses = orderStatusesAllowed();

        if (is_array($allowedStatuses) && count($allowedStatuses) === 0) {
            abort(403, 'Accès non autorisé.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Permission filtre date — basé uniquement sur la permission, sans bypass de rôle
        $canFilter = $user->hasPermissionTo('ventes.filtre');
        if (!$canFilter) {
            $allDates  = true;
            $dateDebut = null;
            $dateFin   = null;
        }

        // Restriction de période glissante — ventes.periode.tout vérifié en premier (plus permissif)
        $periodFrom           = null;
        $hasPeriodRestriction = false;
        if (!$user->hasPermissionTo('ventes.periode.tout')) {
            if ($user->hasPermissionTo('ventes.periode.jour')) {
                $periodFrom           = now()->subHours(48);
                $hasPeriodRestriction = true;
            } elseif ($user->hasPermissionTo('ventes.periode.semaine')) {
                $periodFrom           = now()->subDays(7);
                $hasPeriodRestriction = true;
            } elseif ($user->hasPermissionTo('ventes.periode.mois')) {
                $periodFrom           = now()->subDays(30);
                $hasPeriodRestriction = true;
            }
        }
        // ventes.periode.tout ou aucune permission de période → aucune restriction

        // Par défaut : mois en cours (uniquement si pas de restriction glissante et pas "toutes dates")
        if (!$hasPeriodRestriction && !$allDates) {
            $dateDebut = $dateDebut ?: now()->startOfMonth()->format('Y-m-d');
            $dateFin   = $dateFin   ?: now()->endOfMonth()->format('Y-m-d');
        }

        // ✅ Query de base réutilisable
        $baseQuery = Order::with(['user', 'paymentMethod', 'caisse', 'createdBy'])
            ->when($allowedStatuses !== null, fn($q) => $q->whereIn('status', $allowedStatuses))

            // filtre statut
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))

            // filtre source
            ->when($source, fn($q) => $q->where('source', $source))

            // restriction période (permission) — prioritaire sur le filtre date
            ->when($hasPeriodRestriction, fn($q) => $q->where('created_at', '>=', $periodFrom))

            // filtre date manuel (uniquement si l'utilisateur a la permission et les deux dates sont fournies)
            ->when(!$hasPeriodRestriction && !$allDates && $dateDebut && $dateFin,
                fn($q) => $q->whereBetween('date_order', [$dateDebut, $dateFin]));

        // ✅ Stats via GROUP BY — pas de get()
        $statsRaw = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total, SUM(total) as montant, SUM(solde_restant) as solde')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $countMap = [
            'attente'            => (int) ($statsRaw->get('attente')?->total            ?? 0),
            'en_attente_acompte' => (int) ($statsRaw->get('en_attente_acompte')?->total ?? 0),
            'precommande'        => (int) ($statsRaw->get('precommande')?->total        ?? 0),
            'confirmée'          => (int) ($statsRaw->get('confirmée')?->total          ?? 0),
            'en_cuisine'         => (int) ($statsRaw->get('en_cuisine')?->total         ?? 0),
            'cuisine_terminee'   => (int) ($statsRaw->get('cuisine_terminee')?->total   ?? 0),
            'en_livraison'       => (int) ($statsRaw->get('en_livraison')?->total       ?? 0),
            'livrée'             => (int) ($statsRaw->get('livrée')?->total             ?? 0),
            'annulée'            => (int) ($statsRaw->get('annulée')?->total            ?? 0),
        ];

        // ✅ Montants SANS les commandes annulées
        $statsNonAnnulee = (clone $baseQuery)
            ->where('status', '!=', 'annulée')
            ->selectRaw('SUM(total) as montant_total, SUM(solde_restant) as montant_solde')
            ->first();

        // ✅ Montant REVENU = uniquement commandes LIVRÉES
        $revenueStats = (clone $baseQuery)
            ->where('status', 'livrée')
            ->selectRaw('SUM(total) as montant_revenu')
            ->first();

        $stats = [
            'countAttente'        => $countMap['attente'],
            'countAttenteAcompte' => $countMap['en_attente_acompte'],
            'countPrecommande'    => $countMap['precommande'],
            'countConfirmee'      => $countMap['confirmée'],
            'countCuisine'        => $countMap['en_cuisine'],
            'countCuisineTm'      => $countMap['cuisine_terminee'],
            'countLivraison'      => $countMap['en_livraison'],
            'countLivree'         => $countMap['livrée'],
            'countAnnulee'        => $countMap['annulée'],
            'total'               => $statsRaw->sum('total'), //total de toutes les commandes (tous statuts confondus)
            'montantTotal'        => (float) ($statsNonAnnulee?->montant_total ?? 0), //SANS les annulées
            'montantSolde'        => (float) ($statsNonAnnulee?->montant_solde ?? 0), //SANS les annulées
            'montantRevenu'       => (float) ($revenueStats?->montant_revenu ?? 0), //UNIQUEMENT livrées
        ];

        // ✅ Réponse Ajax DataTables Server-Side
        if ($request->ajax() && $request->has('draw')) {
            $query = (clone $baseQuery)
                ->orderBy('created_at', 'DESC')
                ->when($status && $status !== 'all', fn($q) => $q->whereStatus($status));
            // $query = (clone $baseQuery)
            //     ->orderBy('created_at', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn() //  ajoute DT_RowIndex
                ->addColumn('status_badge', function ($item) {
                    $statusColors = [
                        'attente'            => 'warning',
                        'en_attente_acompte' => 'warning',
                        'confirmée'          => 'success',
                        'en_cuisine'         => 'info',
                        'cuisine_terminee'   => 'primary',
                        'en_livraison'       => 'secondary',
                        'livrée'             => 'success',
                        'annulée'            => 'danger',
                        'precommande'        => 'dark',
                    ];
                    $statusLabels = [
                        'attente'            => 'En attente',
                        'en_attente_acompte' => 'Attente acompte',
                        'confirmée'          => 'Confirmée',
                        'en_cuisine'         => 'En cuisine',
                        'cuisine_terminee'   => 'Cuisine terminée',
                        'en_livraison'       => 'En livraison',
                        'livrée'             => 'Livrée',
                        'annulée'            => 'Annulée',
                        'precommande'        => 'Précommande',
                    ];
                    $sc = $statusColors[$item->status] ?? 'secondary';
                    $sl = $statusLabels[$item->status] ?? $item->status;
                    return "<span class='badge badge-{$sc} text-white p-1 px-2' style='white-space:nowrap;font-size:.75rem'>{$sl}</span>";
                })
                ->addColumn('source_badge', function ($item) {
                    $srcIcon  = Order::$sources[$item->source]['icon']  ?? 'fa-question';
                    $srcLabel = Order::$sources[$item->source]['label'] ?? ($item->source ?? '—');
                    return "<span class='badge-source'><i class='fab {$srcIcon} mr-1'></i>{$srcLabel}</span>";
                })
                ->addColumn('nom_client',  fn($item) => $item->nom_client)
                ->addColumn('tel_client',  fn($item) => $item->tel_client)
                ->addColumn('total_fmt',   fn($item) => number_format($item->total ?? 0, 0, '', ' ') . ' FCFA')
                ->addColumn('acompte_fmt', fn($item) => number_format($item->acompte ?? 0, 0, '', ' '))
                ->addColumn('solde_fmt', function ($item) {
                    $cls = ($item->solde_restant ?? 0) > 0 ? 'text-danger' : 'text-muted';
                    return "<span class='{$cls}'>" . number_format($item->solde_restant ?? 0, 0, '', ' ') . "</span>";
                })
                ->addColumn('date_fmt', fn($item) => Carbon::parse($item->created_at)->format('d/m/Y H:i'))
                ->addColumn('actions', function ($item) {
                    return '
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-sm btn-warning dropdown-toggle">Options</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="' . route('order.show', $item->id) . '" class="dropdown-item has-icon">
                                <i class="fas fa-eye"></i> Détail
                            </a>
                            <a href="' . route('pos.edit', $item->id) . '" class="dropdown-item has-icon">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </div>
                    </div>
                ';
                })
                // ✅ Dire à Yajra sur quelles vraies colonnes SQL chercher
                ->filterColumn('nom_client', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('client_name', 'like', "%{$keyword}%")
                            ->orWhere('client_phone', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('tel_client', function ($query, $keyword) {
                    $query->where('client_phone', 'like', "%{$keyword}%");
                })
                ->filterColumn('code', function ($query, $keyword) {
                    $query->where('code', 'like', "%{$keyword}%");
                })
                ->rawColumns(['status_badge', 'source_badge', 'solde_fmt', 'actions'])
                ->make(true);
        }

        $statuts = $allowedStatuses !== null
            ? array_intersect_key(Order::$statuts, array_flip($allowedStatuses))
            : Order::$statuts;
        $sources = Order::$sources;

        return view('admin.pages.order.order', compact(
            'stats',
            'countMap',
            'dateDebut',
            'dateFin',
            'statuts',
            'sources',
            'allDates',
            'canFilter'
        ));
    }
    //filter
    public function filter(Request $request)
    {
        try {
            // dd($request->date_debut==null);
            $orders = Order::query()
                //when only request('status') 
                ->when($request->status)
                ->whereStatus($request->status)

                //when request('status') and request('date_debut')
                ->when($request->status && $request->date_debut)
                ->whereStatus($request->status)
                ->where('date_order', $request->date_debut)
                ->orderBy('created_at', 'DESC')->get();

            return view('admin.pages.order.order', compact(['orders']));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //show order     
    // detail of order user
    public function showOrder($id)
    {
        $orders = Order::whereId($id)
            ->with([
                'user',
                'paymentMethod',
                'caisse',
                'createdBy',
                'products' => fn($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->first();

        //si la commande n'existe pas
        if (!$orders) {
            return redirect()->back()->with('error', 'Commande introuvable.');
        }

        $statuts        = Order::$statuts;
        $sources        = Order::$sources;
        $paymentMethods = PaymentMethod::actif()->get();

        return view('admin.pages.order.order_show', compact('orders', 'statuts', 'sources', 'paymentMethods'));
    }

    // public function invoice($id)
    // {
    //     //function si on verifie la disponibilite des articles
    //     // $orders= Order::whereId($id)
    //     // ->with(['user','products'
    //     //     =>fn($q)=>$q->with('media')->where('available', 'disponible')
    //     // ])
    //     // ->orderBy('created_at','DESC')->first();


    //     //function sans verification de disponiblite
    //     $orders = Order::whereId($id)
    //         ->with([
    //             'user',
    //             'products'
    //             => fn($q) => $q->with('media')
    //         ])
    //         ->orderBy('created_at', 'DESC')->first();


    //     return PDF::loadView('admin.pages.order.invoicePdf', compact('orders'))
    //         // ->setPaper('a5', 'portrait')
    //         ->setPaper([0, 0, 203.9, 841.9], 'portrait') // 72mm de large
    //         ->setOption('margin_top', 0)
    //         ->setOption('margin_right', 0)
    //         ->setOption('margin_bottom', 0)
    //         ->setOption('margin_left', 0)
    //         ->setWarnings(true)
    //         ->save(public_path("storage/" . $orders['id'] . ".pdf"))
    //         ->stream(Str::slug($orders->code) . ".pdf");

    //     // return $pdf->download(Str::slug($orders->code) . ".pdf");


    //     // dd($orders->toArray());
    //     // return view('admin.pages.order.invoicePdf',compact('orders'));
    // }

    public function invoice($id)
    {
        $orders = Order::whereId($id)
            ->with(['user', 'products', 'paymentMethod'])
            ->orderBy('created_at', 'DESC')
            ->firstOrFail();

        return view('admin.pages.order.invoicePos', compact('orders'));
    }


    //changer le status de la commande
    public function changeState(Request $request)
    {
        $state   = request('cs'); // cs => change state
        $orderId = request('id');

        $allowedStatuses = array_keys(Order::$statuts);

        $order = Order::with('user')->whereId($orderId)->first();

        if (!$order) {
            return back()->with('error', 'Commande introuvable.');
        }

        // ── Contrôle des permissions de transition ────────────────────────────────
        if (!canChangeOrderStatus($state)) {
            return back()->with('error', 'Vous n\'avez pas la permission d\'effectuer ce changement de statut.');
        }

        // ── Règles métier sur l'acompte ──────────────────────────────────────────
        $acompteOptionnel = in_array($state, [
            Order::STATUS_ATTENTE,
            Order::STATUS_PRECOMMANDE,
            Order::STATUS_ATTENTE_ACOMPTE,
            Order::STATUS_ANNULEE,
        ]);
        // Ancienne logique : acompte obligatoire pour tous les statuts sauf ceux où l'acompte est optionnel
        // if (!$acompteOptionnel) {
        //     if ($state === Order::STATUS_LIVREE) {
        //         if (round((float) $order->acompte, 2) !== round((float) $order->total, 2)) {
        //             return back()->with(
        //                 'error',
        //                 "Pour passer en « Livrée », l'acompte (" . number_format($order->acompte, 0, ',', ' ') . " FCFA) doit être égal au total (" . number_format($order->total, 0, ',', ' ') . " FCFA)."
        //             );
        //         }
        //     } elseif ((float) $order->acompte <= 0) {
        //         return back()->with(
        //             'error',
        //             "Un acompte est obligatoire avant de passer au statut « {$state} »."
        //         );
        //     }
        // }

        // SANS ACOMPTE OBLIGATOIRE, MAIS ACOMPTE DOIT ÊTRE ÉGAL AU TOTAL POUR PASSER EN LIVRÉE
        if (!$acompteOptionnel && $state === Order::STATUS_LIVREE) {
            if (round((float) $order->acompte, 2) !== round((float) $order->total, 2)) {
                return back()->with(
                    'error',
                    "Pour passer en « Livrée », l'acompte (" . number_format($order->acompte, 0, ',', ' ') . " FCFA) doit être égal au total (" . number_format($order->total, 0, ',', ' ') . " FCFA)."
                );
            }
        }

        Order::whereId($orderId)->update(['status' => $state, 'created_by' => Auth::id()]);

        // Envoyer SMS seulement si confirmée
        if ($state === Order::STATUS_CONFIRMEE) {
            $this->sendSms($order);
        }

        if ($state === Order::STATUS_LIVREE) {
            Order::whereId($orderId)->update(['delivery_date' => Carbon::now()]);
        }

        if ($state === 'all') {
            Order::whereStatus(Order::STATUS_ATTENTE)->update(['status' => Order::STATUS_CONFIRMEE]);
        }

        // Envoyer email pour les changements de statut importants
        $notifyStatuses = [Order::STATUS_CONFIRMEE, Order::STATUS_ANNULEE, Order::STATUS_LIVREE, Order::STATUS_EN_CUISINE, Order::STATUS_CUISINE_TERMINEE];
        if (!empty($order->user?->email) && in_array($state, $notifyStatuses)) {
            $subject = match ($state) {
                Order::STATUS_CONFIRMEE        => 'Confirmation de commande',
                Order::STATUS_ANNULEE          => 'Annulation de commande',
                Order::STATUS_LIVREE           => 'Commande livrée',
                Order::STATUS_EN_CUISINE       => 'Commande en préparation',
                Order::STATUS_CUISINE_TERMINEE => 'Commande prête',
                default                        => 'Mise à jour de votre commande'
            };

            $emailData = [
                'imagePath'  => asset('site/assets/img/custom/AKADI.png'),
                'clientName' => $order->user->name,
                'orderCode'  => $order->code,
                'status'     => $state,
                'raison'     => $order->raison_annulation_cmd ?? null
            ];

            SendEmailJob::dispatch(
                $order->user->email,
                $subject,
                'emails.order-status',
                $emailData,
                'info@akadi.ci',
                'Akadi'
            );
        }

        return back()->withSuccess('Statut changé avec succès');
    }


    // public function orderCancel(Request $request)  // cancel order with reason
    // {
    //     $motif = "";
    //     if ($request['motif'] == 'autre') {
    //         $motif = $request['motif_autre'];
    //     } else {
    //         $motif = $request['motif'];
    //     }
    //     Order::whereId($request['commandeId'])->update([
    //         'status' => 'annulée',
    //         'raison_annulation_cmd' => $motif
    //     ]);

    //     // Remettre à jour le stock du product_base pour chaque produit de la commande
    //     $order = Order::with('products.productBase', 'user')->whereId($request['commandeId'])->first();
    //     // appelle de la fonction service pour incrementer le stock de chaque product_base lié à la commande annulée
    //     if ($order) {
    //         $this->stockService->reincrementStockOnCancellation($order);
    //     }

    //     // if ($order) {
    //     //     foreach ($order->products as $product) {
    //     //         if ($product->product_base_id && $product->productBase) {
    //     //             $coefficient = $product->coefficient > 0 ? $product->coefficient : 1;
    //     //             $quantite = $product->pivot->quantity;
    //     //             $product->productBase->incrementerStock($quantite * $coefficient);
    //     //         }
    //     //     }
    //     // }

    //     //envoyer email d'annulation via queue
    //     if (!empty($order->user->email)) {
    //         SendEmailJob::dispatch(
    //             $order->user->email,
    //             'Annulation de commande',
    //             'emails.order-status',
    //             [
    //                 'imagePath' => asset('site/assets/img/custom/AKADI.png'),
    //                 'clientName' => $order->user->name,
    //                 'orderCode' => $order->code,
    //                 'status' => 'annulée',
    //                 'raison' => $motif
    //             ],
    //             'info@akadi.ci',
    //             'Akadi'
    //         );
    //     }

    //     return back()->withSuccess('Commande annulée avec success');
    // }
    public function orderCancel(Request $request)
    {
        $motif = $request['motif'] == 'autre'
            ? $request['motif_autre']
            : $request['motif'];

        Order::whereId($request['commandeId'])->update([
            'status'                 => 'annulée',
            'raison_annulation_cmd'  => $motif
        ]);

        // Charger la commande avec user pour l'email
        $order = Order::with('user')->whereId($request['commandeId'])->first();

        if ($order) {
            $this->stockService->reincrementStockOnCancellation($order);
        }

        if (!empty($order->user->email)) {
            SendEmailJob::dispatch(
                $order->user->email,
                'Annulation de commande',
                'emails.order-status',
                [
                    'imagePath'  => asset('site/assets/img/custom/AKADI.png'),
                    'clientName' => $order->user->name,
                    'orderCode'  => $order->code,
                    'status'     => 'annulée',
                    'raison'     => $motif
                ],
                'info@akadi.ci',
                'Akadi'
            );
        }

        return back()->withSuccess('Commande annulée avec succès');
    }

    public function checkNewOrder(Request $request)
    {
        // Ne retourner que les commandes postérieures au dernier ID connu côté client
        $sinceId = (int) $request->input('since_id', 0);

        $orders_new = Order::with('user')
            // ->whereIn('status', [Order::STATUS_ATTENTE, Order::STATUS_PRECOMMANDE])
            ->where(function ($query) {
                // Commandes en attente normale : toujours affichées
                $query->where('status', 'attente')
                    // Précommandes : uniquement si la date commande est aujourd'hui ou passée
                    ->orWhere(function ($q) {
                        $q->where('status', 'precommande')
                            ->whereDate('date_order', '<=', now()->format('Y-m-d'));
                    });
            })
            ->where('source', 'web')
            ->where('payment_status', 'completed')
            ->when($sinceId > 0, fn($q) => $q->where('id', '>', $sinceId))
            ->orderBy('id', 'DESC')
            ->limit(20)
            ->get()
            ->map(fn($order) => [
                'id'           => $order->id,
                'code'         => $order->code,
                'status'       => $order->status,
                'status_label' => Order::$statuts[$order->status]['label'] ?? $order->status,
                'status_color' => Order::$statuts[$order->status]['color'] ?? 'secondary',
                'source'       => $order->source,
                'source_label' => Order::$sources[$order->source]['label'] ?? $order->source,
                'source_icon'  => Order::$sources[$order->source]['icon'] ?? 'fa-question',
                'nom_client'   => $order->nom_client,
                'tel_client'   => $order->tel_client,
                'total'        => (float) $order->total,
                'acompte'      => (float) $order->acompte,
                'solde_restant' => (float) $order->solde_restant,
                'created_at'   => $order->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'status' => 'success',
            'orders' => $orders_new,
            'count'  => $orders_new->count(),
        ]);
    }
}
