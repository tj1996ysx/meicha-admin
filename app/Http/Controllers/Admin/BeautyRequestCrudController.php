<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeautyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BeautyRequestRequest as StoreRequest;
use App\Http\Requests\BeautyRequestRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class BeautyRequestCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BeautyRequestCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BeautyRequest');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/beauty_requests');
        $this->crud->setEntityNameStrings('美容需求', '美容需求');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            [
                'name'   => 'shopper_id',
                'label'  => '顾客',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper->shopper_name;
                }
            ],
            [
                'name'  => 'mobile',
                'label' => '手机号',
            ],
            [
                'name'  => 'city',
                'label' => '城市',
            ],
            [
                'name'  => 'project',
                'label' => '美容项目',
            ],
            [
                'name'  => 'budget',
                'label' => '预算'
            ],
            [
                'name'  => 'remark',
                'label' => '其它说明'
            ],
            [
                'name' => 'status',
                'label' => '处理状态',
                'type' => 'select_from_array',
                'options' => BeautyRequest::STATUSES
            ]
        ]);

        $this->crud->addFields([
            [
                'name' => 'status',
                'label' => '处理状态',
                'type' => 'select_from_array',
                'options' => BeautyRequest::STATUSES
            ],
            [
                'name' => 'remark',
                'label' => '备注',
                'type' => 'textarea'
            ]
        ]);

        $this->crud->addFilter(
            [
                'name' => 'status',
                'type' => 'dropdown',
                'label' => '状态'
            ],
            BeautyRequest::STATUSES,
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->denyAccess(['create', 'delete']);
        $this->crud->allowAccess(['show']);
        $this->crud->orderBy('created_at', 'desc');

        // add asterisk for fields that are required in BeautyRequestRequest
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
