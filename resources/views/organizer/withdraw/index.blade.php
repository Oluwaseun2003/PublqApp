@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Withdraws') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('organizer.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('My Withdraws') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">
                {{ __('My Withdraws') }}
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card-title">{{ __('Your Balance') }} :
                {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                {{ Auth::guard('organizer')->user()->amount }}
                {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}</div>
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">

              <a href="{{ route('organizer.withdraw.create', ['language' => $defaultLang->code]) }}"
                class="btn btn-secondary btn-sm float-lg-right float-left">
                <i class="fas fa-plus"></i> {{ __('Withdraw Now')."!" }}
              </a>

              <button class="btn btn-danger btn-sm float-lg-right float-left mr-2 d-none bulk-delete"
                data-href="{{ route('organizer.witdraw.bulk_delete_withdraw') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">

            @if (session()->has('course_status_warning'))
              <div class="alert alert-warning">
                <p class="text-dark mb-0">{{ session()->get('course_status_warning') }}</p>
              </div>
            @endif



            <div class="table-responsive">
              <table class="table table-striped mt-3" id="basic-datatables">
                <thead>
                  <tr>
                    <th scope="col">
                      <input type="checkbox" class="bulk-check" data-val="all">
                    </th>
                    <th scope="col">{{ __('Withdraw Id') }}</th>
                    <th scope="col">{{ __('Method Name') }}</th>
                    <th scope="col">{{ __('Total Amount') }}</th>
                    <th scope="col">{{ __('Total Charge') }}</th>
                    <th scope="col">{{ __('Total Payable Amount') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($collection as $item)
                    <tr>
                      <td>
                        <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                      </td>
                      <td>
                        {{ $item->withdraw_id }}
                      </td>
                      <td>
                        {{ optional($item->method)->name }}
                      </td>
                      <td>
                        {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                        {{ $item->amount }}
                        {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                      </td>
                      <td>
                        {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                        {{ $item->total_charge }}
                        {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                      </td>
                      <td>
                        {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                        {{ $item->payable_amount }}
                        {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                      </td>
                      <td>
                        @if ($item->status == 0)
                          <span class="badge badge-danger">{{ __('Pending') }}</span>
                        @elseif($item->status == 1)
                          <span class="badge badge-success">{{ __('Approved') }}</span>
                        @elseif($item->status == 2)
                          <span class="badge badge-warning">{{ __('Decline') }}</span>
                        @endif
                      </td>
                      <td>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#withdrawModal{{ $item->id }}"
                          class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                        <form class="deleteForm d-inline-block"
                          action="{{ route('organizer.witdraw.delete_withdraw', ['id' => $item->id]) }}" method="post">

                          @csrf
                          <button type="submit" class="btn btn-danger btn-sm deleteBtn"><i class="fas fa-trash"></i></button>
                        </form>
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
  @foreach ($collection as $item)
    <div class="modal fade" id="withdrawModal{{ $item->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Withdraw Information') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            @php
              $d_feilds = json_decode($item->feilds, true);
            @endphp
            <div class="">
              <p>{{ __('Total Payable Amount') }} :
                {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                {{ $item->payable_amount }}
                {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
              </p>
              @foreach ($d_feilds as $key => $d_feild)
                <p><strong>{{ str_replace('_', ' ', $key) }} : {{ $d_feild }}</strong></p>
              @endforeach
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
              {{ __('Close') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection
