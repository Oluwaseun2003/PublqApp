@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->support_ticket_details_page_title ?? __('Ticket Details') }}
  @else
    {{ __('Ticket Details') }}
  @endif
@endsection
@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="banner-inner">
      <h2 class="page-title">
        @if (!empty($pageHeading))
          {{ $pageHeading->support_ticket_details_page_title ?? __('Ticket Details') }}
        @else
          {{ __('Ticket Details') }}
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
              {{ $pageHeading->support_ticket_details_page_title ?? __('Ticket Details') }}
            @else
              {{ __('Ticket Details') }}
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
                    <h4>
                      @if (!empty($pageHeading))
                        {{ $pageHeading->support_ticket_details_page_title ?? __('Ticket Details') }}
                      @else
                        {{ __('Ticket Details') }}
                      @endif - #{{ $ticket->id }}
                    </h4>
                  </div>
                  <div class="main-info">
                    <div class="subject mb-1">
                      <h5>{{ $ticket->subject }}</h5>
                      <div class=" d-flex align-items-center">
                        @if ($ticket->status == 1)
                          <h6 class="badge badge-warning">{{ __('Pending') }}</h6>
                        @elseif($ticket->status == 2)
                          <h6 class="badge badge-primary">{{ __('Open') }}</h6>
                        @else
                          <h6 class="badge badge-success">{{ __('Closed') }}</h6>
                        @endif
                        <h6><span class="badge badge-light">{{ date('d-M-Y H:s a') }}</span></h6>
                      </div>
                    </div>
                    <div class="description">
                      <p>{{ $ticket->description }}</p>
                    </div>
                    @if ($ticket->attachment != null)
                      <a href="{{ asset('assets/admin/img/support-ticket/' . $ticket->attachment) }}"
                        download="support.zip" class="btn btn-primary"><i class="fas fa-download"></i>
                        {{ __('Download') }}</a>
                    @endif
                    <hr>
                    @if ($ticket->status == 2)
                      <div class="message-section">
                        <h5>{{ __('Reply') }}</h5>
                        <div class="message-lists">
                          <div class="messages">
                            @if (count($ticket->messages) > 0)
                              @foreach ($ticket->messages as $reply)
                                @if ($reply->type == 2)
                                  @php
                                    $admin = App\Models\Admin::where('id', $reply->user_id)->first();
                                  @endphp
                                  <div class="single-message">
                                    <div class="user-details">
                                      <div class="user-img">
                                        <img class="mh-60"
                                          src="{{ $admin->image ? asset('assets/admin/img/admins/' . $admin->image) : asset('assets/admin/img/propics/blank_user.jpg') }}"
                                          alt="">
                                      </div>
                                      <div class="user-infos">
                                        <h6 class="name">{{ $admin->username }}</h6>
                                        <span class="type"><i class="fas fa-user"></i>
                                          {{ $admin->id == 1 ? 'Super Admin' : $admin->role->name }}</span>
                                        <span
                                          class="badge badge-secondary">{{ date_format($reply->created_at, 'd-m-Y h:i a') }}</span>
                                        @if ($reply->file != null)
                                          <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                                            download="support.zip" class="reply-download-btn"><i
                                              class="fas fa-download"></i>
                                            {{ __('Download') }}</a>
                                        @endif
                                      </div>
                                    </div>
                                    <div class="message">
                                      <div class="summernote-content">
                                        <p>{!! $reply->reply !!}</p>
                                      </div>
                                    </div>
                                  </div>
                                @else
                                  @php
                                    $user = App\Models\Customer::where('id', $ticket->user_id)->first();
                                  @endphp
                                  <div class="single-message">
                                    <div class="user-details">
                                      <div class="user-img">
                                        @if ($user->photo != null)
                                          <img class="mh-60"
                                            src="{{ asset('assets/admin/img/customer-profile/' . $user->photo) }}"
                                            alt="">
                                        @else
                                          <img class="mh-60" src="{{ asset('assets/front/images/profile.jpg') }}"
                                            alt="">
                                        @endif
                                      </div>
                                      <div class="user-infos">
                                        <h6 class="name">{{ $user->fname }} {{ $user->lname }}</h6>
                                        <span class="type"><i class="fas fa-user"></i>Customer</span>
                                        <span
                                          class="badge badge-secondary">{{ date_format($reply->created_at, 'd-m-Y h:i a') }}</span>
                                        @if ($reply->file != null)
                                          <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                                            download="support.zip" class="reply-download-btn"><i
                                              class="fas fa-download"></i>
                                            {{ __('Download') }}</a>
                                        @endif

                                      </div>
                                    </div>
                                    <div class="message">
                                      <div class="summernote-content">
                                        <p>{!! $reply->reply !!}</p>
                                      </div>
                                    </div>
                                  </div>
                                @endif
                              @endforeach
                            @else
                              <h4>{{ __('No Message Found') }}</h4>
                            @endif
                          </div>
                          <hr>
                          <div class="reply-section">
                            <h5>{{ __('Reply') }}</h5>
                            <form action="{{ route('customer-reply', $ticket->id) }}" method="POST"
                              enctype="multipart/form-data">
                              @csrf
                              <div class="form-group">
                                <label for="">{{ __('Reply') }} *</label>
                                <textarea name="reply" class="form-control" placeholder="{{ __('Enter Reply') }}"></textarea>
                                @error('reply')
                                  <p class="text-danger">{{ $message }}</p>
                                @enderror
                              </div>
                              <div class="form-group">
                                <input type="file" name="file" class="form-control" accept=".zip">
                                <p class="text-warning">{{ __('Max upload size 5 MB') }}</p>
                                @error('file')
                                  <p class="text-danger">{{ $message }}</p>
                                @enderror
                              </div>
                              <div class="form-group">
                                <button type="submit" class="btn btn-success"><i class="fas fa-retweet"></i>
                                  {{ __('Reply') }}</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
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
