<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Withdraw Payment Method') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <form id="ajaxEditForm" class="modal-form create" action="{{ route('admin.withdraw_payment_method.update') }}"
          method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" id="in_id" name="id">
          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name" placeholder="{{ __('Enter Method Name') }}">
            <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Minimum Limit') . '*' }} ({{ $settings->base_currency_text }})</label>
            <input type="number" id="in_min_limit" class="form-control" name="min_limit"
              placeholder="{{ __('Enter Withdraw Minimum Limit') }}">
            <p id="editErr_min_limit" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Maximum Limit') . '*' }} ({{ $settings->base_currency_text }})</label>
            <input type="number" id="in_max_limit" class="form-control" name="max_limit"
              placeholder="{{ __('Enter Withdraw Maximum Limit') }}">
            <p id="editErr_max_limit" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Percentage Charge') . '*' }} </label>
            <input type="number" id="in_percentage_charge" class="form-control" name="percentage_charge"
              placeholder="{{ __('Enter Fixed Charge') }}">
            <p id="editErr_percentage_charge" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Fixed Charge') . '*' }} ({{ $settings->base_currency_text }})</label>
            <input type="number" id="in_fixed_charge" class="form-control" name="fixed_charge"
              placeholder="{{ __('Enter Fixed Charge') }}">
            <p id="editErr_fixed_charge" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Status') . '*' }}</label>
            <select id="in_status" name="status" class="form-control">
              <option selected disabled>{{ __('Select a Status') }}</option>
              <option value="1">{{ __('Active') }}</option>
              <option value="0">{{ __('Deactive') }}</option>
            </select>
            <p id="editErr_status" class="mt-1 mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-sm btn-primary">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
