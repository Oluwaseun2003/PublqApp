@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Tickets') }}</h4>
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
        <a href="#">{{ __('Support Ticket') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Tickets') }}</a>
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
                {{ __('All Tickets') }}
              </div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <button class="btn btn-danger float-lg-right float-none btn-sm ml-2 mt-1 bulk-delete d-none"
                data-href="{{ route('organizer.support_tickets.bulk_delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>

              <form action="" class="float-lg-right float-none" method="GET">
                <input type="text" name="ticket_id" class="form-control min-w-250" placeholder="Search by Ticket ID"
                  value="{{ !empty(request()->input('ticket_id')) ? request()->input('ticket_id') : '' }}">
              </form>
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
                <h3 class="text-center mt-2">{{ __('NO SUPPORT TICKETS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Ticket ID') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Subject') }}</th>
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
                            {{ $item->id }}
                          </td>
                          <td>
                            {{ $item->email != '' ? $item->email : '-' }}
                          </td>
                          <td>
                            {{ $item->subject }}
                          </td>
                          <td>
                            @if ($item->status == 1)
                              <span class="badge badge-info">{{ __('Pending') }}</span>
                            @elseif($item->status == 2)
                              <span class="badge badge-success">{{ __('Open') }}</span>
                            @elseif($item->status == 3)
                              <span class="badge badge-danger">{{ __('Closed') }}</span>
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <a href="{{ route('organizer.support_tickets.message', $item->id) }}"
                                  class="dropdown-item">
                                  {{ __('Message') }}
                                </a>
                                <form class="deleteForm d-block"
                                  action="{{ route('organizer.support_tickets.delete', $item->id) }}" method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
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

        <div class="card-footer">
          {{ $collection->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
