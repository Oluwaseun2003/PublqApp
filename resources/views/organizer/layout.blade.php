<!DOCTYPE html>
<html>
  <head>
    {{-- required meta tags --}}
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>{{ __('Organizer') . ' | ' . $websiteInfo->website_title }}</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">

    {{-- include styles --}}
    @includeIf('organizer.partials.styles')

    {{-- additional style --}}
    @yield('style')
  </head>

  <body data-background-color="{{ Session::get('organizer_theme_version') == 'light' ? 'white' : 'dark' }}">
    {{-- loader start --}}
    <div class="request-loader">
      <img src="{{ asset('assets/admin/img/loader.gif') }}" alt="loader">
    </div>
    {{-- loader end --}}

    <div class="wrapper">
      {{-- top navbar area start --}}
      @includeIf('organizer.partials.top-navbar')
      {{-- top navbar area end --}}

      {{-- side navbar area start --}}
      @includeIf('organizer.partials.side-navbar')
      {{-- side navbar area end --}}

      <div class="main-panel">
        <div class="content">
          <div class="page-inner">
            @yield('content')
          </div>
        </div>

        {{-- footer area start --}}
        @includeIf('organizer.partials.footer')
        {{-- footer area end --}}
      </div>
    </div>

    {{-- include scripts --}}
    @includeIf('organizer.partials.scripts')
    @includeIf('organizer.partials.modal')
  </body>
</html>
