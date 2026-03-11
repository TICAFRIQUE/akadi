<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthAdminController extends Controller
{
    //

    public function listUser()
    {
        $auth_role = User::find(Auth::id())->roles->pluck('name'); // Rôle(s) de l'utilisateur authentifié
        $clientRoles   = ['client', 'fidele', 'prospect'];
        //si le rôle de l'utilisateur authentifié est 'developpeur' alors on exclut pas le rôle 'developpeur'
        $excludedRoles = $auth_role->contains('developpeur') ? [] : ['developpeur'];

        $roleFilter = request('role');

        $users = User::withCount(['roles'])
            ->whereNotIn('role', array_merge($clientRoles, $excludedRoles))
            ->when($roleFilter, fn($q, $r) => $q->where('role', $r))
            ->orderBy('created_at', 'DESC')
            ->get();

        // Récupérer les rôles distincts présents dans la liste pour les filtres
        $adminRoles = User::whereNotIn('role', array_merge($clientRoles, $excludedRoles))
            ->distinct()
            ->pluck('role')
            ->sort()
            ->values();

        return view('admin.pages.user.userList', compact('users', 'adminRoles'));
    }


    public function userDetail($id)
    {
        $user = User::withCount(['orders'])
            ->with(['roles', 'permissions'])
            ->whereId($id)->firstOrFail();

        $dateDebut = request('date_debut');
        $dateFin   = request('date_fin');

        $baseQuery = Order::where('user_id', $id);

        $orders_livre    = (clone $baseQuery)->whereStatus('livrée')->count(); //
        $orders_annule   = (clone $baseQuery)->whereStatus('annulée')->count();
        $orders_en_cours = (clone $baseQuery)->whereNotIn('status', ['livrée', 'annulée'])->count();
        $ca_total        = (clone $baseQuery)->where('status', '!=', 'annulée')->sum('total');
        $orders_mois     = (clone $baseQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ca_mois = (clone $baseQuery)
            ->where('status', '!=', 'annulée')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $orders = (clone $baseQuery)
            ->when($dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
            ->when($dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
            ->orderBy('created_at', 'DESC')
            ->get();

        $permissions     = Permission::orderBy('name')->get();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        // dd($userPermissions);
        return view('admin.pages.user.userDetail', compact(
            'user', 'orders', 'orders_livre', 'orders_annule',
            'orders_en_cours', 'ca_total', 'ca_mois', 'orders_mois',
            'dateDebut', 'dateFin', 'permissions', 'userPermissions'
        ));
    }

    public function syncPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $permissions = $request->input('permissions', []);
        $user->syncPermissions($permissions);
        return back()->with('success', 'Permissions de l\'utilisateur mises à jour.');
    }



    public function registerForm(Request $request)
    {
        $auth_role = User::find(Auth::id())->roles->pluck('name'); // Rôle(s) de l'utilisateur authentifié
        //si le rôle de l'utilisateur authentifié est 'developpeur' alors on exclut pas le rôle 'developpeur'
        $excludedRoles = $auth_role->contains('developpeur') ? [] : ['developpeur'];
        $clientRoles = ['client', 'fidele', 'prospect'];

        $roles = Role::whereNotIn('name', array_merge($clientRoles, $excludedRoles))->get();
        return view('admin.pages.user.register', compact('roles'));
    }

    public function register(Request $request)
    {

        //on verifie si le nouvel utilisateur est déja dans la BD à partir du phone
        $user_verify_phone = User::wherePhone($request['phone'])->first();
        $user_verify_email = User::whereEmail($request['email'])->first();

        if ($user_verify_phone != null) {
            return back()->withError('Ce numero de telephone est dejà associé un compte, veuillez utiliser un autre');
        } elseif ($user_verify_email != null) {
            return back()->withError('Ce email est dejà associé un compte, veuillez utiliser un autre');
        } else {
            // dd($request);
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);

            $date_anniv = '';
            if ($request->jour && $request->mois) {
                $date_anniv = $request->jour . '-' . $request->mois;
            }



            $user = User::firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request->email,
                'shop_name' => $request->shop_name,
                'role' => $request->role,
                'localisation' => $request->localisation,
                'date_anniversaire' => $date_anniv,
                'password' => Hash::make($request['password']),
            ]);
            if ($request->has('role')) {
                $user->assignRole([$request['role']]);
                syncPrivilegedPermissions($user);
                app()[PermissionRegistrar::class]->forgetCachedPermissions();
            }



            return back()->with([
                'success' => "Utilisateur ajouté avec success",
            ]);
        }
    }

    public function edit($id)
    {
        $user        = User::find($id);
        $auth_role = User::find(Auth::id())->roles->pluck('name'); // Rôle(s) de l'utilisateur authentifié
        //si le rôle de l'utilisateur authentifié est 'developpeur' alors on exclut pas le rôle 'developpeur'
        $excludedRoles = $auth_role->contains('developpeur') ? [] : ['developpeur'];
        $clientRoles = ['client', 'fidele', 'prospect'];
        $roles       = Role::whereNotIn('name', array_merge($clientRoles, $excludedRoles))->get();

        return view('admin.pages.user.edit_user', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->toArray());

        $date_anniv = '';
        if ($request->jour && $request->mois) {
            $date_anniv = $request->jour . '-' . $request->mois; //date anniversaire

        } else {
            $date_anniv = '';
        }


        $user = tap(User::find($id))->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request->email,
            'shop_name' => $request->shop_name,
            'role' => $request->role,
            'date_anniversaire' => $date_anniv,
            'localisation' => $request->localisation,
            'password' => Hash::make($request['password']),
        ]);

        // DB::table('model_has_roles')->where('model_id', $id)->delete();

        if ($request->has('role')) {
            $user->syncRoles($request['role']);
            syncPrivilegedPermissions($user);
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
        }
        return back()->with([
            'success' => "Utilisateur modifié avec success",
        ]);
    }


    public function destroy($id)
    {


        //delete order of this user
        Order::where("user_id", $id)->delete();

        User::whereId($id)->delete();

        return response()->json([
            'status' => 200
        ]);
    }


    public function login(Request $request)
    {
        if (request()->method() == 'GET') {
            return view('admin.pages.user.login');
        } elseif (request()->method() == 'POST') {

            $credentials = $request->validate([
                'email' => ['required',],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard.index')->withSuccess('Connexion réussi,  Bienvenue  ' . Auth::user()->name);
            } else {
                return back()->withError('Email ou mot de passe incorrect');
            }
        }
    }

    //logout
    public function logout()
    {
        Auth::logout();
        Session::forget('user_auth');
        return Redirect('sign-in')->withSuccess('deconnexion réussi');
    }
}
