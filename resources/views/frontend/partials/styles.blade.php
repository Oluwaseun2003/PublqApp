<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap"
  rel="stylesheet">
<!-- FlatIcon Font -->
<link rel="stylesheet" href="{{ asset('assets/front/css/flaticon.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/front/css/fontawesome.5.9.0.min.css') }}">
<!-- Bootstrap css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.4.5.3.min.css') }}">
<!-- Magnific Popup -->
<link rel="stylesheet" href="{{ asset('assets/front/css/magnific-popup.min.css') }}">
<!-- Slick Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/slick.css') }}">
<!-- jQuery UI CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/jquery-ui.min.css') }}">
<!-- Padding Margin -->
<link rel="stylesheet" href="{{ asset('assets/front/css/spacing.min.css') }}">
<!-- Menu css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/menu.css') }}">
<!-- datatables css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/dataTables.bootstrap4.css') }}">
<!-- dashboard css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/dashboard.css') }}">
<!-- Menu css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/menu.css') }}">
<!-- Main css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
<!-- Responsive css -->
<link rel="stylesheet" href="{{ asset('assets/front/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/front/css/toastr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/organizer.css') }}">
@if ($currentLanguageInfo->direction == 1)
  {{-- right-to-left css --}}
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl-style.css') }}">

  {{-- right-to-left-responsive css --}}
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl-responsive.css') }}">
@endif
<style>
  :root {
    scroll-behavior: auto;
    --base-color: #454545;
    --heading-color: #030A15;
    --primary-color: #{{ $basicInfo->primary_color }};
    --light-color: #F7F7F7;
    --base-font: 'Roboto', sans-serif;
    --heading-font: 'Plus Jakarta Sans', sans-serif;
  }

  .overlay:before {
    position: absolute;
    content: '';
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    z-index: -1;
    opacity: {{ $basicInfo->breadcrumb_overlay_opacity }};
    background: #{{ $basicInfo->breadcrumb_overlay_color }};
  }
</style>
