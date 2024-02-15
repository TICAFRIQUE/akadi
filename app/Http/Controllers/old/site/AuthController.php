<?php

namespace App\Http\Controllers\site;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    //socialite List
    protected $providers = ["google", "github", "facebook", "linkedin"];


    /*************************REGISTER *************************************/
    //register form
    public function registerForm()
    {
        return view('site.pages.Auth.register');
    }

    public function register(Request $request)
    {
        $user_verify = User::whereEmail($request['email'])->get();
        // dd($user_verify->count());
        if ($user_verify->count() > 0) {
            return back()->withError('Ce email est dejà associé un compte, veuillez utiliser un autre');
        } else {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
            ]);

            $user = User::firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request['password']),
            ]);

            if ($request->role) {
                $user->assignRole($request->role);
            }
            $url_previous = $request['url_previous'];

            Auth::login($user);

            return redirect()->away($url_previous)->with([
                'success' => "Connexion réussie",
            ]);
        }
    }


    /********************************LOGIN ********************************************/
    //login form
    public function loginForm()
    {
        // if (Auth::check()) {

        // }
        // return view('site.pages.Auth.login');

        if (Auth::check()) {
            return view('site.pages.user-account.dashboard');
        } else {
            return view('site.pages.Auth.login');
        }
    }

    //login
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $url_previous = $request['url_previous'];

        if (Auth::attempt($credentials)) {
            return redirect()->away($url_previous)->withSuccess('connexion reussi');
        } else {
            return redirect()->route('login-form')->withError('Email ou mot de passe incorrect');
        }


        // return Auth::attempt($credentials) ? Redirect($url_previous)->withSuccess('connexion reussi') :
        //     to_route('login-form')->withError('Email ou mot de passe incorrect');
    }


    /****************************************** SOCIALITE ****************************************/

    # redirection vers le provider
    public function redirect(Request $request)
    {

        $provider = $request->provider;

        // On vérifie si le provider est autorisé
        if (in_array($provider, $this->providers)) {
            return Socialite::driver($provider)->stateless()->redirect(); // On redirige vers le provider
        }
        abort(404); // Si le provider n'est pas autorisé
    }


    #callback function
    public function callback(Request $request)
    {

        try {
            $user = Socialite::driver($request['provider'])->stateless()->user();
            // dd($user);
            $authUser = User::updateOrCreate([
                'id_socialite' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),
                'role' => 'client',
            ]);
            $authUser->assignRole('client');

            auth()->login($authUser);


            if (session('cart')) {
                return redirect()->route('checkout')->with([
                    'success' => "Connexion réussie"
                ]);
            } else {
                return redirect()->to('/my-account')->with([
                    'success' => "Connexion réussie",
                ]);
            }
        } catch (\Exception $e) {
            return Redirect('login');
        }
    }





    /*****************************************************************END SOCIALITE*********************** */
    //logout
    public function logout()
    {
        Auth::logout();
        // Session::flush();
        return Redirect('login')->withSuccess('deconnexion réussi');
    }
}
