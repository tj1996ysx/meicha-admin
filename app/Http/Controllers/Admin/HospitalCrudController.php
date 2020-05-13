<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeautyItem;
use App\Models\Hospital;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\HospitalRequest as StoreRequest;
use App\Http\Requests\HospitalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class HospitalCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class HospitalCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Hospital');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/hospitals');
        $this->crud->setEntityNameStrings('医院', '医院');

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            [
                'name'  => 'hospital_image',
                'type'  => 'image',
                'label' => '图片'
            ],
            ['name' => 'name', 'label' => '名称'],
        ]);

        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => '名称',
            ],
//            [
//                'name'              => 'level',
//                'label'             => '等级',
//                'type'              => 'select_from_array',
//                'options'           => Hospital::getLevels(),
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-4'
//                ]
//            ],
//            [
//                'name'      => 'beautyItems',
//                'type'      => 'select2_multiple',
//                'label'     => '美容项目',
//                'entity'    => 'beautyItems',
//                'attribute' => 'name',
//                'model'     => BeautyItem::class,
//                'pivot'     => true
//            ],
//            [   // CKEditor
//                'name'          => 'description',
//                'label'         => 'Description',
//                'type'          => 'ckeditor',
//                // optional:
//                'extra_plugins' => ['oembed', 'widget'],
//                'options'       => [
//                    'autoGrow_minHeight'   => 200,
//                    'autoGrow_bottomSpace' => 50,
//                    'removePlugins'        => 'resize,maximize',
//                ]
//            ],
            [
                'name'              => 'address',
                'label'             => '地址',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ]
            ],
            [
                'name'              => 'latitude',
                'label'             => '纬度',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ],
            [
                'name'              => 'longitude',
                'label'             => '经度',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ],
//            [
//                'name'              => 'telephone',
//                'label'             => '电话',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-6'
//                ]
//            ],
//            [
//                'name'              => 'contact_user',
//                'label'             => '联系人',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-6'
//                ]
//            ],
//
//            [
//                'name'              => 'contact_number',
//                'label'             => '联系电话',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-6'
//                ]
//            ],
            [
                'name'   => 'hospital_image',
                'label'  => '医院图片',
                'type'   => 'browse',
            ],
            [
                'name'   => 'desc',
                'label'  => '医院介绍图片',
                'type'   => 'browse',
            ],
            [
                'name'   => 'map',
                'label'  => '地图图片',
                'type'   => 'browse',
            ],
            [
                'label' => '环境图片',
                'name' => 'environments',
                'type' => 'browse_multiple',
            ],
            [
                'label' => '专家图片',
                'name' => 'experts',
                'type' => 'browse_multiple',
            ]
        ]);


        // add asterisk for fields that are required in HospitalRequest
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
