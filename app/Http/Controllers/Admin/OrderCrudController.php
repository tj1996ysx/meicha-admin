<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrderRequest as StoreRequest;
use App\Http\Requests\OrderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Order');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/orders');
        $this->crud->setEntityNameStrings('订单', '订单');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            [
                'name' => 'shopper_id',
                'label' => '顾客',
                'entity' => 'shopper',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper->shopper_name;
                }
            ],
            [
                'name' => 'mobile',
                'label' => '手机号',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper->mobile;
                }
            ],
            [
                'name' => 'total_paid',
                'label' => '总金额'
            ],
            [
                'name' => 'membership_id',
                'label' => '消费项目',
                'entity' => 'membership',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->membership->name;
                }
            ],
            [
                'name' => 'status',
                'label' => '订单状态',
                'type' => 'select_from_array',
                'options' => $this->getStatusList()
            ],
            [
                'name' => 'paid_at',
                'label' => '支付时间',
            ],
        ]);

        $this->crud->addFilter(
            [
                'name' => 'status',
                'type' => 'dropdown',
                'label' => '订单状态'
            ],
            $this->getStatusList(),
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->denyAccess(['create', 'update', 'delete']);

        // add asterisk for fields that are required in OrderRequest
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
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function getStatusList()
    {
        return [
            Order::STATUS_TO_PAY => '待支付',
            Order::STATUS_ORDER_SUCCESS => '下单成功',
            Order::STATUS_ORDER_FAIL => '下单失败',
            Order::STATUS_PAID_SUCCESS => '支付成功',
            Order::STATUS_PAID_FAIL => '支付失败',
        ];
    }
}
