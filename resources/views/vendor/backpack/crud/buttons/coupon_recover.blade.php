@if ($crud->bulk_actions)
    <a href="javascript:void(0)" onclick="BatchRecoverCoupon(this)" class="btn btn-default bulk-button"> 取消分配</a>
@endif
@push('after_scripts')
    <script>
      if (typeof BatchRecoverCoupon != 'function') {
        function BatchRecoverCoupon(button) {

          if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0) {
            new PNotify({
              title: "{{ trans('backpack::crud.bulk_no_entries_selected_title') }}",
              text: "{{ trans('backpack::crud.bulk_no_entries_selected_message') }}",
              type: "warning"
            });
            return;
          }
          $.ajax({
            url: "{{route('coupon.recover')}}",
            data: {
              coupon_ids: crud.checkedItems
            },
            type: "post",
            success: function (result) {
              window.location.reload();
            },
            error: function (result) {
              window.location.reload();
            }
          });
        }
      }
    </script>
@endpush
