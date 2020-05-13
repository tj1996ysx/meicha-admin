@if ($crud->bulk_actions)
    <a href="javascript:void(0)" onclick="BatchDistributeCoupon(this)" class="btn btn-default bulk-button"> <i class="fa fa-arrow-right"></i> 分配销售人员</a>
    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">分配销售人员</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="seller_id" class="form-control-label">销售人员</label>
                            <select id="seller_id" name="seller_id" class="form-control">
                                @foreach(\App\Models\Shopper::seller()->get() as $sellder)
                                    <option value="{{$sellder->id}}">{{$sellder->name}}
                                        / {{$sellder->nickname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">每张收取金额(仅做记录使用)</label>
                            <input type="text" class="form-control" id="amount">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">备注:</label>
                            <textarea class="form-control" id="comment"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="CouponAllot(this)">提交更改</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endif
@push('after_scripts')
    <script>
      if (typeof BatchDistributeCoupon != 'function') {
        function BatchDistributeCoupon(button) {

          if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0) {
            new PNotify({
              title: "{{ trans('backpack::crud.bulk_no_entries_selected_title') }}",
              text: "{{ trans('backpack::crud.bulk_no_entries_selected_message') }}",
              type: "warning"
            });

            return;
          }
          $('#myModal').modal('show');
        }
      }

      function CouponAllot() {
        var seller_id = $('#seller_id option:selected').val();
        var amount = $("#amount").val();
        var comment = $("#comment").val();
        var coupon_ids = crud.checkedItems;

        if (!seller_id) {
          alert("请选择要分配的销售人员");
          return false;
        }

        $.ajax({
          url: "{{route('coupon.distribute')}}",
          type: "post",
          data: {
            seller_id: seller_id,
            amount: amount,
            coupon_ids: coupon_ids,
            comment: comment
          },
          success: function (result) {
            window.location.reload();
          },
          error: function (result) {
            window.location.reload();
          }
        });
      }

    </script>
@endpush
