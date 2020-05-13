<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;

/**
 * Class SystemLog2ControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SystemLogCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SystemLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/system_logs');
        $this->crud->setEntityNameStrings('请求日志', '请求日志');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns([
            'url', 'method', 'log_type', 'created_at', 'ip'
        ]);

        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->allowAccess(['show']);
    }
}
