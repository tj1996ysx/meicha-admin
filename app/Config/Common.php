<?php

namespace App\Config;

class Common
{
    public static function menu()
    {
        return [
            [
                'label' => trans('backpack::base.dashboard'),
                'icon'  => 'fab fa-dropbox',
                'link'  => backpack_url('dashboard'),
            ],
            [
                'label' => '业务数据',
                'type' => 'title'
            ],
            [
                'label'      => '用户',
                'icon'       => 'fa fa-user-circle',
                'link'       => route("crud.shoppers.index"),
                'permission' => 'business_shoppers',
            ],
            [
                'label'      => '订单',
                'icon'       => 'fa fa-bookmark-o',
                'link'       => route("crud.orders.index"),
                'permission' => 'business_orders',
            ],
            [
                'label'      => '返点',
                'icon'       => 'fa fa-diamond',
                'link'       => route("crud.seller_rebates.index"),
                'permission' => 'business_seller_rebates',
            ],
            [
                'label'      => '提现',
                'icon'       => 'fa fa-money',
                'link'       => route("crud.seller_withdraws.index"),
                'permission' => 'business_seller_rebates',
            ],
            [
                'label'      => '预约',
                'icon'       => 'fa fa-calendar-check-o',
                'link'       => route("crud.reservations.index"),
                'permission' => 'business_reservations',
            ],
            [
                'label'      => '美容需求',
                'icon'       => 'fa fa-question-circle-o',
                'link'       => route("crud.beauty_requests.index"),
                'permission' => 'business_beauty_requests',
            ],
            [
                'label'      => '红人卡',
                'icon'       => 'fa fa-credit-card',
                'link'       => route("crud.memberships.index"),
                'permission' => 'system_memberships',
                ],
            [
                'label'      => '权益券',
                'icon'       => 'fa fa-ticket',
                'link'       => route("crud.vouchers.index"),
                'permission' => 'system_vouchers',
            ],
            [
                'label'      => '医院',
                'icon'       => 'fa fa-hospital-o',
                'link'       => route("crud.hospitals.index"),
                'permission' => 'system_hospitals',
            ],
            [
                'label'      => '美容项目',
                'icon'       => 'fa fa-stethoscope',
                'link'       => route("crud.beauty_items.index"),
                'permission' => 'system_beauty_items',
            ],
            [
                'label'      => '系统管理',
                'permission' => 'system_manage',
                'type' => 'title'
            ],
            [
                'label'      => '角色权限',
                'icon'       => 'fa fa-shield',
                'permission' => 'system_manage',
                'children' => [
                    [
                        'label'      => '用户',
                        'icon'       => 'fa fa-id-card',
                        'link'       => backpack_url('user'),
                    ],
                    [
                        'label'      => '角色',
                        'icon'       => 'fa fa-user-secret',
                        'link'       => backpack_url('role'),
                    ],
                    [
                        'label'      => '权限',
                        'icon'       => 'fa fa-gavel',
                        'link'       => backpack_url('permission'),
                    ],
                ]
            ],
            [
                'label'      => '系统设置',
                'icon'       => 'fa fa-cog',
                'permission' => 'system_manage',
                'link'       => url(config('backpack.base.route_prefix', 'admin') . '/setting'),
            ],
            [
                'label'      => '系统日志',
                'permission' => 'system_manage',
                'icon'       => 'fa fa-history',
                'link'       => route("log-viewer::logs.list"),
            ],
            [
                'label'      => '请求日志',
                'permission' => 'system_manage',
                'icon'       => 'fa fa-feed',
                'link'       => route("crud.system_logs.index"),
            ],
            [
                'label'      => '系统备份',
                'permission' => 'system_manage',
                'icon'       => 'fa fa-hdd-o',
                'link'       => backpack_url('backup'),
            ],
        ];
    }
}
