<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="header">业务数据</li>
<li>
    <a href="{{ backpack_url('dashboard') }}">
        <i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span>
    </a>
</li>
@can(App\Models\Permission::SHOPPER_MANAGEMENT)
    <li>
        <a href="{{ route("crud.shoppers.index") }}">
            <i class="fa fa-user-circle"></i> <span>顾客</span>
        </a>
    </li>
@endcan
@can(App\Models\Permission::ORDER_MANAGEMENT)
    <li>
        <a href="{{ route("crud.orders.index") }}">
            <i class="fa fa-bookmark-o"></i> <span>订单</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.seller_rebates.index") }}">
            <i class="fa fa-diamond"></i> <span>返点</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.seller_withdraws.index") }}">
            <i class="fa fa-money"></i> <span>提现</span>
        </a>
    </li>
@endcan
@can(App\Models\Permission::RESERVE_MANAGEMENT)
    <li>
        <a href="{{ route("crud.reservations.index") }}">
            <i class="fa fa-calendar-check-o"></i> <span>预约</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.beauty_requests.index") }}">
            <i class="ion ion-ios-rose"></i> <span>美容需求</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.shopper_vouchers.index") }}">
            <i class="fa fa-ticket"></i> <span>已售券</span>
        </a>
    </li>
@endcan

@can(App\Models\Permission::DATA_MANAGEMENT)
    <li class="header">数据管理</li>
    <li>
        <a href="{{ backpack_url('coupon_batch') }}">
            <i class="fa fa-credit-card"></i> <span>实体券</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.memberships.index") }}">
            <i class="fa fa-credit-card"></i> <span>红人卡</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.vouchers.index") }}">
            <i class="fa fa-ticket"></i> <span>权益券</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.hospitals.index") }}">
            <i class="fa fa-hospital-o"></i> <span>医院</span>
        </a>
    </li>
    <li>
        <a href="{{ route("crud.beauty_items.index") }}">
            <i class="fa fa-stethoscope"></i> <span>美容项目</span>
        </a>
    </li>
@endcan

@can(App\Models\Permission::SYSTEM_MANAGEMENT)
    <li class="header">系统管理</li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-shield"></i> <span>角色权限</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ backpack_url('user') }}"><i class="fa fa-id-card"></i> <span>用户</span></a></li>
            <li><a href="{{ backpack_url('role') }}"><i class="fa fa-user-secret"></i> <span>角色</span></a></li>
            <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-gavel"></i> <span>权限</span></a></li>
        </ul>
    </li>
    <li>
        <a href='{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}'><i class='fa fa-cog'></i> <span>系统设置</span></a>
    </li>
    <li>
        <a href='{{route("log-viewer::logs.list")}}'><i class='fa fa-history'></i> <span>系统日志</span></a>
    </li>
    <li>
        <a href='{{route("crud.system_logs.index")}}'><i class='fa fa-feed'></i> <span>请求日志</span></a>
    </li>
    <li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/backup') }}'><i class='fa fa-hdd-o'></i> <span>系统备份</span></a></li>
@endcan

