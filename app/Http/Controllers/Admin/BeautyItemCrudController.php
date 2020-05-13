<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BeautyItemRequest as StoreRequest;
use App\Http\Requests\BeautyItemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class BeautyItemCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BeautyItemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BeautyItem');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/beauty_items');
        $this->crud->setEntityNameStrings('美容项目', '美容项目');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name'  => 'id',
                'label' => '#',
            ],
            [
                'name'  => 'items_image',
                'label' => '图片',
                'type'  => 'image'
            ],
            [
                'name'  => 'name',
                'label' => '项目名称'
            ],
            [
                'name'  => 'amount',
                'label' => '价格'
            ],
        ]);

        $this->crud->addFields([
            ['name' => 'item_code', '项目编号'],
            ['name' => 'name', '项目名称'],
            ['name' => 'amount', 'label' => '价格', 'type' => 'number', 'attributes' => ["step" => "any"]],
            [   // CKEditor
                'name'          => 'description',
                'label'         => 'Description',
                'type'          => 'ckeditor',
                // optional:
                'extra_plugins' => ['oembed', 'widget'],
                'options'       => [
                    'autoGrow_minHeight'   => 200,
                    'autoGrow_bottomSpace' => 50,
                    'removePlugins'        => 'resize,maximize',
                ]
            ],
            [
                'name'         => 'items_image',
                'label'        => '项目图片',
                'type'         => 'image',
                'upload'       => true,
                'crop'         => true,
                'aspect_ratio' => 0
            ],
        ]);

        // add asterisk for fields that are required in BeautyItemRequest
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
