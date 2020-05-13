@section('header')
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">
        {!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}
      </span>
            </div>

            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <a href="#" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Select dashboard daterange" data-placement="left">
                        <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
                        <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date">Aug 16</span>
                        <i class="flaticon2-calendar-1"></i>
                    </a>
                    <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="left">
                        <a href="#" class="btn btn-brand btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="flaticon2-plus"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Add new:</span>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-graph-1"></i>
                                        <span class="kt-nav__link-text">Order</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
                                        <span class="kt-nav__link-text">Event</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-layers-1"></i>
                                        <span class="kt-nav__link-text">Report</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
                                        <span class="kt-nav__link-text">Post</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-file-1"></i>
                                        <span class="kt-nav__link-text">File</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->
@endsection


@section('content')

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid {{ $crud->getListContentClass() }}"
         id="kt_content">

        <div class="kt-portlet kt-portlet--mobile col-md-10 offset-md-1">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon"><i class="kt-font-brand flaticon2-line-chart"></i></span>
                    <h3 class="kt-portlet__head-title">
                        {!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            @if ($crud->hasAccess('list'))
                                <a href="{{ url($crud->route) }}" class="btn btn-brand btn-elevate btn-icon-sm">
                                    <i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }}
                                    <span>{{ $crud->entity_name_plural }}</span>
                                </a>
                            @endif
                            <a href="javascript: window.print();" class="btn btn-brand btn-elevate btn-icon-sm"><i
                                        class="fa fa-print"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                @if ($crud->model->translationEnabled())
                    <div>
                        <!-- Change translation button group -->
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{trans('backpack::crud.language')}}
                                : {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }}
                                &nbsp; <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                    <li>
                                        <a href="{{ url($crud->route.'/'.$entry->getKey()) }}?locale={{ $key }}">{{ $locale }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <table class="table">
                    <tbody>
                    @foreach ($crud->columns as $column)
                        <tr>
                            <td>
                                <strong>{{ $column['label'] }}</strong>
                            </td>
                            <td>
                                @if (!isset($column['type']))
                                    @include('crud::columns.text')
                                @else
                                    @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                                        @include('vendor.backpack.crud.columns.'.$column['type'])
                                    @else
                                        @if(view()->exists('crud::columns.'.$column['type']))
                                            @include('crud::columns.'.$column['type'])
                                        @else
                                            @include('crud::columns.text')
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($crud->buttons->where('stack', 'line')->count())
                        <tr>
                            <td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
                            <td>
                                @include('backpack.crud.inc.button_stack', ['stack' => 'line'])
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>


    </div>

@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
@endsection
