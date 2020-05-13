@section('header')
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}</a>
                </div>
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

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-lg-12">
                <form class="kt-form kt-form--fit" method="post" action="{{ url($crud->route) }}"
                      @if ($crud->hasUploadFields('create'))enctype="multipart/form-data"@endif>
                    <!--begin::Portlet-->
                    <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                         id="kt_page_portlet">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                  <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                                <h3 class="kt-portlet__head-title">
                                    {!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                @include('backpack.crud.inc.form_save_buttons')
                            </div>
                        </div>
                        <div class="kt-portlet__body">

                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    @include('backpack.crud.inc.grouped_errors')

                                    {!! csrf_field() !!}
                                    @include('backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                                </div>
                            </div>

                        </div>

                    </div>
                </form>

                <!--end::Portlet-->
            </div>
        </div>
    </div>

    <!-- end:: Content -->

@endsection
