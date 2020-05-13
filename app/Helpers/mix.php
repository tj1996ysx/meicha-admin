<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/8/29
 * Time: 6:08 PM
 */

use EasyWeChat\Kernel\Http\StreamResponse;
use Intervention\Image\Facades\Image;

if (!function_exists('wechat_shopper')) {
    function wechat_shopper()
    {
        $wechat_user = session('wechat.mini_auth_user.default');

        if ($wechat_user) {
            $shopper = \App\Models\Shopper::where('open_id', $wechat_user->getId())->first();
            session('wechat.mini_auth_shopper', $shopper);
        }

        return session('wechat.mini_auth_shopper');
    }
}

/**
 * 根据起点坐标和终点坐标测距离
 *
 * @param  [array]   $from    [起点坐标(经纬度),例如:array(118.012951,36.810024)]
 * @param  [array]   $to    [终点坐标(经纬度)]
 * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
 * @param  [int]     $decimal   精度 保留小数位数
 *
 * @return [string]  距离数值
 */
if (!function_exists('get_distance')) {
    function get_distance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[ 0 ] * pi() / 180 - $to[ 0 ] * pi() / 180) / 2), 2) + cos($from[ 0 ] * pi() / 180) * cos($to[ 0 ] * pi() / 180) * pow(sin(($from[ 1 ] * pi() / 180 - $to[ 1 ] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}

if (!function_exists('mini_qrcode')) {
    function mini_qrcode($code) {

        if (file_exists(public_path('/mini_codes'). '/appcode_'.$code.'.png')) {
            return url('mini_codes/appcode_'.$code.'.png');
        }

        $mini_app = app('wechat.mini_program');
        $response = $mini_app->app_code->get('pages/oauth/main?refer='.$code);

        if ($response instanceof StreamResponse) {
            $filename = $response->saveAs(public_path('/mini_codes'), 'appcode_'.$code.'.png');
            $image    = Image::make('mini_codes/'.$filename);
            return url('mini_codes/appcode_'.$code.'.png');
        }
        return null;
    }
}
