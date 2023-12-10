<!DOCTYPE html>
<html>

<head>
  {{-- required meta tags --}}
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>404</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('frontend.partials.styles')

  {{-- additional style --}}
  @yield('style')
</head>

<body>

  <!--====== 404 PART START ======-->
  <section class="error-area">
    <div class="container text-center padding-90">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <img src="{{ asset('assets/admin/img/404.png') }}" alt="error">
        </div>
        <div class="col-md-12">
          <div class="error-content">
            <h4 class="mb-4">
              {{ __('404') . '!' }} {{ __('Page Not Found') }}
            </h4>
            <ul>
              <li><a href="{{ route('index') }}" class="theme-btn">{{ __('Return Home') }}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== 404 PART ENDS ======-->
</body>

</html>
