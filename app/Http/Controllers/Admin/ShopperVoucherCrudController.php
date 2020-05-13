<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hospital;
use App\Models\ShopperVoucher;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ShopperVoucherRequest as StoreRequest;
use App\Http\Requests\ShopperVoucherRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ShopperVoucherControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ShopperVoucherCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ShopperVoucher');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/shopper_vouchers');
        $this->crud->setEntityNameStrings('已售券', '已售券');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'type' => 'row_number', 'label' => '#'],
            [
                'name'     => 'shopper_name',
                'label'    => '顾客',
                'entity'   => 'shopper',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper_name ?? $entry->shopper->shopper_name;
                },
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('shopper_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('mobile', 'like', '%'.$searchTerm.'%')
                        ->orWhereHas('shopper', function ($q) use ($searchTerm) {
                            $q->where('nickname', 'LIKE', '%'.$searchTerm.'%')
                                ->orWhere('mobile', 'LIKE', '%'.$searchTerm.'%');
                        });
                }
            ],
            [
                'name'     => 'mobile',
                'label'    => '手机号',
                'entity'   => 'shopper',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->mobile ?? $entry->shopper->mobile;
                }
            ],
            [
                'name'  => 'voucher_no',
                'label' => '券号',
            ],
            [
                'name'  => 'item_id',
                'label' => '美容项目',
                'entity' => 'item',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->item ? $entry->item->name : '-';
                }
            ],
            [
                'name'  => 'hospital_id',
                'label' => '医院',
                'entity' => 'hospital',
                'type' => 'closure',
                'function' => function ($entry) {
                    return $entry->hospital ? $entry->hospital->name : '-';
                }
            ],
            [
                'name'  => 'earned_at',
                'label' => '购买时间'
            ],
            [
                'name'    => 'status',
                'label'    => '状态',
                'type'    => 'select_from_array',
                'options' => $this->getStatusList()
            ],
            [
                'name'  => 'reserved_at',
                'label' => '预约时间'
            ],
            [
                'name'  => 'used_at',
                'label' => '消费时间'
            ],
        ]);


        if (!$this->crud->request->order) {
            $this->crud->orderBy('earned_at', 'DESC');
        }

        $this->crud->addFilter(
            [
            'name'  => 'status',
            'label' => '状态',
            'type'  => 'dropdown'
        ],
            $this->getStatusList(),
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->addFilter([ // select2 filter
             'name' => 'hospital_id',
             'type' => 'select2',
             'label'=> '医院'
        ], $this->getHospitalList(), function ($value) { // if the filter is active
            $this->crud->addClause('where', 'hospital_id', $value);
        });

        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->allowAccess(['show']);


        // add asterisk for fields that are required in ShopperVoucherControllerRequest
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
            ShopperVoucher::STATUS_UNUSED   => '未使用',
            ShopperVoucher::STATUS_RESERVED => '已预约',
            ShopperVoucher::STATUS_USED     => '已消费',
        ];
    }

    private function getHospitalList()
    {
        $hospitals = Hospital::get()->pluck('name', 'id')->toArray();
        return $hospitals;
    }
}
