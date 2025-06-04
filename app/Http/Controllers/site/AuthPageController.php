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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthPageController extends Controller
{
    //
    public function register(Request $request)
    {
        if (request()->method() == 'GET') {
            return view('site.pages.auth.register');
        } elseif (request()->method() == 'POST') {
            // dd($request->toArray());

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
                    'email' => 'nullable|unique:users',
                    'password' => 'required',
                ]);


                $date_anniv = '';
                if ($request->jour && $request->mois) {
                    $date_anniv = $request->jour . '-' . $request->mois; //date anniversaire

                } else {
                    $date_anniv = '';
                }


                $user = User::firstOrCreate([
                    'name' => $request['name'],
                    'phone' => $request['phone'],
                    'email' => $request->email,
                    'date_anniversaire' => $date_anniv,
                    'role' => 'client',
                    'password' => Hash::make($request['password']),
                ]);

                if ($user) {
                    $user->assignRole('client');
                }



                // en envoi email si le user a renseigné son email
                // if ($request->email) {
                //     $data = [
                //         "email" => $request['email'],
                //         "pwd" => $request['password'],
                //     ];

                //     //new send mail with phpMailer
                //     $mail = new PHPMailer(true);
                //     // require base_path("vendor/autoload.php");

                //     /* Email SMTP Settings */
                //     $mail->SMTPDebug = 0;
                //     $mail->isSMTP();
                //     $mail->Host = 'mail.akadi.ci';
                //     $mail->SMTPAuth = true;
                //     $mail->Username = 'info@akadi.ci';
                //     $mail->Password = 'S$UBfu.8s(#z';
                //     $mail->SMTPSecure = 'ssl';
                //     $mail->Port = 465;

                //     $mail->setFrom('info@akadi.ci', 'Akadi');
                //     $mail->addAddress($request['email'], $request['name']);
                //     $mail->isHTML(true);
                //     $mail->Subject = 'Bienvenue sur Akadi';
                //     $mail->Body = view('site.pages.auth.email', compact('data'));
                //     $mail->send();
                // }





                //                 //send message after register
                //                 $data = [
                //                     'imagePath' => asset('site/assets/img/custom/AKADI.png'),
                //                 ];
                //                 //new send mail with phpMailer
                //                 $mail = new PHPMailer(true);
                //                 // require base_path("vendor/autoload.php");

                //                 /* Email SMTP Settings */
                //                 $mail->SMTPDebug = 0;
                //                 $mail->isSMTP();
                //                 $mail->Host = 'mail.akadi.ci';
                //                 $mail->SMTPAuth = true;
                //                 $mail->Username = 'info@akadi.ci';
                //                 $mail->Password = 'S$UBfu.8s(#z';
                //                 $mail->SMTPSecure = 'ssl';
                //                 $mail->Port = 465;

                //                 $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
                //                 $mail->addAddress($user->email);

                //                 $mail->isHTML(true);


                //                 $mail->Subject = 'Création de compte';
                //                 $mail->Body =
                //                     '<p style="text-align: center;>  <img src="' . $data['imagePath'] . '" alt="Akadi logo" width="80">

                // </p>

                // <p style="text-align: center;">Hello, ' . $user->name . ' </p>
                // <p style="text-align: center;">Votre compte à été crée avec success</p>
                // <p style="text-align: center;">Merci pour votre confiance , <a href="http://Akadi.ci" target="_blank" rel="noopener noreferrer">www.akadi.ci</a></p>
                //             ';
                //                 $mail->send();



                if ($user->email) {
                    try {
                        $logo = asset('site/assets/img/custom/AKADI.png');

                        $mail = new PHPMailer(true);

                        // Configuration SMTP
                        $mail->isSMTP();
                        $mail->Host       = 'mail.akadi.ci';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'info@akadi.ci';
                        $mail->Password   = 'S$UBfu.8s(#z'; // 🔐 à stocker dans .env si possible
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port       = 465;

                        // Expéditeur et destinataire
                        $mail->setFrom('info@akadi.ci', 'Akadi');
                        $mail->addAddress($user->email);

                        // Contenu HTML
                        $mail->isHTML(true);
                        $mail->Subject = 'Création de compte';

                        $mail->Body = <<<HTML
                            <div style="text-align: center;">
                                <img src="{$logo}" alt="Akadi logo" width="80">
                                <p>Hello, {$user->name}</p>
                                <p>Votre compte a été créé avec succès.</p>
                                <p>Merci pour votre confiance, <a href="http://akadi.ci" target="_blank">www.akadi.ci</a></p>
                            </div>
                        HTML;

                        $mail->send();
                    } catch (Exception $e) {
                        // Log ou gestion d’erreur propre
                        logger()->error("Erreur envoi email : " . $mail->ErrorInfo);
                    }
                }




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

            ###################   EMAIL SEND ###################


            $url = route('reset.password.get', 'token=' . $token);

            //new send mail with phpMailer
            $mail = new PHPMailer(true);
            // require base_path("vendor/autoload.php");

            /* Email SMTP Settings */
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.akadi.ci';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@akadi.ci';
            $mail->Password = 'S$UBfu.8s(#z';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
            $mail->addAddress($request->email);

            $mail->isHTML(true);


            $mail->Subject = 'Email de recuperation de mot de passe';
            $mail->Body = '
            
            <h1 style="text-align: center; color: #220072;"><strong>Email de recuperation de mot de passe</strong></h1>
        <p style="text-align: center;">Vous pouvez réinitialiser votre mot de passe à partir du lien ci-dessous .</p>
        <p style="text-align: center;"><a
        style="background: rgb(35, 35, 35); color: #ffffff; padding: 10px 50px; border-radius: 3px;"  
        href="' . $url . '">Cliquez pour réinitialiser</a></p>
            ';
            $mail->send();


            ###################   EMAIL SEND ###################

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
