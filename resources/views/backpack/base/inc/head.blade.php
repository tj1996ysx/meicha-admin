<head>

    <meta charset="utf-8"/>
    <meta name="description" content="Updates and statistics">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>
        {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
    </title>

    @yield('before_styles')
    @stack('before_styles')
<!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{asset('packages/metronic/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <!--end::Page Vendors Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{asset('packages/metronic/vendors/global/vendors.bundle.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('packages/metronic/css/demo1/style.bundle.css')}}" rel="stylesheet" type="text/css"/>

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{asset('packages/metronic/css/demo1/skins/header/base/light.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('packages/metronic/css/demo1/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('packages/metronic/css/demo1/skins/brand/dark.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('packages/metronic/css/demo1/skins/aside/dark.css')}}" rel="stylesheet" type="text/css"/>

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}"/>

    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/base/backpack.base.css') }}?v=3">
    @if (config('backpack.base.overlays') && count(config('backpack.base.overlays')))
        @foreach (config('backpack.base.overlays') as $overlay)
            <link rel="stylesheet" href="{{ asset($overlay) }}">
        @endforeach
    @endif
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">

    @yield('after_styles')
    @stack('after_styles')

</head>
