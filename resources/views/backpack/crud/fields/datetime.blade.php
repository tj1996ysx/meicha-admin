<!-- html5 datetime input -->

<?php
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ( $field['value'] instanceof \Carbon\Carbon || $field['value'] instanceof \Jenssegers\Date\Date )) {
    $field['value'] = $field['value']->toDateTimeString();
}
?>

<div @include('backpack.crud.inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('backpack.crud.inc.field_translatable_icon')
    <input
        type="datetime-local"
        name="{{ $field['name'] }}"
        value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime(old(square_brackets_to_dots($field['name'])) ? old(square_brackets_to_dots($field['name'])) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )))) }}"
        @include('backpack.crud.inc.field_attributes')
        >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
