<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    private array $typeClientOptions = ['prospect', 'fidele'];

    public function listClient()
    {
        $typeClient = request('type');

        $dateDebut = request('date_debut', !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
        $dateFin   = request('date_fin',   !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);

        $users = User::withCount([
                'orders',
                'orders as orders_month_count' => fn($q) => $q
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year),
            ])
            ->where('role', 'client')
            ->when($typeClient, fn($q) => $q->where('type_client', $typeClient))
            ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
            ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.pages.client.clientList', compact('users', 'dateDebut', 'dateFin'));
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
            'user', 'orders', 'orders_livre', 'orders_annule',
            'orders_en_cours', 'ca_total', 'ca_mois', 'orders_mois',
            'dateDebut', 'dateFin'
        ));
    }

    public function create()
    {
        return view('admin.pages.client.create');
    }

    public function store(Request $request)
    {
        $user_verify_phone = User::wherePhone($request['phone'])->first();

        if ($user_verify_phone != null) {
            return back()->withError('Ce numéro de téléphone est déjà associé à un compte, veuillez utiliser un autre');
        }

        if ($request->filled('email') && User::whereEmail($request['email'])->exists()) {
            return back()->withError('Cet email est déjà associé à un compte, veuillez utiliser un autre');
        }

        $request->validate([
            'name'  => 'required',
            'phone' => 'required',
            'email' => 'nullable|email',
        ]);

        $date_anniv = '';
        if ($request->jour && $request->mois) {
            $date_anniv = $request->jour . '-' . $request->mois;
        }

        $pwd_generate = $request->filled('password') ? null : 'password';
        $password     = $request->filled('password') ? $request->password : $pwd_generate;

        $user = User::create([
            'name'              => $request['name'],
            'phone'             => $request['phone'],
            'email'             => $request->filled('email') ? $request->email : null,
            'shop_name'         => $request->shop_name,
            'role'              => 'client',
            'type_client'       => 'prospect',
            'localisation'      => $request->localisation,
            'date_anniversaire' => $date_anniv,
            'password'          => Hash::make($password),
        ]);

        $user->assignRole('client');

        return back()->with('success', 'Client ajouté avec succès');
    }

    public function edit($id)
    {
        $user              = User::find($id);
        $typeClientOptions = $this->typeClientOptions;
        return view('admin.pages.client.edit_client', compact('user', 'typeClientOptions'));
    }

    public function update(Request $request, $id)
    {
        $date_anniv = '';
        if ($request->jour && $request->mois) {
            $date_anniv = $request->jour . '-' . $request->mois;
        }

        $updateData = [
            'name'              => $request['name'],
            'phone'             => $request['phone'],
            'email'             => $request->email,
            'role'              => 'client',
            'type_client'       => $request->type_client,
            'date_anniversaire' => $date_anniv,
            'localisation'      => $request->localisation,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request['password']);
        }

        tap(User::find($id))->update($updateData);

        return back()->with('success', 'Client modifié avec succès');
    }

    public function destroy($id)
    {
        Order::where('user_id', $id)->delete();
        User::whereId($id)->delete();

        return response()->json(['status' => 200]);
    }
}
