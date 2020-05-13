<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeautyItem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VoucherRequest as StoreRequest;
use App\Http\Requests\VoucherRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class VoucherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VoucherCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Voucher');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/vouchers');
        $this->crud->setEntityNameStrings('权益券', '权益券');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            ['name' => 'image_url', 'label' => '图片', 'type' => 'image'],
            ['name' => 'name', 'label' => '券名'],
            ['name' => 'item_id', 'label' => '美容项目', 'type' => 'select', 'entity' => 'item', 'attribute' =>'name', 'model' => BeautyItem::class],
            ['name' => 'description', 'label' => '描述']
        ]);

        $this->crud->addFields([
            ['name' => 'name', 'label' => '券名'],
            ['name' => 'item_id', 'label' => '美容项目', 'type' => 'select', 'entity' => 'item', 'attribute' =>'name', 'model' => BeautyItem::class],
            ['name' => 'description', 'label' => '描述', 'type' => 'textarea'],
            ['name' => 'image_url', 'label' => '卡面图片', 'type' => 'upload', 'upload' => true, 'disk' => 'uploads'],
        ]);

        // add asterisk for fields that are required in VoucherRequest
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
}
