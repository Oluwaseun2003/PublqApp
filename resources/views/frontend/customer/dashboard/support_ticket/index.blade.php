@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_support_ticket_page_title ?? __('My Tickets') }}
  @else
    {{ __('My Tickets') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="banner-inner">
      <h2 class="page-title">
        @if (!empty($pageHeading))
          {{ $pageHeading->customer_support_ticket_page_title ?? __('My Tickets') }}
        @else
          {{ __('My Tickets') }}
        @endif
      </h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">
              @if (!empty($pageHeading))
                {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
              @else
                {{ __('Dashboard') }}
              @endif
            </a></li>
          <li class="breadcrumb-item active">
            @if (!empty($pageHeading))
              {{ $pageHeading->customer_support_ticket_page_title ?? __('My Tickets') }}
            @else
              {{ __('My Tickets') }}
            @endif
          </li>
        </ol>
      </nav>
    </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!--====== Start Dashboard Section ======-->
  <section class="user-dashbord">
    <div class="container">
      <div class="row">
        @includeIf('frontend.customer.partials.sidebar')
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('My Tickets') }}</h4>
                    <p class="text-right"><a href="{{ route('customer.support_tickert.create') }}"
                        class="btn btn-success btn-sm"><i class="fas fa-plus"></i> {{ __('Submit Ticket') }}</a></p>
                  </div>
                  <div class="main-info">
                    @if (count($collection) > 0)
                      <div class="main-table">
                        <div class="table-responsiv">
                          <table id="example"
                            class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Ticket ID') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Message') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($collection as $item)
                                <tr>
                                  <td>{{ $item->id }}</td>
                                  <td>{{ $item->subject }}</td>
                                  @if ($item->status == 1)
                                    <td><span class="badge badge-warning">{{ __('Pending') }}</span></td>
                                  @elseif($item->status == 2)
                                    <td><span class="badge badge-primary">{{ __('Open') }}</span></td>
                                  @else
                                    <td><span class="badge badge-success">{{ __('Closed') }}</span></td>
                                  @endif
                                  <td>
                                    @php
                                      $status = App\Models\SupportTicketStatus::where('id', 1)->first();
                                    @endphp

                                    <a href="{{ $status->support_ticket_status == 'active' ? route('customer.support_ticket.message', $item->id) : '' }}"
                                      class="btn btn-primary mb-1"><i class="fas fa-envelope"></i></a>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    @else
                      <p class="text-center">{{ __('No Tickets are Found') }}..!</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection
