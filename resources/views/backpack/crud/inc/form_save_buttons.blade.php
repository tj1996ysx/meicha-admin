<div id="saveActions">

    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">

    <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-clean kt-margin-r-10">
        <i class="la la-arrow-left"></i>
        <span class="kt-hidden-mobile">{{ trans('backpack::crud.cancel') }}</span>
    </a>

    <div class="btn-group">

        <button type="submit" class="btn btn-brand">
            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
        </button>

        <button type="button" class="btn btn-brand dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
        </button>

        <ul class="dropdown-menu dropdown-menu-right">
            @foreach( $saveAction['options'] as $value => $label)
            <li><a href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a></li>
            @endforeach
        </ul>

    </div>
</div>
