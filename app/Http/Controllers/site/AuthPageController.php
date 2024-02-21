<?php

namespace App\Http\Controllers\site;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthPageController extends Controller
{
    //
    public function register(Request $request)
    {
        if (request()->method() == 'GET') {
            return view('site.pages.auth.register');
        } elseif (request()->method() == 'POST') {
            //on verifie si le nouvel utilisateur est déja dans la BD à partir du phone
            $user_verify_phone = User::wherePhone($request['phone'])->first();
            $user_verify_email = User::whereEmail($request['email'])->first();

            if ($user_verify_phone != null) {
                return back()->withError('Ce numero de telephone est dejà associé un compte, veuillez utiliser un autre');
            } elseif ($user_verify_email != null) {
                return back()->withError('Ce email est dejà associé un compte, veuillez utiliser un autre');
            } else {
                $request->validate([
                    'name' => 'required',
                    'phone' => 'required|unique:users',
                    'email' => '',
                    'password' => 'required',
                ]);

                $user = User::firstOrCreate([
                    'name' => $request['name'],
                    'phone' => $request['phone'],
                    'email' => $request->email,
                    'role' => 'client',
                    'password' => Hash::make($request['password']),
                ]);

                if ($user) {
                    $user->assignRole('client');
                }
                $url_previous = $request['url_previous'];

                Auth::login($user);

                return redirect()->away($url_previous)->with([
                    'success' => "Connexion réussie",
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        if (request()->method() == 'GET') {
            return view('site.pages.auth.login');
        } elseif (request()->method() == 'POST') {
            $credentials = $request->validate([
                'phone' => ['required'],
                'password' => ['required'],
            ]);

            $url_previous = $request['url_previous'];

            if (Auth::attempt($credentials)) {
                return redirect()->away($url_previous)->withSuccess('connexion reussi');
            } else {
                return redirect()->route('login-form')->withError('Contact ou mot de passe incorrect');
            }
        }
    }



    //Deconnexion
    public function logout()
    {
        Auth::logout();
        // Session::flush();
        return Redirect('/')->withSuccess('deconnexion réussi');
    }
}
