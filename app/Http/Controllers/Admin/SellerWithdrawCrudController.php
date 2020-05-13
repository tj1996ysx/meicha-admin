<?php

namespace App\Http\Controllers\Admin;

use App\Models\SellerRebate;
use App\Models\SellerWithdraw;
use App\Models\Shopper;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SellerWithdrawRequest as StoreRequest;
use App\Http\Requests\SellerWithdrawRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;

/**
 * Class SellerWithdrawCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SellerWithdrawCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SellerWithdraw');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/seller_withdraws');
        $this->crud->setEntityNameStrings('提现', '提现');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name'  => 'row_number',
                'label' => '#',
                'type'  => 'row_number',
            ],
            [
                'name'     => 'seller_id',
                'label'    => '代理',
                'entity'   => 'seller',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->seller->shopper_name;
                }
            ],
            [
                'name'     => 'bank_name',
                'label'    => '提现银行卡',
                'entity'   => 'seller',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->seller->bank_name . ' ' . $entry->seller->bank_card_no;
                }
            ],
            [
                'name'  => 'amount',
                'label' => '提现金额'
            ],
            [
                'name'  => 'requested_at',
                'label' => '请求时间'
            ],
            [
                'name'    => 'status',
                'label'   => '状态',
                'type'    => 'select_from_array',
                'options' => [
                    SellerWithdraw::STATUS_REQUESTED => '待处理',
                    SellerWithdraw::STATUS_PAID      => '已支付'
                ]
            ]
        ]);

        $this->crud->addFields([
            [
                'name'              => 'seller_id',
                'label'             => '销售人员',
                'type'              => 'select',
                'entity'            => 'seller',
                'attribute'         => 'nickname',
                'model'             => Shopper::class,
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
            ],
            [
                'name'              => 'amount',
                'label'             => '提现金额',
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name'              => 'requested_at',
                'label'             => '请求时间',
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name'    => 'status',
                'label'   => '状态',
                'type'    => 'select_from_array',
                'options' => [
                    SellerWithdraw::STATUS_REQUESTED => '待处理',
                    SellerWithdraw::STATUS_PAID      => '已支付'
                ]
            ],
            [
                'name'  => 'pay_by',
                'type'  => 'hidden',
                'value' => auth()->user()->id
            ]
        ]);

        $this->crud->addClause('orderBy', 'requested_at', 'desc');

        $this->crud->denyAccess(['create', 'delete']);

        // add asterisk for fields that are required in SellerWithdrawRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $now = Carbon::now()->toDateTimeString();
        $request->offsetSet('paid_at', $now);
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        $status = $request->input('status');
        if (SellerWithdraw::STATUS_PAID == $status) {
            SellerRebate::where('withdraw_id', $this->crud->entry->id)
                ->update([
                    'status'  => SellerRebate::STATUS_PAID,
                    'paid_at' => $now
                ]);
        }
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
