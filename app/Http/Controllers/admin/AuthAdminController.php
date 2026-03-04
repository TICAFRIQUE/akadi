<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthAdminController extends Controller
{
    //

    public function listUser()
    {
        $clientRoles = ['fidele', 'prospect'];
        $excludedRoles = ['developpeur'];

        $isClient   = request()->has('client');
        $isAdmin    = request()->has('admin');
        $typeClient = request('client');
        $roleFilter = request('user');

        // Filtre date : par défaut mois en cours pour la vue clients
        $dateDebut = request('date_debut', $isClient && !request()->has('date_debut') ? now()->startOfMonth()->format('Y-m-d') : null);
        $dateFin   = request('date_fin',   $isClient && !request()->has('date_fin')   ? now()->endOfMonth()->format('Y-m-d')   : null);

        $users = User::withCount(['roles', 'orders'])
            ->whereNotIn('role', $excludedRoles)
            ->when($isClient, function ($q) use ($clientRoles, $typeClient) {
                if ($typeClient) {
                    $q->where('role', $typeClient);
                } else {
                    $q->whereIn('role', $clientRoles);
                }
            })
            ->when($isAdmin, fn($q) => $q->whereNotIn('role', array_merge($clientRoles, $excludedRoles)))
            ->when($roleFilter, fn($q, $r) => $q->where('role', $r))
            ->when($isClient && $dateDebut, fn($q) => $q->whereDate('created_at', '>=', $dateDebut))
            ->when($isClient && $dateFin,   fn($q) => $q->whereDate('created_at', '<=', $dateFin))
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.pages.user.userList', compact('users', 'dateDebut', 'dateFin'));
    }


    public function userDetail($id)
    {
        $user = User::withCount(['roles', 'orders'])
            ->with(['roles', 'orders'])
            ->whereId($id)->first();

        $orders_annule = Order::where('user_id', $id)
            ->whereStatus('annulée')->count();

        $orders_livre = Order::where('user_id', $id)
            ->whereStatus('livrée')->count();

        return view('admin.pages.user.userDetail', compact(
            'user',
            'orders_annule',
            'orders_livre'
        ));
    }



    public function registerForm(Request $request)
    {
        //si le user connecté est autre que developpeur ou administrateur, on affiche que les roles clients

        $user = User::find(Auth::id());
        //si le user connecté est un gestionnaire, on affiche que les roles clients
       if ($user->hasRole('gestionnaire') ) {
            $roles = Role::whereIn('name', ['client'])->get();

        } 
        //si le user connecté est un developpeur ou administrateur, on affiche tous les roles
         elseif ($user->hasRole('developpeur') || $user->hasRole('administrateur')) {
            $roles = Role::get();
        }
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
                'email' => 'nullable|unique:users',
                // 'password' => 'required',
            ]);

            $date_anniv = '';
            if ($request->jour && $request->mois) {
                $date_anniv = $request->jour . '-' . $request->mois;
            }

            $pwd_generate = $request->filled('password') ? null : Str::random(8);
            $password = $request->filled('password') ? $request->password : $pwd_generate;

            $user = User::firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request->email,
                'shop_name' => $request->shop_name,
                'role' => $request->role,
                'localisation' => $request->localisation,
                'date_anniversaire' => $date_anniv,
                'password' => Hash::make($password),
            ]);
            if ($request->has('role')) {
                $user->assignRole([$request['role']]);
            }

            $data = [
                "email" => $request['email'],
                "pwd" => $pwd_generate ?? '(mot de passe défini manuellement)',
            ];
            $auth_user_details = Session::put('user_auth', $data);

            return back()->with([
                'success' => "Utilisateur ajouté avec success",
            ]);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        $authUser = User::find(Auth::id());

        // Selon le rôle du user connecté, on limite les rôles disponibles
        if ($authUser->hasRole('gestionnaire')) {
            $roles = Role::whereIn('name', ['client', 'fidele', 'prospect'])->get();
        } elseif ($authUser->hasRole(['developpeur', 'administrateur'])) {
            $roles = Role::get();
        } else {
            $roles = collect(); // aucun rôle disponible par défaut
        }

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
