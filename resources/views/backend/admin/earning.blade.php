@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Monthly Total Earning') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Monthly Total Earning') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">

            <div class="col-lg-5">
              <div class="card-title d-inline-block">
                {{ __('Monthly Total Earning ') }}
              </div>
            </div>
            <div class="col-lg-7">
              <div class="card-title d-inline-block">
                <form action="{{ route('admin.monthly_earning') }}" id="year" method="get">
                  <div class="form-group">
                    <select id="year" class="form-control" name="year"
                      onchange="document.getElementById('year').submit()">
                      <option value="">{{ __('Select Year') }}</option>
                      @for ($year = 2023; $year <= date('Y'); $year++)
                        <option
                          @if (request()->input('year') == '' && $year == date('Y')) {{ 'selected' }} 
                          @elseif(request()->input('year') == $year)
                          {{ 'selected' }} @endif
                          value="{{ $year }}">
                          {{ $year }}</option>
                      @endfor
                    </select>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 mx-auto">

            <div class="table-responsive">
              <table class="table table-striped mt-3" id="">
                <thead>
                  <tr>
                    <th scope="col">{{ __('Month Name') }}</th>
                    <th scope="col">{{ __('Total Earning') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($incomes as $key => $value)
                    <tr>
                      <td>
                        @php
                          $monthNum = $key + 1;
                          $dateObj = DateTime::createFromFormat('!m', $monthNum);
                          $monthName = $dateObj->format('F');
                        @endphp
                        {{ $monthName }}
                      </td>
                      <td>
                        {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                        {{ round($value + $taxs[$key], 2) }}
                        {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer"></div>
    </div>
  </div>
  </div>
@endsection
