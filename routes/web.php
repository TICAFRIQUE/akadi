<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\site\AuthPageController;
use App\Http\Controllers\site\CartPageController;
use App\Http\Controllers\site\HomePageController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DeliveryController;
use App\Http\Controllers\admin\AuthAdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\PubliciteController;
use App\Http\Controllers\admin\TemoignageController;
use App\Http\Controllers\site\AccountPageController;
use App\Http\Controllers\site\ProductPageController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\depense\DepenseController;
use App\Http\Controllers\admin\depense\LibelleDepenseController;
use App\Http\Controllers\admin\depense\CategorieDepenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



##login  for dashboard
Route::controller(AuthAdminController::class)->group(function () {
    route::get('/sign-in', 'login')->name('auth.login');
    route::post('/sign-in', 'login')->name('auth.login');
});

Route::middleware(['admin'])->group(function () {

    //Dashboard
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        route::get('', 'index')->name('dashboard.index');
        route::get('product-statistic', 'product_statistic')->name('dashboard.product-statistic');
        route::get('order-period', 'order_period')->name('dashboard.order-period');
        route::get('revenu-period', 'revenu_period')->name('dashboard.revenu-period'); // chiffre d'affaire
        route::get('check-new-order', 'checkNewOrder')->name('dashboard.checkNewOrder');
    });

    //Setting
    Route::prefix('admin/setting')->controller(SettingController::class)->group(function () {
        route::get('', 'index')->name('setting.index');
        route::post('setting/store', 'store')->name('setting.store');
    });

    //Auth admin
    Route::prefix('admin/auth')->controller(AuthAdminController::class)->group(function () {
        route::get('', 'listUser')->name('user.list');
        route::get('detail/{id}', 'userDetail')->name('user.detail');

        // route::get('typeClient', 'typeClient')->name('user.typeClient');

        route::get('register', 'registerForm')->name('user.registerForm');
        route::post('register', 'register')->name('user.register');
        route::get('edit/{id}', 'edit')->name('user.edit');
        route::post('update/{id}', 'update')->name('user.update');
        route::post('destroy/{id}', 'destroy')->name('user.destroy');
        route::get('logout', 'logout')->name('user.logout');
    });


    /** Category **/
    Route::prefix('admin/categorie')->controller(CategoryController::class)->group(function () {
        route::get('', 'index')->name('category.index');
        route::post('', 'store')->name('category.store');
        route::get('edit/{id}', 'edit')->name('category.edit');
        route::post('update/{id}', 'update')->name('category.update');
        route::post('destroy/{id}', 'destroy')->name('category.destroy');
    });

    /***Sous category */
    Route::prefix('admin/sous-categorie')->controller(SubCategoryController::class)->group(function () {
        route::get('', 'index')->name('sub-category.index');
        route::post('', 'store')->name('sub-category.store');
        route::get('edit/{id}', 'edit')->name('sub-category.edit');
        route::post('update/{id}', 'update')->name('sub-category.update');
        route::post('destroy/{id}', 'destroy')->name('sub-category.destroy');
    });



    /** Delivery **/
    Route::prefix('admin/livraison')->controller(DeliveryController::class)->group(function () {
        route::get('', 'index')->name('delivery.index');
        route::post('', 'store')->name('delivery.store');
        route::get('edit/{id}', 'edit')->name('delivery.edit');
        route::post('update/{id}', 'update')->name('delivery.update');
        route::post('destroy/{id}', 'destroy')->name('delivery.destroy');
    });

    /** Product **/
    Route::prefix('admin/produit')->controller(ProductController::class)->group(function () {
        route::get('', 'index')->name('product.index');
        route::get('add', 'create')->name('product.create');
        route::post('add', 'store')->name('product.store');
        route::get('loadSubCat/{id}', 'loadSubcat')->name('product.loadSubcat');
        route::get('edit/{id}', 'edit')->name('product.edit');
        route::get('deleteImage/{id}', 'deleteImage');
        route::post('update/{id}', 'update')->name('product.update');
        route::post('destroy/{id}', 'destroy')->name('product.destroy');
        route::post('availableProduct/{id}', 'availableProduct')->name('product.available');
    });

    //orders
    route::prefix('admin/order')->controller(OrderController::class)->group(function () {
        Route::get('/', 'getAllOrder')->name('order.index');
        Route::get('/filter', 'filter')->name('order.filter');
        Route::get('show/{id}', 'showOrder')->name('order.show');
        Route::get('invoice/{id}', 'invoice')->name('order.invoice');
        Route::get('changeState', 'changeState')->name('order.changeState');
        Route::post('orderCancel', 'orderCancel')->name('order.orderCancel');
    });

    //publicite


    Route::prefix('admin/publicite')->controller(PubliciteController::class)->group(function () {
        route::get('', 'index')->name('publicite.index');
        route::post('', 'store')->name('publicite.store');
        route::get('edit/{id}', 'edit')->name('publicite.edit');
        route::post('update/{id}', 'update')->name('publicite.update');
        route::get('changeState', 'changeState')->name('publicite.changeState'); // activer , desactiver une publicite
        route::post('destroy/{id}', 'destroy')->name('publicite.destroy');
    });
});


