<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\AuthController;
use App\Http\Controllers\site\CartController;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\site\VendorController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\site\AccountController;
use App\Http\Controllers\site\SupportController;
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
use App\Http\Controllers\admin\CollectionController;
use App\Http\Controllers\admin\TemoignageController;
use App\Http\Controllers\site\AccountPageController;
use App\Http\Controllers\site\ProductPageController;
use App\Http\Controllers\admin\SubCategoryController;

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

/********************************** Controller Site *********************************************************/


// Route::controller(AuthController::class)->group(function () {
//     route::get('/login', 'loginForm')->name('login-form');
//     route::post('/login', 'login')->name('login');
//     route::get('/register', 'registerForm')->name('register-form');
//     route::post('/register', 'register')->name('register');



//     /******Socialite route */

//     // La redirection vers le provider
//     Route::get("redirect/{provider}", 'redirect')->name('socialite.redirect');
//     // Le callback du provider
//     Route::get("callback/{provider}", 'callback')->name('socialite.callback');

//     /****** End Socialite route */

//     route::get('/logout', 'logout')->name('logout');
// });

// //support 
// Route::controller(SupportController::class)->group(function () {
//     route::get('/help', 'index')->name('help-index');
//     route::get('/becomeVendor', 'becomeVendor')->name('help-becomeVendor');
//     route::get('/privacyPolicy', 'privacyPolicy')->name('help-privacyPolicy');
//     route::get('/assistance', 'assistance')->name('help-assistance');
//     route::get('/about', 'about')->name('help-about');
// });

// Route::controller(AccountController::class)->group(function () {
//     route::get('/my-account', 'account')->name('my-account')->middleware(['auth']);
//     route::get('/my-profile/{id}', 'profile')->name('my-profile')->middleware(['auth']);
//     route::post('/my-profile/update/{id}', 'profile')->name('my-profile-update')->middleware(['auth']);
//     route::get('/my-order', 'order')->name('my-order')->middleware(['auth']);
//     route::get('/my-order/{id}', 'orderDetail')->name('detail-order')->middleware(['auth']);
// });


// //vendor 
// Route::controller(VendorController::class)->group(function () {
//     route::get('/vendor-order', 'vendorOrder')->name('vendor-order')->middleware(['auth']);
//     route::get('/vendor-order/{id}', 'vendorOrderDetail')->name('vendor-detail-order')->middleware(['auth']);
//     route::post('/vendor-available/{id}', 'vendorAvailable')->name('vendor-available')->middleware(['auth']);
// });




// Route::controller(SiteController::class)->group(function () {
//     if ((new \Jenssegers\Agent\Agent())->isDesktop()) {
//         Route::get('/', 'index')->name('index'); //frame mobile
//         Route::get('/home', 'home')->name('home');
//     } elseif ((new \Jenssegers\Agent\Agent())->isTablet()) {
//         Route::get('/', 'home')->name('home');
//     } elseif ((new \Jenssegers\Agent\Agent())->isMobile()) {
//         Route::get('/', 'home')->name('home');
//     }


//     Route::get('/category-list', 'categoryList')->name('category-list');
//     Route::get('/subcategory', 'subCategoryList')->name('subcategory-list');
//     Route::get('/product-detail/{id}', 'product_detail')->name('product-detail');
//     Route::get('/shop', 'shop')->name('shop');  //with parametre category ID
//     Route::get('product/q', 'searchProduct')->name('search');
// });


// //Cart route
// Route::get('cart', [CartController::class, 'cart'])->name('cart');
// Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
// Route::patch('update-cart', [CartController::class, 'update'])->name('update.cart');
// Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');
// Route::get('checkout', [CartController::class, 'checkout'])->middleware(['auth'])->name('checkout');
// Route::get('refresh-shipping/{id}', [CartController::class, 'refreshShipping']);
// Route::get('save-order', [CartController::class, 'storeOrder'])->name('store.order')->middleware(['auth']);




/******************************************Route  Admin****************************** **/
##login  for dashboard
Route::controller(AuthAdminController::class)->group(function () {
    route::get('/sign-in', 'login')->name('auth.login');
    route::post('/sign-in', 'login')->name('auth.login');
});

Route::middleware(['admin'])->group(function () {

    //Dashboard
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        route::get('', 'index')->name('dashboard.index');
    });

    //Setting
    Route::prefix('admin/setting')->controller(SettingController::class)->group(function () {
        route::get('', 'index')->name('setting.index');
        route::post('setting/store', 'store')->name('setting.store');

    });

    //Auth admin
    Route::prefix('admin/auth')->controller(AuthAdminController::class)->group(function () {
        route::get('', 'listUser')->name('user.list');
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

    /** Collection **/
    Route::prefix('admin/collection')->controller(CollectionController::class)->group(function () {
        route::get('', 'index')->name('collection.index');
        route::post('', 'store')->name('collection.store');
        route::get('edit/{id}', 'edit')->name('collection.edit');
        route::post('update/{id}', 'update')->name('collection.update');
        route::post('destroy/{id}', 'destroy')->name('collection.destroy');
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
        Route::get('show/{id}', 'showOrder')->name('order.show');
        Route::get('invoice/{id}', 'invoice')->name('order.invoice');
        Route::get('changeState', 'changeState')->name('order.changeState');
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
