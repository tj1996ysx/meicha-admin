<!-- password -->
<div @include('backpack.crud.inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('backpack.crud.inc.field_translatable_icon')
    <input
    	type="password"
    	name="{{ $field['name'] }}"
        @include('backpack.crud.inc.field_attributes')
    	>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
