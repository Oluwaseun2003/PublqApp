@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Withdraw Payment Methods') }}</h4>
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
        <a href="#">{{ __('Withdraw Method') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Withdraw Payment Methods') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Withdraw Payment Methods') }}</div>
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Withdraw Payment Method') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($collection) == 0)
                <h3 class="text-center">{{ __('NO WITHDRAW PAYMENT METHODS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Min Limit') }}</th>
                        <th scope="col">{{ __('Max Limit') }}</th>
                        <th scope="col">{{ __('Manage Form') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $item)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            {{ strlen($item->name) > 30 ? mb_substr($item->name, 0, 30, 'UTF-8') . '...' : $item->name }}
                          </td>
                          <td>
                            {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                            {{ $item->min_limit }}
                            {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                            {{ $item->max_limit }}
                            {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                          </td>
                          <td><a class="btn btn-info btn-sm"
                              href="{{ route('admin.withdraw_payment_method.mange_input', ['id' => $item->id]) }}">{{ __('Mange Form') }}</a>
                          </td>
                          <td>
                            @if ($item->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>

                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                              data-fixed_charge="{{ $item->fixed_charge }}" data-min_limit="{{ $item->min_limit }}"
                              data-max_limit="{{ $item->max_limit }}"
                              data-percentage_charge="{{ $item->percentage_charge }}" data-status="{{ $item->status }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.withdraw_payment_method.delete', ['id' => $item->id]) }}"
                              method="post">

                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-xs deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.withdraw.create')

  {{-- edit modal --}}
  @include('backend.withdraw.edit')
@endsection
