<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShopperRequest;
use App\Models\Invitation;
use App\Models\Shopper;
use App\Models\ShopperVoucher;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Backpack\Settings\app\Models\Setting;
use EasyWeChat\Kernel\Http\StreamResponse;
use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

/**
 * Class ShopperCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ShopperCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Shopper');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/shoppers');
        $this->crud->setEntityNameStrings('顾客', '顾客');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            ['name' => 'row_number', 'label' => '#', 'type' => 'row_number'],
            [
                'name'     => 'shopper_name',
                'label'    => '姓名/昵称',
                'type'     => 'closure',
                'function' => function ($entry) {
                    return $entry->shopper_name;
                }
            ],
            [
                'name'    => 'gender',
                'label'   => '性别',
                'type'    => 'select_from_array',
                'options' => [
                    'unknown' => '未知',
                    'male'    => '男',
                    'female'  => '女'
                ]
            ],
            [
                'name'  => 'mobile',
                'label' => '手机号'
            ],
            [
                'name'     => 'points',
                'label'    => '总消费/积分',
                'entity'   => 'member',
                'type'     => 'closure',
                'function' => function ($entry) {
                    $member = $entry->member;
                    if ($member) {
                        return '￥'.number_format($member->total_purchase, 2).'/'.number_format($member->point_balance);
                    }

                    return '-/-';
                }
            ],
            [
                'name'     => 'level',
                'label'    => '等级',
                'type'     => 'closure',
                'function' => function ($entry) {
                    $member = $entry->member;
                    if ($member) {
                        $levels = $this->getLevels();

                        return $levels[ $member->level ];
                    }

                    return '';
                },
            ],
            [
                'name'    => 'role',
                'label'   => '角色',
                'type'    => 'select_from_array',
                'options' => $this->getRoles(),
            ],
            [
                'name'    => 'seller_level',
                'label'   => '代理等级',
            ],
            [
                'name'    => 'parent_seller.name',
                'label'   => '上级代理',
            ],
            [
                'name'  => 'created_at',
                'label' => '关注日期'
            ]
        ]);

        $this->crud->addFields([
            [
                'name'              => 'name',
                'label'             => '真实姓名',
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'              => 'nickname',
                'label'             => '昵称',
                'attributes'        => [
                    'disabled' => 'disabled'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'              => 'gender',
                'label'             => '性别',
                'type'              => 'select2_from_array',
                'options'           => [
                    'unknown' => '未知',
                    'male'    => '男',
                    'female'  => '女'
                ],
                'allow_empty'       => false,
                'attributes'        => [
                    'disabled' => 'disabled'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'       => 'mobile',
                'label'      => '手机号',
                'attributes' => [
                    'disabled' => 'disabled'
                ],
            ],
            [
                'name'       => 'created_at',
                'label'      => '关注日期',
                'attributes' => [
                    'disabled' => 'disabled'
                ],
            ],
            [
                'name'              => 'is_admin',
                'label'             => '管理员',
                'hint'              => '管理员可以邀请销售人员',
                'type'              => 'checkbox',
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'              => 'role',
                'label'             => '角色',
                'type'              => 'select2_from_array',
                'allows_null'       => false,
                'options'           => $this->getRoles(),
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'              => 'rebase_rate',
                'label'             => '返点率',
                'hint'              => '当角色是seller时，需要用来计算返点金额',
                'type'              => 'number',
                'attributes'        => ["step" => "any"],
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
            [
                'name'              => 'seller_level',
                'label'             => '分销等级',
                'hint'              => '当角色是seller时有效',
                'type'              => 'select_from_array',
                'options'           => ['0' => '-', '1' => '一级分销', '2' => '二级分销'],
                'wrapperAttributes' => [
                    'class' => 'form-group, col-md-4'
                ]
            ],
        ]);

        $this->crud->enableDetailsRow();
        $this->crud->addClause('authed');
        $this->crud->orderBy('created_at', 'desc');

        $this->crud->addButtonFromView('line', 'mini_qr', 'mini_qr', 'beginning');

        $this->crud->denyAccess(['create', 'delete']);
        $this->crud->allowAccess(['show', 'update', 'details_row']);
    }

    public function update(ShopperRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function showDetailsRow($id)
    {
        $shopper  = Shopper::find($id);
        $vouchers = $shopper->vouchers;

        $shopper_vouchers = [];
        foreach ($vouchers as $voucher) {
            $_voucher           = $voucher->voucher;
            $shopper_vouchers[] = [
                'voucher_no'   => $voucher->voucher_no,
                'voucher_name' => $_voucher->name,
                'earned_at'    => $voucher->earned_at,
                'status'       => $voucher->status_label,
                'used_at'      => $voucher->used_at,
                'item'         => $_voucher->item ? $_voucher->item->name : '-',
                'hospital'     => $voucher->hospital ? $voucher->hospital->name : '-',
            ];
        }

        return view('shopper_voucher_details', compact('shopper_vouchers'));
    }

    public function qrCode($code = '')
    {
        return redirect(mini_qrcode($code));
    }

    private function getRoles()
    {
        return [
            Shopper::ROLE_SHOPPER => '顾客',
            Shopper::ROLE_SELLER  => '销售代理'
        ];
    }

    private function getLevels()
    {
        static $levels = [];

        if (empty($levels)) {
            $achievement_levels = Setting::get('achievement_level');
            $_levels            = json_decode($achievement_levels, true);
            foreach ($_levels as $level) {
                $levels[ Arr::get($level, 'code') ] = $level[ 'label' ];
            }
        }

        return $levels;
    }
}
