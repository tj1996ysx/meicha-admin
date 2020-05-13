@yield('before_scripts')
@stack('before_scripts')
<script src="{{asset('assets/js/jquery.min.js')}}"></script>

@include('backpack.base.inc.alerts')
<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{asset('/packages/metronic/vendors/global/vendors.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('packages/metronic/js/demo1/scripts.bundle.js')}}" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

@include('backpack.base.inc.alerts')

<!--begin::Page Vendors(used by this page) -->
<script src="{{asset('/packages/metronic/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>
{{--<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>--}}
{{--<script src="{{asset('packages/metronic/vendors/custom/gmaps/gmaps.js')}}" type="text/javascript"></script>--}}
<!--end::Page Vendors -->

<!--begin::Global App Bundle(used by all pages) -->
<script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>

<!-- page script -->
<script type="text/javascript">
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    {{-- Enable deep link to tab --}}
    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

<!--end::Global App Bundle -->
@yield('after_scripts')
@stack('after_scripts')
<!--end::Global App Bundle -->