/** Feedback **/
Route::prefix('admin/temoignage')->controller(TemoignageController::class)->group(function () {
    route::get('', 'index')->name('temoignage.index');
    route::post('', 'store')->name('temoignage.store');
    route::get('edit/{id}', 'edit')->name('temoignage.edit');
    route::post('update/{id}', 'update')->name('temoignage.update');
    route::post('destroy/{id}', 'destroy')->name('temoignage.destroy');
});


/** Coupon de reduction **/
Route::prefix('admin/coupon')->controller(CouponController::class)->group(function () {
    route::get('', 'index')->name('coupon.index');
    route::get('create', 'create')->name('coupon.create');
    route::post('', 'store')->name('coupon.store');
    // route::get('edit/{id}', 'edit')->name('temoignage.edit');
    // route::post('update/{id}', 'update')->name('temoignage.update');
    route::post('destroy/{id}', 'destroy')->name('coupon.destroy');
    route::get('/{id}/pdf', 'generateCouponPdf')->name('coupon.pdf');
});


Route::prefix('admin/categorie-depense')->controller(CategorieDepenseController::class)->group(function () {
    route::get('', 'index')->name('categorie-depense.index');
    route::get('create', 'create')->name('categorie-depense.create');
    route::post('store', 'store')->name('categorie-depense.store');
    route::get('edit/{id}', 'edit')->name('categorie-depense.edit');
    route::post('update/{id}', 'update')->name('categorie-depense.update');
    route::post('destroy/{id}', 'delete')->name('categorie-depense.delete');
    route::post('position/{id}', 'position')->name('categorie-depense.position');
});


Route::prefix('admin/libelle-depense')->controller(LibelleDepenseController::class)->group(function () {
    route::get('', 'index')->name('libelle-depense.index');
    route::get('create', 'create')->name('libelle-depense.create');
    route::post('store', 'store')->name('libelle-depense.store');
    route::get('edit/{id}', 'edit')->name('libelle-depense.edit');
    route::post('update/{id}', 'update')->name('libelle-depense.update');
    route::post('destroy/{id}', 'delete')->name('libelle-depense.delete');
    route::post('position/{id}', 'position')->name('libelle-depense.position');
});

Route::prefix('admin/depense')->controller(DepenseController::class)->group(function () {
    route::get('', 'index')->name('depense.index');
    route::get('create', 'create')->name('depense.create');
    route::post('store', 'store')->name('depense.store');
    route::get('edit/{id}', 'edit')->name('depense.edit');
    route::post('update/{id}', 'update')->name('depense.update');
    route::post('destroy/{id}', 'delete')->name('depense.delete');
});


// rapport exploitation
Route::prefix('admin/rapport')->controller(RapportController::class)->group(function () {
    route::get('', 'exploitation')->name('rapport.exploitation');
    route::get('detail-depense', 'detail_depense')->name('rapport.detail');

});




/***************************************** Route Site ********************************************/

Route::controller(HomePageController::class)->group(function () {
    route::get('/', 'page_acceuil')->name('page-acceuil');
});

Route::controller(ProductPageController::class)->group(function () {
    route::get('produit/detail/{slug}', 'detail_produit')->name('detail-produit');
    route::get('produit', 'liste_produit')->name('liste-produit');
    route::get('commentaire', 'commentaire')->name('commentaire')->middleware('auth');
    Route::get('produit/q', 'recherche')->name('recherche');
});


// //Cart route
Route::get('panier', [CartPageController::class, 'panier'])->name('panier');
Route::get('add-to-cart/{id}', [CartPageController::class, 'addToCart'])->name('add.to.cart');
Route::patch('update-cart', [CartPageController::class, 'update'])->name('update.cart');
Route::delete('remove-from-cart', [CartPageController::class, 'remove'])->name('remove.from.cart');
Route::get('finaliser-ma-commande', [CartPageController::class, 'checkout'])->name('checkout')->middleware(['auth']);
Route::get('refresh-shipping/{id}', [CartPageController::class, 'refreshShipping'])->middleware(['auth']);
Route::get('refresh-coupon/{id}', [CartPageController::class, 'refreshCoupon'])->middleware(['auth']);
Route::get('check-coupon/{code}', [CartPageController::class, 'checkCoupon'])->middleware(['auth']);

Route::get('save-order', [CartPageController::class, 'storeOrder'])->name('store.order')->middleware(['auth']);


//Authentification user
Route::controller(AuthPageController::class)->group(function () {
    route::get('/se-connecter', 'login')->name('login-form');
    route::post('/se-connecter', 'login')->name('login');
    route::get('/inscription', 'register')->name('register-form');
    route::post('/inscription', 'register')->name('register');
    route::get('/mes-commandes', 'userOrder')->name('user-order');
    route::get('/se-deconnecter', 'logout')->name('logout');

    //forget password
    Route::get('forget-password', 'showForgetPasswordForm')->name('forget.password.get');
    Route::post('forget-password',  'submitForgetPasswordForm')->name('forget.password.post');
    Route::get('reset-password', 'showResetPasswordForm')->name('reset.password.get');
    Route::post('reset-password', 'submitResetPasswordForm')->name('reset.password.post');
});




//Authentification user
Route::controller(AccountPageController::class)->group(function () {
    route::get('/mes-commandes', 'userOrder')->name('user-order')->middleware(['auth']);
    route::post('/annuler-commande/{id}', 'cancelOrder')->name('cancel-order')->middleware(['auth']);
    route::get('/mon-profil', 'profil')->name('user-profil')->middleware(['auth']);
});
