<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('mini_auth/login', 'MiniAuthController@onLogin');
Route::post('mini_auth/login_with_username', 'MiniAuthController@loginWithUsername');
Route::any('payment/paid_notify', 'OrderController@paidNotify')->name('paid-notify');
Route::any('ceshi','BeautyItemController@itemList');
Route::get('items', 'BeautyItemController@itemList');

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::post('update/shopper_info', 'MiniAuthController@updateShopperInfo');
    Route::post('register/mobile', 'MiniAuthController@mobileRegister');

    /**
     * update seller bank info
     *
     * @param $seller_name  - required: get from shopper->name
     * @param $bank_name  - sometimes
     * @param $bank_card_no  - required
     *
     * @return 422 | 200 with ['message' => 'xxx']
     */
    Route::post('me/bank_info', 'ShopperController@updateBankInfo');

    //Get my vouchers - params: card id
    Route::get('me/vouchers', 'VoucherController@shopperVouchers');
    //Reserve voucher - params: hospital_id, voucher_no, name, mobile
    Route::post('me/voucher/reserve', 'VoucherController@doReserve');
    //Use voucher - params: voucher_no, hospital_id
    Route::post('voucher/use', 'VoucherController@useVoucher');

    //member info
    Route::get('me/member', 'MiniAuthController@memberInfo');
    Route::get('me/qrcode', 'MiniAuthController@qrCode');

    //seller shoppers, default show 20 shoppers order by date desc, param: "page" start from 1
    Route::get('me/shoppers', 'SaleController@myShoppers');
    //seller withdraw history list, default show 20 records order by date desc, param: "page" start from 1
    Route::get('me/withdraws', 'SaleController@myWithdraws');
    //seller summary data (no detail list)
    Route::get('me/sales', 'SaleController@mySales');
    //request to withdraw
    Route::post('me/withdraw', 'SaleController@withdraw');

    //purchase order
    //do purchase to create order: params: "qty", "cardid"
    Route::get('request_order', 'OrderController@createOrder');
    //get card info such as image
    Route::get('card', 'CardController@cardInfo');
    //cards purchased list
    Route::get('me/cards', 'CardController@myCards');

    //beauty item list
    Route::get('items', 'BeautyItemController@itemList');

    /**
     * 获取医院列表
     *
     * @param  int  $lng  经度
     * @param  int  $lat  维度
     *
     * @return
     * [
     *      [
     *          'id': int,
     *          'name': string,
     *          'logo': url,
     *          'address': string,
     *          'description': url,             // 新版本不用, 保留给旧版本
     *          'desc': url,                    // 上面的医院介绍
     *          'environments': [ url, url ],   // 环境图片 轮播
     *          'experts': [ url, url ],        // 专家图片 轮播
     *          'map': url,                     // 地图, 点击跳转到微信地图
     *          'distance': string              // 和用户的距离, 如果传入经纬度会有这个值, 显示在列表里
     *      ]
     * ]
     *
     */
    Route::get('hospitals', 'HospitalController@index');

    // 变美需求选项
    Route::get('beauty_request_options', 'SettingController@beautyRequest');

    // 提交变美需求
    Route::post('beauty_request', 'BeautyRequestController@submit');

    /**
     * 权益券使用后评价
     *
     * @param  int  $voucher_no  权益券号码
     * @param  int  $rate  评分 (1-5)
     * @param  string  $comment  追加评价 (100字以内)
     */
    Route::post('assessment', 'AssessmentController@submit');


    /**
     * 兑换实体券
     *
     * $param string $password 实体券密码
     */
    Route::post('redeem', 'CouponController@redeem');

    /**
     * 获取邀请码
     */
    Route::post('invitation', 'InvitationController@create')->name('invitation.create');
    Route::get('invitation', 'InvitationController@show')->name('invitation.show');
    Route::post('invitation/accept', 'InvitationController@accept')->name('invitation.accept');
    Route::post('invitation/reject', 'InvitationController@reject')->name('invitation.reject');

    // seller list belongs to me
    Route::get('me/sellers', 'SaleController@mySellers');
    Route::get('me/sellers/{id}/sales', 'SaleController@mySellerSales');
});

Route::any('token/{id}', function ($id) {
    if (config('app.env') != 'local') {
        abort(404);
    }
    $shopper = \App\Models\Shopper::find($id);
    $token   = JWTAuth::fromUser($shopper);

    return response()->json([
        'token' => $token
    ]);
});
