<?php

namespace App\Http\Controllers\site;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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


                //send mail for our register
                Mail::send('site.pages.auth.email.email_register', ['user' => $request['name']], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Création de compte');
                });
                

                //redirection apres connexion
                if (session('cart')) {
                    $url = 'finaliser-ma-commande';  // si le panier n'est pas vide on redirige vers la page checkout
                } else {
                    $url = '/';   // sinon on redirige vers l'accueil
                }

                // $url_previous = $request['url_previous'];

                Auth::login($user);

                return redirect()->away($url)->with([
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

            //redirection apres connexion
            if (session('cart')) {
                $url = 'finaliser-ma-commande';  // si le panier n'est pas vide on redirige vers la page checkout
            } else {
                $url = '/';   // sinon on redirige vers l'accueil
            }

            // $url_previous = $request['url_previous'];

            if (Auth::attempt($credentials)) {
                return redirect()->away($url)->withSuccess('Connexion reussi');
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



    /**********************************************************FORGET PASSWORD ***********************/

    //get form for send email
    public function showForgetPasswordForm(Request $request)
    {
        return view('site.pages.auth.forgetPassword.email_reset');
    }

    //send  mail with link reset password
    public function submitForgetPasswordForm(Request $request)
    {

        $mail_verify = User::whereEmail($request['email'])
            ->first();
        if (!$mail_verify) {
            return back()->withError('Ce email n\'existe pas');
        } else {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            DB::table('password_reset_tokens')->whereEmail($request['email'])->delete();

            $token = Str::random(64);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('site.pages.auth.forgetPassword.email_send', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('réinitialiser son mot de passe');
            });

            return back()->with('success', 'Nous avons envoyé par e-mail le lien de réinitialisation de votre mot de passe !');
        }
    }

    //form for new password

    public function showResetPasswordForm(Request $request)
    {
        $token  = request('token');

        $verifyTokenExist = DB::table('password_reset_tokens')
            ->where([
                // 'email' => $request->email,
                'token' => $token
            ])
            ->first();

        if ($verifyTokenExist) {
            //checking the time of token is expired or not

            $currentTime = Carbon::now();
            $timeWhenTokenCreated = Carbon::parse($verifyTokenExist->created_at);

            $difference = $currentTime->diffInMinutes($timeWhenTokenCreated);

            if ($difference >  15) {
                DB::table('password_reset_tokens')->where(['token' => $token])->delete();

                return redirect()->route('login-form')->withError('Le lien de reinitialisation à expiré');
            } else {
                return view('site.pages.auth.forgetPassword.new_password_reset');
            }
        } else {
            return redirect()->route('login-form')->withError('Le lien de reinitialisation à expiré');
        }
    }



    //store  the new password in database and redirect to login page
    public function submitResetPasswordForm(Request $request)
    {

        $request->validate([
            // 'email' => 'required|email|exists:users',
            'password' => 'required',
            'confirm_password' => 'required'
        ]);

        $verifyTokenExist = DB::table('password_reset_tokens')
            ->where([
                // 'email' => $request->email,
                'token' => $request->token
            ])
            ->first();



        if (!$verifyTokenExist) {
            return back()->withError('Token invalid');
        } else {
            $user = User::where('email', $verifyTokenExist->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();

            return redirect(route('login-form'))->withSuccess('Votre mot de passe a été réinitialisé avec succès ! Connectez-vous maintenant');
        }
    }
}
