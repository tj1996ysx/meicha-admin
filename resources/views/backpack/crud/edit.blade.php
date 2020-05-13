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

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-lg-12">
                <form
                        class="kt-form kt-form--fit" method="post"
                        action="{{ url($crud->route.'/'.$entry->getKey()) }}"
                        @if ($crud->hasUploadFields('update', $entry->getKey()))
                        enctype="multipart/form-data"
                        @endif
                >
                    <!--begin::Portlet-->
                    <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                         id="kt_page_portlet">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="kt-font-brand flaticon2-line-chart"></i></span>
                                <h3 class="kt-portlet__head-title">
                                    {!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}
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
                                    {!! method_field('PUT') !!}

                                    @if ($crud->model->translationEnabled())
                                        <div class="row m-b-10">
                                            <!-- Single button -->
                                            <div class="btn-group pull-right">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    {{trans('backpack::crud.language')}}
                                                    : {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }}
                                                    &nbsp; <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                                        <li>
                                                            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @include('backpack.crud.form_content', ['fields' => $fields, 'action' => 'edit'])

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
