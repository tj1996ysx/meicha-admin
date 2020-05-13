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

      <div class="kt-subheader__toolbar" style="visibility: hidden;">
        <div class="kt-subheader__wrapper">
          <a href="#" class="btn kt-subheader__btn-primary">
            Actions &nbsp;
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- end:: Subheader -->
@endsection

@section('content')
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid {{ $crud->getListContentClass() }}">

    <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
          <span class="kt-portlet__head-icon">
            <i class="kt-font-brand flaticon2-line-chart"></i>
          </span>
          <h3 class="kt-portlet__head-title">
            {{$crud->entity_name_plural}}
          </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
          <div class="kt-portlet__head-wrapper">
            <div class="kt-portlet__head-actions">
              @if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
                @include('backpack.crud.inc.button_stack', ['stack' => 'top'])
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('backpack.crud.inc.filters_navbar')
        @endif

        <!--begin: Datatable -->
        <table id="crudTable" class="display nowrap table table-hover" cellspacing="0" style="font-size: 13px; font-weight: normal;">
          <thead>
          <tr>
            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
              <th
                data-orderable="{{ var_export($column['orderable'], true) }}"
                data-priority="{{ $column['priority'] }}"
                data-visible-in-modal="{{ (isset($column['visibleInModal']) && $column['visibleInModal'] == false) ? 'false' : 'true' }}"
                data-visible="{{ !isset($column['visibleInTable']) ? 'true' : (($column['visibleInTable'] == false) ? 'false' : 'true') }}"
                data-visible-in-export="{{ (isset($column['visibleInExport']) && $column['visibleInExport'] == false) ? 'false' : 'true' }}"
              >
                {!! $column['label'] !!}
              </th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
              <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
            @endif
          </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
          <tr>
            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
              <th>{!! $column['label'] !!}</th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
              <th>{{ trans('backpack::crud.actions') }}</th>
            @endif
          </tr>
          </tfoot>
        </table>
        <!--end: Datatable -->
      </div>
    </div>

  </div>

@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{asset('packages/datatables/css/dataTables.bootstrap4.min.css')}}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	@include('backpack.crud.inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

  <!--begin::Page Vendors(used by this page) -->
  <script type="text/javascript" src="{{asset('packages/datatables/js/datatables.min.js')}}"></script>

  <!--end::Page Vendors -->

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
