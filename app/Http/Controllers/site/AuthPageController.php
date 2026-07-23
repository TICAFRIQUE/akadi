<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use RealRashid\SweetAlert\Facades\Alert;

class AuthPageController extends Controller
{



    //code optimisé v2
    // public function register(Request $request)
    // {
    //     if ($request->method() === 'GET') {
    //         return view('site.pages.auth.register');
    //     }

    //     // ── Validation unique — pas besoin de vérification manuelle en plus ──────
    //     $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'phone'    => 'required|string|unique:users,phone',
    //         'email'    => 'nullable|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //     ], [
    //         'phone.unique'    => 'Ce numéro de téléphone est déjà associé à un compte.',
    //         'email.unique'    => 'Cet email est déjà associé à un compte.',
    //         'name.required'   => 'Le nom est obligatoire.',
    //         'phone.required'  => 'Le téléphone est obligatoire.',
    //         'password.required' => 'Le mot de passe est obligatoire.',
    //         'password.min'    => 'Le mot de passe doit contenir au moins 6 caractères.',
    //     ]);

    //     // ── Date anniversaire ────────────────────────────────────────────────────
    //     $date_anniv = ($request->filled('jour') && $request->filled('mois'))
    //         ? $request->jour . '-' . $request->mois
    //         : null;

    //     // ── Création utilisateur ─────────────────────────────────────────────────
    //     $user = User::create([
    //         'name'              => $request->name,
    //         'phone'             => $request->phone,
    //         'email'             => $request->email,
    //         'date_anniversaire' => $date_anniv,
    //         'role'              => 'client',
    //         'password'          => Hash::make($request->password),
    //     ]);

    //     $user->assignRole('client');

    //     // ── Envoi email de bienvenue ──────────────────────────────────────────────
    //     if ($user->email) {
    //         try {
    //             SendEmailJob::dispatchAfterResponse(
    //                 $user->email,
    //                 'Bienvenue sur Akadi !',
    //                 'emails.register-welcome',
    //                 [
    //                     'logo'     => asset('site/assets/img/custom/AKADI.png'),
    //                     'userName' => $user->name,
    //                 ],
    //                 env('MAIL_FROM_ADDRESS'),
    //                 env('MAIL_FROM_NAME')
    //             );
    //         } catch (\Exception $e) {
    //             logger()->error('Erreur dispatch email inscription : ' . $e->getMessage());
    //         }
    //     }
    //     // ── Connexion et redirection ──────────────────────────────────────────────
    //     Auth::login($user);

    //     $url = session('cart') ? 'finaliser-ma-commande' : '/';

    //     Alert::success('Compte créé avec succès. Bienvenue !');

    //     return redirect()->away($url)->with('success', 'Compte créé avec succès. Bienvenue !');
    // }

