<!-- view field -->

<div @include('backpack.crud.inc.field_wrapper_attributes') >
  @include($field['view'], compact('crud', 'entry', 'field'))
</div>
