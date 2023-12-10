<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Coupon') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('admin.event_management.store_coupon') }}"
          method="post">
          @csrf
          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Name') . '*' }}</label>
                <input type="text" class="form-control" name="name" placeholder="{{ __('Enter Coupon Name') }}">
                <p id="err_name" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Code') . '*' }}</label>
                <input type="text" class="form-control" name="code" placeholder="{{ __('Enter Coupon Code') }}">
                <p id="err_code" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Coupon Type') . '*' }}</label>
                <select name="type" class="form-control">
                  <option selected disabled>{{ __('Select a Type') }}</option>
                  <option value="fixed">{{ __('Fixed') }}</option>
                  <option value="percentage">{{ __('Percentage') }}</option>
                </select>
                <p id="err_type" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Value') . '*' }}</label>
                <input type="number" step="0.01" class="form-control" name="value"
                  placeholder="{{ __('Enter Coupon Value') }}">
                <p id="err_value" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Start Date') . '*' }}</label>
                <input type="text" class="form-control datepicker" name="start_date" placeholder="{{ __('Enter Start Date') }}">
                <p id="err_start_date" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('End Date') . '*' }}</label>
                <input type="text" class="form-control datepicker" name="end_date" placeholder="{{ __('Enter End Date') }}">
                <p id="err_end_date" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Events') }}</label>
                <select class="select2" name="events[]" multiple="multiple" placeholder="Select Events">
                  @foreach ($events as $event)
                    @php
                      $eventInfo = App\Models\Event\EventContent::where('language_id', $deLang->id)
                          ->where('event_id', $event->id)
                          ->select('title', 'id')
                          ->first();
                      $id = $event->id;
                      $event = App\Models\Event::where('id', $id)
                          ->with('organizer')
                          ->select('organizer_id')
                          ->first();
                    @endphp
                    <option value="{{ $id }}">
                      @if ($eventInfo)
                        {{ $title = $eventInfo->title }}
                        @if ($event && $event->organizer != null)
                          ({{ optional($event->organizer)->username }})
                        @else
                          ({{ 'Admin' }})
                        @endif
                      @endif
                    </option>
                  @endforeach
                </select>
                <p class="text-warning">{{ __('Please leave this field blank to make it applicable for all events') }}
                </p>
                <p id="err_events" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-sm btn-primary">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
