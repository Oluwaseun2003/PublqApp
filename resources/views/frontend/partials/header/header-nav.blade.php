<header class="main-header">

  <!--Header-Upper-->
  <div class="header-upper py-25">
    <div class="container clearfix">

      <div class="header-inner">
        <div class="logo-outer">
          <div class="logo"><a href="{{ route('index') }}"><img
                src="{{ asset('assets/admin/img/' . $websiteInfo->logo) }}" alt="Logo"></a></div>
        </div>

        <div class="nav-outer clearfix ml-lg-auto">
          <!-- Main Menu -->
          <nav class="main-menu navbar-expand-xl">
            <div class="navbar-header">
              <div class="logo-mobile"><a href="{{ route('index') }}"><img
                    src="{{ asset('assets/admin/img/' . $websiteInfo->logo) }}" alt="Logo"></a></div>
              <!-- Toggle Button -->
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"
                aria-controls="main-menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <div class="navbar-collapse collapse clearfix" id="main-menu">
              @php
                $links = json_decode($menuInfos, true);
              @endphp
              <ul class="navigation clearfix">
                @foreach ($links as $link)
                  @php
                    $href = get_href($link, $currentLanguageInfo->id);
                  @endphp
                  @if (!array_key_exists('children', $link))
                    <li><a href="{{ $href }}" target="{{ $link['target'] }}">{{ __($link['text']) }}</a></li>
                  @else
                    <li class="dropdown">
                      <a href="{{ $href }}" target="{{ $link['target'] }}">
                        {{ $link['text'] }}
                        <i class="fa fa-angle-down"></i>
                      </a>
                      <ul>
                        @foreach ($link['children'] as $level2)
                          @php
                            $l2Href = get_href($level2, $currentLanguageInfo->id);
                          @endphp
                          <li>
                            <a href="{{ $l2Href }}" target="{{ $level2['target'] }}">{{ __($level2['text']) }}</a>
                          </li>
                        @endforeach
                      </ul>
                    </li>
                  @endif
                @endforeach
              </ul>

              <div class="menu-right">
                <form action="{{ route('change_language') }}" method="get">
                  <select name="lang_code" id="language" class="form-control" onchange="this.form.submit()">
                    @foreach ($allLanguageInfos as $item)
                      <option value="{{ $item->code }}"
                        {{ $item->code == $currentLanguageInfo->code ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </form>
                @if (!Auth::guard('customer')->check())
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ __('Customer') }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('customer.login') }}">{{ __('Login') }}</a>
                      <a class="dropdown-item" href="{{ route('customer.signup') }}">{{ __('Signup') }}</a>
                    </div>
                  </div>
                @else
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ Auth::guard('customer')->user()->username }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('customer.dashboard') }}">{{ __('Dashboard') }}</a>
                      <a class="dropdown-item" href="{{ route('customer.logout') }}">{{ __('Logout') }}</a>
                    </div>
                  </div>
                @endif
                @if (!Auth::guard('organizer')->check())
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle"
                      data-toggle="dropdown">{{ __('Organizer') }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                      <a class="dropdown-item" href="{{ route('organizer.login') }}">{{ __('Login') }}</a>
                      <a class="dropdown-item" href="{{ route('organizer.signup') }}">{{ __('Signup') }}</a>
                    </div>
                  </div>
                @else
                  <div class="dropdown">
                    <button type="button" class="menu-btn dropdown-toggle mr-1"
                      data-toggle="dropdown">{{ Auth::guard('organizer')->user()->username }}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('organizer.dashboard') }}">{{ __('Dashboard') }}</a>
                      <a class="dropdown-item" href="{{ route('organizer.logout') }}">{{ __('Logout') }}</a>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </nav>
          <!-- Main Menu End-->
        </div>
      </div>
    </div>
  </div>
  <!--End Header Upper-->
</header>
