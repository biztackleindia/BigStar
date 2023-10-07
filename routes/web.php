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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

//for maintenance mode
Route::get('/', 'Web\WebController@web_maintenance_mode')->name('maintenance-mode');
Route::get('/user/delete', 'HomeController@DeleteUser')->name('userDelete');
Route::post('/user/delete', 'HomeController@DeleteUserPost')->name('userDeletePost');
Route::get('/home', 'Web\WebController@web_maintenance_mode')->name('home');
Route::get('terms', 'Web\WebController@termsandCondition')->name('terms');
Route::get('privacy-policy', 'Web\WebController@privacy_policy')->name('privacy-policy');

Route::group(['middleware'=>['web_error']], function () {



Route::group(['namespace' => 'Web','middleware'=>['maintenance_mode']], function () {
    Route::get('quick-view', 'WebController@quick_view')->name('quick-view');
    Route::get('searched-products', 'WebController@searched_products')->name('searched-products');
    Route::get('custom-view', 'WebController@CustomView')->name('custom-view');
    Route::get('size-chart', 'WebController@SizeChart')->name('size-chart');
    

    Route::group(['middleware'=>['customer']], function () {
        Route::get('checkout-details', 'WebController@checkout_details')->name('checkout-details');
        Route::get('checkout-shipping', 'WebController@checkout_shipping')->name('checkout-shipping')->middleware('customer');
        Route::get('checkout-payment', 'WebController@checkout_payment')->name('checkout-payment')->middleware('customer');
        Route::get('callback', 'WebController@callback')->name('callback')->middleware('customer');
        Route::get('checkout-review', 'WebController@checkout_review')->name('checkout-review')->middleware('customer');
        Route::get('checkout-complete', 'WebController@checkout_complete')->name('checkout-complete')->middleware('customer');
        Route::get('order-placed', 'WebController@order_placed')->name('order-placed')->middleware('customer');
        Route::get('shop-cart', 'WebController@shop_cart')->name('shop-cart');
        Route::post('order_note', 'WebController@order_note')->name('order_note');
        

        
    });
    // third party api for shipping cost
    // Route::get('shipping_cost', 'WebController@WeightWithShippingCost')->name('shipping_cost');
    Route::post('user_size_store', 'WebController@UserPersonalSize')->name('user_size_store');
    Route::post('subscription', 'WebController@subscription')->name('subscription');
    Route::get('search-shop', 'WebController@search_shop')->name('search-shop');

    Route::get('categories', 'WebController@all_categories')->name('categories');
    Route::get('category-ajax/{id}', 'WebController@categories_by_category')->name('category-ajax');
    // pincode checking
    Route::post('avilable-pincode', 'WebController@PincodeChecking')->name('avilable_pincode');
    // 
    Route::post('approve_msg', 'WebController@ApproveMsg')->name('approve_msg');
    Route::get('brands', 'WebController@all_brands')->name('brands');
    Route::get('sellers', 'WebController@all_sellers')->name('sellers');
    Route::get('seller-profile/{id}', 'WebController@seller_profile')->name('seller-profile');

    Route::get('flash-deals/{id}', 'WebController@flash_deals')->name('flash-deals');
    

    Route::get('/product/{slug}', 'WebController@product')->name('product');
    Route::get('products', 'WebController@products')->name('products');
    Route::get('orderDetails', 'WebController@orderdetails')->name('orderdetails');
    Route::get('discounted-products', 'WebController@discounted_products')->name('discounted-products');

    Route::post('review-list-product','WebController@review_list_product')->name('review-list-product');
    //Chat with seller from product details
    Route::get('chat-for-product', 'WebController@chat_for_product')->name('chat-for-product');

    Route::get('wishlists', 'WebController@viewWishlist')->name('wishlists')->middleware('customer');
    Route::post('store-wishlist', 'WebController@storeWishlist')->name('store-wishlist');
    Route::post('delete-wishlist', 'WebController@deleteWishlist')->name('delete-wishlist');

    Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

    Route::get('about-us', 'WebController@about_us')->name('about-us');

    //profile Route
    Route::get('user-account', 'UserProfileController@user_account')->name('user-account');
    Route::post('user-account-update', 'UserProfileController@user_update')->name('user-update');
    Route::post('user-account-picture', 'UserProfileController@user_picture')->name('user-picture');
    Route::get('account-address', 'UserProfileController@account_address')->name('account-address');
    Route::post('account-address-store', 'UserProfileController@address_store')->name('address-store');
    Route::get('account-address-delete', 'UserProfileController@address_delete')->name('address-delete');
    ROute::get('account-address-edit/{id}','UserProfileController@address_edit')->name('address-edit');
    Route::post('account-address-update', 'UserProfileController@address_update')->name('address-update');
    Route::get('account-payment', 'UserProfileController@account_payment')->name('account-payment');
    Route::get('account-oder', 'UserProfileController@account_oder')->name('account-oder');
    Route::get('account-order-details', 'UserProfileController@account_order_details')->name('account-order-details')->middleware('customer');
    Route::get('generate-invoice/{id}', 'UserProfileController@generate_invoice')->name('generate-invoice');
    Route::get('account-wishlist', 'UserProfileController@account_wishlist')->name('account-wishlist'); //add to card not work
    Route::get('refund-request/{id}','UserProfileController@refund_request')->name('refund-request');
    Route::get('refund-details/{id}','UserProfileController@refund_details')->name('refund-details');
    Route::get('submit-review/{id}','UserProfileController@submit_review')->name('submit-review');
    Route::post('refund-store','UserProfileController@store_refund')->name('refund-store');
    Route::get('account-tickets', 'UserProfileController@account_tickets')->name('account-tickets');
    Route::get('order-cancel/{id}', 'UserProfileController@order_cancel')->name('order-cancel');
    Route::post('ticket-submit', 'UserProfileController@ticket_submit')->name('ticket-submit');

    // Chatting start
    Route::get('chat-with-seller', 'ChattingController@chat_with_seller')->name('chat-with-seller');
    Route::get('messages', 'ChattingController@messages')->name('messages');
    Route::post('messages-store', 'ChattingController@messages_store')->name('messages_store');
    // chatting end

    //Support Ticket
    Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.'], function () {
        Route::get('{id}', 'UserProfileController@single_ticket')->name('index');
        Route::post('{id}', 'UserProfileController@comment_submit')->name('comment');
        Route::get('delete/{id}', 'UserProfileController@support_ticket_delete')->name('delete');
        Route::get('close/{id}', 'UserProfileController@support_ticket_close')->name('close');
    });

    Route::get('account-transaction', 'UserProfileController@account_transaction')->name('account-transaction');
    Route::get('account-wallet-history', 'UserProfileController@account_wallet_history')->name('account-wallet-history');

    Route::post('review', 'ReviewController@store')->name('review.store');

    Route::group(['prefix' => 'track-order', 'as' => 'track-order.'], function () {
        Route::get('', 'UserProfileController@track_order')->name('index');
        Route::get('result-view', 'UserProfileController@track_order_result')->name('result-view');
        Route::get('last', 'UserProfileController@track_last_order')->name('last');
        Route::any('result', 'UserProfileController@track_order_result')->name('result');
    });
    //FAQ route
    Route::get('helpTopic', 'WebController@helpTopic')->name('helpTopic');
    //Contacts
    Route::get('contacts', 'WebController@contacts')->name('contacts');

    //sellerShop
    Route::get('shopView/{id}', 'WebController@seller_shop')->name('shopView');
    Route::post('shopView/{id}', 'WebController@seller_shop_product');

    //top Rated
    Route::get('top-rated', 'WebController@top_rated')->name('topRated');
    Route::get('best-sell', 'WebController@best_sell')->name('bestSell');
    Route::get('new-product', 'WebController@new_product')->name('newProduct');

    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::post('store', 'WebController@contact_store')->name('store');
        Route::get('/code/captcha/{tmp}', 'WebController@captcha')->name('default-captcha');
    });
});

//Seller shop apply
Route::group(['prefix' => 'shop', 'as' => 'shop.', 'namespace' => 'Seller\Auth'], function () {
    Route::get('apply', 'RegisterController@create')->name('apply');
    Route::post('apply', 'RegisterController@store');

});

//check done
Route::group(['prefix' => 'cart', 'as' => 'cart.', 'namespace' => 'Web'], function () {
    Route::post('variant_price', 'CartController@variant_price')->name('variant_price');
    Route::post('add', 'CartController@addToCart')->name('add');
    Route::post('remove', 'CartController@removeFromCart')->name('remove');
    Route::post('nav-cart-items', 'CartController@updateNavCart')->name('nav-cart');
    Route::post('updateQuantity', 'CartController@updateQuantity')->name('updateQuantity');
});

//Seller shop apply
Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'namespace' => 'Web'], function () {
    Route::post('apply', 'CouponController@apply')->name('apply');
});
});