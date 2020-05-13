<template v-cloak>
    <li>
        <a href="#" @click="fetch">
            <i class="fa fa-refresh " v-bind:class="{ 'fa-spin': loading }"></i>
        </a>
    </li>
    <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="最新预约">
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
                        </a>
                    </li>
                </ul>
            </li>
            <li class="footer"><a href="/admin/reservations">所有预约</a></li>
        </ul>

    </li>
    <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="最新需求">
            <i class="fa fa-rss"></i>
            <span class="label label-warning" v-if="request_total_unread">@{{ request_total_unread }}</span>
        </a>
        <ul class="dropdown-menu" style="width:360px;">
            <li class="header">最新需求</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" style="max-height:400px;">
                    <li v-if="0 == request_total_unread">
                        <div class="text-muted text-center p-t-10 p-b-10">暂无新的需求</div>
                    </li>
                    <li v-for="(unread, index) in request_unread_list"><!-- start message -->
                        <a :href="'/admin/beauty_requests/' + unread.id">
                            <div class="pull-left">
                                <img :src="unread.avatar" class="img-circle" alt="Shopper Avatar">
                            </div>
                            <h4>
                                @{{ unread.shopper_name }} / @{{ unread.mobile }}
                                <small><i class="fa fa-clock-o"></i> @{{ unread.requested_at }}</small>
                            </h4>
                            <p>
                                点击查看并处理
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="footer"><a href="/admin/beauty_requests">所有需求</a></li>
        </ul>
    </li>
</template>

@push('after_content')
    <audio src="/images/notify.m4a" muted autoplay loop id="myaudio" style="display: none"></audio>
    <audio src="/images/request.m4a" muted autoplay loop id="request_audio" style="display: none"></audio>
@endpush

@push('after_scripts')
    <script type="text/javascript">

        var audio = document.getElementById('myaudio');
        var request_audio = document.getElementById('request_audio');
        var t1 = '{{\Backpack\Settings\app\Models\Setting::get('reservation_fetch_interval')}}' * 1000;
        var t2 = 2500;
        var t3 = 3500;

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

        var play_request = false;
        function run_request(){
            if(play_request){
                return false;
            }
            request_audio.currentTime = 0;//设置播放的音频的起始时间
            request_audio.volume = 1; //设置音频的声音大小
            request_audio.muted = false;//关闭静音状态
            play_request = true;
            setTimeout(function(){
                play_request = false;
                request_audio.muted = true;//播放完毕，开启静音状态
            },t3);
        }

        new Vue({
            el: '#top_right_navbar',
            data: {
                total_unread: 0,
                loading:false,
                unread_list: [],
                request_unread_list: [],
                request_total_unread: 0
            },
            mounted: function(){
                 this.startTimer();
            },
            methods: {
                startTimer: function() {
                  var that = this;
                  setInterval(function(){
                    that.fetch();
                  }, t1);
                },
                fetch(){
                    var _this = this;
                    if(_this.loading) {
                      return;
                    }
                    _this.loading = true;
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
                            }
                            _this.total_unread = cb.total;
                            _this.unread_list = cb.list;

                            if(cb.request_total > 0){
                                if(_this.request_unread_list.length === 0 || cb.request_list[0].id !== _this.request_unread_list[0].id){
                                    run_request();
                                }
                            }
                            _this.request_total_unread = cb.request_total;
                            _this.request_unread_list = cb.request_list;

                            if(cb.total > 0 || cb.request_total > 0){
                                $('head title').html('(' + (cb.total + cb.request_total) + ')' + cure_title);
                            }else{
                                $('head title').html(cure_title);
                            }

                            _this.loading = false;
                        },
                        error: function(response) {
                          _this.loading = false;
                          console.log(response);
                        }
                    });
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
