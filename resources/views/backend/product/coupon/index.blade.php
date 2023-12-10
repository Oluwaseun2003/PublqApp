@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Coupons') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="">{{ __('Shop Management') }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Coupons') }}</a>
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
                {{ __('Coupons')  }}
              </div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                <a href=""  data-toggle="modal" data-target="#createModal" class="btn btn-secondary btn-sm float-right" ><i class="fas fa-plus-circle"></i> {{ __('Add New') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete" data-href="{{ route('admin.shop_management.bulk_delete_coupon') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
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

              @if (count($collection) == 0)
                <h3 class="text-center mt-2">{{ __('NO COUPONS ARE FOUND'). '!' }}</h3>
              @else

                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Created') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $item)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                          </td>
                          <td>
                            {{$item->name}}
                          </td>
                          <td>
                            {{$item->code}}
                          </td>
                          <td>
                            {{$item->type == 'fixed'  ? '$ '. $item->value: '' }} {{$item->type == 'percentage' ? $item->value.' %' : ''}}
                          </td>
                          <td>
                            @php
                              $end = Carbon\Carbon::parse($item->end_date);
                              $start = Carbon\Carbon::parse($item->start_date);
                              $now = Carbon\Carbon::now();
                              $diff = $end->diffInDays($now);
                            @endphp
                            @if ($start->greaterThan($now))
                            <h3 class="d-inline-block badge badge-warning">{{ __('Pending') }}</h3>
                            @else
                                @if ($now->lessThan($end))
                                <h3 class="d-inline-block badge badge-success">{{ __('Active') }}</h3>
                                @else
                                <h3 class="d-inline-block badge badge-danger">{{ __('Expired') }}</h3>
                                @endif
                            @endif
                          </td>
                          <td>
                            @php
                                $created = Carbon\Carbon::parse($item->created_at);
                                $diff = $created->diffInDays($now);
                            @endphp
                            {{$created->subDays($diff)->diffForHumans()}}
                        </td>

                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal" data-target="#editshippingCharge" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-code="{{ $item->code }}" data-type="{{ $item->type }}" data-value="{{ $item->value }}" data-start_date="{{ $item->start_date }}" data-end_date="{{ $item->end_date }}" data-start_date="{{ $item->start_date }}" data-minimum_spend="{{ $item->minimum_spend }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.shop_management.delete_coupon', ['id' => $item->id]) }}" method="post">

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
  {{-- --create modal --}}
  @includeIf('backend.product.coupon.create')
  {{-- edit modal --}}
  @includeIf('backend.product.coupon.edit')
@endsection
