<?php

namespace App\Http\Controllers\Admin;

use App\Models\SellerRebate;
use App\Models\Shopper;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SellerRebateRequest as StoreRequest;
use App\Http\Requests\SellerRebateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SellerRebateCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SellerRebateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SellerRebate');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/seller_rebates');
        $this->crud->setEntityNameStrings('销售返点', '销售返点');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name'  => 'id',
                'label' => '#'
            ],
            [
                'name'     => 'seller_id',
                'label'    => '销售人员',
                'entity'   => 'seller',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->seller->shopper_name;
                }
            ],
            [
                'name'     => 'parent_seller_id',
                'label'    => '上级代理',
                'entity'   => 'parent_seller',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->parent_seller->shopper_name;
                }
            ],
            [
                'name'  => 'amount',
                'label' => '金额'
            ],
            [
                'name'     => 'shopper_id',
                'label'    => '顾客',
                'entity'   => 'shopper',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper->shopper_name;
                }
            ],
            [
                'name'     => 'order_id',
                'label'    => '订单号',
                'entity'   => 'order',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->order->order_no;
                }
            ],
            [
                'name'  => 'rebased_at',
                'label' => '返点时间'
            ],
            [
                'name'    => 'status',
                'label'   => '状态',
                'type'    => 'select_from_array',
                'options' => $this->getStatusList()
            ],
            [
                'name'  => 'paid_at',
                'label' => '提现时间'
            ],
        ]);

        $this->crud->addFilter(
            [
                'name'  => 'status',
                'type'  => 'select2',
                'label' => '返点状态',
            ],
            $this->getStatusList(),
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );
        $this->crud->orderBy('id', 'desc');

        $this->crud->denyAccess(['create', 'delete', 'update']);

        $this->crud->addFilter(
            [
                'name'  => 'seller_id',
                'label' => '销售人员',
                'type'  => 'dropdown'
            ],
            Shopper::where('role', 'seller')->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'seller_id', $value);
            }
        );
        $this->crud->addFilter(
            [
                'name'  => 'parent_seller_id',
                'label' => '上级代理',
                'type'  => 'dropdown'
            ],
            Shopper::where('role', 'seller')->where('seller_level', '1')->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'parent_seller_id', $value);
            }
        );

        // add asterisk for fields that are required in SellerRebateRequest
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
            SellerRebate::STATUS_UNSETTLED   => '未提现',
            SellerRebate::STATUS_WITHDRAWING => '待处理',
            SellerRebate::STATUS_PAID        => '已支付',
        ];
    }
}
