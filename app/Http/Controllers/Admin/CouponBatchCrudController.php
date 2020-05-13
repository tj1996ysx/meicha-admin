<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CouponBatchRequest as StoreRequest;
use App\Http\Requests\CouponBatchRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CouponBatchCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CouponBatchCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CouponBatch');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/coupon_batch');
        $this->crud->setEntityNameStrings('实体券', '实体券');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'id', 'label' => '#', 'type' => 'row_number'],
            ['name' => 'prefix', 'label' => '前缀'],
            ['name' => 'start_code', 'label' => '起始码'],
            ['name' => 'end_code', 'label' => '结束码'],
            ['name' => 'comment', 'label' => '备注'],
            ['name' => 'created_at', 'label' => '操作时间'],
        ]);

        $this->crud->addFields([
            [
                'name'              => 'prefix',
                'label'             => '前缀',
                'hint'              => '建议使用2~3位大写字母',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
            ],
            [
                'name'              => 'start_code',
                'label'             => '起始号码',
                'hint'              => '最小为1',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name'              => 'end_code',
                'label'             => '结束号码',
                'hint'              => '大于起始号码, 一次最多1000张',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'label'     => "会员卡",
                'type'      => "select",
                'name'      => 'membership_id',
                'entity'    => 'membership',
                'attribute' => "name",
            ],
            [
                'name'  => 'comment',
                'label' => '备注',
            ],
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->addButtonFromView('line', 'coupon_list', 'coupon_list', 'end');
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
