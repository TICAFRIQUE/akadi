<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class ClientController extends Controller
{
    private array $typeClientOptions = ['prospect', 'fidele'];

    //liste des client avec search sans name et phone
    // public function listClient()
    // {
    //     $typeClient = request('type');

    //     $dateDebut = request('date_debut', !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
    //     $dateFin   = request('date_fin',   !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);

    //     $name = request('name');
    //     $phone = request('phone');

    //     if ($name) {
    //         $name = Str::of($name)->trim()->replaceMatches('/\s+/', ' '); // Nettoyer les espaces
    //     }

    //     if ($phone) {
    //         $phone = Str::of($phone)->trim()->replaceMatches('/\s+/', ' '); // Nettoyer les espaces
    //     }

    //     $users = User::withCount([
    //         'orders',
    //         'orders as orders_month_count' => fn($q) => $q
    //             ->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year),
    //     ])
    //         ->where('role', 'client')
    //         ->when($typeClient, fn($q) => $q->where('type_client', $typeClient))
    //         ->when($name, fn($q) => $q->where('name', 'like', "%{$name}%"))
    //         ->when($phone, fn($q) => $q->where('phone', 'like', "%{$phone}%"))
    //         ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
    //         ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     return view('admin.pages.client.clientList', compact('users', 'dateDebut', 'dateFin'));
    // }

    //liste des client avec search sans name et phone v1
    // public function listClient()
    // {
    //     $typeClient = request('type');
    //     $name  = request('name');
    //     $phone = request('phone');

    //     if ($name) {
    //         $name = Str::of($name)->trim()->replaceMatches('/\s+/', ' ');
    //     }

    //     if ($phone) {
    //         $phone = Str::of($phone)->trim()->replaceMatches('/\s+/', ' ');
    //     }

    //     // Si recherche par nom ou téléphone → pas de filtre date
    //     $searchActive = $name || $phone;

    //     $dateDebut = null;
    //     $dateFin   = null;

    //     if (!$searchActive) {
    //         $dateDebut = request('date_debut', !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
    //         $dateFin   = request('date_fin',   !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);
    //     }

    //     $users = User::withCount([
    //         'orders',
    //         'orders as orders_month_count' => fn($q) => $q
    //             ->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year),
    //     ])
    //         ->where('role', 'client')
    //         ->when($typeClient, fn($q) => $q->where('type_client', $typeClient))
    //         ->when($name,  fn($q) => $q->where('name',  'like', "%{$name}%"))
    //         ->when($phone, fn($q) => $q->where('phone', 'like', "%{$phone}%"))
    //         ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
    //         ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     return view('admin.pages.client.clientList', compact('users', 'dateDebut', 'dateFin'));
    // }


    //liste des client avec search sans name et phone v2
    // public function listClient()
    // {
    //     $typeClient = request('type');
    //     $allDates   = request()->boolean('all_dates');
    //     $name       = request('name');
    //     $phone      = request('phone');

    //     if ($name) {
    //         $name = Str::of($name)->trim()->replaceMatches('/\s+/', ' ');
    //     }

    //     if ($phone) {
    //         $phone = Str::of($phone)->trim()->replaceMatches('/\s+/', ' ');
    //     }

    //     $searchActive = $name || $phone;

    //     $dateDebut = null;
    //     $dateFin   = null;

    //     if (!$searchActive && !$allDates) {
    //         $dateDebut = request('date_debut', !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
    //         $dateFin   = request('date_fin',   !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);
    //     }

    //     $users = User::withCount([
    //         'orders',
    //         'orders as orders_month_count' => fn($q) => $q
    //             ->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year),
    //     ])
    //         ->where('role', 'client')
    //         ->when($typeClient, fn($q) => $q->where('type_client', $typeClient))
    //         ->when($name,      fn($q) => $q->where('name',  'like', "%{$name}%"))
    //         ->when($phone,     fn($q) => $q->where('phone', 'like', "%{$phone}%"))
    //         ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
    //         ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     return view('admin.pages.client.clientList', compact('users', 'dateDebut', 'dateFin', 'allDates'));
    // }



    public function listClient(Request $request)
    {
        $typeClient = request('type');
        $allDates   = request()->boolean('all_dates');

        $dateDebut = null;
        $dateFin   = null;

        if (!$allDates) {
            $dateDebut = request('date_debut', !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
            $dateFin   = request('date_fin',   !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);
        }

        // ✅ Query builder, pas get()
        $query = User::withCount([
            'orders',
            'orders as orders_month_count' => fn($q) => $q
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
        ])
            ->where('role', 'client')
            ->when($typeClient, fn($q) => $q->where('type_client', $typeClient))
            ->when($dateDebut,  fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
            ->when($dateFin,    fn($q) => $q->whereDate('created_at', '<=', $dateFin))
            ->orderBy('created_at', 'DESC');

        // Réponse Ajax pour DataTables
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($user) {
                    $color = $user->orders_count > 0 ? 'success' : 'primary';
                    $label = $user->orders_count > 0 ? 'A commandé' : 'Aucune commande';
                    return "<span class='badge badge-{$color}'>{$label}</span>";
                })
                ->addColumn('type_badge', function ($user) {
                    $color = match ($user->type_client) {
                        'fidele'   => 'success',
                        'prospect' => 'warning',
                        default    => 'secondary',
                    };
                    return "<span class='badge badge-{$color}'>" . ucfirst($user->type_client ?? 'prospect') . "</span>";
                })
                // ->addColumn('date_anniversaire_fmt', function ($user) {
                //     if (!$user->date_anniversaire) return '-';
                //     $date = \Carbon\Carbon::parse($user->date_anniversaire . '-' . date('Y'))->locale('fr_FR');
                //     return $date->day . ' ' . $date->monthName;
                // })
                ->addColumn('actions', function ($user) {
                    return '
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">Options</a>
                        <div class="dropdown-menu">
                            <a href="' . route('client.detail', $user->id) . '" class="dropdown-item has-icon">
                                <i class="far fa-eye"></i> Détail
                            </a>
                            <a href="' . route('client.edit', $user->id) . '" class="dropdown-item has-icon">
                                <i class="far fa-edit"></i> Modifier
                            </a>
                            <a href="#" role="button" data-id="' . $user->id . '" class="dropdown-item has-icon text-danger delete">
                                <i class="far fa-trash-alt"></i> Supprimer
                            </a>
                        </div>
                    </div>
                ';
                })
                ->rawColumns(['status_badge', 'type_badge', 'actions'])
                ->make(true);
        }

        // ✅ Compte réel sans charger la collection
        $totalCount = (clone $query)->count();

        return view('admin.pages.client.clientList', compact('dateDebut', 'dateFin', 'allDates', 'totalCount'));
    }

    public function detail($id)
    {
        $user = User::withCount(['orders'])
            ->with(['roles'])
            ->whereId($id)->firstOrFail();

        $dateDebut = request('date_debut');
        $dateFin   = request('date_fin');

        // Stats globales (toutes périodes)
        $baseQuery = Order::where('user_id', $id);
        $orders_livre    = (clone $baseQuery)->whereStatus('livrée')->count();
        $orders_annule   = (clone $baseQuery)->whereStatus('annulée')->count();
        $orders_en_cours = (clone $baseQuery)->whereNotIn('status', ['livrée', 'annulée'])->count();
        $ca_total = (clone $baseQuery)->where('status', '!=', 'annulée')->sum('total');
        $orders_mois = (clone $baseQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ca_mois = (clone $baseQuery)
            ->where('status', '!=', 'annulée')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Commandes filtrées pour le tableau
        $orders = (clone $baseQuery)
            ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
            ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.pages.client.detail', compact(
            'user',
            'orders',
            'orders_livre',
            'orders_annule',
            'orders_en_cours',
            'ca_total',
            'ca_mois',
            'orders_mois',
            'dateDebut',
            'dateFin'
        ));
    }

    public function create()
    {

        //recuperer la liste des motifs
        $motifs = User::MOTIFS;

        return view('admin.pages.client.create', compact('motifs'));
    }

    // public function store(Request $request)
    // {

    //     // Bloquer les bots via honeypot
    //     if ($request->filled('website')) {
    //         return back()->withError('Inscription bloquée.');
    //     }

    //     $user_verify_phone = User::wherePhone($request['phone'])->first();

    //     if ($user_verify_phone != null) {
    //         return back()->withError('Ce numéro de téléphone est déjà associé à un compte, veuillez utiliser un autre');
    //     }

    //     if ($request->filled('email') && User::whereEmail($request['email'])->exists()) {
    //         return back()->withError('Cet email est déjà associé à un compte, veuillez utiliser un autre');
    //     }

    //     $request->validate([
    //         'name'     => 'required|min:3|max:100',
    //         'phone'    => 'required|digits_between:8,15|unique:users,phone',
    //         'email'    => 'nullable|email',
    //         'password' => 'required|min:8',
    //     ]);

    //     $date_anniv = '';
    //     if ($request->jour && $request->mois) {
    //         $date_anniv = $request->jour . '-' . $request->mois;
    //     }

    //     $pwd_generate = $request->filled('password') ? null : 'password';
    //     $password     = $request->filled('password') ? $request->password : $pwd_generate;




    //     $user = User::create([
    //         'name'              => $request['name'],
    //         'phone'             => $request['phone'],
    //         'email'             => $request->filled('email') ? $request->email : null,
    //         'shop_name'         => $request->shop_name,
    //         'role'              => 'client',
    //         'type_client'       => 'prospect',
    //         'localisation'      => $request->localisation,
    //         'date_anniversaire' => $date_anniv,
    //         'password'          => Hash::make($password),
    //         'motif'             => $request->motif,
    //         'motif_autre'       => $request->motif == 'autre' ? $request->motif_autre : null,
    //     ]);

    //     $user->assignRole('client');

    //     return back()->with('success', 'Client ajouté avec succès');
    // }

    // CONTROLLER COMPLET

    public function store(Request $request)
    {
        // Honeypot anti-bot
        if ($request->filled('website')) {

            Log::warning('BOT BLOQUÉ - HONEYPOT', [
                'ip'    => $request->ip(),
                'agent' => $request->userAgent(),
                'data'  => $request->all(),
            ]);

            return back()->withError('Inscription bloquée.');
        }

        // Vérification temps minimum du formulaire
        if ((time() - (int)$request->form_time) < 4) {

            Log::warning('BOT BLOQUÉ - TEMPS TROP RAPIDE', [
                'ip'    => $request->ip(),
                'agent' => $request->userAgent(),
            ]);

            return back()->withError('Activité suspecte détectée.');
        }

        // Validation
        $request->validate([
            'name' => [
                'required',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-]+$/u'
            ],

            'phone' => [
                'required',
                'digits_between:8,15',
                'unique:users,phone'
            ],

            'email' => [
                'nullable',
                'email',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'min:8'
            ],

            'jour' => [
                'nullable',
                'numeric',
                'min:1',
                'max:31'
            ],

            'mois' => [
                'nullable',
                'numeric',
                'min:1',
                'max:12'
            ],
        ]);

        // Gestion anniversaire
        $date_anniv = null;

        if (
            $request->filled('jour') &&
            $request->filled('mois')
        ) {

            $date_anniv =
                str_pad($request->jour, 2, '0', STR_PAD_LEFT)
                . '-' .
                str_pad($request->mois, 2, '0', STR_PAD_LEFT);
        }

        // Création utilisateur
        $user = User::create([
            'name'              => trim($request->name),
            'phone'             => trim($request->phone),
            'email'             => $request->filled('email')
                ? trim($request->email)
                : null,

            'shop_name'         => $request->shop_name,
            'role'              => 'client',
            'type_client'       => 'prospect',
            'localisation'      => $request->localisation,
            'date_anniversaire' => $date_anniv,

            'password'          => Hash::make($request->password),

            'motif'             => $request->motif,

            'motif_autre'       => $request->motif == 'autre'
                ? $request->motif_autre
                : null,
        ]);

        $user->assignRole('client');

        return back()->with('success', 'Compte créé avec succès.');
    }

    public function edit($id)
    {
        $user              = User::find($id);
        $typeClientOptions = $this->typeClientOptions;
        return view('admin.pages.client.edit_client', compact('user', 'typeClientOptions'));
    }



    // public function update(Request $request, $id)
    // {
    //     $date_anniv = '';
    //     if ($request->jour && $request->mois) {
    //         $date_anniv = $request->jour . '-' . $request->mois;
    //     }

    //     // Rendre motif_autre obligatoire uniquement si "autre" est sélectionné
    //     if ($request->motif === 'autre') {
    //         $rules['motif_autre'] = 'required|string|max:255';
    //     }

    //     $updateData = [
    //         'name'              => $request['name'],
    //         'phone'             => $request['phone'],
    //         'email'             => $request->email,
    //         'role'              => 'client',
    //         'type_client'       => $request->type_client,
    //         'date_anniversaire' => $date_anniv,
    //         'localisation'      => $request->localisation,
    //         'motif'             => $request->motif,
    //         'motif_autre'       => $request->motif === 'autre' ? $request->motif_autre : null,
    //     ];

    //     if ($request->filled('password')) {
    //         $updateData['password'] = Hash::make($request['password']);
    //     }

    //     tap(User::find($id))->update($updateData);

    //     return back()->with('success', 'Client modifié avec succès');
    // }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $request->validate([

            'name' => [
                'required',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-]+$/u'
            ],

            'phone' => [
                'required',
                'digits_between:8,15',
                'unique:users,phone,' . $user->id
            ],

            'email' => [
                'nullable',
                'email',
                'unique:users,email,' . $user->id
            ],

            'password' => [
                'nullable',
                'min:8'
            ],

            'jour' => [
                'nullable',
                'numeric',
                'min:1',
                'max:31'
            ],

            'mois' => [
                'nullable',
                'numeric',
                'min:1',
                'max:12'
            ],
            
            'motif' => [
                'nullable',
                'string',
                'max:255'
            ],

            'motif_autre' => [
                'nullable',
                'string',
                'max:255'
            ],

        ]);

        // Motif autre obligatoire si motif = autre
        if (
            $request->motif === 'autre' &&
            !$request->filled('motif_autre')
        ) {

            return back()
                ->withErrors([
                    'motif_autre' => 'Veuillez préciser le motif.'
                ])
                ->withInput();
        }

        // Gestion date anniversaire
        $date_anniv = null;

        if (
            $request->filled('jour') &&
            $request->filled('mois')
        ) {

            $date_anniv =
                str_pad($request->jour, 2, '0', STR_PAD_LEFT)
                . '-' .
                str_pad($request->mois, 2, '0', STR_PAD_LEFT);
        }

        // Données à mettre à jour
        $updateData = [

            'name' => trim($request->name),

            'phone' => trim($request->phone),

            'email' => $request->filled('email')
                ? trim($request->email)
                : null,

            'role' => 'client',

            'type_client' => $request->type_client,

            'date_anniversaire' => $date_anniv,

            'localisation' => $request->localisation,

            'motif' => $request->motif,

            'motif_autre' => $request->motif === 'autre'
                ? $request->motif_autre
                : null,
        ];

        // Mise à jour mot de passe si renseigné
        if ($request->filled('password')) {

            $updateData['password'] =
                Hash::make($request->password);
        }

        // Update
        $user->update($updateData);

        return back()->with(
            'success',
            'Client modifié avec succès'
        );
    }

    public function destroy($id)
    {
        Order::where('user_id', $id)->delete();
        User::whereId($id)->delete();

        return response()->json(['status' => 200]);
    }
}
