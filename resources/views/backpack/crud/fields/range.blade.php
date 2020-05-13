<!-- html5 range input -->
<div @include('backpack.crud.inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('backpack.crud.inc.field_translatable_icon')
    <input
        type="range"
        name="{{ $field['name'] }}"
        value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
        @include('backpack.crud.inc.field_attributes')
        >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
