<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\Collection;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::statement("SET lc_time_names = 'fr_FR'");


        /****************************start publicite */

        //declenchez la promo publicitaire
        $publicite = Publicite::whereType('top-promo')->whereStatus('active')->first();

        if ($publicite) {
            $status_pub = ''; // bientot , en cour, termine
            $status = '';  // active ,  desactive
            if ($publicite['date_debut_pub'] > Carbon::now()) {
                $status_pub = 'bientot';
                $status = 'active';
            } elseif ($publicite['date_fin_pub'] < Carbon::now()) {
                $status_pub = 'termine';
                $status = 'desactive';
            } else {
                $status_pub = 'en cour';
                $status = 'active';
            }


            Publicite::whereType('top-promo')->whereStatus('active')
                ->update([
                    'status' => $status,
                    'status_pub' => $status_pub,
                ]);
        }

        /****************************end publicite */




        /****************************start product remise */

        //declenchez la promo product
        $remise = Product::where('montant_remise', '!=', null)->get();

        if ($remise) {
            $status_remise = ''; // bientot , en cour, termine
            $montant_remise = '';
            $date_debut_remise = '';
            $date_fin_remise = '';

            foreach ($remise as $value) {
                if ($value['date_debut_remise'] > Carbon::now()) {
                    $status_remise = 'bientot';
                } elseif ($value['date_fin_remise'] < Carbon::now()) {
                    $status_remise = 'termine';
                } else {
                    $status_remise = 'en cour';
                }

                Product::whereId($value['id'])
                    ->update([
                        'status_remise' => $status_remise,
                    ]);
            }
        }

        /****************************end product remise */


        /****************************coupon de reduction */
        //declenchez le coupon de reduction
        $coupon = Coupon::get();

        if ($coupon) {
            $status_coupon = ''; // bientot , en cour, termine
            $date_debut_coupon = '';
            $date_fin_coupon = '';

            foreach ($coupon as $value) {
                if ($value['date_debut'] > Carbon::now()) {
                    $status_coupon = 'bientot';
                } elseif ($value['date_fin'] < Carbon::now()) {
                    $status_coupon = 'terminer';
                } else {
                    $status_coupon = 'en_cours';
                }
                // update status
                Coupon::whereId($value['id'])
                    ->update([
                        'status' => $status_coupon,
                    ]);
            }

            //delete coupon status ==terminé
            // Coupon::where('status', 'termine')->delete();
        }



        //update user role
        //user have not order
        $users = User::withCount(['roles', 'orders'])
            ->whereNotIn('role', ['developpeur', 'administrateur', 'gestionnaire'])
            // ->having('orders_count', '=', 1)
            // ->whereHas('roles', fn ($q) => $q->where('name', '!=', 'developpeur'))
            ->orderBy('created_at', 'DESC')->get();

        // dd($users->toArray());
        $now = Carbon::now()->format('m');
        // dd($now);
        foreach ($users as $key => $value) {


            if ($value['orders_count'] == 0) {
                // $value->syncRoles('prospect');
                User::whereId($value['id'])->update(['role' => 'prospect']);
            }
            foreach ($value['orders'] as $key => $order) {
                $order_month = Carbon::parse($order['date_order'])->format('m');
                if ($value['orders_count'] > 0 && $order_month == $now) {
                    User::whereId($value['id'])->update(['role' => 'fidele']);
                }
            }
        }

        ################################################  NOTIFY BIRTHDAY FUNCTION #####################

        // notify_birthday 
        $now = Carbon::now()->endOfDay();

        $user = User::get();
        foreach ($user as $key => $value) {
            $Y = date('Y');
            $nex_date = $value['date_anniversaire'] . '-' . $Y;
            $date_birthday = Carbon::parse($nex_date)->endOfDay();
            $date_diff = $now->diffInDays($date_birthday, false);
            // dd($date_diff);
            //update diff date in notify birthday
            User::whereId($value['id'])->update(['notify_birthday' => $date_diff]);
        }



        $user_upcoming_birthday = User::where('notify_birthday', 2)->OrWhere('notify_birthday', 1)->get(); // anniversaire a venir dans 2 jours
        $user_birthday = User::where('notify_birthday', 0)->get(); // anniversaire du jour 


        ###SEND MAIL TO ADMIN USER IF BIRTHDAY DATE ARRIVED ###

        // if (count($user_upcoming_birthday) > 0) {
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

        //     $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
        //     $mail->addAddress('alexkouamelan96@gmail.com');

        //     $mail->isHTML(true);


        //     $mail->Subject = 'Anniversaire';
        //     $mail->Body =
        //         '<b> Bonjour, C\'est bientot l\'anniversaire de vos client   <b>';

        //     $mail->send();
        // }

        ######################################################### //new send mail with phpMailer










        //category frontend
        $category = Category::with([
            'products' => fn ($q) => $q->whereDisponibilite(1)->orderBy('created_at', 'DESC')->get(), 'media', 'subcategories'
        ])
            ->whereNotIn('name', ['Pack'])
            ->orderBy('created_at', 'DESC')->get();
        // dd($category->toArray());

        //category backend
        $category_backend = Category::with([
            'products' => fn ($q) => $q->orderBy('created_at', 'DESC')->get(), 'media', 'subcategories'
        ])
            ->orderBy('created_at', 'DESC')->get();
        // dd($category->toArray());

        $subcategory = SubCategory::with(['products', 'media', 'category'])->orderBy('name', 'ASC')->get();



        $roles = Role::where('name', '!=', 'developpeur')->get();
        $roleWithoutClient = Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get();



        /*********************ORDER GET */

        $orders_attente = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente'])
            ->get();


        $orders_new = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente', 'precommande'])
            ->get();

        //information from publicite
        // recuperer les informations publicité
        $information = Publicite::with('media')->whereType('information')->whereStatus('active')->first();


        View::composer('*', function ($view) use (
            $category,
            $subcategory,
            $roles,
            $category_backend,
            $orders_attente,
            $orders_new,
            $roleWithoutClient,
            $information,
            $user_upcoming_birthday,
            $user_birthday
        ) {
            $view->with([
                'information' => $information,
                'categories' => $category,
                'subcategories' => $subcategory,
                'roles' => $roles,
                'roleWithoutClient' => $roleWithoutClient,

                'category_backend' => $category_backend,
                'orders_attente' => $orders_attente,
                'orders_new' => $orders_new,
                'user_upcoming_birthday' => $user_upcoming_birthday,
                'user_birthday' => $user_birthday,


            ]);
        });
    }
}
