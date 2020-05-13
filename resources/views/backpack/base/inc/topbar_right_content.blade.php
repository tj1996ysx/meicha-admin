<!-- This file is used to store topbar (right) items -->

<li class="dropdown messages-menu" id="top_right_nav_container" v-cloak>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning" v-if="total_unread">@{{ total_unread }}</span>
    </a>
    <ul class="dropdown-menu" style="width:360px;">
        <li class="header">最新预约</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu" style="max-height:400px;">
                <li v-if="0 == total_unread">
                    <div class="text-muted text-center p-t-10 p-b-10">暂无新的预约</div>
                </li>
                <li v-for="(unread, index) in unread_list"><!-- start message -->
                    <a :href="'/admin/reservations/' + unread.id">
                        <div class="pull-left">
                            <img :src="unread.avatar" class="img-circle" alt="Shopper Avatar">
                        </div>
                        <h4>
                            @{{ unread.shopper_name }} / @{{ unread.mobile }}
                            <small><i class="fa fa-clock-o"></i> @{{ unread.reserved_at }}</small>
                        </h4>
                        <p>
                           点击查看并处理
                        </p>
{{--                        <p v-else>--}}
{{--                            <button class="btn btn-sm" @click="markAsRead(unread)">标为已读</button>--}}
{{--                        </p>--}}
                    </a>
                </li>
            </ul>
        </li>
        <li class="footer"><a href="/admin/reservations">所有预约</a></li>
    </ul>
</li>

@push('after_content')
    <audio src="/images/notify.m4a" muted autoplay loop id="myaudio" style="display: none"></audio>
@endpush

@push('after_scripts')
    <script type="text/javascript">

        var audio = document.getElementById('myaudio');
        var t1 = '{{\Backpack\Settings\app\Models\Setting::get('reservation_fetch_interval')}}' * 1000;
        var t2 = 3500;
        var play = false;
        function run(){
            if(play){
                return false;
            }
            audio.currentTime = 0;//设置播放的音频的起始时间
            audio.volume = 1; //设置音频的声音大小
            audio.muted = false;//关闭静音状态
            play = true;
            setTimeout(function(){
                play = false;
                audio.muted = true;//播放完毕，开启静音状态
            },t2);
        }

        new Vue({
            el: '#top_right_nav_container',
            data: {
                total_unread: 0,
                unread_list: [],
                new_coming: false
            },
            mounted: function(){
                this.fetch();
            },
            methods: {
                fetch(){
                    var _this = this;
                    setInterval(function(){

                        $.ajax({
                            method: 'get',
                            url: '/admin/reservation/fetch',
                            success: function (cb) {

                                var site_title = $('head title').text();
                                var cure_title = site_title.substring(site_title.indexOf(')') + 1);
                                if(cb.total > 0){

                                    if(_this.unread_list.length === 0 || cb.list[0].id !== _this.unread_list[0].id){
                                        run();
                                    }

                                    $('head title').html('(' + cb.total + ')' + cure_title);
                                }else{
                                    $('head title').html(cure_title);
                                }

                                _this.total_unread = cb.total;
                                _this.unread_list = cb.list;
                            }
                        });
                    }, t1);
                },
                markAsRead: function (unread) {
                    $.ajax({
                        method: 'post',
                        url: '/admin/reservation/read',
                        data: {
                            reserve_id: unread.id
                        },
                        success: function (cb) {
                            unread.read_at = cb.read_at;
                        }
                    })
                }
            }
        });
    </script>
@endpush
