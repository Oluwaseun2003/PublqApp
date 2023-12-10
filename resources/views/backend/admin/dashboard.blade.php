@extends('backend.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="{{ $settings->admin_theme_version == 'light' ? 'text-dark' : 'text-light' }} pb-2">{{ __('Welcome back,') }}
      {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name . '!' }}</h2>
  </div>

  {{-- dashboard information start --}}
  @php
    if (!is_null($roleInfo)) {
        $rolePermissions = json_decode($roleInfo->permissions);
    }
  @endphp

  <div class="row dashboard-items">

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Lifetime Earning', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.monthly_earning') }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-sack-dollar"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Life Time Earning') }}</p>
                    <h4 class="card-title">
                      {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                      {{ $total_earning->total_revenue }}
                      {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}

                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Profit', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.monthly_profit') }}">
          <div class="card card-stats card-earning card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-usd-square"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Total Profit') }}</p>
                    <h4 class="card-title">
                      {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                      {{ $total_earning->total_earning }}
                      {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}

                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-success card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-calendar-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Events') }}</p>
                    <h4 class="card-title">{{ $totalEvents }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.event_management.categories', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-danger card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-sitemap"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Event Categories') }}</p>
                    <h4 class="card-title">{{ $totalEventCategories }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif


    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transaction', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.transcation') }}">
          <div class="card card-stats card-secondary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-exchange-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Total Transcation') }}</p>
                    <h4 class="card-title">{{ $transcation_count }}
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Bookings', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.event.booking') }}">
          <div class="card card-stats card-primary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-hotel"></i>
                  </div>
                </div>
                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Total Event Booking') }}</p>
                    <h4 class="card-title">{{ $totalEventBookings }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Organizer Mangement', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.organizer_management.registered_organizer', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-warning card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-chalkboard-teacher"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Organizers') }}</p>
                    <h4 class="card-title">{{ $totalOrganizers }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-blog"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Blog') }}</p>
                    <h4 class="card-title">{{ $totalBlog }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Customer Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.organizer_management.registered_customer') }}">
          <div class="card card-stats card-secondary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="la flaticon-users"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Registered Customers') }}</p>
                    <h4 class="card-title">{{ $totalRegisteredUsers }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-danger card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-shopping-basket"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Products') }}</p>
                    <h4 class="card-title">{{ $totalProducts }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('admin.product.order') }}">
          <div class="card card-stats card-success card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-receipt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Orders') }}</p>
                    <h4 class="card-title">{{ $totalOrders }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

  </div>

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Management', $rolePermissions)))
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">{{ __('Event Booking Monthly Earning') }} ({{ date('Y') }})</div>
          </div>

          <div class="card-body">
            <div class="chart-container">
              <canvas id="incomeChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">{{ __('Monthly Event Bookings') }} ({{ date('Y') }})</div>
          </div>

          <div class="card-body">
            <div class="chart-container">
              <canvas id="TotalEventBookingChart"></canvas>
            </div>
          </div>
        </div>
      </div>
  @endif

  {{-- product chart --}}
  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Product Order Monthly Income') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="ProductOrderChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Monthly Product Orders') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="TotalProductOrderChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  @endif
  </div>
  {{-- dashboard information end --}}
@endsection

@section('script')
  {{-- chart js --}}
  <script type="text/javascript" src="{{ asset('assets/admin/js/chart.min.js') }}"></script>

  <script>
    "use strict";
    const monthArr = @php echo json_encode($eventMonths) @endphp;
    const incomeArr = @php echo json_encode($eventIncomes) @endphp;
    const totalBookings = @php echo json_encode($totalBookings) @endphp;

    const productIncome = @php echo json_encode($productIncome) @endphp;
    const totalOders = @php echo json_encode($totalOders) @endphp;
  </script>

  <script type="text/javascript" src="{{ asset('assets/admin/js/chart-init.js') }}"></script>
@endsection
