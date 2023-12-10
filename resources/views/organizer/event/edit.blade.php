@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Event') }}</h4>
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
        <a href="#">{{ __('Event Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('organizer.event_management.event', ['language' => $defaultLang->code]) }}">{{ __('All Events') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      @php
        $event_title = DB::table('event_contents')
            ->where('language_id', $defaultLang->id)
            ->where('event_id', $event->id)
            ->select('title')
            ->first();
        if (empty($event_title)) {
            $event_title = DB::table('event_contents')
                ->where('event_id', $event->id)
                ->select('title')
                ->first();
        }
        
      @endphp
      <li class="nav-item">
        <a href="#">
          {{ strlen($event_title->title) > 35 ? mb_substr($event_title->title, 0, 35, 'UTF-8') . '...' : $event_title->title }}
        </a>

      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Event') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Event') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ url()->previous() }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
          <a class="mr-2 btn btn-success btn-sm float-right d-inline-block"
            href="{{ route('event.details', ['slug' => eventSlug($defaultLang->id, $event->id), 'id' => $event->id]) }}"
            target="_blank">
            <span class="btn-label">
              <i class="fas fa-eye"></i>
            </span>
            {{ __('Preview') }}
          </a>
          @if ($event->event_type == 'venue')
            <a class="mr-2 btn btn-secondary btn-sm float-right d-inline-block"
              href="{{ route('organizer.event.ticket', ['language' => $defaultLang->code, 'event_id' => $event->id, 'event_type' => $event->event_type]) }}"
              target="_blank">
              <span class="btn-label">
                <i class="far fa-ticket"></i>
              </span>
              {{ __('Tickets') }}
            </a>
          @endif
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} **</strong></label>
                <div id="reload-slider-div">
                  <div class="row mt-2">
                    <div class="col">
                      <table class="table" id="img-table">

                      </table>
                    </div>
                  </div>
                </div>
                <form action="{{ route('organizer.event.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                  <input type="hidden" value="{{ $event->id }}" name="event_id">
                </form>
                <div class=" mb-0" id="errpreimg">

                </div>
                <p class="text-warning">{{ __('Image Size') . ' : 1170x570' }}</p>
              </div>

              <form id="eventForm" action="{{ route('organizer.event.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="event_type" value="{{ $event->event_type }}">
                <input type="hidden" name="gallery_images" value="0">
                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img
                      src="{{ $event->thumbnail ? asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="uploaded-img">
                  </div>
                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="thumbnail">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image Size') . ' : 320x230' }}</p>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Date Type') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" {{ $event->date_type == 'single' ? 'checked' : '' }}
                            value="single" class="selectgroup-input eventDateType" checked>
                          <span class="selectgroup-button">{{ __('Single') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" {{ $event->date_type == 'multiple' ? 'checked' : '' }}
                            value="multiple" class="selectgroup-input eventDateType">
                          <span class="selectgroup-button">{{ __('Multiple') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row countDownStatus {{ $event->date_type == 'multiple' ? 'd-none' : '' }} ">
                  <div class="col-lg-12">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Countdown Status') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="countdown_status" value="1" class="selectgroup-input"
                            {{ $event->countdown_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="countdown_status" value="0" class="selectgroup-input"
                            {{ $event->countdown_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- single dates --}}
                <div class="row {{ $event->date_type == 'multiple' ? 'd-none' : '' }}" id="single_dates">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Start Date') . '*' }}</label>
                      <input type="date" name="start_date" value="{{ $event->start_date }}"
                        placeholder="Enter Start Date" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('Start Time') . '*' }}</label>
                      <input type="time" name="start_time" value="{{ $event->start_time }}" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('End Date"') . '*' }}</label>
                      <input type="date" name="end_date" value="{{ $event->end_date }}"
                        placeholder="Enter End Date" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('End Time') . '*' }}</label>
                      <input type="time" name="end_time" value="{{ $event->end_time }}" class="form-control">
                    </div>
                  </div>
                </div>

                {{-- multiple dates --}}
                <div class="row">
                  <div class="col-lg-12 {{ $event->date_type == 'single' ? 'd-none' : '' }}" id="multiple_dates">
                    @if ($event->date_type == 'multiple')
                      @php
                        $event_dates = $event->dates()->get();
                      @endphp
                    @else
                      @php
                        $event_dates = [];
                      @endphp
                    @endif
                    <div class="form-group">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>{{ __('Start Date') }}</th>
                              <th>{{ __('Start Time') }}</th>
                              <th>{{ __('End Date') }}</th>
                              <th>{{ __('End Time') }}</th>
                              <th><a href="javascrit:void(0)" class="btn btn-success addDateRow"><i
                                    class="fas fa-plus-circle"></i></a></th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (count($event_dates) > 0)
                              @foreach ($event_dates as $date)
                                <tr>
                                  <td>
                                    <div class="form-group">
                                      <label for="">{{ __('Start Date') . '*' }}</label>
                                      <input type="date" name="m_start_date[]" class="form-control"
                                        value="{{ $date->start_date }}">
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group">
                                      <label for="">{{ __('Start Time') . '*' }}</label>
                                      <input type="time" name="m_start_time[]" class="form-control"
                                        value="{{ $date->start_time }}">
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group">
                                      <label for="">{{ __('End Date') . '*' }}
                                      </label>
                                      <input type="date" name="m_end_date[]" class="form-control"
                                        value="{{ $date->end_date }}">
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group">
                                      <label for="">{{ __('End Time') . '*' }}
                                      </label>
                                      <input type="time" name="m_end_time[]" class="form-control"
                                        value="{{ $date->end_time }}">
                                    </div>
                                  </td>
                                  <input type="hidden" name="date_ids[]" value="{{ $date->id }}">
                                  <td>
                                    <a href="javascript:void(0)"
                                      data-url="{{ route('admin.event.delete.date', $date->id) }}"
                                      class="btn btn-danger deleteDateDbRow">
                                      <i class="fas fa-minus"></i></a>
                                  </td>
                                </tr>
                              @endforeach
                            @else
                              <tr>
                                <td>
                                  <div class="form-group">
                                    <label for="">{{ __('Start Date') . '*' }}</label>
                                    <input type="date" name="m_start_date[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group">
                                    <label for="">{{ __('Start Time') . '*' }}</label>
                                    <input type="time" name="m_start_time[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group">
                                    <label for="">{{ __('End Date') . '*' }}
                                    </label>
                                    <input type="date" name="m_end_date[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group">
                                    <label for="">{{ __('End Time') . '*' }}
                                    </label>
                                    <input type="time" name="m_end_time[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <a href="javascript:void(0)" class="btn btn-danger deleteDateRow">
                                    <i class="fas fa-minus"></i></a>
                                </td>
                              </tr>
                            @endif

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>



                <div class="row ">

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option {{ $event->status == '1' ? 'selected' : '' }} value="1">
                          {{ __('Active') }}
                        </option>
                        <option {{ $event->status == '0' ? 'selected' : '' }} value="0">
                          {{ __('Deactive') }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Is Feature') . '*' }}</label>
                      <select name="is_featured" class="form-control">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option value="yes" {{ $event->is_featured == 'yes' ? 'selected' : '' }}>
                          {{ __('Yes') }}
                        </option>
                        <option value="no" {{ $event->is_featured == 'no' ? 'selected' : '' }}>
                          {{ __('No') }}
                        </option>
                      </select>
                    </div>
                  </div>


                  @if ($event->event_type == 'venue')
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Latitude') }}</label>
                        <input type="text" placeholder="{{ __('Latitude') }}" name="latitude"
                          value="{{ $event->latitude }}" class="form-control">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Longitude') }}</label>
                        <input type="text" placeholder="{{ __('Longitude') }}" name="longitude"
                          value="{{ $event->longitude }}" class="form-control">
                      </div>
                    </div>
                  @endif
                </div>
                @if ($event->event_type == 'online')
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Total Number of Available Tickets') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="ticket_available_type" value="unlimited"
                              class="selectgroup-input"
                              {{ @$event->ticket->ticket_available_type == 'unlimited' ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="ticket_available_type" value="limited"
                              class="selectgroup-input"
                              {{ @$event->ticket->ticket_available_type == 'limited' ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Limited') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 {{ @$event->ticket->ticket_available_type == 'limited' ? '' : 'd-none' }}"
                      id="ticket_available">
                      <div class="form-group">
                        <label>{{ __('Enter total number of available tickets') . '*' }}</label>
                        <input type="number" name="ticket_available"
                          placeholder="{{ __('Enter total number of available tickets') }}" class="form-control"
                          value="{{ @$event->ticket->ticket_available }}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Maximum number of tickets for each customer') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="max_ticket_buy_type" value="unlimited"
                              class="selectgroup-input"
                              {{ @$event->ticket->max_ticket_buy_type == 'unlimited' ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="max_ticket_buy_type" value="limited" class="selectgroup-input"
                              {{ @$event->ticket->max_ticket_buy_type == 'limited' ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Limited') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 {{ @$event->ticket->max_ticket_buy_type == 'limited' ? '' : 'd-none' }}"
                      id="max_buy_ticket">
                      <div class="form-group">
                        <label>{{ __('Enter Maximum number of tickets for each customer') . '*' }}</label>
                        <input type="number" name="max_buy_ticket"
                          placeholder="{{ __('Enter Maximum number of tickets for each customer') }}"
                          class="form-control" value="{{ @$event->ticket->max_buy_ticket }}">
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="">
                        <div class="form-group">
                          <label for="">{{ __('Price') }}
                            ({{ $getCurrencyInfo->base_currency_text }})
                            *</label>
                          <input type="number" name="price" id="ticket-pricing"
                            value="{{ $event->ticket->price }}" placeholder="{{ __('Enter Price') }}"
                            class="form-control {{ optional($event->ticket)->pricing_type == 'free' ? 'd-none' : '' }}">
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="checkbox" name="pricing_type"
                          {{ optional($event->ticket)->pricing_type == 'free' ? 'checked' : '' }} value="free"
                          class="" id="free_ticket"> <label
                          for="free_ticket">{{ __('Tickets are Free') }}</label>
                      </div>
                    </div>
                  </div>



                  <div class="row {{ optional($event->ticket)->pricing_type == 'free' ? 'd-none' : '' }}"
                    id="early_bird_discount_free">
                    <div class="col-lg-12">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Early Bird Discount') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="early_bird_discount_type"
                              {{ optional($event->ticket)->early_bird_discount == 'disable' ? 'checked' : '' }}
                              value="disable" class="selectgroup-input" checked>
                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="early_bird_discount_type"
                              {{ optional($event->ticket)->early_bird_discount == 'enable' ? 'checked' : '' }}
                              value="enable" class="selectgroup-input">
                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="col-lg-12 {{ optional($event->ticket)->early_bird_discount == 'disable' ? 'd-none' : '' }}"
                      id="early_bird_dicount">
                      <div class="row">
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount') }} *</label>
                            <select name="discount_type" class="form-control discount_type">
                              <option disabled>{{ __('Select Discount Type') }}</option>
                              <option
                                {{ optional($event->ticket)->early_bird_discount_type == 'fixed' ? 'selected' : '' }}
                                value="fixed">{{ __('Fixed') }}</option>
                              <option
                                {{ optional($event->ticket)->early_bird_discount_type == 'percentage' ? 'selected' : '' }}
                                value="percentage">{{ __('Percentage') }}</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Amount') }} *</label>
                            <input type="number" name="early_bird_discount_amount"
                              value="{{ optional($event->ticket)->early_bird_discount_amount }}"
                              class="form-control early_bird_discount_amount">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount End Date') }} *</label>
                            <input type="date" name="early_bird_discount_date"
                              value="{{ optional($event->ticket)->early_bird_discount_date }}" class="form-control">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount End Time') }} *</label>
                            <input type="time" name="early_bird_discount_time"
                              value="{{ optional($event->ticket)->early_bird_discount_time }}" class="form-control">
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                @endif


                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . ' ' . __('Language') }}
                            {{ $language->is_default == 1 ? '(' . __('Default') . ')' : '' }}
                          </button>
                        </h5>
                      </div>
                      @php
                        $event_content = DB::table('event_contents')
                            ->where('language_id', $language->id)
                            ->where('event_id', $event->id)
                            ->first();
                      @endphp
                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Event Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  value="{{ @$event_content->title }}" placeholder="{{ __('Enter Event Name') }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = DB::table('event_categories')
                                      ->where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->orderBy('serial_number', 'asc')
                                      ->get();
                                @endphp

                                <label for="">{{ __('Category') . '*' }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control">
                                  <option selected disabled>{{ __('Select Category') }}
                                  </option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                      {{ @$event_content->event_category_id == $category->id ? 'selected' : '' }}>
                                      {{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          @if ($event->event_type == 'venue')
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="form-group">
                                  <label for="">{{ __('Address') . '*' }}</label>
                                  <input type="text" name="{{ $language->code }}_address"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                    placeholder="{{ __('Enter Address') }}" value="{{ @$event_content->address }}">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">{{ __('County') . '*' }}</label>
                                  <input type="text" name="{{ $language->code }}_country"
                                    placeholder="{{ __('Enter Country') }}"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                    value="{{ @$event_content->country }}">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">{{ __('State') }}</label>
                                  <input type="text" name="{{ $language->code }}_state"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                    placeholder="{{ __('Enter State') }}" value="{{ @$event_content->state }}">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">{{ __('City') . '*' }}</label>
                                  <input type="text" name="{{ $language->code }}_city"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                    placeholder="{{ __('Enter City') }}" value="{{ @$event_content->city }}">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">{{ __('Zip/Post Code ') }}</label>
                                  <input type="text" placeholder="{{ __('Enter Zip/Post Code') }}"
                                    name="{{ $language->code }}_zip_code"
                                    class="form-control {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                    value="{{ @$event_content->zip_code }}">
                                </div>
                              </div>
                            </div>
                          @endif

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                  name="{{ $language->code }}_description" placeholder="{{ __('Enter Event Description') }}" data-height="300">{!! @$event_content->description !!}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Refund Policy') }} *</label>
                                <textarea class="form-control" name="{{ $language->code }}_refund_policy" rows="5"
                                  placeholder="{{ __('Enter Refund Policy') }}">{{ @$event_content->refund_policy }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  value="{{ @$event_content->meta_keywords }}"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}">{{ @$event_content->meta_description }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div id="sliders"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="EventSubmit" class="btn btn-primary">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @php
    $languages = App\Models\Language::get();
  @endphp
  <script>
    let languages = "{{ $languages }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin_dropzone.js') }}"></script>
@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('organizer.event.imagesstore') }}";
    var removeUrl = "{{ route('organizer.event.imagermv') }}";

    var rmvdbUrl = "{{ route('organizer.event.imgdbrmv') }}";
    var loadImgs = "{{ route('organizer.event.images', $event->id) }}";
  </script>
@endsection
