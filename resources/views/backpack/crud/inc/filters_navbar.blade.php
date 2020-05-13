<ul class="kt-shape-bg-color-1 kt-navbar-filters">
    <!-- THE ACTUAL FILTERS -->
    @foreach ($crud->filters as $filter)
        @include($filter->view)
    @endforeach
    <li>
        <a href="#" id="remove_filters_button"
           class="{{ count(Request::input()) != 0 ? '' : 'hidden' }}">
            <i class="fa fa-eraser"></i>{{ trans('backpack::crud.remove_filters') }}
        </a>
    </li>
</ul>

@push('after_styles')
    <style>
        .hidden { display: none!important; }
        .kt-navbar-filters{ padding: 0}
        .kt-navbar-filters>li {
            display: inline;
            float: left;
            transition: all 0.3s;
            font-size: 1rem;
            font-weight: 400;
            padding: 0.75rem 1rem;
        }
        .kt-navbar-filters>li.active {
            background-color: var(--primary);
        }
        .kt-navbar-filters>li.active>a{
            color: white;
        }
        .kt-navbar-filters li a{
            color: #595d6e;
        }
        .kt-navbar-filters li .dropdown-menu li:hover a{
            color: #ffffff;
            text-decoration: none;
            background-color: #758bfd;
        }
        .kt-navbar-filters li .dropdown-menu .active a{
            color: #ffffff;
            text-decoration: none;
            background-color: #758bfd;
        }
    </style>
@endpush

@push('crud_list_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.2/URI.min.js" type="text/javascript"></script>
    <script>
        function addOrUpdateUriParameter(uri, parameter, value) {
            var new_url = normalizeAmpersand(uri);

            new_url = URI(new_url).normalizeQuery();

            if (new_url.hasQuery(parameter)) {
                new_url.removeQuery(parameter);
            }

            if (value != '') {
                new_url = new_url.addQuery(parameter, value);
            }

            $('#remove_filters_button').removeClass('hidden');

            console.log(123);
            return new_url.toString();

        }

        function normalizeAmpersand(string) {
            return string.replace(/&amp;/g, "&").replace(/amp%3B/g, "");
        }

        // button to remove all filters
        jQuery(document).ready(function ($) {
            $("#remove_filters_button").click(function (e) {
                e.preventDefault();

                // behaviour for ajax table
                var new_url = '{{ url($crud->route.'/search') }}';
                var ajax_table = $("#crudTable").DataTable();

                // replace the datatables ajax url with new_url and reload it
                ajax_table.ajax.url(new_url).load();

                // clear all filters
                $("li[filter-name]").trigger('filter:clear');
                $('#remove_filters_button').addClass('hidden');

                // remove filters from URL
                crud.updateUrl(new_url);
            })
        });
    </script>
@endpush
