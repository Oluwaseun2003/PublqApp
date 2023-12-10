@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Customer Details') }}</h4>
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
                <a href="#">{{ __('Customers Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('admin.organizer_management.registered_customer', ['language' => $defaultLang->code]) }}">{{ __('Registered Customers') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Customer Details') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="author">
                                        @if (!is_null($customer->photo))
                                            <img src="{{ asset('assets/admin/img/customer-profile/' . $customer->photo) }}"
                                                alt="" class="rounded-circle customer-img">
                                        @else
                                            <img src="{{ asset('assets/admin/img/blank_user.jpg') }}" alt=""
                                                class="rounded-circle customer-img">
                                        @endif

                                        <div class="h6 card-title">{{ __('Customer Information') }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <a class="btn btn-info btn-sm float-right d-inline-block mr-2"
                                        href="{{ route('admin.organizer_management.registered_customer', ['language' => $defaultLang->code]) }}">
                                        <span class="btn-label">
                                            <i class="fas fa-backward"></i>
                                        </span>
                                        {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="payment-information">
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Name') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->fname }} {{ $customer->lname }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Email') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->email }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Phone') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->phone }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Country') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->country }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('City') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->city }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('State') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->state }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Zip Code') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->zip_code }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Gender') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->gender }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Address') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $customer->address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card-title">{{ __('Event Bookings') }}</div>
                                </div>

                                <div class="col-lg-6 offset-lg-2">
                                    <button class="btn btn-danger btn-sm float-right d-none bulk-delete ml-3 mt-1"
                                        data-href="{{ route('admin.event_booking.bulk_delete') }}">
                                        <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                                    </button>

                                    <form class="float-right ml-3"
                                        action="{{ route('admin.customer_management.customer_details', ['id' => $customer->id]) }}"
                                        method="GET">
                                        <input name="booking_id" type="text" class="form-control"
                                            placeholder="Search By Order ID"
                                            value="{{ !empty(request()->input('booking_id')) ? request()->input('booking_id') : '' }}">
                                    </form>

                                    <form id="searchByStatusForm" class="float-right d-flex flex-row align-items-center"
                                        action="{{ route('admin.event.booking') }}" method="GET">
                                        <label class="mr-2">{{ __('Payment') }}</label>
                                        <select class="form-control" name="status"
                                            onchange="document.getElementById('searchByStatusForm').submit()">
                                            <option value=""
                                                {{ empty(request()->input('status')) ? 'selected' : '' }}>
                                                {{ __('All') }}
                                            </option>
                                            <option value="completed"
                                                {{ request()->input('status') == 'completed' ? 'selected' : '' }}>
                                                {{ __('Completed') }}
                                            </option>
                                            <option value="pending"
                                                {{ request()->input('status') == 'pending' ? 'selected' : '' }}>
                                                {{ __('Pending') }}
                                            </option>
                                            <option value="rejected"
                                                {{ request()->input('status') == 'rejected' ? 'selected' : '' }}>
                                                {{ __('Rejected') }}
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if (count($bookings) == 0)
                                        <h3 class="text-center mt-2">{{ __('NO EVENT BOOKING FOUND') . '!' }}</h3>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            <input type="checkbox" class="bulk-check" data-val="all">
                                                        </th>
                                                        <th scope="col">{{ __('Booking ID') }}</th>
                                                        <th scope="col">{{ __('Event') }}</th>
                                                        <th scope="col">{{ __('Username') }}</th>
                                                        <th scope="col">{{ __('Paid via') }}</th>
                                                        <th scope="col">{{ __('Payment Status') }}</th>
                                                        <th scope="col">{{ __('Attachment') }}</th>
                                                        <th scope="col">{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bookings as $booking)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="bulk-check"
                                                                    data-val="{{ $booking->id }}">
                                                            </td>
                                                            <td>{{ '#' . $booking->booking_id }}</td>

                                                            @php
                                                                $eventInfo = \App\Models\Event\EventContent::where('language_id', $defaultLang->id)->first();
                                                                $title = $eventInfo->title;
                                                                $slug = $eventInfo->slug;
                                                            @endphp

                                                            <td>
                                                                <a href="{{ route('event.details', ['slug' => $slug, 'id' => $eventInfo->event_id]) }}"
                                                                    target="_blank">
                                                                    {{ strlen($title) > 35 ? mb_substr($title, 0, 35, 'utf-8') . '...' : $title }}
                                                                </a>
                                                            </td>

                                                            @php
                                                                $customer = $booking->customerInfo()->first();
                                                            @endphp

                                                            <td>{{ $customer->fname }} {{ $customer->lname }}</td>
                                                            <td>{{ !is_null($booking->paymentMethod) ? $booking->paymentMethod : '-' }}
                                                            </td>
                                                            <td>
                                                                @if ($booking->gatewayType == 'online')
                                                                    <h2 class="d-inline-block"><span
                                                                            class="badge badge-success">{{ __('Completed') }}</span>
                                                                    </h2>
                                                                @elseif ($booking->gatewayType == 'offline')
                                                                    <form id="paymentStatusForm-{{ $booking->id }}"
                                                                        class="d-inline-block"
                                                                        action="{{ route('admin.event_booking.update_payment_status', $booking->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <select
                                                                            class="form-control form-control-sm @if ($booking->paymentStatus == 'completed') bg-success @elseif ($booking->paymentStatus == 'pending') bg-warning text-dark @else bg-danger @endif"
                                                                            name="payment_status"
                                                                            onchange="document.getElementById('paymentStatusForm-{{ $booking->id }}').submit()">
                                                                            <option value="completed"
                                                                                {{ $booking->paymentStatus == 'completed' ? 'selected' : '' }}>
                                                                                {{ __('Completed') }}
                                                                            </option>
                                                                            <option value="pending"
                                                                                {{ $booking->paymentStatus == 'pending' ? 'selected' : '' }}>
                                                                                {{ __('Pending') }}
                                                                            </option>
                                                                            <option value="rejected"
                                                                                {{ $booking->paymentStatus == 'rejected' ? 'selected' : '' }}>
                                                                                {{ __('Rejected') }}
                                                                            </option>
                                                                        </select>
                                                                    </form>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!is_null($booking->attachmentFile))
                                                                    <a class="btn btn-sm btn-info" href="#"
                                                                        data-toggle="modal"
                                                                        data-target="#attachmentModal-{{ $booking->id }}">
                                                                        {{ __('Show') }}
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-secondary btn-sm dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                        {{ __('Select') }}
                                                                    </button>

                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton">
                                                                        <a href="{{ route('admin.event_booking.details', ['id' => $booking->id]) }}"
                                                                            class="dropdown-item">
                                                                            {{ __('Details') }}
                                                                        </a>

                                                                        <a href="{{ asset('assets/admin/file/invoices/' . $booking->invoice) }}"
                                                                            class="dropdown-item" target="_blank">
                                                                            {{ __('Invoice') }}
                                                                        </a>

                                                                        <form class="deleteForm d-block"
                                                                            action="{{ route('admin.event_booking.delete', ['id' => $booking->id]) }}"
                                                                            method="post">

                                                                            @csrf
                                                                            <button type="submit" class="deleteBtn">
                                                                                {{ __('Delete') }}
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        @includeIf('backend.event.booking.show-attachment')
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <div class="d-inline-block mt-3">
                                {{ $bookings->appends([
                                        'booking_id' => request()->input('booking_id'),
                                        'status' => request()->input('status'),
                                    ])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
