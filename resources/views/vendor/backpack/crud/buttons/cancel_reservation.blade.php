@if ($entry->status == \App\Models\ShopperVoucher::STATUS_RESERVED)
    <a href="{{route('reservation.cancel', $entry->id)}}" class="btn btn-xs btn-warning">
        <i class="fa fa-times"></i> 取消预约
    </a>
@endif
