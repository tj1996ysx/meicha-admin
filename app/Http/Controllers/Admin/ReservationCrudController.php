<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeautyItem;
use App\Models\BeautyRequest;
use App\Models\Hospital;
use App\Models\Reservation;
use App\Models\Shopper;
use App\Models\ShopperVoucher;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReservationRequest as StoreRequest;
use App\Http\Requests\ReservationRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ReservationCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReservationCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Reservation');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/reservations');
        $this->crud->setEntityNameStrings('预约', '预约');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            [
                'name'     => 'shopper_name',
                'label'    => '顾客',
                'entity'   => 'shopper',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper_name ?? $entry->shopper->shopper_name;
                }
            ],
            [
                'name'     => 'mobile',
                'label'    => '手机号',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->mobile ?? $entry->shopper->mobile;
                }
            ],
            [
                'name'  => 'reserved_at',
                'label' => '预约申请时间',
            ],
            //            [
            //                'name' => 'agreed_at',
            //                'label' => '约定时间',
            //            ],
            [
                'name'  => 'voucher_no',
                'label' => '券号',
            ],
            [
                'name'  => 'voucher_name',
                'label' => '美容项目',
            ],
            [
                'name'     => 'hospital_id',
                'label'    => '医院',
                'entity'   => 'hospital',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->hospital ? $entry->hospital->name : '-';
                }
            ],
            [
                'name'    => 'status',
                'label'   => '状态',
                'type'    => 'select_from_array',
                'options' => $this->getStatusList()
            ]
        ]);

        $this->crud->addFields([
            [
                'name'              => 'shopper_id',
                'label'             => '顾客',
                'type'              => 'select',
                'entity'            => 'shopper',
                'attribute'         => 'nickname',
                'model'             => Shopper::class,
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
            ],
            //            [
            //                'name' => 'shopper_id',
            //                'label' => '手机号',
            //                'type' => 'select',
            //                'entity' => 'shopper',
            //                'attribute' => 'mobile',
            //                'model' => Shopper::class,
            //                'attributes' => [
            //                    'disabled'=>'disabled',
            //                ],
            //                'wrapperAttributes' => [
            //                    'class' => 'form-group col-md-6'
            //                ],
            //            ],
            [
                'name'              => 'item_id',
                'label'             => '美容项目',
                'type'              => 'select',
                'entity'            => 'item',
                'attribute'         => 'name',
                'model'             => BeautyItem::class,
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name'              => 'reserved_at',
                'label'             => '预约申请时间',
                'attributes'        => [
                    'disabled' => 'disabled',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name'              => 'hospital_id',
                'label'             => '医院',
                'type'              => 'select',
                'entity'            => 'hospital',
                'attribute'         => 'name',
                'model'             => Hospital::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            //            [
            //                'name' => 'agreed_at',
            //                'label' => '约定时间',
            //                'type' => 'datetime_picker',
            //                'datetime_picker_options' => [
            //                    'format' => 'YYYY-MM-DD HH:mm:ss',
            //                    'language' => 'cn'
            //                ],
            //                'allows_null' => true,
            //                'wrapperAttributes' => [
            //                    'class' => 'form-group col-md-6'
            //                ],
            //            ],
            [
                'name'  => 'remark',
                'label' => '预约备注',
                'type'  => 'textarea'
            ]
        ]);

        $this->crud->allowAccess(['update', 'show']);
        $this->crud->denyAccess(['create', 'delete']);

        $this->crud->addClause('orderBy', 'reserved_at', 'DESC');

        $this->crud->addButtonFromView('line', 'cancel_reservation', 'cancel_reservation', 'beginning');

//        $this->crud->addFilter([
//                'name' => 'status',
//                'label' => '状态',
//                'type' => 'dropdown'
//            ], $this->getStatusList(),
//            function ($value){
//                $this->crud->addClause('where', 'status', $value);
//            });
        $this->crud->addFilter(
            [
                'name'  => 'reserved_at',
                'label' => '预约日期范围',
                'type'  => 'date_range'
            ],
            [Carbon::now()->toDate(), Carbon::now()->toDate()],
            function ($value) {
                $dates = json_decode($value);
                if ($dates) {
                    if ($dates->from && $dates->to) {
//                        $this->crud->addClause('whereBetween', 'reserved_at', [$dates->from, $dates->to]);
                        $this->crud->addClause(
                            'whereRaw',
                            DB::raw("DATE_FORMAT(reserved_at, '%Y-%m-%d') BETWEEN '".$dates->from."' AND '".$dates->to."'")
                        );
                    }
                }
            }
        );

        $this->crud->addFilter(
            [
                'name'       => 'time',
                'type'       => 'range',
                'label'      => '预约时间范围',
                'label_from' => '开始(00:00)',
                'label_to'   => '截止(23:59)'
            ],
            ['00:00', '23:59'],
            function ($value) { // if the filter is active
                $range = json_decode($value);
                if ($range->from && $range->to) {
                    $dates = $this->request->reserved_at;
                    if ($dates) {
                        $dates_obj  = json_decode($dates);
                        $start_date = $dates_obj->from;
                        $end_date   = $dates_obj->to;
                    } else {
                        $start_date = $end_date = date('Y-m-d');
                    }
                    $this->crud->addClause(
                        'whereBetween',
                        'reserved_at',
                        [$start_date.' '.$range->from, $end_date.' '.$range->to]
                    );
                }
            }
        );

        if (!$this->crud->request->status) {
            $this->crud->addClause('where', 'status', ShopperVoucher::STATUS_RESERVED);
        }

//        $this->crud->enableBulkActions();
//        $this->crud->addButtonFromView('top', 'mark_reserving', 'mark_reserving', 'end');

        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in ReservationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $show = parent::show($id);
        if (!$this->crud->entry->read_at) {
            $this->crud->entry->read_at = Carbon::now()->toDateTimeString();
            $this->crud->entry->read_by = auth()->user()->id;
            $this->crud->entry->save();
        }

        return $show;
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

    public function batchReserve()
    {
        $entries = $this->request->input('entries');
        ShopperVoucher::whereIn('id', $entries)->update([
            'status' => ShopperVoucher::STATUS_RESERVED
        ]);

        return ['ok'];
    }

    /**
     * for admin web page notification list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationList()
    {
        $reservations = Reservation::whereNull('read_at')->where('status', ShopperVoucher::STATUS_RESERVED)
            ->orderBy('reserved_at', 'DESC')->get();

        $list = [];
        foreach ($reservations as $reservation) {
            $list[] = [
                'id'           => $reservation->id,
                'avatar'       => $reservation->shopper->avatar,
                'shopper_name' => $reservation->shopper_name,
                'mobile'       => $reservation->mobile,
                'item'         => $reservation->item ? $reservation->item->name : '-',
                'hospital'     => $reservation->hospital ? $reservation->hospital->name : '-',
                'reserved_at'  => $reservation->reserved_at,
                'read_at'      => Carbon::parse($reservation->read_at)->toDateTimeString()
            ];
        }

        $requests     = BeautyRequest::where('status', BeautyRequest::STATUS_PENDING)
            ->orderBy('created_at', 'DESC')->get();
        $request_list = [];
        foreach ($requests as $request) {
            $request_list[] = [
                'id'           => $request->id,
                'avatar'       => $request->shopper->avatar,
                'shopper_name' => $request->shopper->nickname,
                'mobile'       => $request->mobile,
                'requested_at' => Carbon::parse($request->created_at)->toDateTimeString()
            ];
        }

        return response()->json([
            'total'         => $reservations->count(),
            'list'          => $list,
            'request_total' => $requests->count(),
            'request_list'  => $request_list,
        ]);
    }

    public function markRead(Request $request)
    {
        $reservation_id = $request->input('reserve_id');
        $user_id        = auth()->user()->id;
        $reservation    = Reservation::find($reservation_id);

        $read_at              = Carbon::now()->toDateTimeString();
        $reservation->read_at = $read_at;
        $reservation->read_by = $user_id;
        $reservation->save();

        return response()->json(['read_at' => $read_at]);
    }

    private function getStatusList()
    {
        return [
            ShopperVoucher::STATUS_UNUSED => '未使用',
            ShopperVoucher::STATUS_RESERVED => '预约中',
//            ShopperVoucher::STATUS_RESERVING => '预约中',
            ShopperVoucher::STATUS_USED => '已使用'
        ];
    }

    public function cancel($id)
    {
        $model = Reservation::findOrFail($id);
        if ($model->cancel()) {
            \Alert::success('预约取消成功')->flash();
        } else {
            \Alert::error('预约取消失败')->flash();
        }

        return redirect()->back();
    }
}
