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
        $role = request('user');

        $users = User::with('roles')
            ->whereHas('roles', fn ($q) => $q->where('name', '!=', 'developpeur'))
            ->when($role, fn ($q, $role) => $q->whereHas('roles', fn ($q) => $q->where('name', $role)))
            ->orderBy('created_at', 'DESC')->get();
        // dd($user->toArray());
        return view('admin.pages.user.userList', compact('users'));
    }


    public function registerForm(Request $request)
    {
        $roles = Role::get();
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
                'email' => 'required|unique:users',
                // 'password' => 'required',
            ]);

            $pwd_generate = Str::random('8');
            $user = User::firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request->email,
                'shop_name' => $request->shop_name,
                'role' => $request->role,
                'localisation' => $request->localisation,
                'password' => Hash::make($pwd_generate),
            ]);
            if ($request->has('role')) {
                $user->assignRole([$request['role']]);
            }

            $data = [
                "email" => $request['email'],
                "pwd" => $pwd_generate,
            ];
            $auth_user_details = Session::put('user_auth', $data);

            return back()->with([
                'success' => "Utilisateur ajouté avec success",
            ]);
        }
    }

    public function edit($id)
    {
        $user = User::with('roles')->whereId($id)->first();
        return view('admin.pages.user.edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $user = tap(User::find($id))->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request->email,
            'shop_name' => $request->shop_name,
            'role' => $request->role,
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
