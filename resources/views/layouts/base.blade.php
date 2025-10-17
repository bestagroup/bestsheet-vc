@php
    $theme = session('theme', 'theme-default');
@endphp
    <!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{$thispage['list']}}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icon.png') }}"/>

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/IRANSans.css') }}"/>

    <!-- Toastr -->

    <!-- Core & Theme CSS -->
    @if(session('theme') === 'theme-default-dark')
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core-dark.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}"/>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone6/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}"/>
    <style>
        /* همیشه تمام‌عرض */
        .select2-container {
            width: 100% !important;
        }

        /* ظاهر کلی هماهنگ با فرم‌های مدرن */
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            border: 1px solid #d9dee3;
            border-radius: .75rem;
            min-height: 44px;
            padding: 6px 12px;
            font-family: IRANSans, sans-serif;
            background-color: #fff;
        }

        /* فوکوس با رنگ برند (نزدیک به رنگ آیکن‌هات #696cff) */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: #696cff;
            box-shadow: 0 0 0 .2rem rgba(105, 108, 255, .15);
        }

        /* تک‌انتخابه: متن راست‌چین و هم‌تراز فارسی */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px; /* برای ارتفاع 44px */
            text-align: right;
        }

        /* فلش در RTL سمت چپ باشد */
        html[dir="rtl"] .select2-container--default .select2-selection--single .select2-selection__arrow {
            left: 10px;
            right: auto;
        }

        /* چندانتخابه: «چیپ‌»های زیبا و خوانا */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #eef1ff;
            border: 1px solid #d6d9ff;
            color: #343a40;
            border-radius: 1rem;
            padding: 2px 8px;
            margin-top: 6px;
        }

        /* دکمه حذف در RTL سمت چپ چیپ */
        html[dir="rtl"] .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            float: left;
            margin-left: 4px;
            margin-right: 0;
        }

        /* آیکن Clear در RTL سمت چپ قرار بگیرد */
        html[dir="rtl"] .select2-container--default .select2-selection--single .select2-selection__clear {
            float: left;
        }

        /* جعبه نتایج */
        .select2-dropdown {
            border-radius: .75rem;
            border-color: #d9dee3;
        }

        .select2-search--dropdown .select2-search__field {
            text-align: right;
            font-family: IRANSans, sans-serif;
        }

        .select2-results__option {
            padding: 10px 12px;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: #f2f3f6;
            color: #111;
        }

        .select2-results__message {
            color: #6c757d;
        }

        /* اندازه‌های متنوع: کوچک و بزرگ (اختیاری) */
        .select2-sm .select2-selection--single,
        .select2-sm .select2-selection--multiple {
            min-height: 38px;
            padding: 4px 10px;
        }

        .select2-lg .select2-selection--single,
        .select2-lg .select2-selection--multiple {
            min-height: 50px;
            padding: 10px 14px;
        }
    </style>


    <!-- Helpers & Config -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @yield('style')
    @stack('styles')
</head>
<!-- Debug -->

<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Sidebar -->
        @include('partials.sidebar')
        <!-- / Sidebar -->

        <div class="layout-page">
            <!-- Header -->
            @include('partials.header')
            <!-- / Header -->

            <!-- Main Content -->
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>


<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/dropzone6/dropzone.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jdate/jdate.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/fa-jdate.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>


<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
{{--<script>--}}
{{--    (function ($) {--}}
{{--        function initSelect2(context) {--}}
{{--            $(context).find('select.select2').each(function () {--}}
{{--                const $el = $(this);--}}
{{--                if ($el.data('select2')) return; // جلوگیری از دوباره‌سازی--}}

{{--                $el.select2({--}}
{{--                    width: '100%',--}}
{{--                    dir: 'rtl',--}}
{{--                    placeholder: $el.data('placeholder') || 'انتخاب کنید',--}}
{{--                    allowClear: $el.data('allow-clear') !== undefined ? !!$el.data('allow-clear') : true,--}}
{{--                    minimumResultsForSearch: $el.data('min-search') ? parseInt($el.data('min-search'), 10) : 0, // مثلا 10 برای تک‌انتخاب‌های کوتاه--}}
{{--                    dropdownParent: $el.closest('.modal').length ? $el.closest('.modal') : $(document.body),--}}
{{--                    // پیام‌های فارسی--}}
{{--                    language: {--}}
{{--                        errorLoading: function () { return 'خطا در بارگذاری نتایج'; },--}}
{{--                        inputTooLong: function (args) { return 'حداکثر ' + (args.maximum) + ' نویسه مجاز است'; },--}}
{{--                        inputTooShort: function (args) { return 'حداقل ' + (args.minimum - args.input.length) + ' نویسه دیگر وارد کنید'; },--}}
{{--                        loadingMore: function () { return 'در حال بارگذاری بیشتر…'; },--}}
{{--                        maximumSelected: function (args) { return 'حداکثر ' + args.maximum + ' مورد قابل انتخاب است'; },--}}
{{--                        noResults: function () { return 'نتیجه‌ای یافت نشد'; },--}}
{{--                        searching: function () { return 'در حال جستجو…'; },--}}
{{--                        removeAllItems: function () { return 'حذف همه موارد'; }--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        }--}}

{{--        $(document).ready(function () { initSelect2(document); });--}}
{{--        $(document).on('shown.bs.modal', '.modal', function () { initSelect2(this); });--}}
{{--    })(jQuery);--}}
{{--</script>--}}

@yield('script')
@stack('scripts')
</body>
</html>
