@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-label-primary btn-bold btn-icon-h kt-margin-l-10" data-style="zoom-in">
    <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span>
  </a>
@endif
