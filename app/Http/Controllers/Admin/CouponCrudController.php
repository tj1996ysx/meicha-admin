<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\CouponBatch;
use App\Models\Shopper;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CouponRequest as StoreRequest;
use App\Http\Requests\CouponRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class CouponCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CouponCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(Coupon::class);
        $this->crud->setEntityNameStrings('兑换券', '兑换券');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/coupon');

        $coupon_batch_id = request()->route('coupon_batch_id');
        if ($coupon_batch_id) {
            $coupon_batch                    = CouponBatch::findOrFail($coupon_batch_id);
            $this->data[ 'coupon_batch_id' ] = $coupon_batch_id;
            $this->data[ 'coupon_batch' ]    = $coupon_batch;
            $this->crud->addClause('where', 'coupon_batch_id', $coupon_batch_id);
            $this->crud->setRoute(config('backpack.base.route_prefix').'/coupon_batch/'.$coupon_batch_id.'/coupon');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns([
            ['name' => 'id', 'label' => '序号'],
            ['name' => 'coupon_number', 'label' => '券号'],
            ['name' => 'password', 'label' => '密码'],
            [
                'label'     => "销售人员",
                'type'      => "select",
                'name'      => 'seller_id',
                'entity'    => 'seller',
                'attribute' => "name",
                'model'     => "App\Models\Shopper",
            ],
            [
                'name'    => 'status',
                'label'   => "状态",
                'type'    => 'select_from_array',
                'options' => Coupon::STATUSES,
            ],
            [
                'name'  => 'amount',
                'label' => "金额",
            ],
            [
                'name'  => 'comment',
                'label' => '备注'
            ],
        ]);
        $this->crud->denyAccess(['update', 'delete', 'create']);
        $this->crud->enableExportButtons();
        $this->crud->enableBulkActions();
        $this->crud->addButtonFromView('top', 'coupon_distribute', 'coupon_distribute', 'end');
        $this->crud->addButtonFromView('top', 'coupon_recover', 'coupon_recover', 'end');
        $this->crud->addButtonFromView('top', 'coupon_disable', 'coupon_disable', 'end');

        $this->crud->addFilter(
            [
                'name'  => 'status',
                'type'  => 'dropdown',
                'label' => '兑换券状态'
            ],
            Coupon::STATUSES,
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name'  => 'seller_id',
                'type'  => 'dropdown',
                'label' => '销售人员'
            ],
            Shopper::seller()->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'seller_id', $value);
            }
        );
    }

    public function distribute(Request $request)
    {
        $coupon_ids = $request->input('coupon_ids');
        $seller_id  = $request->input('seller_id');
        $amount     = $request->input('amount');
        $comment    = $request->input('comment');
        foreach ($coupon_ids as $coupon_id) {
            $coupon = Coupon::valid()->where('id', $coupon_id)->first();
            if (!$coupon) {
                continue;
            }
            $coupon->seller_id = $seller_id;
            $coupon->amount    = $amount;
            $coupon->comment   = $comment;
            $coupon->save();
        }
        \Alert::success('分配成功')->flash();

        return response()->json(['message' => 'ok']);
    }

    public function recover(Request $request)
    {
        $coupon_ids = $request->input('coupon_ids');
        foreach ($coupon_ids as $coupon_id) {
            $coupon = Coupon::valid()->where('id', $coupon_id)->first();
            if (!$coupon) {
                continue;
            }
            $coupon->seller_id = null;
            $coupon->amount    = null;
            $coupon->comment   = null;
            $coupon->save();
        }
        \Alert::success('取消分配成功')->flash();

        return response()->json(['message' => 'ok']);
    }

    public function disable(Request $request)
    {
        $coupon_ids = $request->input('coupon_ids');
        foreach ($coupon_ids as $coupon_id) {
            $coupon = Coupon::valid()->where('id', $coupon_id)->whereNull('seller_id')->first();
            if (!$coupon) {
                continue;
            }
            $coupon->seller_id = null;
            $coupon->amount    = null;
            $coupon->comment   = null;
            $coupon->status    = Coupon::STATUS_INVALID;
            $coupon->save();
        }
        \Alert::success('销毁成功')->flash();

        return response()->json(['message' => 'ok']);
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}
