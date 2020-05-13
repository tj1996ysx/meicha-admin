@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        工作台 <small>欢迎使用{{config('app.name')}}后台管理系统</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection

@section('content')

    <div class="row">
        @can(App\Models\Permission::SHOPPER_MANAGEMENT)
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{App\Models\Shopper::today()->count()}}</h3>
                    <p>今日新增用户</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route("crud.shoppers.index") }}" class="small-box-footer">
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{App\Models\Shopper::count()}}</h3>
                    <p>用户</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-circle"></i>
                </div>
                <a href="{{ route("crud.shoppers.index") }}" class="small-box-footer">
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endcan

        @can(App\Models\Permission::ORDER_MANAGEMENT)
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{App\Models\Order::paid()->count()}}</h3>
                    <p>订单数</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>
                <a href="{{backpack_url('orders?status=paid_success')}}" class="small-box-footer">
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{App\Models\Order::paid()->sum('total_paid')}}</h3>
                    <p>销售额</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
                <a href="{{backpack_url('orders?status=paid_success')}}" class="small-box-footer">
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endcan

        @can(App\Models\Permission::RESERVE_MANAGEMENT)
            @php
            $pending = App\Models\Reservation::pending()->count();
            @endphp
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-{{$pending?'orange':'green'}}">
                    <div class="inner">
                        <h3>{{$pending}}</h3>
                        <p>未处理预约</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <a href="{{ route("crud.reservations.index") }}" class="small-box-footer">
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ App\Models\Reservation::today()->where('status', App\Models\ShopperVoucher::STATUS_RESERVED)->count()}}</h3>
                            <p>今日预约</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-calendar"></i>
                        </div>
                        <a href="{{ route("crud.reservations.index", ['reserved_at'=>json_encode(['from'=>date('Y-m-d'), 'to'=>date('Y-m-d')])]) }}" class="small-box-footer">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ App\Models\BeautyRequest::pending()->count()}}</h3>
                            <p>美容需求</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-rose"></i>
                        </div>
                        <a href="{{ route("crud.beauty_requests.index", ['status'=> App\Models\BeautyRequest::STATUS_PENDING]) }}" class="small-box-footer">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
        @endcan
    </div>

@endsection
