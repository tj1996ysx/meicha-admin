<?php

namespace App\Http\Controllers;

class SettingController extends Controller
{

    /**
     * 变美需求中的选项配置
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function beautyRequest()
    {
        return response()->json(config('app.beauty_request'));
    }
}
