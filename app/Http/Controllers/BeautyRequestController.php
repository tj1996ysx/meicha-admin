<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeautyRequestRequest;
use App\Models\BeautyRequest;
use Illuminate\Http\Request;

class BeautyRequestController extends ApiController
{
    public function submit(BeautyRequestRequest $request)
    {
        $model = BeautyRequest::create([
            'mobile'     => $request->mobile,
            'photo'      => json_encode($request->photo),
            'budget'     => $request->budget,
            'city'       => $request->city,
            'project'    => $request->project,
            'remark'     => $request->remark,
            'shopper_id' => auth('api')->user()->id
        ]);

        return $this->success($model);
    }
}
