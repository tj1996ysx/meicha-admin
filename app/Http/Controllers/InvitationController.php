<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Models\Invitation;
use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends ApiController
{
    public function create(InvitationRequest $request)
    {
        $from_shopper = auth('api')->user();

        switch ($request->get('type')) {
            case Invitation::TYPE_FIRST_LEVEL:
                if (!$from_shopper->is_admin) {
                    return $this->fail('没有权限进行操作', 403);
                }
                break;
            case Invitation::TYPE_SECOEND_LEVEL:
                if ($from_shopper->seller_level != 1) {
                    return $this->fail('没有权限进行操作', 403);
                }
                break;
            default:
                return $this->fail('请指定邀请类型');
        }

        $invitation = Invitation::create([
            'from_shopper_id' => $from_shopper->id,
            'status'          => Invitation::STATUS_VALID,
            'name'            => $request->get('name'),
            'rebate'          => .5,
            'type'            => $request->get('type'),
        ]);

        return $this->success([
            'code' => $invitation->code
        ]);
    }

    public function show()
    {
        $code       = request()->get('code');
        $invitation = Invitation::valid()->where('code', $code)->first();

        if (!$invitation) {
            return $this->fail('邀请码无效');
        }

        return $this->success([
            'code' => $code,
            'status' => $invitation->status,
            'type' => $invitation->type,
            'from_nickname' => $invitation->fromShopper->nickname,
            'from_avatar' => $invitation->fromShopper->avatar
        ]);
    }

    public function accept()
    {
        $code       = request()->get('code');
        $invitation = Invitation::valid()->where('code', $code)->first();

        if (!$invitation) {
            return $this->fail('邀请码无效');
        }

        $shopper = auth()->user();

        if ($shopper->role == Shopper::ROLE_SELLER) {
            return $this->fail('您已经有销售权限');
        }

        \DB::beginTransaction();
        if ($invitation->accept($shopper)) {
            $shopper->parent_seller_id = $invitation->from_shopper_id;
            $shopper->role             = Shopper::ROLE_SELLER;
            $shopper->seller_level     = $invitation->type;
            $shopper->name             = $invitation->name;
            $shopper->rebase_rate      = $invitation->rebate;
            $shopper->save();
            \DB::commit();

            return $this->success(['message' => '邀请接受成功']);
        }

        return $this->fail('邀请码无效');
    }

    public function reject()
    {
        $code       = request()->get('code');
        $invitation = Invitation::valid()->where('code', $code)->first();
        if (!$invitation) {
            return $this->fail('邀请码无效');
        }

        $shopper = auth('api')->user();
        if ($invitation->reject($shopper)) {
            return $this->success(['message' => '邀请拒绝成功']);
        }

        return $this->fail('邀请码无效');
    }
}
