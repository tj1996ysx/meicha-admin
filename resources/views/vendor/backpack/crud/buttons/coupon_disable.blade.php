@if ($crud->bulk_actions)
    <a href="javascript:void(0)" onclick="BatchDisableCoupon(this)" class="btn btn-default bulk-button"> <i class="fa fa-trash"></i> 销毁</a>
    <div class="modal fade" id="couponDestroy" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"> <i class="fa fa-warning"></i> 确认操作, 此操作不可撤销</h4>
                </div>
                <div class="modal-body">是否销毁选中的实体券, 已分配或已使用的不可以销毁</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="confirm-destroy">确定</button>
                </div>
            </div>
        </div>
    </div>
@endif
@push('after_scripts')
    <script>
      if (typeof BatchDisableCoupon != 'function') {
        function BatchDisableCoupon(button) {

          if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0) {
            new PNotify({
              title: "{{ trans('backpack::crud.bulk_no_entries_selected_title') }}",
              text: "{{ trans('backpack::crud.bulk_no_entries_selected_message') }}",
              type: "warning"
            });
            return;
          }
          $('#couponDestroy').modal('show');
          $("#confirm-destroy").click(function () {
            $.ajax({
              url: "{{route('coupon.disable')}}",
              type: "post",
              data: {
                coupon_ids: crud.checkedItems
              },
              success: function (result) {
                window.location.reload();
              },
              error: function (result) {
                window.location.reload();
              }
            });
          });
        }
      }
    </script>
@endpush
