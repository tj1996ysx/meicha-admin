@if ($crud->bulk_actions)
    <a href="javascript:void(0)" onclick="batchReserveEntries(this)" class="btn btn-default btn-primary"><i class="fa fa-clone"></i> 批量预约</a>
@endif

@push('after_scripts')
    <script>
        if (typeof batchReserveEntries != 'function') {
            function batchReserveEntries(button) {

                if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
                {
                    new PNotify({
                        title: "{{ trans('backpack::crud.bulk_no_entries_selected_title') }}",
                        text: "{{ trans('backpack::crud.bulk_no_entries_selected_message') }}",
                        type: "warning"
                    });

                    return;
                }

                var message = "确定将所选的:number次预约置为 预约中 么?";
                message = message.replace(":number", crud.checkedItems.length);

                // show confirm message
                if (confirm(message) == true) {
                    var ajax_calls = [];
                    var clone_route = "{{ url($crud->route) }}/bulk-reserve";

                    // submit an AJAX delete call
                    $.ajax({
                        url: clone_route,
                        type: 'POST',
                        data: { entries: crud.checkedItems },
                        success: function(result) {
                            // Show an alert with the result
                            new PNotify({
                                title: "批量预约成功",
                                text: crud.checkedItems.length+"次预约设置成功.",
                                type: "success"
                            });

                            crud.checkedItems = [];
                            crud.table.ajax.reload();
                        },
                        error: function(result) {
                            // Show an alert with the result
                            new PNotify({
                                title: "Reserve failed",
                                text: "One or more entries could not be created. Please try again.",
                                type: "warning"
                            });
                        }
                    });
                }
            }
        }
    </script>
@endpush