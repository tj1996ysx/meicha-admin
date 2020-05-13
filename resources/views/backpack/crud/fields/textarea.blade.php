<!-- textarea -->
<div @include('backpack.crud.inc.field_wrapper_attributes') >
    <label class="col-form-label">{!! $field['label'] !!}</label>

    @include('backpack.crud.inc.field_translatable_icon')
    <textarea
            name="{{ $field['name'] }}"
            @include('backpack.crud.inc.field_attributes')
            id="kt_autosize"
    >{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push("after_scripts")
    <script type="text/javascript">
        jQuery(document).ready(function() {
            autosize($('#kt_autosize'));
        });
    </script>
@endpush
