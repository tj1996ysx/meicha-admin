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

use App\Models\Permission;

Route::get('/', 'PageController@home');

Route::any('/wechat', 'WechatController@serve');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

    Route::group(['middleware' => ['permission:'.Permission::SHOPPER_MANAGEMENT]], function () {
        CRUD::resource('shoppers', 'ShopperCrudController');
        Route::get('shopper/mini_qr/{code?}', 'ShopperCrudController@qrCode');
    });

    Route::group(['middleware' => ['permission:'.Permission::RESERVE_MANAGEMENT]], function () {
        //reservations
        CRUD::resource('reservations', 'ReservationCrudController');
        Route::post('reservations/bulk-reserve', 'ReservationCrudController@batchReserve');
        Route::get('reservation/fetch', 'ReservationCrudController@getReservationList');
        Route::post('reservation/read', 'ReservationCrudController@markRead');
        Route::get('reservation/{id}/cancel', 'ReservationCrudController@cancel')->name('reservation.cancel');
        CRUD::resource('shopper_vouchers', 'ShopperVoucherCrudController');

        //requests
        CRUD::resource('beauty_requests', 'BeautyRequestCrudController');
    });

    Route::group(['middleware' => ['permission:'.Permission::DATA_MANAGEMENT]], function () {

        //vouchers
        CRUD::resource('vouchers', 'VoucherCrudController');
        CRUD::resource('memberships', 'MembershipCrudController');
        CRUD::resource('hospitals', 'HospitalCrudController');
        CRUD::resource('beauty_items', 'BeautyItemCrudController');
        CRUD::resource('system_logs', 'SystemLogCrudController');
        CRUD::resource('assessment', 'AssessmentCrudController');
        CRUD::resource('coupon_batch', 'CouponBatchCrudController');
        CRUD::resource('coupon_batch/{coupon_batch_id}/coupon', 'CouponCrudController');
        CRUD::resource('coupon', 'CouponCrudController');
        CRUD::resource('invitation', 'InvitationCrudController');

        /**
         * 分配兑换券
         */
        Route::post('coupon/distribute', 'CouponCrudController@distribute')->name('coupon.distribute');
        Route::post('coupon/recover', 'CouponCrudController@recover')->name('coupon.recover');
        Route::post('coupon/disable', 'CouponCrudController@disable')->name('coupon.disable');
        Route::post('coupon/enable', 'CouponCrudController@enable')->name('coupon.enable');
    });

    Route::group(['middleware' => ['permission:'.Permission::SYSTEM_MANAGEMENT]], function () {
        //orders
        CRUD::resource('orders', 'OrderCrudController');
        //rebate
        CRUD::resource('seller_rebates', 'SellerRebateCrudController');
        CRUD::resource('seller_withdraws', 'SellerWithdrawCrudController');
    });

});

