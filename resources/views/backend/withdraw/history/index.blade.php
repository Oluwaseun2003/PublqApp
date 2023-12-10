@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Withdraw Requests') }}</h4>
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
                <a href="#">{{ __('Withdraw Requests') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Withdraw Requests') }}</div>
                        </div>
                        <div class="col-lg-6 offset-lg-2">
                            <form class="float-right" action="{{ route('admin.withdraw.withdraw_request') }}"
                                method="GET">
                                <input name="search" type="text" class="form-control min-230"
                                    placeholder="Search  withdraw id, method name" value="{{ request()->input('search') }}">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($collection) == 0)
                                <h3 class="text-center">{{ __('NO WITHDRAW REQUESTS FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th scope="col">{{ __('Withdraw Id') }}</th>
                                                <th scope="col">{{ __('Method Name') }}</th>
                                                <th scope="col">{{ __('Total Amount') }}</th>
                                                <th scope="col">{{ __('Total Charge') }}</th>
                                                <th scope="col">{{ __('Total Payable Amount') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($collection as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->withdraw_id }}</td>
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
                                                        <a href="javascript:void(0)" data-toggle="modal"
                                                            data-target="#withdrawModal{{ $item->id }}"
                                                            class="btn btn-primary mt-1 btn-xs"><span class="btn-label">
                                                                <i class="fas fa-eye"></i>
                                                            </span> {{ __('View') }}</a>
                                                        @if ($item->status == 0)
                                                            <a href="{{ route('admin.witdraw.approve_withdraw', ['id' => $item->id]) }}"
                                                                class="btn btn-success mt-1 btn-xs  confirmBtn"><span
                                                                    class="btn-label">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </span> {{ __('Approve') }}</a>
                                                            <a href="{{ route('admin.witdraw.decline_withdraw', ['id' => $item->id]) }}"
                                                                class="btn btn-warning mt-1 btn-xs confirmBtn"><span
                                                                    class="btn-label">
                                                                    <i class="fas fa-times"></i>
                                                                </span> {{ __('Decline') }}</a>
                                                        @endif



                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.witdraw.delete_withdraw', ['id' => $item->id]) }}"
                                                            method="post">

                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger mt-1 btn-xs deleteBtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="">
                                    {{ $collection->appends([
                                            'search' => request()->input('search'),
                                        ])->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    @include('backend.withdraw.history.view')
@endsection
