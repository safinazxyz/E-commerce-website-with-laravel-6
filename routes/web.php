<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//view index
Route::get('/', 'IndexController@index');

//Category Listing page
Route::get('/products/{url}', 'ProductsController@products');

//Product Detail Page
Route::get('/product/{id}', 'ProductsController@product');

//Get Product Attribute Price
Route::any('/get-product-price', 'ProductsController@getProductPrice');

//Add to Cart Route
Route::match(['get', 'post'], '/add-cart', 'ProductsController@addtocart');

//Cart Route Page Blade
Route::match(['get', 'post'], '/cart', 'ProductsController@cart');

//Product Cart Delete Items
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct');

//Update Product Cart Items Quantity
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductsController@updateCartQuantity');

//Apply Coupon
Route::post('/cart/apply-coupon', 'ProductsController@applyCoupon');

//User Register/Login
Route::get('/login-register', 'UsersController@userLoginRegister');

//Forgot Password
Route::match(['get', 'post'], 'forgot-password', 'UsersController@forgotPassword');

//Users Register Form Submit
Route::post('/user-register', 'UsersController@register');

//Confirm Account
Route::get('confirm/{code}','UsersController@confirmAccount');

//User Login
Route::post('/user-login', 'UsersController@login');

//User Logout
Route::get('/user-logout', 'UsersController@logout');

//Search Products
Route::post('/search-products','ProductsController@searchProducts');

//All Routes after Login
Route::group(['middleware' => ['frontlogin']], function () {
    //User Account  Page
    Route::match(['get', 'post'], 'account', 'UsersController@account');
    //Check User Current Password
    Route::post('/check-user-pwd', 'UsersController@chkUserPassword');
    //Update User Password
    Route::post('/update-user-pwd', 'UsersController@updateUserPassword');
    //Checkout  Page
    Route::match(['get', 'post'], 'checkout', 'ProductsController@checkout');
    //Order Review Page
    Route::match(['get', 'post'], '/order-review', 'ProductsController@orderReview');
    //Place Order
    Route::match(['get', 'post'], '/place-order', 'ProductsController@placeOrder');
    //Thanks Page
    Route::get('/thanks', 'ProductsController@thanks');
    //Paypal Page
    Route::get('/paypal', 'ProductsController@paypal');
    //Users Orders Page
    Route::get('/user-orders', 'ProductsController@userOrders');
    //Users Ordered Products Detail Page
    Route::get('/orders/{id}', 'ProductsController@userOrderDetails');
});

//Check if User already exist
Route::match(['get', 'post'], '/check-email', 'UsersController@checkEmail');

Route::group(['middleware' => ['adminlogin']], function () {
    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings', 'AdminController@settings');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');

    //Categories Route (Admin)
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@create');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@edit');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@delete');
    Route::get('/admin/view-categories', 'CategoryController@view');

    //Products Route(Admin)
    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@create');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@edit');
    Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');
    Route::get('/admin/delete-product/{id}', 'ProductsController@delete');
    Route::get('/admin/view-products', 'ProductsController@view');
    Route::get('/admin/delete-alt-image/{id}', 'ProductsController@deleteAltImage');
    //ProductsAttributes Route(Admin)
    Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@createAttributes');
    Route::match(['get', 'post'], '/admin/edit-attributes/{id}', 'ProductsController@editAttributes');
    Route::match(['get', 'post'], '/admin/add-images/{id}', 'ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');
    //Coupons Routes
    Route::match(['get', 'post'], '/admin/add-coupon', 'CouponsController@create');
    Route::match(['get', 'post'], '/admin/edit-coupon/{id}', 'CouponsController@edit');
    Route::get('/admin/view-coupons', 'CouponsController@view');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@delete');
    //BANNER
    Route::match(['get', 'post'], '/admin/add-banner', 'BannersController@create');
    Route::match(['get', 'post'], '/admin/edit-banner/{id}', 'BannersController@edit');
    Route::match(['get', 'post'], '/admin/delete-banner/{id}', 'BannersController@delete');
    Route::get('/admin/view-banners', 'BannersController@view');
    //Admin Orders Route
    Route::get('/admin/view-orders', 'ProductsController@viewOrders');
    //Admin View Order Details
    Route::get('/admin/view-orders-detail/{id}', 'ProductsController@viewOrdersDetail');
    //Order Invoice
    Route::get('admin/view-order-invoice/{id}','ProductsController@viewOrderInvoice');
    //Update Order Status
    Route::post('/admin/update-order-status', 'ProductsController@updateOrderStatus');
    //Admin Users Route
    Route::get('/admin/view-users', 'UsersController@viewUsers');

    //Admin CMS PAGES
    Route::match(['get', 'post'], '/admin/add-cms-page', 'CmsController@create');
    Route::match(['get', 'post'], '/admin/edit-cms-page/{id}', 'CmsController@edit');
    Route::match(['get', 'post'], '/admin/delete-cms-page/{id}', 'CmsController@delete');
    Route::get('/admin/view-cms-pages', 'CmsController@view');
});

Route::get('/logout', 'AdminController@logout');

//Route for Display Front-End CMS Pages
Route::match(['get', 'post'], '/page/{url}', 'CmsController@cmsPage');