@if ($entry->role == 'seller')
    <button type="button" class="btn btn-xs btn-primary" data-nickname="{{$entry->nickname}}" data-code="{{$entry->refer_code}}"
            data-toggle="modal" data-target="#modal-info">
        <i class="fa fa-qrcode"></i> 预览二维码
    </button>
@endif

@includeWhen(true, 'backpack::mini_qr_modal')
<script>

    $(function () {
        $('#modal-info').on('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            $('#mini_qr_name').html($(btn).data('nickname'));
            $('#mini_qr_img').attr('src', '/admin/shopper/mini_qr/' + $(btn).data('code'));
        })
    });
</script>