<?php

namespace App\Http\Controllers\Admin;

use App\Models\Voucher;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MembershipRequest as StoreRequest;
use App\Http\Requests\MembershipRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MembershipCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MembershipCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Membership');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/memberships');
        $this->crud->setEntityNameStrings('红人卡', '红人卡');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            ['name' => 'name', 'label' => '卡名'],
            ['name' => 'description', 'label' => '描述'],
            ['name' => 'price', 'label' => '价格'],
            ['name' => 'rebate', 'label' => '返点'],
            ['name' => 'prefix', 'label' => '卡号前缀'],
            [
                // n-n relationship (with pivot table)
                'label' => "优惠券", // Table column heading
                'type' => "select_multiple",
                'name' => 'vouchers', // the method that defines the relationship in your Model
                'entity' => 'vouchers', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => Voucher::class, // foreign key model
            ],
        ]);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => '卡名',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-8'
                ],
            ],
            [
                'name' => 'prefix',
                'label' => '卡号前缀',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ],
            [   // CKEditor
                'name' => 'description',
                'label' => 'Description',
                'type' => 'ckeditor',
                // optional:
                'extra_plugins' => ['oembed', 'widget'],
                'options' => [
                'autoGrow_minHeight' => 200,
                'autoGrow_bottomSpace' => 50,
                'removePlugins' => 'resize,maximize',
                ]
            ],
            [
                'name' => 'price',
                'label' => '价格',
                'type' => 'number',
                'attributes' => ["step" => "any"],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name' => 'rebate',
                'label' => '返点',
                'type' => 'number',
                'attributes' => ["step" => "any"],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                // n-n relationship (with pivot table)
                'label' => "优惠券", // Table column heading
                'type' => "select2_multiple",
                'name' => 'vouchers', // the method that defines the relationship in your Model
                'entity' => 'vouchers', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'pivot' => true,
                'model' => Voucher::class, // foreign key model
            ],
            ['name' => 'image_url', 'label' => '卡面图片', 'type' => 'image', 'upload' => true, 'crop' => true, 'aspect_ratio' => 0],
        ]);

        // add asterisk for fields that are required in MembershipRequest
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