    //code v3 optimisé
    public function register(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | AFFICHAGE PAGE
    |--------------------------------------------------------------------------
    */
        if ($request->method() === 'GET') {
            return view('site.pages.auth.register');
        }

        // dd($request->all());

        /*
    |--------------------------------------------------------------------------
    | HONEYPOT ANTI-BOT
    |--------------------------------------------------------------------------
    */
        if ($request->filled('website')) {

            Log::warning('BOT BLOQUÉ - HONEYPOT', [
                'ip'    => $request->ip(),
                'agent' => $request->userAgent(),
                'data'  => $request->all(),
            ]);
            Alert::error('Requête invalide.');

            return back()->withErrors([
                'error' => 'Requête invalide.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | TEMPS MINIMUM FORMULAIRE
    |--------------------------------------------------------------------------
    */
        if ((time() - (int) $request->form_time) < 4) {

            Log::warning('BOT BLOQUÉ - TEMPS TROP RAPIDE', [
                'ip' => $request->ip(),
            ]);

            Alert::error('Activité suspecte détectée.');
            return back()->withErrors([
                'error' => 'Activité suspecte détectée.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | BLOQUER JOUR / MOIS FAKE
    |--------------------------------------------------------------------------
    */
        if (
            $request->jour === 'Jour' ||
            $request->mois === 'Mois'
        ) {

            Log::warning('BOT JOUR/MOIS DETECTÉ', [
                'ip'   => $request->ip(),
                'data' => $request->all(),
            ]);

            Alert::error('Date invalide.');
            return back()->withErrors([
                'error' => 'Date invalide.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | BLOQUER DOMAINES EMAIL SUSPECTS
    |--------------------------------------------------------------------------
    */
        if ($request->filled('email')) {

            $blockedDomains = [
                'pacificcrest.us',
                'mail.ru',
                'tempmail.com',
                'guerrillamail.com',
                '10minutemail.com',
            ];

            $emailDomain = strtolower(
                substr(strrchr($request->email, "@"), 1)
            );

            if (in_array($emailDomain, $blockedDomains)) {

                Log::warning('EMAIL BOT BLOQUÉ', [
                    'email' => $request->email,
                    'ip'    => $request->ip(),
                ]);

                Alert::error('Adresse email non autorisée.');
                return back()->withErrors([
                    'email' => 'Adresse email non autorisée.'
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
        $request->validate([

            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-]+$/u'
            ],

            'phone' => [
                'required',

                // Côte d’Ivoire uniquement
                'regex:/^(01|05|07)[0-9]{8}$/',

                'unique:users,phone'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email'
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

        ], [

            'phone.unique' => 'Ce numéro est déjà utilisé.',

            'phone.regex' =>
            'Veuillez entrer un numéro ivoirien valide.',

            'email.unique' =>
            'Cet email est déjà associé à un compte.',

            'name.required' =>
            'Le nom est obligatoire.',

            'phone.required' =>
            'Le téléphone est obligatoire.',
        ]);

        /*
    |--------------------------------------------------------------------------
    | DATE ANNIVERSAIRE SAFE
    |--------------------------------------------------------------------------
    */
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

        /*
    |--------------------------------------------------------------------------
    | CRÉATION UTILISATEUR
    |--------------------------------------------------------------------------
    */
        $user = User::create([

            'name' => trim($request->name),

            'phone' => trim($request->phone),

            'email' => $request->filled('email')
                ? trim($request->email)
                : null,

            'date_anniversaire' => $date_anniv,

            'role' => 'client',

            'type_client' => 'prospect',

            'password' => Hash::make($request->phone),
        ]);

        $user->assignRole('client');

        /*
    |--------------------------------------------------------------------------
    | EMAIL BIENVENUE
    |--------------------------------------------------------------------------
    */
        if ($user->email) {

            try {

                SendEmailJob::dispatchAfterResponse(
                    $user->email,
                    'Bienvenue sur Akadi !',
                    'emails.register-welcome',
                    [
                        'logo'     => asset('site/assets/img/custom/AKADI.png'),
                        'userName' => $user->name,
                    ],
                    env('MAIL_FROM_ADDRESS'),
                    env('MAIL_FROM_NAME')
                );
            } catch (\Exception $e) {

                logger()->error(
                    'Erreur email inscription : '
                        . $e->getMessage()
                );
            }
        }

        /*
    |--------------------------------------------------------------------------
    | CONNEXION
    |--------------------------------------------------------------------------
    */
        Auth::login($user);

        /*
    |--------------------------------------------------------------------------
    | REDIRECTION
    |--------------------------------------------------------------------------
    */
        $url = session('cart')
            ? 'finaliser-ma-commande'
            : '/';

        Alert::success(
            'Compte créé avec succès. Bienvenue !'
        );

        return redirect($url)->with(
            'success',
            'Compte créé avec succès. Bienvenue !'
        );
    }
    public function login(Request $request)
    {
        if (request()->method() == 'GET') {

            return view('site.pages.auth.login');
        } elseif (request()->method() == 'POST') {
            $request->validate([
                'phone' => ['required', 'string'],
            ]);

            $phone = trim($request->phone);

            $url = session('cart') ? 'finaliser-ma-commande' : '/';

            // Tentative normale : numéro = identifiant ET mot de passe
            if (Auth::attempt(['phone' => $phone, 'password' => $phone])) {
                Alert::success('Connexion réussie. Bienvenue !');
                return redirect()->away($url);
            }

            // Fallback pour les anciens comptes clients uniquement
            $user = \App\Models\User::where('phone', $phone)
                ->where('role', 'client')
                ->first();

            if ($user) {
                // Mettre à jour le password avec le numéro pour les prochaines connexions
                $user->update(['password' => Hash::make($phone)]);
                Auth::login($user);
                Alert::success('Connexion réussie. Bienvenue !');
                return redirect()->away($url);
            }

            return redirect()->route('login-form')->withError('Numéro de téléphone introuvable.');
        }
    }







    //Deconnexion
    public function logout()
    {
        Auth::logout();
        // Session::flush();
        Alert::success('Déconnexion réussie. À bientôt !');
        return Redirect('/')->withSuccess('deconnexion réussi');
    }



    /**********************************************************FORGET PASSWORD ***********************/

    //get form for send email
    public function showForgetPasswordForm(Request $request)
    {
        return view('site.pages.auth.forgetPassword.email_reset');
    }

    //send  mail with link reset password
    // public function submitForgetPasswordForm(Request $request)
    // {

    //     $mail_verify = User::whereEmail($request['email'])
    //         ->first();
    //     if (!$mail_verify) {
    //         return back()->withError('Ce email n\'existe pas');
    //     } else {
    //         $request->validate([
    //             'email' => 'required|email|exists:users',
    //         ]);

    //         DB::table('password_reset_tokens')->whereEmail($request['email'])->delete();

    //         $token = Str::random(64);

    //         DB::table('password_reset_tokens')->insert([
    //             'email' => $request->email,
    //             'token' => $token,
    //             'created_at' => Carbon::now()
    //         ]);

    //         ###################   EMAIL SEND ###################


    //         $url = route('reset.password.get', 'token=' . $token);

    //         //new send mail with phpMailer
    //         $mail = new PHPMailer(true);
    //         // require base_path("vendor/autoload.php");

    //         /* Email SMTP Settings */
    //         $mail->SMTPDebug = 0;
    //         $mail->isSMTP();
    //         $mail->Host = 'mail.akadi.ci';
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'info@akadi.ci';
    //         $mail->Password = 'S$UBfu.8s(#z';
    //         $mail->SMTPSecure = 'ssl';
    //         $mail->Port = 465;

    //         $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
    //         $mail->addAddress($request->email);

    //         $mail->isHTML(true);


    //         $mail->Subject = 'Email de recuperation de mot de passe';
    //         $mail->Body = '

    //         <h1 style="text-align: center; color: #220072;"><strong>Email de recuperation de mot de passe</strong></h1>
    //     <p style="text-align: center;">Vous pouvez réinitialiser votre mot de passe à partir du lien ci-dessous .</p>
    //     <p style="text-align: center;"><a
    //     style="background: rgb(35, 35, 35); color: #ffffff; padding: 10px 50px; border-radius: 3px;"  
    //     href="' . $url . '">Cliquez pour réinitialiser</a></p>
    //         ';
    //         $mail->send();


    //         ###################   EMAIL SEND ###################

    //         return back()->with('success', 'Nous avons envoyé par e-mail le lien de réinitialisation de votre mot de passe !');
    //     }
    // }


    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists'   => "Cet email n'existe pas dans notre base.",
            'email.required' => "L'email est obligatoire.",
            'email.email'    => "L'email n'est pas valide.",
        ]);

        // Supprimer l'ancien token s'il existe
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Créer un nouveau token
        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        // Envoyer l'email via queue
        $url = route('reset.password.get', 'token=' . $token);

        SendEmailJob::dispatchAfterResponse(
            $request->email,
            'Réinitialisation de votre mot de passe',
            'emails.reset-password',
            ['url' => $url],
            env('MAIL_FROM_ADDRESS'),
            env('MAIL_FROM_NAME')
        );

        return back()->with('success', 'Nous avons envoyé le lien de réinitialisation à votre adresse email.');
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
