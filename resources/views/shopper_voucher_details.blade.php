<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>券号</th>
        <th>券名</th>
        <th>项目</th>
        <th>获得时间</th>
        <th>状态</th>
        <th>使用时间</th>
        <th>医院</th>
    </tr>
    </thead>
    <tbody>
        @if(count($shopper_vouchers) == 0)
            <tr>
                <td colspan="7" class="text-center">暂无券显示</td>
            </tr>
        @else
            @foreach($shopper_vouchers as $voucher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $voucher['voucher_no']}}</td>
                    <td>{{ $voucher['voucher_name']}}</td>
                    <td>{{ $voucher['item'] }}</td>
                    <td>{{ $voucher['earned_at'] }}</td>
                    <td>{{ $voucher['status'] }}</td>
                    <td>{{ $voucher['used_at'] }}</td>
                    <td>{{ $voucher['hospital'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
