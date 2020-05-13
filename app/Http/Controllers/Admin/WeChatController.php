<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class WeChatController extends Controller
{
    public function listMenu()
    {
        $app = app('wechat.official_account');

        $buttons = [
            [
                "type" => "view",
                "name" => "红人卡",
                "key"  => "V1001_MEMBER_CARD",
                "url"  => "https://meicha.parse.cn/wechat/member"
            ],
            [
                "type" => "view",
                "name" => "查医院",
                "key"  => "V1002_HOSPITAL",
                "url"  => "https://meicha.parse.cn/wechat/hospital"
            ],
            [
                "type" => "view",
                "name" => "合作",
                "key"  => "V1003_CONTACT",
                "url"  => "https://meicha.parse.cn/wechat/contact"
            ],
        ];
        $app->menu->create($buttons);

        $list = $app->menu->list();
        return $list;
    }

    public function users()
    {
        $app = app('wechat.official_account');
        return $app->user->list();
    }
}
