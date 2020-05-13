<?php

namespace App\Http\Controllers;

use App\Models\MemberCard;
use App\Models\Shopper;
use EasyWeChat\Kernel\Http\StreamResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
use Overtrue\Socialite\User;

class MiniAuthController extends ApiController
{

    /**
     * submit login
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function onLogin(Request $request)
    {
        $code        = $request->input('code', '');
        $refer       = $request->input('refer', '');
        $mini_app    = app('wechat.mini_program');
        $certificate = $mini_app->auth->session($code);

        $return_code = Arr::get($certificate, 'errcode', 0);
        if (0 == $return_code) {
            $open_id     = Arr::get($certificate, 'openid');
            $session_key = Arr::get($certificate, 'session_key');
            $union_id    = Arr::get($certificate, 'unionid');

            $shopper = Shopper::where('open_id', $open_id)->first();
            if ($shopper) {
                $shopper->session_key = $session_key;
                $shopper->union_id    = $union_id;
                $shopper->save();
            } else {

                //refer only for the first create
                $source_shopper_id = 0;
                if ($refer) {
                    $refer_shopper     = Shopper::where('refer_code', $refer)->first();
                    $source_shopper_id = $refer_shopper ? $refer_shopper->id : 0;
                }
                $info    = [
                    'open_id'           => $open_id,
                    'session_key'       => $session_key,
                    'union_id'          => $union_id,
                    'source_shopper_id' => $source_shopper_id
                ];
                $shopper = Shopper::create($info);
            }

            $token       = auth('api')->login($shopper);
            $member_info = $this->getMemberInfoByShopper($shopper);

            return response()->json(array_merge([
                'token'             => $token,
                'role'              => $shopper->role,
                'user'              => $shopper,
                'info_required'     => $shopper->nickname ? 0 : 1,
                'mobile_required'   => $shopper->mobile ? 0 : 1,
                'password_required' => true
            ], $member_info));
        } else {
            switch ($return_code) {
                case -1:
                    $message = '系统繁忙，请稍候再试';
                    break;
                case 40029:
                    $message = 'code 无效';
                    break;
                case 45011:
                    $message = '请求频率太高，已限制';
                    break;
                default:
                    $message = '未知错误';
                    break;
            }

            return response()->json(['message' => $message, 'state' => $return_code], 403);
        }
    }

    public function loginWithUsername(Request $request)
    {
        $code        = $request->input('code', '');
        $mini_app    = app('wechat.mini_program');
        $certificate = $mini_app->auth->session($code);

        $return_code = Arr::get($certificate, 'errcode', 0);
        if (0 != $return_code) {
            return $this->fail('授权失败');
        }
        $open_id     = Arr::get($certificate, 'openid');
        $session_key = Arr::get($certificate, 'session_key');
        $union_id    = Arr::get($certificate, 'unionid');

        if (auth('api')->attempt($request->only(['username', 'password']))) {
            $shopper = auth('api')->user();

            // delete other account
            Shopper::where('open_id', $open_id)
                ->where('username', '<>', $request->username)
                ->delete();

            $shopper->open_id     = $open_id;
            $shopper->session_key = $session_key;
            $shopper->union_id    = $union_id;
            $shopper->save();

            $token = auth('api')->login($shopper);

            $member_info = $this->getMemberInfoByShopper($shopper);

            return response()->json(array_merge([
                'token'           => $token,
                'user'            => $shopper,
                'info_required'   => $shopper->nickname ? 0 : 1,
                'mobile_required' => $shopper->mobile ? 0 : 1,
            ], $member_info));
        } else {
            return $this->fail('用户名或密码错误', 401);
        }
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     */
    public function updateShopperInfo(Request $request)
    {
        $info              = $request->all();
        $shopper           = auth('api')->user();
        $shopper->nickname = Arr::get($info, 'nickName');
        $shopper->gender   = 1 == Arr::get($info, 'gender') ? 'male' : 'female';
        $shopper->language = Arr::get($info, 'language');
        $shopper->city     = Arr::get($info, 'city');
        $shopper->province = Arr::get($info, 'province');
        $shopper->country  = Arr::get($info, 'country');
        $shopper->avatar   = Arr::get($info, 'avatarUrl');
        $shopper->save();

        return response()->json(['status' => 'ok', 'user' => $shopper]);
    }

    /**
     * register the mobile
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mobileRegister(Request $request)
    {
        $encrypt_data = $request->input('encryptedData');
        $iv           = $request->input('iv');

        $mini_app     = app('wechat.mini_program');
        $shopper      = auth('api')->user();
        $session_key  = $shopper->session_key;
        $decrypt_data = $mini_app->encryptor->decryptData($session_key, $iv, $encrypt_data);

        if ($phone_number = Arr::get($decrypt_data, 'phoneNumber', null)) {
            $shopper->country_code = Arr::get($decrypt_data, 'countryCode');
            $shopper->mobile       = Arr::get($decrypt_data, 'purePhoneNumber');
            $shopper->save();

            return response()->json(['message' => '手机号注册成功', 'user' => $shopper]);
        } else {
            return response()->json(['message' => '手机号注册失败'], 403);
        }
    }

    /**
     * get the shopper's member info
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function memberInfo(Request $request)
    {
        $shopper     = auth('api')->user();
        $member_info = $this->getMemberInfoByShopper($shopper);
        $member_info['userinfo'] = $shopper;
        return response()->json($member_info);
    }

    private function getMemberInfoByShopper($shopper)
    {
        $member = $shopper->member;
        if ($member) {
            $cards       = $shopper->cards()->count();
            $member_info = [
                'member_no'  => $member->member_no,
                'points'     => (int)$member->point_balance,
                'purchase'   => $member->total_puchase,
                'level'      => $member->level,
                'cards'      => $cards,
                'cards_left' => MemberCard::MAX_CARD_AMOUNT - $cards,
                'vouchers'   => $member->vouchers()->whereNull('used_at')->count()
            ];
        } else {
            $member_info = [
                'member_no'  => null,
                'points'     => 0,
                'purchase'   => 0.00,
                'level'      => '路人',
                'cards_left' => MemberCard::MAX_CARD_AMOUNT,
                'vouchers'   => 0
            ];
        }

        $member_info[ 'role' ]   = $shopper->role;
        $qr_code = mini_qrcode($shopper->refer_code);
        $member_info[ 'qrcode' ] = [ $qr_code ];
        return $member_info;
    }
}
